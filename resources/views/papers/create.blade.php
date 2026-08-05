<x-app-layout>
    <x-slot name="pageTitle">Jurnal Baru</x-slot>
    <x-slot name="pageSubtitle">Mulai tulis, jangan kebanyakan mikir.</x-slot>

    <div class="max-w-2xl">
        <form method="POST" action="{{ route('papers.store') }}" class="space-y-5">
            @csrf

            {{-- Title --}}
            <div>
                <label class="block text-xs font-semibold text-[#8c8479] uppercase tracking-widest mb-2">Judul Jurnal</label>
                <input type="text" name="title" value="{{ old('title') }}" required
                       class="w-full bg-white dark:bg-slate-900/70 border border-stone-300 rounded-lg px-4 py-3 text-[#1a1814] font-display text-lg placeholder-stone-400 focus:outline-none focus:border-[#c45c2a] focus:ring-2 focus:ring-[#c45c2a]/20 transition dark:border-slate-700"
                       placeholder="Judul yang bikin orang penasaran...">
                @error('title') <p class="text-xs text-[#c45c2a] mt-1.5">{{ $message }}</p> @enderror
            </div>

            {{-- Abstract --}}
            <div>
                <label class="block text-xs font-semibold text-[#8c8479] uppercase tracking-widest mb-2">Abstrak <span class="text-stone-400 normal-case font-normal">(opsional)</span></label>
                <textarea name="abstract" rows="4"
                          class="w-full bg-white dark:bg-slate-900/70 border border-stone-300 rounded-lg px-4 py-3 text-sm text-[#1a1814] placeholder-stone-400 focus:outline-none focus:border-[#c45c2a] focus:ring-2 focus:ring-[#c45c2a]/20 transition resize-none dark:border-slate-700"
                          placeholder="Gambaran singkat tentang jurnal ini...">{{ old('abstract') }}</textarea>
            </div>

            {{-- Subject + Visibility row --}}
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-semibold text-[#8c8479] uppercase tracking-widest mb-2">Subject</label>
                    <select name="subject_id" class="w-full bg-white dark:bg-slate-900/70 border border-stone-300 rounded-lg px-4 py-3 text-sm text-[#1a1814] focus:outline-none focus:border-[#c45c2a] transition dark:border-slate-700">
                        <option value="">— Pilih subject —</option>
                        @foreach($subjects as $s)
                            <option value="{{ $s->id }}" {{ old('subject_id') == $s->id ? 'selected' : '' }}>
                                {{ $s->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-semibold text-[#8c8479] uppercase tracking-widest mb-2">Visibilitas</label>
                    <select name="visibility" class="w-full bg-white dark:bg-slate-900/70 border border-stone-300 rounded-lg px-4 py-3 text-sm text-[#1a1814] focus:outline-none focus:border-[#c45c2a] transition dark:border-slate-700">
                        <option value="private"      {{ old('visibility', 'private') === 'private'      ? 'selected' : '' }}><i class="ph ph-lock text-[1.1em] align-middle"></i> Private</option>
                        <option value="subject_only" {{ old('visibility') === 'subject_only' ? 'selected' : '' }}><i class="ph ph-buildings text-[1.1em] align-middle"></i> Subject only</option>
                        <option value="public"       {{ old('visibility') === 'public'       ? 'selected' : '' }}><i class="ph ph-globe-hemisphere-west text-[1.1em] align-middle"></i> Public</option>
                    </select>
                </div>
            </div>

            {{-- Tags --}}
            <div x-data="{ 
                tags: @js(old('tags', [])),
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
                <label class="block text-xs font-semibold text-[#8c8479] uppercase tracking-widest mb-2">Tags</label>
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

            {{-- Actions --}}
            <div class="flex items-center gap-3 pt-2">
                <button type="submit"
                        class="px-6 py-2.5 bg-[#1a1814] text-white text-sm font-medium rounded-lg hover:bg-[#c45c2a] transition-colors">
                    Buat Jurnal
                </button>
                <a href="{{ route('papers.my') }}" class="text-sm text-[#8c8479] hover:text-[#1a1814] transition-colors">
                    Batal
                </a>
            </div>
        </form>

        {{-- Quote --}}
        <div class="mt-10 pt-8 border-t border-stone-200 dark:border-slate-700/50">
            <p class="font-display italic text-[#8c8479] text-sm">"Every masterpiece starts with a blank page and the courage to begin."</p>
        </div>
    </div>
</x-app-layout>
