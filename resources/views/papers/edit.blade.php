<x-app-layout>
    <x-slot name="pageTitle">{{ Str::limit($paper->title, 40) }}</x-slot>
    <x-slot name="pageSubtitle">Ngedit jurnal · {{ $paper->status }}</x-slot>
    <x-slot name="headerActions">
        @if($paper->status !== 'published')
            <form method="POST" action="{{ route('papers.publish', $paper) }}">
                @csrf
                <button type="submit"
                        class="px-4 py-2 bg-[#4a6741] text-white text-sm font-medium rounded-lg hover:bg-[#3d5535] transition-colors">
                    Publish
                </button>
            </form>
        @else
            <span class="text-xs text-[#4a6741] font-mono bg-[#4a6741]/10 px-3 py-2 rounded-lg"><i class="ph ph-check text-[1.1em] align-middle"></i> published</span>
        @endif
        <form method="POST" action="{{ route('papers.destroy', $paper) }}">
            @csrf @method('DELETE')
            <button type="submit" onclick="return confirm('Hapus jurnal ini?')"
                    class="p-2 text-stone-400 hover:text-[#c45c2a] transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                </svg>
            </button>
        </form>
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
    }" class="max-w-5xl grid grid-cols-1 lg:grid-cols-3 gap-6 relative">

        {{-- Editor column --}}
        <div class="lg:col-span-2 space-y-4">

            {{-- Update metadata --}}
            <form method="POST" action="{{ route('papers.update', $paper) }}" id="paper-meta-form">
                @csrf @method('PATCH')

                <div class="bg-white dark:bg-slate-900/70 border border-stone-300/60 rounded-xl overflow-hidden">
                    <div class="px-5 py-4 border-b border-stone-200 dark:border-slate-700/50">
                        <h2 class="text-xs font-semibold text-[#8c8479] uppercase tracking-widest">Info Jurnal</h2>
                    </div>
                    <div class="p-5 space-y-4">
                        {{-- Title --}}
                        <div>
                            <label class="block text-xs text-[#8c8479] mb-1.5">Judul</label>
                            <input type="text" name="title" value="{{ old('title', $paper->title) }}" required
                                   class="w-full bg-[#f5f0e8] border border-stone-200 rounded-lg px-4 py-2.5 font-display text-[#1a1814] text-base focus:outline-none focus:border-[#c45c2a] transition dark:border-slate-700/50">
                        </div>

                        {{-- Abstract --}}
                        <div>
                            <label class="block text-xs text-[#8c8479] mb-1.5">Abstrak</label>
                            <textarea name="abstract" rows="3"
                                      class="w-full bg-[#f5f0e8] border border-stone-200 rounded-lg px-4 py-2.5 text-sm text-[#1a1814] focus:outline-none focus:border-[#c45c2a] transition resize-none dark:border-slate-700/50">{{ old('abstract', $paper->abstract) }}</textarea>
                        </div>

                        <div class="grid grid-cols-2 gap-3">
                            <div>
                                <label class="block text-xs text-[#8c8479] mb-1.5">Subject</label>
                                <select name="subject_id" class="w-full bg-[#f5f0e8] border border-stone-200 rounded-lg px-3 py-2.5 text-sm focus:outline-none focus:border-[#c45c2a] transition dark:border-slate-700/50">
                                    <option value="">— Pilih —</option>
                                    @foreach($subjects as $s)
                                        <option value="{{ $s->id }}" {{ $paper->subject_id == $s->id ? 'selected' : '' }}>{{ $s->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label class="block text-xs text-[#8c8479] mb-1.5">Visibilitas</label>
                                <select name="visibility" class="w-full bg-[#f5f0e8] border border-stone-200 rounded-lg px-3 py-2.5 text-sm focus:outline-none focus:border-[#c45c2a] transition dark:border-slate-700/50">
                                    <option value="private"      {{ $paper->visibility === 'private'      ? 'selected' : '' }}><i class="ph ph-lock text-[1.1em] align-middle"></i> Private</option>
                                    <option value="subject_only" {{ $paper->visibility === 'subject_only' ? 'selected' : '' }}><i class="ph ph-buildings text-[1.1em] align-middle"></i> Subject only</option>
                                    <option value="public"       {{ $paper->visibility === 'public'       ? 'selected' : '' }}><i class="ph ph-globe-hemisphere-west text-[1.1em] align-middle"></i> Public</option>
                                </select>
                            </div>
                        </div>

                        <div>
                            <label class="block text-xs text-[#8c8479] mb-2">Tags</label>
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
                                <div class="bg-white dark:bg-slate-900/70 border border-stone-300 rounded-lg px-4 py-2 min-h-[50px] flex flex-wrap gap-2 items-center focus-within:border-[#c45c2a] focus-within:ring-2 focus-within:ring-[#c45c2a]/20 transition dark:border-slate-700">
                                    <template x-for="(tag, index) in tags" :key="index">
                                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded bg-stone-100 border border-stone-200 text-xs font-medium text-stone-700 dark:bg-slate-900/80 dark:border-slate-700/50">
                                            <span x-text="tag"></span>
                                            <button type="button" @click="removeTag(index)" class="text-stone-400 hover:text-red-500 focus:outline-none">
                                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                                            </button>
                                            <input type="hidden" name="tags[]" :value="tag">
                                        </span>
                                    </template>
                                    <input type="text" x-model="newTag" @keydown.enter.prevent="addTag" @keydown.comma.prevent="addTag" 
                                           class="flex-1 min-w-[120px] bg-transparent border-none p-0 text-sm focus:ring-0 focus:outline-none placeholder-stone-400" 
                                           placeholder="Ketik tag, tekan Enter...">
                                </div>
                                <p class="text-[11px] text-stone-400 mt-1.5">*Tekan Enter atau Koma (,) untuk memisahkan tag.</p>
                            </div>
                        </div>

                        <button type="submit"
                                class="px-4 py-2 bg-[#1a1814] text-white text-sm rounded-lg hover:bg-[#c45c2a] transition-colors">
                            Simpan Perubahan
                        </button>
                    </div>
                </div>
            </form>

            {{-- Sections --}}
            <div class="bg-white dark:bg-slate-900/70 border border-stone-300/60 rounded-xl overflow-hidden">
                <div class="px-5 py-4 border-b border-stone-200 flex items-center justify-between dark:border-slate-700/50">
                    <h2 class="text-xs font-semibold text-[#8c8479] uppercase tracking-widest">Sections</h2>
                    <div class="flex items-center gap-4">
                        <span class="text-xs text-[#8c8479]">{{ $paper->sections->count() }} section</span>
                        <button @click="showCreateModal = true" type="button" class="text-xs font-medium bg-[#1a1814] text-white px-3 py-1.5 rounded hover:bg-[#c45c2a] transition">
                            + Tambah Bagian
                        </button>
                    </div>
                </div>

                <div class="divide-y divide-stone-100">
                    @forelse($paper->sections as $section)
                        <div class="px-5 py-5 hover:bg-stone-50/50 transition-colors group">
                            <div class="flex items-start gap-4">
                                <span class="font-mono text-xs text-stone-400 mt-1">{{ str_pad($loop->index + 1, 2, '0', STR_PAD_LEFT) }}</span>
                                <div class="flex-1 min-w-0">
                                    <div class="flex items-start justify-between">
                                        <div>
                                            <p class="text-base font-semibold font-display text-[#1a1814]">{{ $section->title }}</p>
                                            <p class="text-xs text-[#8c8479] mt-0.5 font-mono uppercase tracking-wider">{{ $section->type }}</p>
                                        </div>
                                        <div class="opacity-0 group-hover:opacity-100 transition-opacity flex items-center gap-2">
                                            <button type="button" @click='openEditModal(@json($section))' class="text-xs font-medium text-stone-500 hover:text-[#1a1814] bg-stone-200 hover:bg-stone-300 px-3 py-1.5 rounded transition dark:bg-slate-800">
                                                Tulis Konten
                                            </button>
                                            <form method="POST" action="{{ route('papers.sections.destroy', [$paper, $section]) }}" onsubmit="return confirm('Hapus bagian ini secara permanen?')">
                                                @csrf @method('DELETE')
                                                <button type="submit" class="text-stone-400 hover:text-[#c45c2a] p-1.5">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                                    </svg>
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                    @if($section->content)
                                        <div class="mt-3 text-sm text-[#4a4640] line-clamp-3 font-serif leading-relaxed prose prose-stone prose-sm">
                                            {!! nl2br(e($section->content)) !!}
                                        </div>
                                    @else
                                        <p class="text-xs text-stone-300 mt-3 italic">Belum ada konten...</p>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="px-5 py-12 text-center">
                            <p class="text-sm text-[#8c8479] italic mb-4">Jurnal ini belum memiliki isi.</p>
                            <button @click="showCreateModal = true" type="button" class="text-sm font-medium bg-[#1a1814] text-white px-4 py-2 rounded hover:bg-[#c45c2a] transition">
                                Mulai Menulis
                            </button>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>

        {{-- Right sidebar --}}
        <div class="space-y-4">
            {{-- Paper info --}}
            <div class="bg-white dark:bg-slate-900/70 border border-stone-300/60 rounded-xl p-5">
                <h3 class="text-xs font-semibold text-[#8c8479] uppercase tracking-widest mb-4">Status</h3>
                <dl class="space-y-3">
                    <div>
                        <dt class="text-xs text-stone-400">Status</dt>
                        <dd class="text-sm font-mono text-[#1a1814] mt-0.5">{{ $paper->status }}</dd>
                    </div>
                    <div>
                        <dt class="text-xs text-stone-400">Dibuat</dt>
                        <dd class="text-sm text-[#1a1814] mt-0.5">{{ $paper->created_at->format('d M Y') }}</dd>
                    </div>
                    <div>
                        <dt class="text-xs text-stone-400">Terakhir diupdate</dt>
                        <dd class="text-sm text-[#1a1814] mt-0.5">{{ $paper->updated_at->diffForHumans() }}</dd>
                    </div>
                    <div>
                        <dt class="text-xs text-stone-400">Views</dt>
                        <dd class="text-sm text-[#1a1814] mt-0.5">{{ number_format($paper->view_count) }} kali</dd>
                    </div>
                    @if($paper->subject)
                        <div>
                            <dt class="text-xs text-stone-400">Subject</dt>
                            <dd class="text-sm text-[#1a1814] mt-0.5">{{ $paper->subject->name }}</dd>
                        </div>
                    @endif
                </dl>
            </div>

            {{-- Tags --}}
            <div class="bg-white dark:bg-slate-900/70 border border-stone-300/60 rounded-xl p-5">
                <h3 class="text-xs font-semibold text-[#8c8479] uppercase tracking-widest mb-3">Tags</h3>
                @if($paper->tags->count())
                    <div class="flex flex-wrap gap-2">
                        @foreach($paper->tags as $tag)
                            <span class="px-2.5 py-1 rounded text-xs font-mono text-white" style="background-color: {{ $tag->color }}">
                                {{ $tag->name }}
                            </span>
                        @endforeach
                    </div>
                @else
                    <p class="text-xs text-stone-400 italic">Belum ada tag.</p>
                @endif
            </div>

            {{-- Link ke paper --}}
            @if($paper->status === 'published')
                <a href="{{ route('papers.show', $paper) }}" target="_blank"
                   class="flex items-center justify-center gap-2 w-full py-2.5 border border-[#4a6741] text-[#4a6741] text-sm font-medium rounded-lg hover:bg-[#4a6741]/5 transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/>
                    </svg>
                    Lihat Jurnal
                </a>
            @endif
        </div>

        {{-- Modal Create Section --}}
        <div x-show="showCreateModal" style="display: none;" class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
            <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                <div x-show="showCreateModal" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" class="fixed inset-0 bg-stone-900/75 transition-opacity" aria-hidden="true" @click="showCreateModal = false"></div>
                <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
                <div x-show="showCreateModal" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100" x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" class="inline-block align-bottom bg-[#faf8f5] rounded-xl text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg w-full">
                    <form method="POST" action="{{ route('papers.sections.store', $paper) }}">
                        @csrf
                        <div class="bg-[#faf8f5] px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                            <div class="sm:flex sm:items-start">
                                <div class="mt-3 text-center sm:mt-0 sm:text-left w-full">
                                    <h3 class="text-lg leading-6 font-display font-bold text-[#1a1814] mb-4" id="modal-title">Tambah Bagian Baru</h3>
                                    <div class="space-y-4">
                                        <div>
                                            <label class="block text-xs font-semibold text-[#8c8479] uppercase tracking-widest mb-2">Tipe Bagian</label>
                                            <select name="type" required class="w-full bg-white dark:bg-slate-900 border border-stone-200 rounded-lg px-3 py-2.5 text-sm focus:outline-none focus:border-[#c45c2a] transition dark:border-slate-700/50">
                                                <option value="introduction">Introduction (Pendahuluan)</option>
                                                <option value="body">Body (Isi/Pembahasan)</option>
                                                <option value="conclusion">Conclusion (Kesimpulan)</option>
                                                <option value="references">References (Daftar Pustaka)</option>
                                                <option value="appendix">Appendix (Lampiran)</option>
                                                <option value="custom">Custom</option>
                                            </select>
                                        </div>
                                        <div>
                                            <label class="block text-xs font-semibold text-[#8c8479] uppercase tracking-widest mb-2">Judul Bagian</label>
                                            <input type="text" name="title" required class="w-full bg-white dark:bg-slate-900 border border-stone-200 rounded-lg px-4 py-2.5 font-display text-[#1a1814] focus:outline-none focus:border-[#c45c2a] transition dark:border-slate-700/50" placeholder="Misal: Latar Belakang Masalah">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="bg-stone-100/50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                            <button type="submit" class="w-full inline-flex justify-center rounded-lg border border-transparent shadow-sm px-4 py-2 bg-[#1a1814] text-base font-medium text-white hover:bg-[#c45c2a] focus:outline-none sm:ml-3 sm:w-auto sm:text-sm transition-colors">Tambah</button>
                            <button type="button" @click="showCreateModal = false" class="mt-3 w-full inline-flex justify-center rounded-lg border border-stone-300 shadow-sm px-4 py-2 bg-white dark:bg-slate-900 text-base font-medium text-stone-700 hover:bg-stone-50 focus:outline-none sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm transition-colors dark:border-slate-700">Batal</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        {{-- Modal Edit Section --}}
        <div x-show="showEditModal" style="display: none;" class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
            <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                <div x-show="showEditModal" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" class="fixed inset-0 bg-stone-900/75 transition-opacity" aria-hidden="true" @click="showEditModal = false"></div>
                <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
                <div x-show="showEditModal" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100" x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" class="inline-block align-bottom bg-[#faf8f5] rounded-xl text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-4xl w-full">
                    <form method="POST" :action="editSectionData.url">
                        @csrf @method('PATCH')
                        <div class="bg-[#faf8f5] px-4 pt-5 pb-4 sm:p-6 sm:pb-4 flex flex-col h-[75vh]">
                            <div class="flex justify-between items-center mb-4 shrink-0">
                                <h3 class="text-xl leading-6 font-display font-bold text-[#1a1814]" id="modal-title">Tulis Bagian</h3>
                                <button type="button" @click="showEditModal = false" class="text-stone-400 hover:text-[#1a1814]">
                                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                    </svg>
                                </button>
                            </div>
                            
                            <div class="flex gap-4 shrink-0 mb-4">
                                <div class="w-1/3">
                                    <label class="block text-xs font-semibold text-[#8c8479] uppercase tracking-widest mb-2">Tipe Bagian</label>
                                    <select name="type" x-model="editSectionData.type" required class="w-full bg-white dark:bg-slate-900 border border-stone-200 rounded-lg px-3 py-2.5 text-sm focus:outline-none focus:border-[#c45c2a] transition dark:border-slate-700/50">
                                        <option value="introduction">Introduction (Pendahuluan)</option>
                                        <option value="body">Body (Isi/Pembahasan)</option>
                                        <option value="conclusion">Conclusion (Kesimpulan)</option>
                                        <option value="references">References (Daftar Pustaka)</option>
                                        <option value="appendix">Appendix (Lampiran)</option>
                                        <option value="custom">Custom</option>
                                    </select>
                                </div>
                                <div class="w-2/3">
                                    <label class="block text-xs font-semibold text-[#8c8479] uppercase tracking-widest mb-2">Judul Bagian</label>
                                    <input type="text" name="title" x-model="editSectionData.title" required class="w-full bg-white dark:bg-slate-900 border border-stone-200 rounded-lg px-4 py-2.5 font-display font-bold text-[#1a1814] focus:outline-none focus:border-[#c45c2a] transition dark:border-slate-700/50" placeholder="Judul Bagian">
                                </div>
                            </div>

                            <div class="flex-1 flex flex-col mt-2">
                                <label class="block text-xs font-semibold text-[#8c8479] uppercase tracking-widest mb-2">Konten</label>
                                <textarea name="content" x-model="editSectionData.content" class="w-full flex-1 bg-white dark:bg-slate-900 border border-stone-200 rounded-lg p-5 font-serif text-base text-[#1a1814] leading-relaxed focus:outline-none focus:border-[#c45c2a] focus:ring-2 focus:ring-[#c45c2a]/20 transition resize-none shadow-inner dark:border-slate-700/50" placeholder="Tulis sesuatu yang hebat di sini... (Mendukung HTML tags dasar seperti <b>, <i>, <br> jika diperlukan)"></textarea>
                            </div>
                        </div>
                        <div class="bg-stone-100/50 px-4 py-3 sm:px-6 sm:flex justify-between items-center shrink-0">
                            <span class="text-xs text-stone-400 italic">* Disimpan sebagai plain-text elegan.</span>
                            <div class="flex gap-3">
                                <button type="button" @click="showEditModal = false" class="w-full inline-flex justify-center rounded-lg border border-stone-300 shadow-sm px-4 py-2 bg-white dark:bg-slate-900 text-base font-medium text-stone-700 hover:bg-stone-50 focus:outline-none sm:w-auto sm:text-sm transition-colors dark:border-slate-700">Batal</button>
                                <button type="submit" class="w-full inline-flex justify-center rounded-lg border border-transparent shadow-sm px-4 py-2 bg-[#1a1814] text-base font-medium text-white hover:bg-[#c45c2a] focus:outline-none sm:w-auto sm:text-sm transition-colors">Simpan Konten</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>

    </div>
</x-app-layout>
