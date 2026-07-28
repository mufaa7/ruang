<x-app-layout>
    <x-slot name="pageTitle">Eksplor Jurnal</x-slot>
    <x-slot name="pageSubtitle">Karya dari komunitas RUANG</x-slot>

    <div class="max-w-5xl space-y-5">

        {{-- Search & filter --}}
        <form method="GET" class="flex flex-col sm:flex-row gap-3">
            <div class="flex-1 relative w-full">
                <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-stone-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                </svg>
                <input type="text" name="search" value="{{ request('search') }}"
                       class="w-full bg-white dark:bg-slate-900/70 border border-stone-300 rounded-lg pl-9 pr-4 py-2.5 min-h-[44px] text-sm text-[#1a1814] placeholder-stone-400 focus:outline-none focus:border-[#c45c2a] transition dark:border-slate-700"
                       placeholder="Cari jurnal...">
            </div>
            <select name="subject_id" class="w-full sm:w-auto bg-white dark:bg-slate-900/70 border border-stone-300 rounded-lg px-4 py-2.5 min-h-[44px] text-sm text-[#1a1814] focus:outline-none focus:border-[#c45c2a] transition dark:border-slate-700">
                <option value="">Semua Subject</option>
                @foreach($subjects as $s)
                    <option value="{{ $s->id }}" {{ request('subject_id') == $s->id ? 'selected' : '' }}>{{ $s->name }}</option>
                @endforeach
            </select>
            <button type="submit" class="w-full sm:w-auto justify-center px-4 py-2.5 min-h-[44px] bg-[#1a1814] text-white text-sm rounded-lg hover:bg-[#c45c2a] active:scale-95 transition-colors flex items-center gap-2">
                Cari
            </button>
        </form>

        {{-- Results --}}
        @if($papers->count())
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                @foreach($papers as $paper)
                    <a href="{{ route('papers.show', $paper) }}"
                       class="group bg-white dark:bg-slate-900/70 border border-stone-300/60 rounded-xl p-5 hover:border-[#c45c2a]/50 hover:shadow-sm transition-all block">

                        <div class="flex items-start justify-between gap-2 mb-3">
                            @if($paper->subject)
                                <span class="text-xs font-mono text-stone-400">{{ $paper->subject->code }}</span>
                            @endif
                            <span class="text-xs text-stone-400 ml-auto">{{ $paper->view_count }} views</span>
                        </div>

                        <h3 class="font-display font-bold text-[#1a1814] group-hover:text-[#c45c2a] transition-colors leading-snug">
                            {{ $paper->title }}
                        </h3>

                        @if($paper->abstract)
                            <p class="text-xs text-[#8c8479] mt-2 line-clamp-2 leading-relaxed">{{ $paper->abstract }}</p>
                        @endif

                        <div class="flex items-center justify-between mt-4 pt-3 border-t border-stone-100">
                            <div class="flex items-center gap-2">
                                <div class="w-5 h-5 rounded-full bg-[#c45c2a] flex items-center justify-center text-white text-xs font-bold">
                                    {{ strtoupper(substr($paper->author->name, 0, 1)) }}
                                </div>
                                <span class="text-xs text-[#8c8479]">{{ $paper->author->name }}</span>
                            </div>
                            <span class="text-xs text-stone-400">{{ $paper->published_at?->diffForHumans() }}</span>
                        </div>

                        @if($paper->tags->count())
                            <div class="flex flex-wrap gap-1.5 mt-3">
                                @foreach($paper->tags->take(3) as $tag)
                                    <span class="px-2 py-0.5 text-xs rounded text-white font-mono" style="background:{{ $tag->color }}">{{ $tag->name }}</span>
                                @endforeach
                            </div>
                        @endif
                    </a>
                @endforeach
            </div>

            @if($papers->hasPages())
                <div class="mt-6">{{ $papers->links() }}</div>
            @endif
        @else
            <div class="text-center py-20 border border-dashed border-stone-300 rounded-xl dark:border-slate-700">
                <p class="font-display italic text-[#8c8479]">"Not found, but the search goes on."</p>
                <p class="text-xs text-stone-400 mt-2">Coba kata kunci lain</p>
            </div>
        @endif
    </div>
</x-app-layout>
