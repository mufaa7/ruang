<x-app-layout>
    <x-slot name="pageTitle">{{ $paper->title }}</x-slot>
    <x-slot name="pageSubtitle">{{ $paper->author->name }} · {{ $paper->published_at?->format('d M Y') }}</x-slot>

    <div class="max-w-4xl grid grid-cols-1 lg:grid-cols-4 gap-6 animate-fadeIn">

        {{-- Main content --}}
        <div class="lg:col-span-3 space-y-6">

            {{-- Header card --}}
            <div class="bg-black/40 backdrop-blur-xl border border-white/10 rounded-[24px] p-8 shadow-2xl relative overflow-hidden">
                <div class="absolute inset-0 bg-gradient-to-br from-white/5 to-transparent pointer-events-none"></div>

                <div class="flex items-start gap-3 mb-6 relative z-10">
                    <div class="flex items-center gap-2 flex-wrap">
                        @if($paper->subject)
                            <span class="text-[11px] font-bold uppercase tracking-widest px-3 py-1.5 rounded-lg bg-amber-500/10 border border-amber-500/20 text-amber-500 shadow-inner">{{ $paper->subject->code }} · {{ $paper->subject->name }}</span>
                        @endif
                        @foreach($paper->tags as $tag)
                            <span class="text-[10px] font-bold uppercase tracking-widest px-3 py-1.5 rounded-lg text-white/90 shadow-inner" style="background-color: {{ $tag->color }}80; border: 1px solid {{ $tag->color }}40;">{{ $tag->name }}</span>
                        @endforeach
                    </div>
                </div>
                <h1 class="font-geist text-3xl md:text-4xl font-bold text-white leading-tight tracking-tight mb-4 relative z-10">{{ $paper->title }}</h1>
                @if($paper->abstract)
                    <p class="text-slate-300 text-[15px] leading-relaxed mt-4 border-l-2 border-amber-500/50 pl-5 italic relative z-10 bg-white/5 py-3 pr-4 rounded-r-xl">
                        {{ $paper->abstract }}
                    </p>
                @endif
                <div class="flex flex-wrap items-center gap-6 mt-8 pt-6 border-t border-white/10 relative z-10">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-full bg-gradient-to-br from-amber-500 to-amber-700 flex items-center justify-center text-white text-sm font-bold shadow-inner">
                            {{ strtoupper(substr($paper->author->name, 0, 1)) }}
                        </div>
                        <span class="text-[15px] font-bold text-white">{{ $paper->author->name }}</span>
                    </div>
                    <div class="flex gap-4 ml-auto">
                        <span class="text-xs font-medium text-slate-400 flex items-center gap-1.5 bg-white/5 px-3 py-1.5 rounded-lg border border-white/10"><i class="ph-bold ph-eye text-amber-500"></i> {{ $paper->view_count }}</span>
                        <span class="text-xs font-medium text-slate-400 flex items-center gap-1.5 bg-white/5 px-3 py-1.5 rounded-lg border border-white/10"><i class="ph-bold ph-download-simple text-amber-500"></i> {{ $paper->download_count }}</span>
                    </div>
                </div>
            </div>

            {{-- Sections --}}
            @foreach($paper->sections as $section)
                <div class="bg-black/40 backdrop-blur-xl border border-white/10 rounded-[24px] overflow-hidden shadow-2xl relative">
                    <div class="absolute inset-0 bg-gradient-to-b from-white/5 to-transparent pointer-events-none"></div>
                    
                    <div class="px-8 py-5 border-b border-white/10 flex items-center gap-4 bg-white/5 relative z-10">
                        <span class="font-geist text-sm font-bold text-amber-500 bg-amber-500/10 px-2 py-1 rounded border border-amber-500/20">{{ str_pad($loop->index + 1, 2, '0', STR_PAD_LEFT) }}</span>
                        <h2 class="font-geist text-xl font-bold text-white">{{ $section->title }}</h2>
                    </div>
                    <div class="px-8 py-8 max-w-none font-serif-editor text-[1.1rem] leading-loose text-slate-200 whitespace-pre-wrap relative z-10 selection:bg-amber-500/30">
                        {!! strip_tags($section->content, '<b><i><u><strong><em><br><ul><ol><li><a><blockquote>') !!}
                    </div>
                </div>
            @endforeach

        </div>

        {{-- Sidebar --}}
        <div class="space-y-5">
            @if(auth()->id() === $paper->user_id)
                <a href="{{ route('papers.edit', $paper) }}"
                   class="flex items-center justify-center gap-2 w-full py-3 min-h-[48px] bg-white/10 hover:bg-white/20 border border-white/10 text-white font-bold text-sm rounded-xl transition-all hover:scale-105 active:scale-95 shadow-sm">
                    <i class="ph-bold ph-pencil-simple text-lg"></i>
                    Edit Jurnal
                </a>
            @endif

            <div class="bg-black/40 backdrop-blur-xl border border-white/10 rounded-[20px] p-6 shadow-2xl relative">
                <div class="absolute inset-0 bg-gradient-to-br from-white/5 to-transparent pointer-events-none rounded-[20px]"></div>
                <h3 class="text-[11px] font-bold text-amber-500 uppercase tracking-widest mb-5 flex items-center gap-2 relative z-10"><i class="ph-fill ph-info"></i> Detail Jurnal</h3>
                <dl class="space-y-4 relative z-10">
                    <div class="flex justify-between items-center"><dt class="text-xs font-medium text-slate-400">Total Bagian</dt><dd class="text-sm font-bold text-white bg-white/10 px-2 py-0.5 rounded">{{ $paper->sections->count() }}</dd></div>
                    <div class="flex justify-between items-center"><dt class="text-xs font-medium text-slate-400">Dipublish</dt><dd class="text-sm font-bold text-white">{{ $paper->published_at?->format('d M Y') ?? '—' }}</dd></div>
                    <div class="flex justify-between items-center"><dt class="text-xs font-medium text-slate-400">Kolaborator</dt><dd class="text-sm font-bold text-white">{{ $paper->collaborators->count() }} orang</dd></div>
                </dl>
            </div>

            {{-- Collaborators --}}
            @if($paper->collaborators->count())
                <div class="bg-black/40 backdrop-blur-xl border border-white/10 rounded-[20px] p-6 shadow-2xl relative">
                    <div class="absolute inset-0 bg-gradient-to-br from-white/5 to-transparent pointer-events-none rounded-[20px]"></div>
                    <h3 class="text-[11px] font-bold text-amber-500 uppercase tracking-widest mb-4 flex items-center gap-2 relative z-10"><i class="ph-fill ph-users-three"></i> Tim Penulis</h3>
                    <div class="space-y-3 relative z-10">
                        @foreach($paper->collaborators as $collab)
                            <div class="flex items-center gap-3 p-2 rounded-xl hover:bg-white/5 border border-transparent hover:border-white/10 transition-colors">
                                <div class="w-8 h-8 rounded-full bg-gradient-to-br from-sky-400 to-sky-600 flex items-center justify-center text-white text-xs font-bold shadow-inner">
                                    {{ strtoupper(substr($collab->name, 0, 1)) }}
                                </div>
                                <div class="flex-1 min-w-0">
                                    <div class="text-sm font-bold text-white truncate">{{ $collab->name }}</div>
                                    <div class="text-[10px] font-bold text-sky-400 uppercase tracking-wider">{{ $collab->pivot->role }}</div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
