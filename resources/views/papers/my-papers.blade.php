<x-app-layout>
    <x-slot name="pageTitle">Jurnal Kamu</x-slot>
    <x-slot name="pageSubtitle">{{ auth()->user()->papers()->count() }} karya total</x-slot>
    <div class="flex items-center justify-between gap-4 mb-6">
        <div></div> {{-- Spacer --}}
        <a href="{{ route('papers.create') }}"
           class="inline-flex items-center gap-2 px-5 py-2.5 bg-white/10 hover:bg-white/20 border border-white/10 text-white text-sm font-bold rounded-xl shadow-[0_0_15px_rgba(255,255,255,0.05)] transition-all hover:scale-105 active:scale-95 backdrop-blur-md">
            <i class="ph-bold ph-plus text-lg"></i>
            Jurnal Baru
        </a>
    </div>

    {{-- Filter tabs --}}
    <div class="flex gap-2 mb-6 border-b border-white/10 overflow-x-auto whitespace-nowrap hide-scrollbar scroll-smooth pb-px">
        @foreach(['Semua' => '', 'Draft' => 'draft', 'Published' => 'published', 'Archived' => 'archived'] as $label => $val)
            <a href="{{ route('papers.my', $val ? ['status' => $val] : []) }}"
               class="px-5 py-3 min-h-[44px] flex items-center justify-center text-sm transition-all border-b-2 -mb-px
               {{ request('status', '') === $val
                   ? 'border-amber-500 text-amber-500 font-bold'
                   : 'border-transparent text-slate-400 hover:text-white font-medium hover:border-white/20' }}">
                {{ $label }}
            </a>
        @endforeach
    </div>

    @if($papers->count())
        <div class="space-y-3 animate-fadeIn relative">
            <div class="absolute inset-0 bg-gradient-to-br from-white/5 to-transparent pointer-events-none rounded-[24px]"></div>
            
            @foreach($papers as $paper)
                <div class="group bg-black/40 border border-white/10 rounded-[18px] px-6 py-5 hover:bg-white/5 hover:border-white/20 hover:shadow-[0_0_20px_rgba(255,255,255,0.05)] transition-all flex items-start gap-4 backdrop-blur-xl relative z-10">

                    {{-- Status bar --}}
                    <div class="shrink-0 mt-1.5">
                        <div @class([
                            'w-2.5 h-2.5 rounded-full',
                            'bg-emerald-400 shadow-[0_0_8px_rgba(52,211,153,0.8)]'  => $paper->status === 'published',
                            'bg-slate-400 shadow-[0_0_8px_rgba(148,163,184,0.5)]'  => $paper->status === 'draft',
                            'bg-amber-400 shadow-[0_0_8px_rgba(251,191,36,0.8)]'  => $paper->status === 'in_review',
                            'bg-stone-500 shadow-[0_0_8px_rgba(120,113,108,0.5)]'  => $paper->status === 'archived',
                        ])></div>
                    </div>

                    <div class="flex-1 min-w-0">
                        <a href="{{ route('papers.edit', $paper) }}"
                           class="font-geist font-bold text-white group-hover:text-amber-500 transition-colors line-clamp-1 text-lg">
                            {{ $paper->title }}
                        </a>
                        @if($paper->abstract)
                            <p class="text-sm text-slate-400 mt-1.5 line-clamp-2 leading-relaxed">{{ $paper->abstract }}</p>
                        @endif
                        <div class="flex items-center gap-3 mt-3">
                            <span class="text-[10px] font-bold uppercase tracking-widest {{ $paper->status === 'published' ? 'text-emerald-400' : 'text-slate-500' }}">{{ $paper->status }}</span>
                            @if($paper->subject)
                                <span class="text-[11px] font-medium text-slate-400 border-l border-white/10 pl-3">{{ $paper->subject->name }}</span>
                            @endif
                            <span class="text-[11px] text-slate-400 border-l border-white/10 pl-3 flex items-center gap-1.5"><i class="ph-bold ph-clock"></i> {{ $paper->updated_at->diffForHumans() }}</span>
                        </div>
                    </div>

                    <div class="flex flex-col sm:flex-row items-center gap-2 shrink-0 opacity-100 sm:opacity-0 group-hover:opacity-100 transition-opacity">
                        <a href="{{ route('papers.edit', $paper) }}"
                           class="w-[44px] h-[44px] flex items-center justify-center text-slate-400 hover:text-white bg-white/5 hover:bg-white/10 border border-white/5 hover:border-white/20 rounded-xl transition-all active:scale-95">
                            <i class="ph-bold ph-pencil-simple text-lg"></i>
                        </a>
                        @if($paper->status === 'published')
                            <a href="{{ route('papers.show', $paper) }}"
                               class="w-[44px] h-[44px] flex items-center justify-center text-emerald-400 hover:text-emerald-300 bg-emerald-500/10 hover:bg-emerald-500/20 border border-emerald-500/20 hover:border-emerald-500/30 rounded-xl transition-all active:scale-95">
                                <i class="ph-bold ph-arrow-square-out text-lg"></i>
                            </a>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>

        {{-- Pagination --}}
        @if($papers->hasPages())
            <div class="mt-8">{{ $papers->links() }}</div>
        @endif
    @else
        <div class="text-center py-24 bg-black/20 backdrop-blur-md border border-white/5 rounded-[24px] flex flex-col items-center justify-center">
            <div class="w-20 h-20 rounded-full bg-white/5 border border-white/10 flex items-center justify-center mb-5 shadow-2xl">
                <i class="ph-fill ph-files text-4xl text-slate-500"></i>
            </div>
            <p class="font-geist font-bold text-xl text-white mb-2">"Every journey starts with a single word."</p>
            <p class="text-sm text-slate-500 mb-8 max-w-sm">Mulai tuangkan idemu menjadi jurnal yang bermanfaat buat komunitas.</p>
            <a href="{{ route('papers.create') }}" class="inline-flex justify-center items-center gap-2 px-6 py-3 bg-white/10 border border-white/10 text-white text-sm font-bold rounded-xl hover:bg-white/20 transition-all shadow-[0_0_15px_rgba(255,255,255,0.05)] hover:scale-105 active:scale-95">
                <i class="ph-bold ph-plus text-lg"></i> Tulis Jurnal Pertama
            </a>
        </div>
    @endif
</x-app-layout>
