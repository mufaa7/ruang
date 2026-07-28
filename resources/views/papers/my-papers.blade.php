<x-app-layout>
    <x-slot name="pageTitle">Jurnal Kamu</x-slot>
    <x-slot name="pageSubtitle">{{ auth()->user()->papers()->count() }} karya total</x-slot>
    <x-slot name="headerActions">
        <a href="{{ route('papers.create') }}"
           class="inline-flex items-center gap-2 px-4 py-2 bg-neutral-900 text-white text-sm font-semibold rounded-xl shadow-sm hover:bg-neutral-800 transition-colors">
            <i class="ph-bold ph-plus text-lg"></i>
            Jurnal Baru
        </a>
    </x-slot>

    {{-- Filter tabs --}}
    <div class="flex gap-1 mb-5 border-b border-stone-200 overflow-x-auto whitespace-nowrap hide-scrollbar scroll-smooth dark:border-slate-700/50">
        @foreach(['Semua' => '', 'Draft' => 'draft', 'Published' => 'published', 'Archived' => 'archived'] as $label => $val)
            <a href="{{ route('papers.my', $val ? ['status' => $val] : []) }}"
               class="px-4 py-2.5 min-h-[44px] flex items-center justify-center text-sm transition-colors border-b-2 -mb-px
               {{ request('status', '') === $val
                   ? 'border-neutral-900 text-neutral-900 font-bold'
                   : 'border-transparent text-neutral-500 hover:text-neutral-900 font-medium' }} dark:text-white dark:text-slate-400">
                {{ $label }}
            </a>
        @endforeach
    </div>

    @if($papers->count())
        <div class="space-y-2">
            @foreach($papers as $paper)
                <div class="group bg-white border border-stone-200 rounded-[14px] px-5 py-4 hover:border-stone-300 hover:shadow-sm transition-all flex items-start gap-4 dark:bg-slate-900 dark:border-slate-700/50">

                    {{-- Status bar --}}
                    <div class="shrink-0 mt-1">
                        <div @class([
                            'w-2 h-2 rounded-full mt-1.5',
                            'bg-emerald-500'  => $paper->status === 'published',
                            'bg-stone-300'  => $paper->status === 'draft',
                            'bg-amber-500'  => $paper->status === 'in_review',
                            'bg-stone-400'  => $paper->status === 'archived',
                        ])></div>
                    </div>

                    <div class="flex-1 min-w-0">
                        <a href="{{ route('papers.edit', $paper) }}"
                           class="font-bold text-neutral-900 group-hover:text-neutral-700 transition-colors line-clamp-1 text-base dark:text-white">
                            {{ $paper->title }}
                        </a>
                        @if($paper->abstract)
                            <p class="text-xs text-neutral-500 mt-1 line-clamp-1 dark:text-slate-400">{{ $paper->abstract }}</p>
                        @endif
                        <div class="flex items-center gap-3 mt-2">
                            <span class="text-[10px] font-bold uppercase tracking-wider text-neutral-400">{{ $paper->status }}</span>
                            @if($paper->subject)
                                <span class="text-[11px] font-medium text-neutral-400 border-l border-stone-200 pl-3 dark:border-slate-700/50">{{ $paper->subject->name }}</span>
                            @endif
                            <span class="text-[11px] text-neutral-400 border-l border-stone-200 pl-3 flex items-center gap-1 dark:border-slate-700/50"><i class="ph-fill ph-clock"></i> {{ $paper->updated_at->diffForHumans() }}</span>
                        </div>
                    </div>

                    <div class="flex flex-col sm:flex-row items-center gap-2 shrink-0 opacity-100 sm:opacity-0 group-hover:opacity-100 transition-opacity">
                        <a href="{{ route('papers.edit', $paper) }}"
                           class="w-[44px] h-[44px] flex items-center justify-center text-neutral-400 hover:text-neutral-900 bg-stone-50 hover:bg-stone-100 rounded-xl transition-colors active:scale-95 dark:bg-slate-900/50">
                            <i class="ph-bold ph-pencil-simple text-base"></i>
                        </a>
                        @if($paper->status === 'published')
                            <a href="{{ route('papers.show', $paper) }}"
                               class="w-[44px] h-[44px] flex items-center justify-center text-neutral-400 hover:text-neutral-900 bg-stone-50 hover:bg-stone-100 rounded-xl transition-colors active:scale-95 dark:bg-slate-900/50">
                                <i class="ph-bold ph-arrow-square-out text-base"></i>
                            </a>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>

        {{-- Pagination --}}
        @if($papers->hasPages())
            <div class="mt-6">{{ $papers->links() }}</div>
        @endif
    @else
        <div class="text-center py-20 border border-dashed border-stone-200 bg-stone-50/50 rounded-[20px] dark:border-slate-700/50">
            <i class="ph-fill ph-files text-5xl text-neutral-300 mb-3 block"></i>
            <p class="font-medium text-neutral-500 text-sm dark:text-slate-400">"Every journey starts with a single word."</p>
            <a href="{{ route('papers.create') }}" class="mt-5 inline-flex justify-center items-center gap-2 px-6 py-2.5 min-h-[44px] bg-neutral-900 text-white text-sm font-semibold rounded-xl hover:bg-neutral-800 transition-colors shadow-sm active:scale-95 w-full sm:w-auto">
                <i class="ph-bold ph-plus text-lg"></i> Tulis Jurnal Pertama
            </a>
        </div>
    @endif
</x-app-layout>
