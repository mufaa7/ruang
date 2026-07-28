<x-app-layout>
    <x-slot name="pageTitle">{{ $paper->title }}</x-slot>
    <x-slot name="pageSubtitle">{{ $paper->author->name }} · {{ $paper->published_at?->format('d M Y') }}</x-slot>

    <div class="max-w-4xl grid grid-cols-1 lg:grid-cols-4 gap-6">

        {{-- Main content --}}
        <div class="lg:col-span-3 space-y-6">

            {{-- Header card --}}
            <div class="bg-white dark:bg-slate-900/70 border border-stone-300/60 rounded-xl p-6">
                <div class="flex items-start gap-3 mb-4">
                    <div class="flex items-center gap-2 flex-wrap">
                        @if($paper->subject)
                            <span class="text-xs font-mono px-2 py-1 rounded bg-stone-100 text-stone-500 dark:bg-slate-900/80">{{ $paper->subject->code }} · {{ $paper->subject->name }}</span>
                        @endif
                        @foreach($paper->tags as $tag)
                            <span class="text-xs px-2 py-1 rounded text-white font-mono" style="background:{{ $tag->color }}">{{ $tag->name }}</span>
                        @endforeach
                    </div>
                </div>
                <h1 class="font-display text-2xl font-bold text-[#1a1814] leading-tight">{{ $paper->title }}</h1>
                @if($paper->abstract)
                    <p class="text-[#8c8479] text-sm leading-relaxed mt-3 border-l-2 border-stone-300 pl-4 italic dark:border-slate-700">
                        {{ $paper->abstract }}
                    </p>
                @endif
                <div class="flex items-center gap-4 mt-4 pt-4 border-t border-stone-100">
                    <div class="flex items-center gap-2">
                        <div class="w-7 h-7 rounded-full bg-[#c45c2a] flex items-center justify-center text-white text-xs font-bold">
                            {{ strtoupper(substr($paper->author->name, 0, 1)) }}
                        </div>
                        <span class="text-sm text-[#1a1814]">{{ $paper->author->name }}</span>
                    </div>
                    <span class="text-xs text-stone-400">{{ $paper->view_count }} views</span>
                    <span class="text-xs text-stone-400">{{ $paper->download_count }} downloads</span>
                </div>
            </div>

            {{-- Sections --}}
            @foreach($paper->sections as $section)
                <div class="bg-white dark:bg-slate-900/70 border border-stone-300/60 rounded-xl overflow-hidden">
                    <div class="px-6 py-4 border-b border-stone-100 flex items-center gap-3">
                        <span class="font-mono text-xs text-stone-400">{{ str_pad($loop->index + 1, 2, '0', STR_PAD_LEFT) }}</span>
                        <h2 class="font-display font-bold text-[#1a1814]">{{ $section->title }}</h2>
                    </div>
                    <div class="px-8 py-8 max-w-none font-serif text-[1.1rem] leading-loose text-[#2a2520] whitespace-pre-wrap">
                        {!! strip_tags($section->content, '<b><i><u><strong><em><br><ul><ol><li><a><blockquote>') !!}
                    </div>
                </div>
            @endforeach

        </div>

        {{-- Sidebar --}}
        <div class="space-y-4">
            @if(auth()->id() === $paper->user_id)
                <a href="{{ route('papers.edit', $paper) }}"
                   class="flex items-center justify-center gap-2 w-full py-2.5 min-h-[44px] active:scale-95 border border-stone-300 text-[#1a1814] text-sm rounded-lg hover:border-[#c45c2a] hover:text-[#c45c2a] transition-colors dark:border-slate-700">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                    </svg>
                    Edit Jurnal
                </a>
            @endif

            <div class="bg-white dark:bg-slate-900/70 border border-stone-300/60 rounded-xl p-5">
                <h3 class="text-xs font-semibold text-[#8c8479] uppercase tracking-widest mb-4">Detail</h3>
                <dl class="space-y-3">
                    <div><dt class="text-xs text-stone-400">Sections</dt><dd class="text-sm text-[#1a1814] mt-0.5">{{ $paper->sections->count() }}</dd></div>
                    <div><dt class="text-xs text-stone-400">Dipublish</dt><dd class="text-sm text-[#1a1814] mt-0.5">{{ $paper->published_at?->format('d M Y') ?? '—' }}</dd></div>
                    <div><dt class="text-xs text-stone-400">Kolaborator</dt><dd class="text-sm text-[#1a1814] mt-0.5">{{ $paper->collaborators->count() }} orang</dd></div>
                </dl>
            </div>

            {{-- Collaborators --}}
            @if($paper->collaborators->count())
                <div class="bg-white dark:bg-slate-900/70 border border-stone-300/60 rounded-xl p-5">
                    <h3 class="text-xs font-semibold text-[#8c8479] uppercase tracking-widest mb-3">Kolaborator</h3>
                    <div class="space-y-2">
                        @foreach($paper->collaborators as $collab)
                            <div class="flex items-center gap-2">
                                <div class="w-6 h-6 rounded-full bg-[#4a6741] flex items-center justify-center text-white text-xs font-bold">
                                    {{ strtoupper(substr($collab->name, 0, 1)) }}
                                </div>
                                <span class="text-sm text-[#1a1814]">{{ $collab->name }}</span>
                                <span class="text-xs text-stone-400 font-mono">{{ $collab->pivot->role }}</span>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
