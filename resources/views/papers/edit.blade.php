<x-app-layout>
    <x-slot name="pageTitle">{{ Str::limit($paper->title, 40) }}</x-slot>
    <x-slot name="pageSubtitle">Ngedit jurnal · <span class="font-bold text-amber-500 uppercase text-[10px] tracking-widest">{{ $paper->status }}</span></x-slot>
    <x-slot name="headerActions">
        <div class="flex items-center gap-3">
            <a href="{{ route('papers.my') }}" class="px-5 py-2.5 bg-white/5 text-slate-300 text-sm font-bold rounded-xl hover:bg-white/10 hover:text-white transition-all active:scale-95 flex items-center gap-2 border border-white/10 shadow-inner">
                <i class="ph-bold ph-arrow-left"></i> Kembali
            </a>
            @if($paper->status !== 'published')
                <form method="POST" action="{{ route('papers.publish', $paper) }}">
                    @csrf
                    <button type="submit"
                            class="px-5 py-2.5 bg-emerald-500 text-white text-sm font-bold rounded-xl hover:bg-emerald-400 hover:scale-105 active:scale-95 transition-all shadow-[0_0_15px_rgba(16,185,129,0.3)] flex items-center gap-2">
                        <i class="ph-bold ph-paper-plane-right"></i> Publish
                    </button>
                </form>
            @else
                <span class="text-[11px] font-bold text-emerald-400 uppercase tracking-widest bg-emerald-500/10 border border-emerald-500/20 px-3 py-2 rounded-xl flex items-center gap-1.5 shadow-inner">
                    <i class="ph-bold ph-check-circle"></i> Published
                </span>
            @endif
            
            <form method="POST" action="{{ route('papers.destroy', $paper) }}">
                @csrf @method('DELETE')
                <button type="submit" onclick="return confirm('Hapus jurnal ini?')"
                        class="w-10 h-10 flex items-center justify-center bg-white/5 border border-white/10 rounded-xl text-slate-400 hover:text-rose-500 hover:bg-rose-500/10 hover:border-rose-500/20 transition-all hover:scale-105 active:scale-95">
                    <i class="ph-bold ph-trash text-lg"></i>
                </button>
            </form>
        </div>
    </x-slot>

    <div x-data="{ 
        showCreateModal: false, 
        showEditModal: false,
        editingSection: null,
        editSectionData: { title: '', type: '', content: '', id: null, url: '' },
        openEditModal(section) {
            this.editingSection = section;
            this.editSectionData = {
                id: section.id,
                title: section.title,
                type: section.type,
                content: section.content || '',
                url: `/my/papers/{{ $paper->id }}/sections/${section.id}`
            };
            this.showEditModal = true;
        }
    }" class="max-w-5xl grid grid-cols-1 lg:grid-cols-3 gap-6 relative animate-fadeIn">

        @if(session('success'))
            <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 5000)" class="lg:col-span-3 bg-emerald-500/10 border border-emerald-500/20 rounded-[20px] p-4 flex items-center justify-between shadow-inner animate-fadeIn">
                <div class="flex items-center gap-3 text-emerald-400">
                    <i class="ph-fill ph-check-circle text-xl"></i>
                    <p class="text-sm font-bold">{{ session('success') }}</p>
                </div>
                <button @click="show = false" class="text-emerald-500/50 hover:text-emerald-400 transition-colors"><i class="ph-bold ph-x text-lg"></i></button>
            </div>
        @endif

        {{-- Editor column --}}
        <div class="lg:col-span-2 space-y-6">

            {{-- Update metadata --}}
            <form method="POST" action="{{ route('papers.update', $paper) }}" id="paper-meta-form">
                @csrf @method('PATCH')

                <div class="bg-black/40 backdrop-blur-xl border border-white/10 rounded-[24px] overflow-hidden shadow-2xl relative">
                    <div class="absolute inset-0 bg-gradient-to-br from-white/5 to-transparent pointer-events-none"></div>

                    <div class="px-6 py-5 border-b border-white/10 bg-white/5 relative z-10 flex items-center gap-3">
                        <i class="ph-bold ph-info text-amber-500 text-lg"></i>
                        <h2 class="text-[11px] font-bold text-amber-500 uppercase tracking-widest">Info Jurnal</h2>
                    </div>
                    
                    <div class="p-6 md:p-8 space-y-6 relative z-10">
                        {{-- Title --}}
                        <div>
                            <label class="block text-xs font-bold text-slate-400 mb-2">Judul</label>
                            <input type="text" name="title" value="{{ old('title', $paper->title) }}" required
                                   class="w-full bg-white/5 border border-white/10 rounded-xl px-4 py-3.5 font-geist font-bold text-white text-lg placeholder-slate-500 focus:outline-none focus:border-amber-500/50 focus:ring-4 focus:ring-amber-500/10 transition-all shadow-inner">
                        </div>

                        {{-- Abstract --}}
                        <div>
                            <label class="block text-xs font-bold text-slate-400 mb-2">Abstrak</label>
                            <textarea name="abstract" rows="4"
                                      class="w-full bg-white/5 border border-white/10 rounded-xl px-4 py-3.5 text-sm text-slate-200 placeholder-slate-500 focus:outline-none focus:border-amber-500/50 focus:ring-4 focus:ring-amber-500/10 transition-all resize-none shadow-inner leading-relaxed">{{ old('abstract', $paper->abstract) }}</textarea>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                            <div>
                                <label class="block text-xs font-bold text-slate-400 mb-2">Subject</label>
                                <div class="relative">
                                    <select name="subject_id" class="w-full bg-white/5 border border-white/10 rounded-xl pl-4 pr-10 py-3.5 text-sm text-white focus:outline-none focus:border-amber-500/50 focus:ring-4 focus:ring-amber-500/10 transition-all shadow-inner appearance-none cursor-pointer">
                                        <option value="" class="bg-slate-900">— Pilih —</option>
                                        @foreach($subjects as $s)
                                            <option value="{{ $s->id }}" class="bg-slate-900" {{ $paper->subject_id == $s->id ? 'selected' : '' }}>{{ $s->name }}</option>
                                        @endforeach
                                    </select>
                                    <i class="ph-bold ph-caret-down absolute right-4 top-1/2 -translate-y-1/2 text-sm text-slate-400 pointer-events-none"></i>
                                </div>
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-slate-400 mb-2">Visibilitas</label>
                                <div class="relative">
                                    <select name="visibility" class="w-full bg-white/5 border border-white/10 rounded-xl pl-4 pr-10 py-3.5 text-sm text-white focus:outline-none focus:border-amber-500/50 focus:ring-4 focus:ring-amber-500/10 transition-all shadow-inner appearance-none cursor-pointer">
                                        <option class="bg-slate-900" value="private"      {{ $paper->visibility === 'private'      ? 'selected' : '' }}>🔒 Private</option>
                                        <option class="bg-slate-900" value="subject_only" {{ $paper->visibility === 'subject_only' ? 'selected' : '' }}>🏫 Subject only</option>
                                        <option class="bg-slate-900" value="public"       {{ $paper->visibility === 'public'       ? 'selected' : '' }}>🌍 Public</option>
                                    </select>
                                    <i class="ph-bold ph-caret-down absolute right-4 top-1/2 -translate-y-1/2 text-sm text-slate-400 pointer-events-none"></i>
                                </div>
                            </div>
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-slate-400 mb-2">Tags</label>
                            <div x-data="{ 
                                tags: @js(old('tags', $paper->tags->pluck('name')->toArray())),
                                newTag: '',
                                addTag() {
                                    const tag = this.newTag.trim();
                                    if (tag && !this.tags.includes(tag)) {
                                        this.tags.push(tag);
                                    }
                                    this.newTag = '';
                                },
                                removeTag(index) {
                                    this.tags.splice(index, 1);
                                }
                            }">
                                <div class="bg-white/5 border border-white/10 rounded-xl px-4 py-3 min-h-[54px] flex flex-wrap gap-2 items-center focus-within:border-amber-500/50 focus-within:ring-4 focus-within:ring-amber-500/10 transition-all shadow-inner">
                                    <template x-for="(tag, index) in tags" :key="index">
                                        <span class="inline-flex items-center gap-2 px-3 py-1.5 rounded-lg bg-amber-500/10 border border-amber-500/20 text-xs font-bold text-amber-500 shadow-inner">
                                            <span x-text="tag"></span>
                                            <button type="button" @click="removeTag(index)" class="text-amber-500/50 hover:text-amber-500 focus:outline-none transition-colors">
                                                <i class="ph-bold ph-x"></i>
                                            </button>
                                            <input type="hidden" name="tags[]" :value="tag">
                                        </span>
                                    </template>
                                    <input type="text" x-model="newTag" @keydown.enter.prevent="addTag" @keydown.comma.prevent="addTag" 
                                           class="flex-1 min-w-[120px] bg-transparent border-none p-0 text-sm text-white focus:ring-0 focus:outline-none placeholder-slate-500" 
                                           placeholder="Ketik tag, tekan Enter...">
                                </div>
                                <p class="text-[11px] text-slate-500 mt-2 font-medium flex items-center gap-1.5"><i class="ph-fill ph-info"></i> Tekan Enter atau Koma (,) untuk memisahkan tag.</p>
                            </div>
                        </div>

                        <div class="pt-4 mt-2 border-t border-white/10">
                            <button type="submit"
                                    class="px-6 py-3 min-h-[48px] bg-white/10 border border-white/10 text-white font-bold text-sm rounded-xl hover:bg-white/20 active:scale-95 transition-all shadow-sm flex items-center gap-2 w-full sm:w-auto justify-center">
                                <i class="ph-bold ph-floppy-disk"></i> Simpan Perubahan
                            </button>
                        </div>
                    </div>
                </div>
            </form>

            {{-- Sections --}}
            <div class="bg-black/40 backdrop-blur-xl border border-white/10 rounded-[24px] overflow-hidden shadow-2xl relative">
                <div class="absolute inset-0 bg-gradient-to-br from-white/5 to-transparent pointer-events-none"></div>

                <div class="px-6 py-5 border-b border-white/10 bg-white/5 relative z-10 flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <i class="ph-bold ph-list-dashes text-amber-500 text-lg"></i>
                        <h2 class="text-[11px] font-bold text-amber-500 uppercase tracking-widest">Sections</h2>
                    </div>
                    <div class="flex items-center gap-4">
                        <span class="text-xs font-bold text-slate-400 bg-white/5 px-2.5 py-1 rounded-lg border border-white/10">{{ $paper->sections->count() }} section</span>
                        <button @click="showCreateModal = true" type="button" class="text-xs font-bold bg-white/10 hover:bg-white/20 border border-white/10 text-white px-3.5 py-1.5 rounded-lg transition-all shadow-sm active:scale-95 flex items-center gap-1.5">
                            <i class="ph-bold ph-plus"></i> Tambah Bagian
                        </button>
                    </div>
                </div>

                <div class="divide-y divide-white/10 relative z-10">
                    @forelse($paper->sections as $section)
                        <div class="px-6 py-6 hover:bg-white/5 transition-colors group">
                            <div class="flex items-start gap-4">
                                <span class="font-geist text-sm font-bold text-amber-500 bg-amber-500/10 px-2 py-1 rounded border border-amber-500/20 mt-0.5">{{ str_pad($loop->index + 1, 2, '0', STR_PAD_LEFT) }}</span>
                                <div class="flex-1 min-w-0">
                                    <div class="flex items-start justify-between gap-4">
                                        <div>
                                            <p class="text-lg font-bold font-geist text-white">{{ $section->title }}</p>
                                            <p class="text-[10px] font-bold text-slate-500 mt-1 uppercase tracking-widest">{{ $section->type }}</p>
                                        </div>
                                        <div class="opacity-0 group-hover:opacity-100 transition-opacity flex items-center gap-2">
                                            <button type="button" @click='openEditModal(@json($section))' class="text-xs font-bold text-white bg-white/10 hover:bg-white/20 border border-white/10 px-3 py-1.5 rounded-lg transition-all active:scale-95 flex items-center gap-1.5">
                                                <i class="ph-bold ph-pencil-simple"></i> Tulis Konten
                                            </button>
                                            <form method="POST" action="{{ route('papers.sections.destroy', [$paper, $section]) }}" onsubmit="return confirm('Hapus bagian ini secara permanen?')">
                                                @csrf @method('DELETE')
                                                <button type="submit" class="w-[32px] h-[32px] flex items-center justify-center text-slate-400 hover:text-rose-500 hover:bg-rose-500/10 rounded-lg transition-colors">
                                                    <i class="ph-bold ph-trash"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                    @if($section->content)
                                        <div class="mt-4 text-[15px] text-slate-300 line-clamp-3 font-serif-editor leading-relaxed bg-black/20 p-4 rounded-xl border border-white/5">
                                            {!! strip_tags($section->content) !!}
                                        </div>
                                    @else
                                        <div class="mt-4 text-sm text-slate-500 italic bg-white/5 p-4 rounded-xl border border-white/5 flex items-center gap-2">
                                            <i class="ph-fill ph-file-dashed text-lg"></i> Belum ada konten...
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="px-6 py-16 text-center flex flex-col items-center justify-center">
                            <div class="w-16 h-16 rounded-full bg-white/5 border border-white/10 flex items-center justify-center mb-4">
                                <i class="ph-fill ph-file-plus text-2xl text-slate-500"></i>
                            </div>
                            <p class="text-base font-bold text-white mb-2">Jurnal ini belum memiliki isi.</p>
                            <p class="text-sm text-slate-500 mb-6">Mulai tambahkan bagian pertama untuk jurnalmu.</p>
                            <button @click="showCreateModal = true" type="button" class="text-sm font-bold bg-white/10 hover:bg-white/20 border border-white/10 text-white px-5 py-2.5 rounded-xl transition-all shadow-sm active:scale-95 flex items-center gap-2">
                                <i class="ph-bold ph-plus"></i> Tambah Bagian Pertama
                            </button>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>

        {{-- Right sidebar --}}
        <div class="space-y-6">
            {{-- Paper info --}}
            <div class="bg-black/40 backdrop-blur-xl border border-white/10 rounded-[20px] p-6 shadow-2xl relative">
                <div class="absolute inset-0 bg-gradient-to-br from-white/5 to-transparent pointer-events-none rounded-[20px]"></div>
                <h3 class="text-[11px] font-bold text-amber-500 uppercase tracking-widest mb-5 flex items-center gap-2 relative z-10"><i class="ph-fill ph-info"></i> Status & Info</h3>
                <dl class="space-y-4 relative z-10">
                    <div class="flex justify-between items-center">
                        <dt class="text-xs font-medium text-slate-400">Status</dt>
                        <dd class="text-xs font-bold text-white uppercase tracking-widest bg-white/10 px-2.5 py-1 rounded-md">{{ $paper->status }}</dd>
                    </div>
                    <div class="flex justify-between items-center">
                        <dt class="text-xs font-medium text-slate-400">Dibuat</dt>
                        <dd class="text-sm font-bold text-white">{{ $paper->created_at->format('d M Y') }}</dd>
                    </div>
                    <div class="flex justify-between items-center">
                        <dt class="text-xs font-medium text-slate-400">Terakhir update</dt>
                        <dd class="text-sm font-bold text-white">{{ $paper->updated_at->diffForHumans() }}</dd>
                    </div>
                    <div class="flex justify-between items-center">
                        <dt class="text-xs font-medium text-slate-400">Views</dt>
                        <dd class="text-sm font-bold text-white flex items-center gap-1.5"><i class="ph-fill ph-eye text-amber-500"></i> {{ number_format($paper->view_count) }}</dd>
                    </div>
                    @if($paper->subject)
                        <div class="flex justify-between items-center">
                            <dt class="text-xs font-medium text-slate-400">Subject</dt>
                            <dd class="text-sm font-bold text-white">{{ $paper->subject->name }}</dd>
                        </div>
                    @endif
                </dl>
            </div>

            {{-- Tags --}}
            <div class="bg-black/40 backdrop-blur-xl border border-white/10 rounded-[20px] p-6 shadow-2xl relative">
                <div class="absolute inset-0 bg-gradient-to-br from-white/5 to-transparent pointer-events-none rounded-[20px]"></div>
                <h3 class="text-[11px] font-bold text-amber-500 uppercase tracking-widest mb-4 flex items-center gap-2 relative z-10"><i class="ph-fill ph-tag"></i> Tags</h3>
                @if($paper->tags->count())
                    <div class="flex flex-wrap gap-2 relative z-10">
                        @foreach($paper->tags as $tag)
                            <span class="px-2.5 py-1.5 rounded-lg text-[10px] font-bold uppercase tracking-widest text-white/90 shadow-inner" style="background-color: {{ $tag->color }}80; border: 1px solid {{ $tag->color }}40;">
                                {{ $tag->name }}
                            </span>
                        @endforeach
                    </div>
                @else
                    <p class="text-sm text-slate-500 italic relative z-10 bg-white/5 p-3 rounded-xl border border-white/5 flex items-center gap-2"><i class="ph-fill ph-warning-circle"></i> Belum ada tag.</p>
                @endif
            </div>

            {{-- Link ke paper --}}
            @if($paper->status === 'published')
                <a href="{{ route('papers.show', $paper) }}" target="_blank"
                   class="flex items-center justify-center gap-2 w-full py-3 bg-emerald-500/10 hover:bg-emerald-500/20 border border-emerald-500/20 text-emerald-400 text-sm font-bold rounded-xl transition-all hover:scale-105 active:scale-95 shadow-inner">
                    <i class="ph-bold ph-arrow-square-out text-lg"></i>
                    Lihat Jurnal
                </a>
            @endif
        </div>

        {{-- Modal Create Section --}}
        <div x-show="showCreateModal" style="display: none;" class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
            <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                <div x-show="showCreateModal" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" class="fixed inset-0 bg-black/60 backdrop-blur-sm transition-opacity" aria-hidden="true" @click="showCreateModal = false"></div>
                <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
                <div x-show="showCreateModal" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100" x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" class="inline-block align-bottom bg-slate-900 border border-white/10 rounded-2xl text-left overflow-hidden shadow-2xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg w-full">
                    <form method="POST" action="{{ route('papers.sections.store', $paper) }}">
                        @csrf
                        <div class="px-6 pt-6 pb-6 relative">
                            <div class="absolute inset-0 bg-gradient-to-br from-white/5 to-transparent pointer-events-none"></div>
                            <h3 class="text-xl font-geist font-bold text-white mb-6 flex items-center gap-2 relative z-10" id="modal-title"><i class="ph-bold ph-plus-circle text-amber-500"></i> Tambah Bagian Baru</h3>
                            <div class="space-y-5 relative z-10">
                                <div>
                                    <label class="block text-xs font-bold text-slate-400 mb-2">Tipe Bagian</label>
                                    <div class="relative">
                                        <select name="type" required class="w-full bg-black/40 border border-white/10 rounded-xl pl-4 pr-10 py-3.5 text-sm text-white focus:outline-none focus:border-amber-500/50 focus:ring-4 focus:ring-amber-500/10 transition-all shadow-inner appearance-none cursor-pointer">
                                            <option value="introduction" class="bg-slate-900">Introduction (Pendahuluan)</option>
                                            <option value="body" class="bg-slate-900">Body (Isi/Pembahasan)</option>
                                            <option value="conclusion" class="bg-slate-900">Conclusion (Kesimpulan)</option>
                                            <option value="references" class="bg-slate-900">References (Daftar Pustaka)</option>
                                            <option value="appendix" class="bg-slate-900">Appendix (Lampiran)</option>
                                            <option value="custom" class="bg-slate-900">Custom</option>
                                        </select>
                                        <i class="ph-bold ph-caret-down absolute right-4 top-1/2 -translate-y-1/2 text-sm text-slate-400 pointer-events-none"></i>
                                    </div>
                                </div>
                                <div>
                                    <label class="block text-xs font-bold text-slate-400 mb-2">Judul Bagian</label>
                                    <input type="text" name="title" required class="w-full bg-black/40 border border-white/10 rounded-xl px-4 py-3.5 font-geist font-bold text-white text-base focus:outline-none focus:border-amber-500/50 focus:ring-4 focus:ring-amber-500/10 transition-all shadow-inner" placeholder="Misal: Latar Belakang Masalah">
                                </div>
                            </div>
                        </div>
                        <div class="bg-black/50 px-6 py-4 sm:flex sm:flex-row-reverse border-t border-white/5 shrink-0">
                            <button type="submit" class="w-full inline-flex justify-center items-center gap-2 rounded-xl border border-transparent shadow-sm px-5 py-2.5 bg-white/10 hover:bg-white/20 border-white/10 text-sm font-bold text-white focus:outline-none sm:ml-3 sm:w-auto transition-all active:scale-95"><i class="ph-bold ph-check"></i> Tambah</button>
                            <button type="button" @click="showCreateModal = false" class="mt-3 w-full inline-flex justify-center items-center gap-2 rounded-xl border border-transparent shadow-sm px-5 py-2.5 bg-transparent text-sm font-bold text-slate-400 hover:text-white focus:outline-none sm:mt-0 sm:ml-3 sm:w-auto transition-colors">Batal</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        {{-- Modal Edit Section --}}
        <div x-show="showEditModal" style="display: none;" class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
            <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                <div x-show="showEditModal" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" class="fixed inset-0 bg-black/80 backdrop-blur-md transition-opacity" aria-hidden="true" @click="showEditModal = false"></div>
                <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
                <div x-show="showEditModal" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100" x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" class="inline-block align-bottom bg-slate-900 border border-white/10 rounded-[24px] text-left overflow-hidden shadow-2xl transform transition-all sm:my-8 sm:align-middle sm:max-w-5xl w-full">
                    <form method="POST" :action="editSectionData.url">
                        @csrf @method('PATCH')
                        <div class="px-6 sm:px-8 pt-6 pb-6 flex flex-col h-[85vh] relative">
                            <div class="absolute inset-0 bg-gradient-to-b from-white/5 to-transparent pointer-events-none"></div>
                            
                            <div class="flex justify-between items-center mb-6 shrink-0 relative z-10">
                                <h3 class="text-2xl font-geist font-bold text-white flex items-center gap-2" id="modal-title"><i class="ph-bold ph-pencil-simple text-amber-500"></i> Tulis Bagian</h3>
                                <button type="button" @click="showEditModal = false" class="w-10 h-10 rounded-full flex items-center justify-center bg-white/5 border border-white/10 text-slate-400 hover:text-white hover:bg-rose-500/20 hover:border-rose-500/30 transition-all">
                                    <i class="ph-bold ph-x text-lg"></i>
                                </button>
                            </div>
                            
                            <div class="flex flex-col sm:flex-row gap-5 shrink-0 mb-6 relative z-10">
                                <div class="sm:w-1/3">
                                    <label class="block text-xs font-bold text-slate-400 mb-2">Tipe Bagian</label>
                                    <div class="relative">
                                        <select name="type" x-model="editSectionData.type" required class="w-full bg-black/40 border border-white/10 rounded-xl pl-4 pr-10 py-3.5 text-sm text-white focus:outline-none focus:border-amber-500/50 focus:ring-4 focus:ring-amber-500/10 transition-all shadow-inner appearance-none cursor-pointer">
                                            <option value="introduction" class="bg-slate-900">Introduction</option>
                                            <option value="body" class="bg-slate-900">Body</option>
                                            <option value="conclusion" class="bg-slate-900">Conclusion</option>
                                            <option value="references" class="bg-slate-900">References</option>
                                            <option value="appendix" class="bg-slate-900">Appendix</option>
                                            <option value="custom" class="bg-slate-900">Custom</option>
                                        </select>
                                        <i class="ph-bold ph-caret-down absolute right-4 top-1/2 -translate-y-1/2 text-sm text-slate-400 pointer-events-none"></i>
                                    </div>
                                </div>
                                <div class="sm:w-2/3">
                                    <label class="block text-xs font-bold text-slate-400 mb-2">Judul Bagian</label>
                                    <input type="text" name="title" x-model="editSectionData.title" required class="w-full bg-black/40 border border-white/10 rounded-xl px-4 py-3.5 font-geist font-bold text-white focus:outline-none focus:border-amber-500/50 focus:ring-4 focus:ring-amber-500/10 transition-all shadow-inner" placeholder="Judul Bagian">
                                </div>
                            </div>

                            <div class="flex-1 flex flex-col mt-2 relative z-10">
                                <label class="block text-xs font-bold text-slate-400 mb-2 flex items-center gap-2"><i class="ph-bold ph-text-align-left"></i> Konten Editor</label>
                                <textarea name="content" x-model="editSectionData.content" class="w-full flex-1 bg-black/40 border border-white/10 rounded-xl p-6 font-serif-editor text-[1.05rem] text-slate-200 leading-relaxed focus:outline-none focus:border-amber-500/50 focus:ring-4 focus:ring-amber-500/10 transition-all resize-none shadow-inner selection:bg-amber-500/30" placeholder="Tulis sesuatu yang hebat di sini... (Mendukung HTML tags dasar seperti <b>, <i>, <br> jika diperlukan)"></textarea>
                            </div>
                        </div>
                        <div class="bg-black/50 px-6 sm:px-8 py-4 sm:flex justify-between items-center shrink-0 border-t border-white/5 relative z-10">
                            <span class="text-xs font-medium text-slate-500 flex items-center gap-1.5 mb-4 sm:mb-0"><i class="ph-fill ph-info"></i> Disimpan sebagai plain-text elegan.</span>
                            <div class="flex gap-3">
                                <button type="button" @click="showEditModal = false" class="w-full inline-flex justify-center rounded-xl border border-transparent px-5 py-2.5 bg-transparent text-sm font-bold text-slate-400 hover:text-white focus:outline-none sm:w-auto transition-colors">Batal</button>
                                <button type="submit" class="w-full inline-flex justify-center items-center gap-2 rounded-xl border border-white/10 shadow-sm px-6 py-2.5 bg-white/10 hover:bg-white/20 text-sm font-bold text-white focus:outline-none sm:w-auto transition-all active:scale-95"><i class="ph-bold ph-floppy-disk"></i> Simpan Konten</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>

    </div>
</x-app-layout>
