<x-app-layout>
    <x-slot name="pageTitle">Jurnal Baru</x-slot>
    <x-slot name="pageSubtitle">Mulai tulis, jangan kebanyakan mikir.</x-slot>

    <div class="max-w-2xl animate-fadeIn relative">
        <div class="absolute inset-0 bg-gradient-to-br from-white/5 to-transparent pointer-events-none rounded-[24px]"></div>

        <form method="POST" action="{{ route('papers.store') }}" class="space-y-6 relative z-10 bg-black/40 backdrop-blur-xl border border-white/10 rounded-[24px] p-8 shadow-2xl">
            @csrf

            {{-- Title --}}
            <div>
                <label class="block text-[11px] font-bold text-amber-500 uppercase tracking-widest mb-3 flex items-center gap-2"><i class="ph-bold ph-text-T"></i> Judul Jurnal</label>
                <input type="text" name="title" value="{{ old('title') }}" required
                       class="w-full bg-white/5 border border-white/10 rounded-xl px-4 py-3.5 text-white font-geist font-bold text-lg placeholder-slate-500 focus:outline-none focus:border-amber-500/50 focus:ring-4 focus:ring-amber-500/10 transition-all shadow-inner"
                       placeholder="Judul yang bikin orang penasaran...">
                @error('title') <p class="text-xs text-rose-500 mt-2 font-medium flex items-center gap-1"><i class="ph-fill ph-warning-circle"></i> {{ $message }}</p> @enderror
            </div>

            {{-- Abstract --}}
            <div>
                <label class="block text-[11px] font-bold text-amber-500 uppercase tracking-widest mb-3 flex items-center gap-2">
                    <i class="ph-bold ph-text-align-left"></i> Abstrak <span class="text-slate-500 normal-case font-medium ml-1">(opsional)</span>
                </label>
                <textarea name="abstract" rows="4"
                          class="w-full bg-white/5 border border-white/10 rounded-xl px-4 py-3.5 text-sm text-slate-200 placeholder-slate-500 focus:outline-none focus:border-amber-500/50 focus:ring-4 focus:ring-amber-500/10 transition-all resize-none shadow-inner leading-relaxed"
                          placeholder="Gambaran singkat tentang jurnal ini...">{{ old('abstract') }}</textarea>
            </div>

            {{-- Subject + Visibility row --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                <div>
                    <label class="block text-[11px] font-bold text-amber-500 uppercase tracking-widest mb-3 flex items-center gap-2"><i class="ph-bold ph-books"></i> Subject</label>
                    <div class="relative">
                        <select name="subject_id" class="w-full bg-white/5 border border-white/10 rounded-xl pl-4 pr-10 py-3.5 text-sm text-white focus:outline-none focus:border-amber-500/50 focus:ring-4 focus:ring-amber-500/10 transition-all shadow-inner appearance-none cursor-pointer">
                            <option value="" class="bg-slate-900">— Pilih subject —</option>
                            @foreach($subjects as $s)
                                <option value="{{ $s->id }}" class="bg-slate-900" {{ old('subject_id') == $s->id ? 'selected' : '' }}>
                                    {{ $s->name }}
                                </option>
                            @endforeach
                        </select>
                        <i class="ph-bold ph-caret-down absolute right-4 top-1/2 -translate-y-1/2 text-sm text-slate-400 pointer-events-none"></i>
                    </div>
                </div>

                <div>
                    <label class="block text-[11px] font-bold text-amber-500 uppercase tracking-widest mb-3 flex items-center gap-2"><i class="ph-bold ph-eye"></i> Visibilitas</label>
                    <div class="relative">
                        <select name="visibility" class="w-full bg-white/5 border border-white/10 rounded-xl pl-4 pr-10 py-3.5 text-sm text-white focus:outline-none focus:border-amber-500/50 focus:ring-4 focus:ring-amber-500/10 transition-all shadow-inner appearance-none cursor-pointer">
                            <option class="bg-slate-900" value="private"      {{ old('visibility', 'private') === 'private'      ? 'selected' : '' }}>🔒 Private</option>
                            <option class="bg-slate-900" value="subject_only" {{ old('visibility') === 'subject_only' ? 'selected' : '' }}>🏫 Subject only</option>
                            <option class="bg-slate-900" value="public"       {{ old('visibility') === 'public'       ? 'selected' : '' }}>🌍 Public</option>
                        </select>
                        <i class="ph-bold ph-caret-down absolute right-4 top-1/2 -translate-y-1/2 text-sm text-slate-400 pointer-events-none"></i>
                    </div>
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
                <label class="block text-[11px] font-bold text-amber-500 uppercase tracking-widest mb-3 flex items-center gap-2"><i class="ph-bold ph-tag"></i> Tags</label>
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

            {{-- Actions --}}
            <div class="flex items-center gap-4 pt-4 mt-2 border-t border-white/10">
                <button type="submit"
                        class="px-6 py-3 min-h-[48px] bg-white/10 border border-white/10 text-white font-bold text-sm rounded-xl hover:bg-white/20 active:scale-95 transition-all shadow-sm flex items-center gap-2">
                    <i class="ph-bold ph-check"></i> Buat Jurnal
                </button>
                <a href="{{ route('papers.my') }}" class="px-6 py-3 min-h-[48px] text-sm font-medium text-slate-400 hover:text-white transition-colors flex items-center justify-center">
                    Batal
                </a>
            </div>
        </form>

        {{-- Quote --}}
        <div class="mt-8 text-center">
            <p class="font-geist italic text-slate-500 text-sm">"Every masterpiece starts with a blank page and the courage to begin."</p>
        </div>
    </div>
</x-app-layout>
