<x-app-layout>
    <x-slot name="pageTitle">Detail Mata Kuliah</x-slot>
    @php
        // REAL DATA MAPPING
        $course = (object)[
            'id' => $subject->id, 
            'title' => $subject->name, 
            'code' => $subject->code ?? 'MK-101', 
            'lecturer' => $subject->lecturer ?: '-'
        ];

        $notes = $subject->notes()->where('user_id', auth()->id())->with('tags')->latest()->get()->map(function($note) {
            return (object)[
                'id' => $note->id,
                'title' => $note->title,
                'excerpt' => Str::limit(strip_tags($note->content), 100),
                'content' => $note->content,
                'keywords' => $note->tags?->pluck('name')->toArray() ?? [],
                'is_ai' => $note->is_ai_generated ?? false,
                'date' => $note->created_at->diffForHumans()
            ];
        });

        $materials = $subject->materials()->where('user_id', auth()->id())->latest()->get()->map(function($mat) {
            $type = $mat->file_type ?? 'text';
            $size = $mat->file_size ? number_format($mat->file_size / 1024, 2) . ' KB' : '';
            return (object)[
                'id' => $mat->id, 
                'title' => $mat->title, 
                'type' => $type, 
                'size' => $size, 
                'preview' => Str::limit(strip_tags($mat->content), 150) ?: 'Tidak ada preview', 
                'content' => $mat->content ?? 'Tidak ada teks konten (mungkin berupa file dokumen).',
                'date' => $mat->created_at->diffForHumans()
            ];
        });

        $quizzes = $subject->quizzes()
            ->where('type', 'admin')
            ->doesntHave('flashcardSet')
            ->where(function ($query) {
                $query->whereHas('targets', function ($q) {
                    $q->where('user_id', auth()->id());
                })->orWhereDoesntHave('targets');
            })
            ->withCount('questions')
            ->with(['attempts' => function($q) {
                $q->where('user_id', auth()->id())->latest();
            }])
            ->latest()->get()->map(function($quiz) {
                $latestAttempt = $quiz->attempts->first();
                return (object)[
                    'id' => $quiz->id, 
                    'title' => $quiz->title, 
                    'questions' => $quiz->questions_count ?? 0, 
                    'score' => $latestAttempt ? $latestAttempt->score : null, 
                    'status' => $latestAttempt ? 'selesai' : 'belum'
                ];
            });

        $flashcardSetsRaw = $subject->flashcardSets()
            ->where('type', 'admin')
            ->where(function ($query) {
                $query->whereHas('targets', function ($q) {
                    $q->where('user_id', auth()->id());
                })->orWhereDoesntHave('targets');
            })
            ->with(['quiz.attempts' => function($q) {
                $q->where('user_id', auth()->id())->latest();
            }])
            ->withCount('flashcards')
            ->latest()
            ->get();

        $flashcardSets = $flashcardSetsRaw->map(function($set) {
            $latestAttempt = $set->quiz ? $set->quiz->attempts->first() : null;

            return (object)[
                'id' => $set->id,
                'title' => $set->title,
                'count' => $set->flashcards_count ?? 0,
                'score' => $latestAttempt ? $latestAttempt->score : null,
                'status' => $latestAttempt ? 'selesai' : 'belum',
                'quiz_id' => $set->quiz_id,
            ];
        });
    @endphp

    <div x-data="{ 
            activeTab: '{{ session('tab', 'materi') }}', 
            showUploadModal: false,
            showNoteModal: false,
            showMaterialModal: false,
            isEditingMaterial: false,
            showCreateNoteModal: false,
            showAiModal: false,
            activeNote: { title: '', content: '', is_ai: false, keywords: [] },
            activeMaterial: { id: '', title: '', type: '', content: '', date: '' }
         }" 
         class="space-y-6 animate-fadeIn">
        
        {{-- HEADER MATA KULIAH --}}
        <div class="dashboard-card p-6 border-white/10">
            <div class="flex items-start gap-4">
                <a href="{{ Route::has('subjects.index') ? route('subjects.index') : '#' }}" class="mt-1 w-10 h-10 rounded-xl bg-white/5 flex items-center justify-center text-slate-300 hover:text-white hover:bg-white/10 border border-white/10 hover:border-transparent transition-colors">
                    <i class="ph ph-caret-left text-lg"></i>
                </a>
                <div class="flex-1">
                    <div class="flex items-center gap-2 mb-1.5">
                        <span class="text-[11px] font-bold text-slate-300 bg-white/5 border border-white/10 px-2.5 py-1 rounded-lg uppercase tracking-wider">{{ $course->code }}</span>
                    </div>
                    <h1 class="text-2xl md:text-3xl font-bold text-white font-geist tracking-tight">{{ $course->title }}</h1>
                    <p class="text-sm font-medium text-slate-300 mt-1 flex items-center gap-2">
                        <i class="ph ph-user"></i>
                        {{ $course->lecturer }}
                    </p>
                </div>
            </div>

            {{-- TABS NAVIGATION --}}
            <div class="flex items-center gap-6 mt-8 border-b border-white/10 overflow-x-auto whitespace-nowrap hide-scrollbar scroll-smooth">
                <button @click="activeTab = 'catatan'" :class="{ 'border-white text-white': activeTab === 'catatan', 'border-transparent text-slate-400 hover:text-white': activeTab !== 'catatan' }" class="pb-3 min-h-11 text-sm font-bold uppercase tracking-wider border-b-2 transition-colors flex items-center gap-2">Catatan</button>
                <button @click="activeTab = 'materi'" :class="{ 'border-white text-white': activeTab === 'materi', 'border-transparent text-slate-400 hover:text-white': activeTab !== 'materi' }" class="pb-3 min-h-11 text-sm font-bold uppercase tracking-wider border-b-2 transition-colors flex items-center gap-2">Materi Kelas</button>
                <button @click="activeTab = 'kuis'" :class="{ 'border-white text-white': activeTab === 'kuis', 'border-transparent text-slate-400 hover:text-white': activeTab !== 'kuis' }" class="pb-3 min-h-11 text-sm font-bold uppercase tracking-wider border-b-2 transition-colors flex items-center gap-2">Latihan Kuis</button>
            </div>
        </div>

        {{-- TAB: CATATAN (Excerpt 2 Baris Saja) --}}
        <div x-show="activeTab === 'catatan'" style="display: none;" x-transition.opacity class="space-y-4">
            <div class="flex flex-col sm:flex-row justify-end gap-3 mb-4">
                <button @click="showAiModal = true" class="w-full sm:w-auto justify-center min-h-11 px-4 py-2 bg-white/5 border border-white/10 text-slate-300 hover:bg-white/10 hover:text-white font-semibold text-xs rounded-xl shadow-sm transition-all active:scale-95 flex items-center gap-2">
                    <i class="ph-fill ph-sparkle"></i>
                    Buat Ringkasan AI
                </button>
                <button @click="showCreateNoteModal = true" class="w-full sm:w-auto justify-center min-h-11 px-4 py-2 bg-white border border-transparent text-black hover:bg-neutral-200 font-semibold text-xs rounded-xl shadow-sm transition-all active:scale-95 flex items-center gap-2">
                    <i class="ph ph-plus"></i>
                    Tulis Catatan Kosong
                </button>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                @foreach($notes as $note)
                <div @click="activeNote = {{ json_encode($note) }}; showNoteModal = true" 
                     class="dashboard-card {{ $note->is_ai ? 'border-amber-500/30 bg-white/5' : 'border-white/10' }} p-5 hover:-translate-y-0.5 transition-all cursor-pointer group flex flex-col h-full">
                    
                    <div class="flex justify-between items-start mb-2">
                        <h4 class="text-base font-bold text-white group-hover:text-amber-200 transition-colors line-clamp-1">{{ $note->title }}</h4>
                        @if($note->is_ai)
                            <span class="shrink-0 ml-3 text-[10px] font-bold bg-amber-400/20 text-amber-300 px-2 py-0.5 rounded-full flex items-center gap-1 uppercase tracking-wider">
                                <i class="ph-fill ph-sparkle text-xs"></i>
                                AI
                            </span>
                        @endif
                    </div>
                    
                    {{-- Excerpt 2 Baris untuk Catatan --}}
                    <p class="text-[13px] text-slate-300 line-clamp-2 leading-relaxed font-serif-editor flex-1">{{ $note->excerpt }}</p>
                    
                    @if(isset($note->keywords) && count($note->keywords) > 0)
                    <div class="mt-3 flex flex-wrap gap-1.5">
                        @foreach($note->keywords as $keyword)
                            <span class="px-2 py-0.5 {{ $note->is_ai ? 'bg-amber-400/20 text-amber-300' : 'bg-white/5 text-slate-400' }} text-[10px] font-bold uppercase tracking-wider rounded-md border border-white/5">
                                {{ $keyword }}
                            </span>
                        @endforeach
                    </div>
                    @endif
                    
                    <div class="mt-4 pt-4 border-t border-white/10 flex items-center justify-between text-xs font-medium text-slate-400">
                        <span>{{ $note->date }}</span>
                        <span class="text-white opacity-0 group-hover:opacity-100 transition-opacity flex items-center gap-1">Perbesar <i class="ph ph-arrows-out"></i></span>
                    </div>
                </div>
                @endforeach
            </div>
        </div>

        {{-- TAB: MATERI KELAS (DESAIN BARU: Ikon -> Judul -> 3 Baris Preview Teks) --}}
        <div x-show="activeTab === 'materi'" x-transition.opacity class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-5">
            
            @foreach($materials as $mat)
            <div class="dashboard-card p-5 flex flex-col h-full group">
                
                <div class="flex items-start justify-between gap-3 mb-3">
                    {{-- Icon Dokumen --}}
                    <div class="w-11 h-11 rounded-xl {{ $mat->type === 'pdf' ? 'bg-rose-500/20 text-rose-400' : 'bg-blue-500/20 text-blue-400' }} flex items-center justify-center shrink-0 border {{ $mat->type === 'pdf' ? 'border-rose-500/30' : 'border-blue-500/30' }}">
                        @if($mat->type === 'pdf')
                            <i class="ph-fill ph-file-pdf text-2xl"></i>
                        @else
                            <i class="ph-fill ph-file-text text-2xl"></i>
                        @endif
                    </div>
                    
                    {{-- Tombol Pratinjau (Mata) --}}
                    <button @click="activeMaterial = {{ json_encode($mat) }}; showMaterialModal = true" class="p-2 text-slate-400 hover:text-white hover:bg-white/10 rounded-lg transition-colors" title="Pratinjau Materi">
                        <i class="ph ph-eye text-xl"></i>
                    </button>
                </div>

                {{-- Judul Materi --}}
                <h4 class="text-base font-bold text-white group-hover:text-amber-200 transition-colors line-clamp-1 mb-2">{{ $mat->title }}</h4>
                
                {{-- Preview Teks 3 Baris --}}
                <p class="text-[13px] text-slate-300 line-clamp-3 leading-relaxed font-serif-editor flex-1 mb-4">
                    {{ $mat->preview }}
                </p>

                {{-- Footer Info --}}
                <div class="mt-auto pt-4 border-t border-white/10 flex items-center justify-between">
                    <div class="flex items-center gap-2">
                        <span class="text-[10px] font-bold text-slate-300 bg-white/10 border border-white/10 px-2 py-0.5 rounded uppercase tracking-wider">{{ $mat->type }}</span>
                        @if($mat->size)
                            <span class="w-1 h-1 rounded-full bg-white/20"></span>
                            <span class="text-[11px] font-medium text-slate-400">{{ $mat->size }}</span>
                        @endif
                    </div>
                    <span class="text-[11px] font-medium text-slate-400">{{ $mat->date }}</span>
                </div>
            </div>
            @endforeach

            {{-- TOMBOL UPLOAD MATERI BARU (Diletakkan di Card Grid Terakhir) --}}
            <button @click="showUploadModal = true" class="border-2 border-dashed border-white/20 rounded-[24px] p-5 flex flex-col items-center justify-center text-slate-400 hover:text-white hover:border-white/40 hover:bg-white/5 transition-all group min-h-[220px]">
                <div class="w-12 h-12 bg-white/10 border border-white/20 rounded-full flex items-center justify-center mb-3 group-hover:scale-110 transition-all shadow-sm">
                    <i class="ph ph-upload-simple text-xl text-slate-300 group-hover:text-white"></i>
                </div>
                <span class="font-bold text-sm text-slate-300 group-hover:text-white">Upload Materi Baru</span>
                <span class="text-xs text-slate-500 mt-1">PDF, Word, atau Text</span>
            </button>
        </div>

        {{-- TAB: KUIS / LATIHAN --}}
        <div x-show="activeTab === 'kuis'" style="display: none;" x-transition.opacity>
            <div class="flex justify-between items-center mb-6">
                <h3 class="text-lg font-bold text-white">Daftar Latihan & Kuis</h3>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-8">
                @forelse($quizzes as $quiz)
                <a href="{{ route('latihan.quiz.show', $quiz->id) }}"
                   class="dashboard-card p-5 flex items-center justify-between hover:border-white/30 hover:shadow-md transition-all cursor-pointer group bg-transparent border border-white/10">
                    <div class="flex items-center gap-4">
                        <div class="w-12 h-12 rounded-2xl {{ $quiz->status === 'selesai' ? 'bg-emerald-500/20 text-emerald-400' : 'bg-white/5 text-slate-300' }} flex items-center justify-center shrink-0 group-hover:scale-110 group-hover:rotate-3 transition-transform shadow-sm">
                            <i class="ph-fill ph-exam text-2xl"></i>
                        </div>
                        <div>
                            <h4 class="text-[15px] font-bold text-white group-hover:text-amber-200 transition-colors">{{ $quiz->title }}</h4>
                            <p class="text-xs text-slate-400 mt-1">{{ $quiz->questions }} Pertanyaan (PG & Essay)</p>
                        </div>
                    </div>
                    <div class="shrink-0 text-right">
                        @if($quiz->status === 'selesai')
                            <span class="text-[10px] font-black uppercase tracking-widest text-slate-500">Nilai</span>
                            <p class="text-3xl font-black font-geist {{ $quiz->score >= 70 ? 'text-emerald-400' : 'text-rose-400' }} leading-none mt-1">{{ $quiz->score }}</p>
                            <span class="text-[10px] font-bold text-white group-hover:underline mt-1.5 block">Review Hasil &rarr;</span>
                        @else
                            <span class="inline-flex items-center justify-center px-4 py-2.5 bg-white/10 text-white font-bold text-xs rounded-xl group-hover:bg-white/20 transition-all">
                                Mulai Kerjakan &rarr;
                            </span>
                        @endif
                    </div>
                </a>
                @empty
                    <div class="col-span-full py-8 text-center dashboard-card border-dashed border-white/20 px-4">
                        <div class="w-16 h-16 mx-auto bg-white/5 rounded-full flex items-center justify-center mb-3 mt-2 border border-white/10">
                            <i class="ph ph-exam text-3xl text-slate-400"></i>
                        </div>
                        <p class="text-slate-300 font-semibold">Belum ada Kuis</p>
                        <p class="text-xs text-slate-500 mt-1">Admin belum merilis kuis untuk mata kuliah ini.</p>
                    </div>
                @endforelse
            </div>

            <h3 class="text-lg font-bold text-white mb-4">Flashcards</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-8">
                @forelse($flashcardSets as $set)
                <a href="{{ $set->status === 'selesai' ? route('latihan.quiz.show', ['quiz' => $set->quiz_id, 'from' => 'subject']) : route('latihan.flashcard.show', $set->id) }}" 
                   class="dashboard-card p-5 flex items-center justify-between hover:border-orange-500/50 hover:shadow-md transition-all cursor-pointer group bg-transparent border border-white/10">
                    <div class="flex items-center gap-4">
                        <div class="w-12 h-12 rounded-2xl {{ $set->status === 'selesai' ? 'bg-emerald-500/20 text-emerald-400' : 'bg-orange-500/20 text-orange-400' }} flex items-center justify-center shrink-0 group-hover:scale-110 group-hover:-rotate-3 transition-transform shadow-sm">
                            <i class="ph-fill ph-cards text-2xl"></i>
                        </div>
                        <div>
                            <h4 class="text-[15px] font-bold text-white group-hover:text-amber-200 transition-colors">{{ $set->title }}</h4>
                            <p class="text-xs text-slate-400 mt-1">{{ $set->count }} Kartu Memori</p>
                        </div>
                    </div>
                    <div class="shrink-0 text-right">
                        @if($set->status === 'selesai' && $set->score !== null)
                            <span class="text-[10px] font-black uppercase tracking-widest text-slate-500">Nilai Kuis</span>
                            <p class="text-3xl font-black font-geist {{ $set->score >= 70 ? 'text-emerald-400' : 'text-rose-400' }} leading-none mt-1">{{ $set->score }}</p>
                            <span class="text-[10px] font-bold text-orange-400 group-hover:underline mt-1.5 block">Review Kuis &rarr;</span>
                        @elseif($set->status === 'selesai')
                            <span class="text-[10px] font-black uppercase tracking-widest text-slate-500">Status</span>
                            <p class="text-xl font-black font-geist text-emerald-400 leading-none mt-2">Tamat</p>
                            <span class="text-[10px] font-bold text-orange-400 group-hover:underline mt-2 block">Ulangi Hafalan &rarr;</span>
                        @else
                            <span class="inline-flex items-center justify-center px-4 py-2.5 bg-white/10 text-white font-bold text-xs rounded-xl group-hover:bg-white/20 transition-all">
                                Mulai Hafalkan &rarr;
                            </span>
                        @endif
                    </div>
                </a>
                @empty
                <div class="col-span-full py-8 text-center dashboard-card border-dashed border-white/20 px-4">
                    <div class="w-16 h-16 mx-auto bg-white/5 rounded-full flex items-center justify-center mb-3 mt-2 border border-white/10">
                        <i class="ph ph-cards text-3xl text-slate-400"></i>
                    </div>
                    <p class="text-slate-300 font-semibold">Belum ada Flashcard</p>
                    <p class="text-xs text-slate-500 mt-1">Belum ada flashcard buat di-review hari ini.</p>
                </div>
                @endforelse
            </div>
        </div>

        {{-- ========================================== --}}
        {{-- SECTION MODALS --}}
        {{-- ========================================== --}}

        {{-- 1. MODAL PRATINJAU MATERI (DENGAN SCROLL) --}}
        <div x-show="showMaterialModal" style="display: none;" class="fixed inset-0 z-50 flex items-center justify-center p-4 sm:p-6">
            <div x-show="showMaterialModal" x-transition.opacity class="absolute inset-0 bg-neutral-900/80 backdrop-blur-xl" @click="showMaterialModal = false; isEditingMaterial = false;"></div>

            <form x-show="showMaterialModal" 
                 :action="`/subjects/{{ $subject->id }}/materials/${activeMaterial.id}`" method="POST"
                 x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-8 scale-95" x-transition:enter-end="opacity-100 translate-y-0 scale-100"
                 x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100 translate-y-0 scale-100" x-transition:leave-end="opacity-0 translate-y-8 scale-95"
                 class="relative bg-black/40 backdrop-blur-xl rounded-[24px] max-w-4xl w-full max-h-[85vh] flex flex-col shadow-2xl border border-white/10">
                @csrf
                @method('PATCH')
                
                {{-- Header Pratinjau --}}
                <div class="flex items-center justify-between p-6 border-b border-white/10 shrink-0 bg-transparent rounded-t-[24px]">
                    <div class="flex items-center gap-4">
                        <div class="w-10 h-10 rounded-xl bg-white/5 text-slate-300 border border-white/10 flex items-center justify-center shrink-0">
                            <i class="ph-fill ph-file-text text-xl"></i>
                        </div>
                        <div>
                            <h3 class="text-lg font-bold text-white font-geist" x-text="activeMaterial.title"></h3>
                            <div class="flex items-center gap-2 mt-0.5">
                                <span class="text-[10px] font-bold text-slate-300 bg-white/5 border border-white/10 px-2 py-0.5 rounded uppercase tracking-wider" x-text="activeMaterial.type"></span>
                                <span class="text-[11px] text-slate-400" x-text="activeMaterial.date"></span>
                            </div>
                        </div>
                    </div>
                    <button type="button" @click="showMaterialModal = false; isEditingMaterial = false;" class="p-2 hover:bg-white/10 rounded-full text-slate-400 hover:text-white transition-colors">
                        <i class="ph ph-x text-xl"></i>
                    </button>
                </div>

                {{-- Area Pratinjau Teks/PDF (Scrollable) --}}
                <div class="p-6 md:p-8 overflow-y-auto font-serif-editor text-slate-200 text-[15px] md:text-base leading-relaxed whitespace-pre-wrap bg-transparent flex-1">
                    
                    <template x-if="activeMaterial.type === 'pdf'">
                        <iframe :src="activeMaterial.file_path ? '{{ asset('storage') }}/' + activeMaterial.file_path : ''" class="w-full h-[60vh] border border-white/10 rounded-lg shadow-inner"></iframe>
                    </template>
                    
                    <template x-if="activeMaterial.type !== 'pdf'">
                        <div>
                            <div x-show="!isEditingMaterial" x-text="activeMaterial.content"></div>
                            <textarea x-show="isEditingMaterial" name="content" x-model="activeMaterial.content" class="w-full h-[40vh] bg-transparent border-0 focus:ring-0 text-slate-200 p-0 font-serif-editor text-[15px] md:text-base leading-relaxed resize-none"></textarea>
                        </div>
                    </template>

                </div>

                {{-- Footer Aksi (Edit / Hapus) --}}
                <div class="p-4 sm:p-5 border-t border-white/10 shrink-0 flex flex-col-reverse sm:flex-row justify-between sm:items-center gap-3 bg-white/5 rounded-b-[24px]">
                    <div class="w-full sm:w-auto">
                        <button type="button" @click="$refs.deleteForm.submit()" onclick="return confirm('Yakin ingin menghapus materi ini?')" class="w-full sm:w-auto justify-center px-4 py-2 min-h-11 text-rose-400 hover:bg-rose-500/20 hover:text-rose-300 font-semibold text-sm rounded-xl transition-colors active:scale-95 flex items-center gap-2">
                            <i class="ph ph-trash"></i>
                            Hapus
                        </button>
                    </div>
                    
                    <template x-if="activeMaterial.type !== 'pdf'">
                        <div class="w-full sm:w-auto flex flex-col sm:flex-row gap-2">
                            <button x-show="isEditingMaterial" type="button" @click="isEditingMaterial = false" class="w-full sm:w-auto justify-center px-5 py-2.5 min-h-11 bg-transparent text-slate-300 hover:text-white font-semibold text-sm rounded-xl transition-colors active:scale-95">
                                Batal
                            </button>
                            <button x-show="!isEditingMaterial" type="button" @click="isEditingMaterial = true" class="w-full sm:w-auto justify-center px-5 py-2.5 min-h-11 bg-white text-black font-semibold text-sm rounded-xl hover:bg-neutral-200 shadow-sm transition-colors active:scale-95">
                                Edit Teks Materi
                            </button>
                            <button x-show="isEditingMaterial" type="submit" class="w-full sm:w-auto justify-center px-5 py-2.5 min-h-11 bg-white text-black font-semibold text-sm rounded-xl hover:bg-neutral-200 shadow-sm transition-colors active:scale-95">
                                Simpan Perubahan
                            </button>
                        </div>
                    </template>
                </div>
            </form>
            
            <form x-ref="deleteForm" method="POST" :action="`/subjects/{{ $subject->id }}/materials/${activeMaterial.id}`" class="hidden">
                @csrf
                @method('DELETE')
            </form>
        </div>

        {{-- 2. MODAL PERBESAR CATATAN --}}
        <div x-show="showNoteModal" style="display: none;" class="fixed inset-0 z-50 flex items-center justify-center p-4 sm:p-6">
            <div x-show="showNoteModal" x-transition.opacity class="absolute inset-0 bg-neutral-900/80 backdrop-blur-xl" @click="showNoteModal = false"></div>

            <form x-show="showNoteModal" 
                 :action="'/coretan/' + activeNote.id" method="POST"
                 x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-8 scale-95" x-transition:enter-end="opacity-100 translate-y-0 scale-100"
                 x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100 translate-y-0 scale-100" x-transition:leave-end="opacity-0 translate-y-8 scale-95"
                 class="relative bg-neutral-900/80 backdrop-blur-xl rounded-[24px] max-w-3xl w-full max-h-[85vh] flex flex-col shadow-2xl border border-white/10">
                @csrf
                @method('PATCH')
                
                <div class="flex items-start justify-between p-6 border-b border-white/10 shrink-0">
                    <div class="flex-1 mr-4">
                        <div class="flex items-center gap-3 mb-2">
                            <input type="text" name="title" x-model="activeNote.title" class="w-full text-xl font-bold text-white font-geist bg-transparent border-0 border-b-2 border-transparent hover:border-white/20 focus:border-white/50 focus:ring-0 px-0 py-1 transition-colors" placeholder="Judul Catatan">
                            <span x-show="activeNote.is_ai" class="text-[10px] font-bold bg-amber-400/20 text-amber-300 px-2 py-0.5 rounded-full uppercase tracking-wider shrink-0 flex items-center gap-1 border border-amber-400/20">
                                <i class="ph-fill ph-sparkle text-xs"></i> AI Generated
                            </span>
                        </div>
                        <template x-if="activeNote.keywords && activeNote.keywords.length > 0">
                            <div class="flex flex-wrap gap-2">
                                <template x-for="keyword in activeNote.keywords">
                                    <span class="px-2.5 py-1 bg-white/5 text-slate-300 border border-white/10 text-[11px] font-bold uppercase tracking-wider rounded-lg" x-text="keyword"></span>
                                </template>
                            </div>
                        </template>
                    </div>
                    <button type="button" @click="showNoteModal = false" class="p-2 hover:bg-white/10 rounded-full text-slate-400 hover:text-white transition-colors">
                        <i class="ph ph-x text-xl"></i>
                    </button>
                </div>

                <div class="p-6 md:p-8 overflow-y-auto flex-1 bg-transparent">
                    <textarea name="content" x-model="activeNote.content" class="w-full h-full min-h-[300px] font-serif-editor text-slate-200 text-lg leading-relaxed bg-transparent border-0 focus:ring-0 px-0 resize-none placeholder:text-slate-500" placeholder="Isi catatan..."></textarea>
                </div>

                <div class="p-4 sm:p-6 border-t border-white/10 shrink-0 bg-white/5 rounded-b-[24px] flex flex-col sm:flex-row justify-between sm:items-center gap-4">
                    <span class="text-xs font-medium text-slate-400" x-text="'Terakhir diedit: ' + activeNote.date"></span>
                    
                    <div class="flex flex-col-reverse sm:flex-row items-center gap-2 sm:gap-3 w-full sm:w-auto">
                        <button type="button" @click="if(confirm('Yakin ingin menghapus catatan ini?')) { $el.closest('form').action = '/coretan/' + activeNote.id; $el.closest('form').querySelector('input[name=_method]').value = 'DELETE'; $el.closest('form').submit(); }" class="w-full sm:w-auto justify-center px-4 py-2 min-h-11 text-rose-400 hover:bg-rose-500/20 hover:text-rose-300 font-semibold text-sm rounded-xl transition-colors active:scale-95">
                            Hapus
                        </button>
                        <button type="submit" class="w-full sm:w-auto justify-center px-5 py-2 min-h-11 bg-white text-black font-semibold text-sm rounded-xl hover:bg-neutral-200 shadow-sm transition-colors flex items-center gap-2 active:scale-95">
                            <i class="ph ph-floppy-disk text-lg"></i>
                            Simpan Perubahan
                        </button>
                    </div>
                </div>
            </form>
        </div>

        {{-- 3. MODAL UPLOAD MATERI --}}
        <div x-show="showUploadModal" style="display: none;" class="fixed inset-0 z-50 flex items-center justify-center p-4">
            <div x-show="showUploadModal" x-transition.opacity class="absolute inset-0 bg-neutral-900/80 backdrop-blur-xl" @click="showUploadModal = false"></div>

            <div x-show="showUploadModal" 
                 x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100"
                 x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-95"
                 class="relative bg-neutral-900/80 backdrop-blur-xl rounded-[24px] max-w-lg w-full p-6 shadow-2xl border border-white/10">
                
                <div class="flex items-center justify-between pb-4 border-b border-white/10 mb-5">
                    <h3 class="text-lg font-bold text-white font-geist">Upload / Tulis Materi</h3>
                    <button @click="showUploadModal = false" class="p-1.5 hover:bg-white/10 rounded-full text-slate-400 hover:text-white transition-colors">
                        <i class="ph ph-x text-xl"></i>
                    </button>
                </div>

                <form action="{{ route('subjects.materials.store', $subject) }}" method="POST" enctype="multipart/form-data" class="space-y-5">
                    @csrf
                    <div>
                        <label class="block text-xs font-bold text-slate-300 uppercase tracking-wider mb-2">Judul Materi</label>
                        <input type="text" name="title" required placeholder="Contoh: Bab 2 - Teori Kritis" class="w-full px-4 py-3 bg-black/30 border border-transparent rounded-xl outline-none text-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-500 transition-all text-white placeholder:text-white/40" />
                    </div>

                    <div x-data="{ inputType: 'file', fileName: '' }">
                        <div class="flex items-center gap-4 mb-3">
                            <label class="flex items-center gap-2 text-sm font-semibold cursor-pointer text-slate-300 hover:text-white transition-colors">
                                <input type="radio" name="input_type" value="file" x-model="inputType" class="text-blue-500 focus:ring-blue-500 bg-black/30 border-white/20">
                                Upload File
                            </label>
                            <label class="flex items-center gap-2 text-sm font-semibold cursor-pointer text-slate-300 hover:text-white transition-colors">
                                <input type="radio" name="input_type" value="text" x-model="inputType" class="text-blue-500 focus:ring-blue-500 bg-black/30 border-white/20">
                                Paste Text
                            </label>
                        </div>
                        
                        <div x-show="inputType === 'file'" onclick="document.getElementById('file-upload').click()" class="relative flex justify-center px-6 pt-5 pb-6 border-2 border-white/20 border-dashed rounded-xl hover:bg-white/5 hover:border-white/40 transition-colors group cursor-pointer">
                            <div class="space-y-1 text-center pointer-events-none">
                                <i class="ph ph-upload-simple text-4xl text-slate-400 group-hover:text-white transition-colors"></i>
                                <div class="flex text-sm text-slate-300 justify-center">
                                    <label class="relative cursor-pointer rounded-md font-semibold text-white">
                                        <span>Pilih File</span>
                                        <input id="file-upload" name="file" type="file" class="sr-only" @change="fileName = $event.target.files[0] ? $event.target.files[0].name : ''">
                                    </label>
                                </div>
                                <p class="text-xs text-slate-400" x-show="!fileName">PDF, DOCX up to 10MB</p>
                                <p class="text-sm font-bold text-white mt-2" x-show="fileName" x-text="fileName" style="display: none;"></p>
                            </div>
                        </div>

                        <div x-show="inputType === 'text'" style="display: none;">
                            <textarea name="content" rows="6" placeholder="Paste materi tulisan panjang di sini..." class="w-full px-4 py-3 bg-black/30 border border-transparent rounded-xl outline-none text-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-500 transition-all text-white placeholder:text-white/40 font-serif-editor"></textarea>
                        </div>
                    </div>

                    <div class="pt-4 flex flex-col-reverse sm:flex-row items-center sm:justify-end gap-3 border-t border-white/10 mt-2">
                        <button type="button" @click="showUploadModal = false" class="w-full sm:w-auto justify-center px-5 py-2.5 min-h-11 bg-white/10 text-white font-semibold text-sm rounded-xl hover:bg-white/20 transition-colors active:scale-95">Batal</button>
                        <button type="submit" class="w-full sm:w-auto justify-center px-5 py-2.5 min-h-11 bg-white text-black font-semibold text-sm rounded-xl hover:bg-neutral-200 shadow-sm transition-colors flex items-center gap-2 active:scale-95">Simpan Materi</button>
                    </div>
                </form>
            </div>
        </div>

        {{-- 4. MODAL BUAT CATATAN BARU --}}
        <div x-show="showCreateNoteModal" style="display: none;" class="fixed inset-0 z-50 flex items-center justify-center p-4">
            <div x-show="showCreateNoteModal" x-transition.opacity class="absolute inset-0 bg-neutral-900/80 backdrop-blur-xl" @click="showCreateNoteModal = false"></div>

            <div x-show="showCreateNoteModal" 
                 x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100"
                 x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-95"
                 class="relative bg-neutral-900/80 backdrop-blur-xl rounded-[24px] max-w-2xl w-full p-6 shadow-2xl border border-white/10">
                
                <div class="flex items-center justify-between pb-4 border-b border-white/10 mb-5">
                    <h3 class="text-lg font-bold text-white font-geist">Tulis Catatan Baru</h3>
                    <button @click="showCreateNoteModal = false" class="p-1.5 hover:bg-white/10 rounded-full text-slate-400 hover:text-white transition-colors">
                        <i class="ph ph-x text-xl"></i>
                    </button>
                </div>

                <form action="{{ route('coretan.store') }}" method="POST" class="space-y-5">
                    @csrf
                    <input type="hidden" name="subject_id" value="{{ $course->id }}">
                    <input type="hidden" name="visibility" value="private">
                    
                    <div>
                        <label class="block text-xs font-bold text-slate-300 uppercase tracking-wider mb-2">Judul Catatan <span class="text-red-500">*</span></label>
                        <input type="text" name="title" required placeholder="Contoh: Rangkuman Bab 1" class="w-full px-4 py-3 bg-black/30 border border-transparent rounded-xl outline-none text-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-500 transition-all text-white placeholder:text-white/40" />
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-300 uppercase tracking-wider mb-2">Isi Catatan <span class="text-red-500">*</span></label>
                        <textarea name="content" rows="8" required placeholder="Mulai mengetik catatan Anda di sini..." class="w-full px-4 py-3 bg-black/30 border border-transparent rounded-xl outline-none text-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-500 transition-all text-white placeholder:text-white/40 font-serif-editor"></textarea>
                    </div>

                    <div class="pt-4 flex flex-col-reverse sm:flex-row items-center sm:justify-end gap-3 border-t border-white/10 mt-2">
                        <button type="button" @click="showCreateNoteModal = false" class="w-full sm:w-auto justify-center px-5 py-2.5 min-h-11 bg-white/10 text-white font-semibold text-sm rounded-xl hover:bg-white/20 transition-colors active:scale-95">Batal</button>
                        <button type="submit" class="w-full sm:w-auto justify-center px-5 py-2.5 min-h-11 bg-white text-black font-semibold text-sm rounded-xl hover:bg-neutral-200 shadow-sm transition-colors flex items-center gap-2 active:scale-95">Simpan Catatan</button>
                    </div>
                </form>
            </div>
        </div>

        {{-- 5. MODAL BUAT RINGKASAN AI --}}
        <div x-show="showAiModal" style="display: none;" class="fixed inset-0 z-50 flex items-center justify-center p-4">
            <div x-show="showAiModal" x-transition.opacity class="absolute inset-0 bg-neutral-900/80 backdrop-blur-xl" @click="showAiModal = false"></div>

            <div x-show="showAiModal" 
                 x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100"
                 x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-95"
                 class="relative bg-neutral-900/80 backdrop-blur-xl rounded-[24px] max-w-lg w-full p-6 shadow-2xl border border-white/10">
                
                <div class="flex items-center justify-between pb-4 border-b border-white/10 mb-5">
                    <h3 class="text-lg font-bold text-white font-geist flex items-center gap-2">
                        <i class="ph-fill ph-sparkle text-white"></i>
                        Buat Ringkasan AI
                    </h3>
                    <button @click="showAiModal = false" class="p-1.5 hover:bg-white/10 rounded-full text-slate-400 hover:text-white transition-colors">
                        <i class="ph ph-x text-xl"></i>
                    </button>
                </div>

                <form action="{{ route('subjects.notes.ai', $course->id) }}" method="POST" class="space-y-5" x-data="{ aiSource: 'materi' }">
                    @csrf
                    
                    <div>
                        <div class="flex items-center gap-4 mb-4">
                            <label class="flex items-center gap-2 text-sm font-semibold cursor-pointer text-slate-300 hover:text-white transition-colors">
                                <input type="radio" name="ai_source" value="materi" x-model="aiSource" class="text-blue-500 focus:ring-blue-500 bg-black/30 border-white/20">
                                Dari Materi Kelas
                            </label>
                            <label class="flex items-center gap-2 text-sm font-semibold cursor-pointer text-slate-300 hover:text-white transition-colors">
                                <input type="radio" name="ai_source" value="manual" x-model="aiSource" class="text-blue-500 focus:ring-blue-500 bg-black/30 border-white/20">
                                Teks Manual
                            </label>
                        </div>
                        
                        <div x-show="aiSource === 'materi'" class="space-y-2">
                            <label class="block text-xs font-bold text-slate-300 uppercase tracking-wider">Pilih Materi</label>
                            <select name="material_id" class="w-full px-4 py-3 bg-black/30 border border-transparent rounded-xl outline-none text-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-500 transition-all text-white">
                                <option value="" class="bg-slate-900 text-white">-- Pilih Materi --</option>
                                @foreach($materials as $mat)
                                    <option value="{{ $mat->id }}" class="bg-slate-900 text-white">{{ $mat->title }}</option>
                                @endforeach
                            </select>
                            <p class="text-[11px] text-slate-400 mt-1">AI akan mengekstrak teks dari file materi tersebut untuk dirangkum.</p>
                        </div>

                        <div x-show="aiSource === 'manual'" style="display: none;" class="space-y-2">
                            <label class="block text-xs font-bold text-slate-300 uppercase tracking-wider">Paste Teks / Artikel</label>
                            <textarea name="manual_text" rows="6" placeholder="Paste jurnal, artikel, atau tulisan panjang di sini..." class="w-full px-4 py-3 bg-black/30 border border-transparent rounded-xl outline-none text-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-500 transition-all text-white placeholder:text-white/40 font-serif-editor"></textarea>
                        </div>
                    </div>

                    <div class="pt-4 flex flex-col-reverse sm:flex-row items-center sm:justify-end gap-3 border-t border-white/10 mt-2">
                        <button type="button" @click="showAiModal = false" class="w-full sm:w-auto justify-center px-5 py-2.5 min-h-11 bg-white/10 text-white font-semibold text-sm rounded-xl hover:bg-white/20 transition-colors active:scale-95">Batal</button>
                        <button type="submit" class="w-full sm:w-auto justify-center px-5 py-2.5 min-h-11 bg-white text-black font-semibold text-sm rounded-xl hover:bg-neutral-200 shadow-sm transition-colors flex items-center gap-2 active:scale-95">Generate AI Summary</button>
                    </div>
                </form>
            </div>
        </div>

    </div>
</x-app-layout>
