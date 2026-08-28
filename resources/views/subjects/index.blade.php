<x-app-layout>
    <x-slot name="pageTitle">Mata Kuliah</x-slot>
    @php
        // Map real data from actual user subjects
        $mappedSubjects = collect($mySubjects)->map(function($subject) {
            return (object)[
                'id' => $subject->id, 
                'title' => $subject->name, 
                'lecturer' => $subject->lecturer ?: '-', 
                'description' => $subject->description,
                'code' => $subject->code ?? 'MK-101', 
                'notesCount' => $subject->notes_count ?? 0, 
                'materialsCount' => $subject->materials_count ?? 0, 
                'icon' => 'ph-books'
            ];
        });

        // Get recent notes and materials for activities (hanya yang nyambung sama mata kuliah)
        $recentNotes = \App\Models\Note::where('user_id', auth()->id())->whereNotNull('subject_id')->latest()->take(5)->get()->map(function($note) {
            return (object)['id' => 'n'.$note->id, 'type' => 'note', 'title' => $note->title, 'courseTitle' => $note->subject?->name ?? 'Tanpa Mata Kuliah', 'timeAgo' => $note->created_at->diffForHumans(), 'created_at' => $note->created_at];
        });
        $recentMaterials = \App\Models\Material::where('user_id', auth()->id())->whereNotNull('subject_id')->latest()->take(5)->get()->map(function($mat) {
            return (object)['id' => 'm'.$mat->id, 'type' => 'pdf', 'title' => $mat->title, 'courseTitle' => $mat->subject?->name ?? 'Tanpa Mata Kuliah', 'timeAgo' => $mat->created_at->diffForHumans(), 'created_at' => $mat->created_at];
        });
        
        $recentActivities = $recentNotes->concat($recentMaterials)->sortByDesc('created_at')->take(5);
    @endphp

    {{-- WRAPPER ALPINE.JS UNTUK MODAL: x-data="{ showAddModal: false, showEditModal: false, editData: {} }" --}}
    <div x-data="{ showAddModal: false, showEditModal: false, editData: {} }" class="space-y-8 animate-fadeIn mt-4">
        
        {{-- Top Header & Search/Add Bar --}}
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 pb-6 border-b border-white/10">
            <div>
                <h1 class="text-2xl sm:text-3xl font-bold text-white tracking-tight font-geist">
                    Mata Kuliah & Ruang Belajar <span style="font-family: 'Apple Color Emoji', 'Segoe UI Emoji', 'Noto Color Emoji', sans-serif;"><i class="ph ph-books text-[1.1em] align-middle text-amber-300"></i></span>
                </h1>
                <p class="text-sm text-slate-300 mt-2">
                    Kelola catatan, materi bacaan, dan latihan kuis per mata kuliah di sini.
                </p>
            </div>

            {{-- Tombol Buka Modal --}}
            <button @click="showAddModal = true" class="ios-liquid-btn w-full sm:w-auto justify-center min-h-11 px-5 py-2.5 rounded-xl font-semibold text-sm flex items-center gap-2 cursor-pointer">
                <i class="ph-bold ph-plus text-lg"></i>
                Tambah Mata Kuliah
            </button>
        </div>

        {{-- Courses Grid --}}
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 lg:gap-8">
            @forelse($mappedSubjects as $c)
                {{-- Di Laravel, onClick biasanya diganti dengan tag <a> href ke halaman detail --}}
                <a href="{{ Route::has('subjects.show') ? route('subjects.show', $c->id) : '#' }}" 
                   class="dashboard-card relative p-6 cursor-pointer group flex flex-col justify-between min-h-[220px]">
                    
                    <div class="relative z-10">
                        <div class="flex items-center justify-between mb-5">
                            <div class="w-12 h-12 rounded-2xl bg-white/5 border border-white/10 text-slate-300 group-hover:bg-white/10 group-hover:text-white flex items-center justify-center transition-colors">
                                <i class="ph {{ $c->icon }} text-2xl"></i>
                            </div>
                            
                            <div class="flex items-center gap-2">
                                <span class="text-[11px] font-bold text-amber-300/80 px-2.5 py-1 uppercase tracking-widest">
                                    {{ $c->code ?? 'MK-101' }}
                                </span>
                                
                                {{-- Aksi Edit & Hapus --}}
                                <div class="opacity-100 sm:opacity-0 sm:group-hover:opacity-100 transition-opacity flex items-center gap-1">
                                    <button @click.prevent.stop="editData = { id: '{{ $c->id }}', name: '{{ addslashes($c->title) }}', code: '{{ addslashes($c->code ?? '') }}', lecturer: '{{ addslashes($c->lecturer ?? '') }}', description: '{{ addslashes($c->description ?? '') }}' }; showEditModal = true" class="p-1.5 text-slate-300 hover:text-white hover:bg-white/10 active:bg-white/20 rounded-lg transition-colors" title="Edit Mata Kuliah">
                                        <i class="ph ph-pencil-simple text-lg"></i>
                                    </button>
                                    <form action="{{ route('subjects.destroy', $c->id) }}" method="POST" class="inline" @click.stop onsubmit="return confirm('Hapus mata kuliah ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="p-1.5 text-slate-400 hover:text-rose-400 hover:bg-rose-500/20 active:bg-rose-500/30 rounded-lg transition-colors" title="Hapus Mata Kuliah">
                                            <i class="ph ph-trash text-lg"></i>
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>

                        <h3 class="text-xl font-bold text-white group-hover:text-amber-200 transition-colors leading-snug">
                            {{ $c->title }}
                        </h3>
                        <p class="text-[13px] font-medium text-slate-300 mt-1.5">{{ $c->lecturer }}</p>
                        
                        @if($c->description)
                            <p class="text-xs text-slate-400 mt-3 line-clamp-2 leading-relaxed font-medium">
                                {{ $c->description }}
                            </p>
                        @endif
                    </div>

                    <div class="mt-6 pt-4 border-t border-white/10 flex items-center justify-between text-xs font-semibold text-slate-300 relative z-10">
                        <div class="flex items-center gap-4">
                            <span class="flex items-center gap-1.5">
                                <i class="ph ph-file-text text-lg text-slate-400"></i>
                                {{ $c->notesCount }} Catatan
                            </span>
                            <span class="flex items-center gap-1.5">
                                <i class="ph ph-file-pdf text-lg text-slate-400"></i>
                                {{ $c->materialsCount }} Materi
                            </span>
                        </div>
                        <i class="ph ph-caret-right text-lg text-slate-400 group-hover:text-white group-hover:translate-x-1 transition-all"></i>
                    </div>
                </a>
            @empty
                <div class="col-span-full py-20 flex flex-col items-center justify-center text-center dashboard-card border-dashed border-white/20">
                    <div class="w-16 h-16 bg-white/5 border border-white/10 rounded-full flex items-center justify-center mb-6">
                        <i class="ph ph-ghost text-3xl text-slate-300"></i>
                    </div>
                    <h3 class="text-xl font-bold text-white">Belum ada mata kuliah</h3>
                    <p class="text-slate-300 mt-2">Input mata kuliah dulu biar keliatan sibuk.</p>
                </div>
            @endforelse
        </div>

        {{-- Aktivitas Terakhir Section --}}
        @if($recentActivities->isNotEmpty())
        <div class="pt-4">
            <h3 class="text-[11px] font-bold uppercase tracking-widest text-slate-400 mb-4 flex items-center gap-2">
                <span class="w-2 h-2 rounded-full bg-white/20"></span> Aktivitas Terakhir
            </h3>
            
            <div class="dashboard-card divide-y divide-white/10 overflow-hidden !p-0">
                @foreach($recentActivities as $act)
                    <div class="p-4 hover:bg-white/5 flex items-center justify-between transition-colors cursor-pointer group">
                        <div class="flex items-center gap-4">
                            <div class="w-10 h-10 rounded-xl bg-white/5 text-slate-300 flex items-center justify-center shrink-0 border border-white/10 group-hover:bg-white/10 group-hover:text-white transition-colors">
                                @if($act->type === 'note')
                                    <i class="ph ph-file-text text-xl"></i>
                                @elseif($act->type === 'pdf')
                                    <i class="ph ph-file-pdf text-xl"></i>
                                @else
                                    <i class="ph ph-file text-xl"></i>
                                @endif
                            </div>
                            <div>
                                <h4 class="text-sm font-semibold text-white">{{ $act->title }}</h4>
                                <p class="text-xs text-slate-400 mt-0.5 font-medium">{{ $act->courseTitle }}</p>
                            </div>
                        </div>
                        <span class="text-[11px] font-semibold text-slate-400">{{ $act->timeAgo }}</span>
                    </div>
                @endforeach
            </div>
        </div>
        @endif

        {{-- ADD COURSE MODAL (Dikendalikan oleh Alpine.js) --}}
        <div x-show="showAddModal" 
             style="display: none;" 
             class="fixed inset-0 z-50 flex items-center justify-center p-4 sm:p-0">
            
            {{-- Backdrop dengan efek Glass --}}
            <div x-show="showAddModal" 
                 x-transition:enter="ease-out duration-300"
                 x-transition:enter-start="opacity-0"
                 x-transition:enter-end="opacity-100"
                 x-transition:leave="ease-in duration-200"
                 x-transition:leave-start="opacity-100"
                 x-transition:leave-end="opacity-0"
                 class="absolute inset-0 bg-neutral-900/40 backdrop-blur-sm"
                 @click="showAddModal = false">
            </div>

            {{-- Modal Box --}}
            <div x-show="showAddModal"
                 x-transition:enter="ease-out duration-300"
                 x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                 x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                 x-transition:leave="ease-in duration-200"
                 x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                 x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                 class="relative bg-neutral-900/80 backdrop-blur-xl rounded-[24px] max-w-md w-full p-6 sm:p-8 shadow-2xl border border-white/10">
                
                <div class="flex items-center justify-between pb-4 border-b border-white/10">
                    <h3 class="text-lg font-bold text-white">Tambah Mata Kuliah</h3>
                    <button @click="showAddModal = false" class="p-1.5 hover:bg-white/10 rounded-full text-slate-400 hover:text-white transition-colors">
                        <i class="ph ph-x text-lg"></i>
                    </button>
                </div>

                {{-- FORM (Akan di-POST ke web.php route 'subjects.store') --}}
                <form action="{{ Route::has('subjects.store') ? route('subjects.store') : '#' }}" method="POST" class="mt-5 space-y-4">
                    @csrf
                    
                    <div>
                        <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-2">Nama Mata Kuliah <span class="text-red-500">*</span></label>
                        <input type="text" name="name" required placeholder="Contoh: Psikologi Perkembangan"
                               class="w-full px-4 py-3 bg-black/30 border border-transparent rounded-xl outline-none text-sm text-white placeholder-white/40 focus:ring-2 focus:ring-blue-500 transition-all font-medium" />
                    </div>

                    <div>
                        <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-2">Dosen Pengampu</label>
                        <input type="text" name="lecturer" placeholder="Contoh: Dr. Herman, M.Si."
                               class="w-full px-4 py-3 bg-black/30 border border-transparent rounded-xl outline-none text-sm text-white placeholder-white/40 focus:ring-2 focus:ring-blue-500 transition-all font-medium" />
                    </div>

                    <div>
                        <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-2">Deskripsi Mata Kuliah</label>
                        <textarea name="description" placeholder="Contoh: Mata kuliah ini membahas tentang..." rows="2"
                               class="w-full px-4 py-3 bg-black/30 border border-transparent rounded-xl outline-none text-sm text-white placeholder-white/40 focus:ring-2 focus:ring-blue-500 transition-all font-medium"></textarea>
                    </div>

                    <div>
                        <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-2">Kode MK</label>
                        <input type="text" name="code" placeholder="Contoh: PSI-201"
                               class="w-full px-4 py-3 bg-black/30 border border-transparent rounded-xl outline-none text-sm text-white placeholder-white/40 focus:ring-2 focus:ring-blue-500 transition-all font-medium" />
                    </div>

                    <div class="pt-4 flex items-center justify-end gap-3">
                        <button type="button" @click="showAddModal = false" class="px-5 py-2.5 bg-white/10 text-white font-semibold text-sm rounded-xl hover:bg-white/20 transition-colors">
                            Batal
                        </button>
                        <button type="submit" class="px-5 py-2.5 bg-white text-black font-medium text-sm rounded-xl hover:bg-neutral-200 shadow-lg transition-colors">
                            Simpan
                        </button>
                    </div>
                </form>

            </div>
        </div>

        {{-- EDIT COURSE MODAL --}}
        <div x-show="showEditModal" 
             style="display: none;" 
             class="fixed inset-0 z-50 flex items-center justify-center p-4 sm:p-0">
            
            <div x-show="showEditModal" 
                 x-transition:enter="ease-out duration-300"
                 x-transition:enter-start="opacity-0"
                 x-transition:enter-end="opacity-100"
                 x-transition:leave="ease-in duration-200"
                 x-transition:leave-start="opacity-100"
                 x-transition:leave-end="opacity-0"
                 class="absolute inset-0 bg-neutral-900/40 backdrop-blur-sm"
                 @click="showEditModal = false">
            </div>

            <div x-show="showEditModal"
                 x-transition:enter="ease-out duration-300"
                 x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                 x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                 x-transition:leave="ease-in duration-200"
                 x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                 x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                 class="relative bg-neutral-900/80 backdrop-blur-xl rounded-[24px] max-w-md w-full p-6 sm:p-8 shadow-2xl border border-white/10">
                
                <div class="flex items-center justify-between pb-4 border-b border-white/10">
                    <h3 class="text-lg font-bold text-white">Edit Mata Kuliah</h3>
                    <button @click="showEditModal = false" class="p-1.5 hover:bg-white/10 rounded-full text-slate-400 hover:text-white transition-colors">
                        <i class="ph ph-x text-lg"></i>
                    </button>
                </div>

                <form :action="`/subjects/${editData.id}`" method="POST" class="mt-5 space-y-4">
                    @csrf
                    @method('PUT')
                    
                    <div>
                        <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-2">Nama Mata Kuliah <span class="text-red-500">*</span></label>
                        <input type="text" name="name" x-model="editData.name" required placeholder="Contoh: Psikologi Perkembangan"
                               class="w-full px-4 py-3 bg-black/30 border border-transparent rounded-xl outline-none text-sm text-white placeholder-white/40 focus:ring-2 focus:ring-blue-500 transition-all font-medium" />
                    </div>

                    <div>
                        <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-2">Dosen Pengampu</label>
                        <input type="text" name="lecturer" x-model="editData.lecturer" placeholder="Contoh: Dr. Herman, M.Si."
                               class="w-full px-4 py-3 bg-black/30 border border-transparent rounded-xl outline-none text-sm text-white placeholder-white/40 focus:ring-2 focus:ring-blue-500 transition-all font-medium" />
                    </div>

                    <div>
                        <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-2">Deskripsi Mata Kuliah</label>
                        <textarea name="description" x-model="editData.description" placeholder="Contoh: Mata kuliah ini membahas tentang..." rows="2"
                               class="w-full px-4 py-3 bg-black/30 border border-transparent rounded-xl outline-none text-sm text-white placeholder-white/40 focus:ring-2 focus:ring-blue-500 transition-all font-medium"></textarea>
                    </div>

                    <div>
                        <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-2">Kode MK</label>
                        <input type="text" name="code" x-model="editData.code" placeholder="Contoh: PSI-201"
                               class="w-full px-4 py-3 bg-black/30 border border-transparent rounded-xl outline-none text-sm text-white placeholder-white/40 focus:ring-2 focus:ring-blue-500 transition-all font-medium" />
                    </div>

                    <div class="pt-4 flex items-center justify-end gap-3">
                        <button type="button" @click="showEditModal = false" class="px-5 py-2.5 bg-white/10 text-white font-semibold text-sm rounded-xl hover:bg-white/20 transition-colors">
                            Batal
                        </button>
                        <button type="submit" class="px-5 py-2.5 bg-white text-black font-medium text-sm rounded-xl hover:bg-neutral-200 shadow-lg transition-colors">
                            Simpan Perubahan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>