<x-app-layout>
    <x-slot name="pageTitle">Eksplor Jurnal</x-slot>
    <x-slot name="pageSubtitle">Karya dari komunitas RUANG</x-slot>

    <div class="max-w-5xl space-y-6 animate-fadeIn relative">
        <div class="absolute inset-0 bg-gradient-to-br from-white/5 to-transparent pointer-events-none rounded-[24px]"></div>

        {{-- Search & filter --}}
        <form method="GET" class="flex flex-col sm:flex-row gap-3 relative z-10">
            <div class="flex-1 relative w-full group">
                <i class="ph-bold ph-magnifying-glass absolute left-4 top-1/2 -translate-y-1/2 text-lg text-slate-400 group-focus-within:text-amber-500 transition-colors"></i>
                <input type="text" name="search" value="{{ request('search') }}"
                       class="w-full bg-black/40 backdrop-blur-xl border border-white/10 rounded-xl pl-11 pr-4 py-3 min-h-[48px] text-sm text-white placeholder-slate-500 focus:outline-none focus:border-amber-500/50 focus:ring-4 focus:ring-amber-500/10 transition-all shadow-inner"
                       placeholder="Cari jurnal...">
            </div>
            <div class="relative w-full sm:w-auto">
                <i class="ph-bold ph-books absolute left-4 top-1/2 -translate-y-1/2 text-lg text-slate-400 pointer-events-none"></i>
                <select name="subject_id" class="w-full sm:w-auto bg-black/40 backdrop-blur-xl border border-white/10 rounded-xl pl-11 pr-10 py-3 min-h-[48px] text-sm text-white focus:outline-none focus:border-amber-500/50 focus:ring-4 focus:ring-amber-500/10 transition-all shadow-inner appearance-none cursor-pointer">
                    <option value="" class="bg-slate-900">Semua Subject</option>
                    @foreach($subjects as $s)
                        <option value="{{ $s->id }}" class="bg-slate-900" {{ request('subject_id') == $s->id ? 'selected' : '' }}>{{ $s->name }}</option>
                    @endforeach
                </select>
                <i class="ph-bold ph-caret-down absolute right-4 top-1/2 -translate-y-1/2 text-sm text-slate-400 pointer-events-none"></i>
            </div>
            <button type="submit" class="w-full sm:w-auto justify-center px-6 py-3 min-h-[48px] bg-white/10 border border-white/10 text-white font-bold text-sm rounded-xl hover:bg-white/20 active:scale-95 transition-all shadow-sm flex items-center gap-2">
                <i class="ph-bold ph-magnifying-glass"></i> Cari
            </button>
        </form>

        {{-- Results --}}
        @if($papers->count())
            <div class="grid grid-cols-1 md:grid-cols-2 gap-5 relative z-10">
                @foreach($papers as $paper)
                    <a href="{{ route('papers.show', $paper) }}"
                       class="group bg-black/40 backdrop-blur-xl border border-white/10 rounded-[20px] p-6 hover:bg-white/5 hover:border-white/20 hover:shadow-[0_0_20px_rgba(255,255,255,0.05)] transition-all block flex flex-col h-full relative overflow-hidden">
                       
                        <div class="absolute inset-0 bg-gradient-to-br from-amber-500/5 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-500"></div>

                        <div class="flex items-start justify-between gap-3 mb-4 relative z-10">
                            @if($paper->subject)
                                <span class="text-[10px] font-bold uppercase tracking-widest text-amber-500 bg-amber-500/10 border border-amber-500/20 px-2.5 py-1 rounded-md">{{ $paper->subject->code }}</span>
                            @endif
                            <span class="text-xs font-medium text-slate-500 ml-auto flex items-center gap-1.5 bg-white/5 px-2.5 py-1 rounded-md border border-white/10"><i class="ph-fill ph-eye"></i> {{ $paper->view_count }}</span>
                        </div>

                        <h3 class="font-geist font-bold text-xl text-white group-hover:text-amber-500 transition-colors leading-snug relative z-10">
                            {{ $paper->title }}
                        </h3>

                        @if($paper->abstract)
                            <p class="text-sm text-slate-400 mt-3 line-clamp-3 leading-relaxed relative z-10 flex-1">{{ $paper->abstract }}</p>
                        @endif

                        <div class="flex items-center justify-between mt-5 pt-4 border-t border-white/10 relative z-10">
                            <div class="flex items-center gap-2.5">
                                <div class="w-7 h-7 rounded-full bg-gradient-to-br from-amber-500 to-amber-700 flex items-center justify-center text-white text-xs font-bold shadow-inner">
                                    {{ strtoupper(substr($paper->author->name, 0, 1)) }}
                                </div>
                                <span class="text-xs font-medium text-slate-300">{{ $paper->author->name }}</span>
                            </div>
                            <span class="text-[11px] font-medium text-slate-500 flex items-center gap-1.5"><i class="ph-bold ph-calendar-blank"></i> {{ $paper->published_at?->diffForHumans() }}</span>
                        </div>

                        @if($paper->tags->count())
                            <div class="flex flex-wrap gap-2 mt-4 relative z-10">
                                @foreach($paper->tags->take(3) as $tag)
                                    <span class="px-2 py-0.5 text-[10px] font-bold uppercase tracking-widest rounded-md text-white/90 shadow-inner" style="background-color: {{ $tag->color }}80; border: 1px solid {{ $tag->color }}40;">{{ $tag->name }}</span>
                                @endforeach
                            </div>
                        @endif
                    </a>
                @endforeach
            </div>

            @if($papers->hasPages())
                <div class="mt-8 relative z-10">{{ $papers->links() }}</div>
            @endif
        @else
            <div class="text-center py-24 bg-black/20 backdrop-blur-md border border-white/5 rounded-[24px] relative z-10 flex flex-col items-center justify-center">
                <div class="w-16 h-16 rounded-full bg-white/5 border border-white/10 flex items-center justify-center mb-4">
                    <i class="ph-fill ph-magnifying-glass text-2xl text-slate-500"></i>
                </div>
                <p class="font-geist font-bold text-xl text-white mb-2">"Not found, but the search goes on."</p>
                <p class="text-sm text-slate-500">Coba kata kunci lain atau pilih subject berbeda.</p>
            </div>
        @endif
    </div>
</x-app-layout>
