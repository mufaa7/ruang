<x-app-layout>
    <x-slot name="pageTitle">Jejak</x-slot>

    <div class="space-y-6 sm:space-y-8 animate-fadeIn pb-10">

        {{-- Header --}}
        <div class="flex flex-col sm:flex-row sm:items-end justify-between gap-6 pb-6 border-b border-white/10">
            <div class="max-w-2xl">
                <h1 class="text-2xl sm:text-3xl font-bold text-white font-geist tracking-tight flex items-center gap-3">
                    Rekam jejak lu, biar kelihatan rajin. <i class="ph-fill ph-calendar text-amber-500 drop-shadow-[0_0_15px_rgba(245,158,11,0.5)]"></i>
                </h1>
                <p class="text-sm sm:text-base text-slate-400 mt-2">
                    Semua aktivitas lu di RUANG tercatat di sini. Termasuk yang iseng-iseng.
                </p>
            </div>

            {{-- Stats Singkat --}}
            <div class="flex items-center gap-6 shrink-0 bg-black/40 backdrop-blur-xl border border-white/10 rounded-[20px] p-4 shadow-2xl w-full sm:w-auto justify-around sm:justify-start relative overflow-hidden">
                <div class="absolute inset-0 bg-gradient-to-br from-white/5 to-transparent pointer-events-none"></div>
                <div class="text-center relative z-10">
                    <p class="text-2xl sm:text-3xl font-bold text-white font-geist leading-none">{{ $totalToday }}</p>
                    <p class="text-[10px] sm:text-[11px] font-bold text-amber-300 uppercase tracking-widest mt-1.5 flex items-center gap-1"><i class="ph-fill ph-lightning"></i> Hari Ini</p>
                </div>
                <div class="w-px h-10 bg-white/10 relative z-10"></div>
                <div class="text-center relative z-10">
                    <p class="text-2xl sm:text-3xl font-bold text-white font-geist leading-none">{{ $totalAll }}</p>
                    <p class="text-[10px] sm:text-[11px] font-bold text-slate-300 uppercase tracking-widest mt-1.5 flex items-center gap-1"><i class="ph-fill ph-clock-counter-clockwise"></i> Total</p>
                </div>
            </div>
        </div>

        {{-- Filter Tipe (Scrollable di Mobile) --}}
        <div class="flex items-center gap-3 overflow-x-auto pb-4 -mx-4 px-4 sm:mx-0 sm:px-0 hide-scrollbar">
            <a href="{{ route('jejak.index') }}" 
               class="shrink-0 px-5 py-2.5 rounded-xl text-sm font-bold transition-all active:scale-95 flex items-center gap-2 shadow-sm
               {{ !request('type') ? 'bg-amber-400/15 border border-amber-400/30 text-amber-300' : 'bg-white/5 text-slate-300 border border-white/10 hover:text-white hover:bg-white/10' }}">
                <i class="ph-bold ph-squares-four"></i> Semua
            </a>
            @foreach($activityTypes as $type => $label)
            <a href="{{ route('jejak.index', ['type' => $type]) }}" 
               class="shrink-0 px-5 py-2.5 rounded-xl text-sm font-bold transition-all active:scale-95 flex items-center gap-2 shadow-sm
               {{ request('type') === $type ? 'bg-amber-400/15 border border-amber-400/30 text-amber-300' : 'bg-white/5 text-slate-300 border border-white/10 hover:text-white hover:bg-white/10' }}">
                {!! $label['emoji'] !!} {{ $label['label'] }}
            </a>
            @endforeach
        </div>

        {{-- Timeline Aktivitas --}}
        <div class="space-y-10 relative">
            @forelse($groupedActivities as $date => $dayActivities)
            <div class="relative">
                {{-- Tanggal Header --}}
                <div class="sticky top-0 z-10 bg-[#0b101e]/80 backdrop-blur-md py-4 flex items-center gap-4 mb-2">
                    <div class="px-4 py-2 bg-white/10 border border-white/10 rounded-xl shadow-inner">
                        <span class="text-[11px] font-bold uppercase tracking-widest text-white flex items-center gap-1.5">
                            @if($date === now()->format('Y-m-d'))
                                <i class="ph-fill ph-lightning text-amber-500"></i> Hari Ini
                            @elseif($date === now()->subDay()->format('Y-m-d'))
                                <i class="ph-fill ph-clock-counter-clockwise text-slate-400"></i> Kemarin
                            @else
                                <i class="ph-fill ph-calendar-blank text-slate-400"></i> {{ \Carbon\Carbon::parse($date)->translatedFormat('l, d F Y') }}
                            @endif
                        </span>
                    </div>
                    <div class="flex-1 h-px bg-white/10"></div>
                    <span class="text-[11px] font-bold text-slate-500 bg-white/5 px-2.5 py-1 rounded-md border border-white/5">{{ $dayActivities->count() }} aktivitas</span>
                </div>

                {{-- List per hari --}}
                <div class="bg-black/40 backdrop-blur-xl border border-white/10 rounded-[24px] divide-y divide-white/10 overflow-hidden shadow-2xl relative">
                    <div class="absolute inset-0 bg-gradient-to-br from-white/5 to-transparent pointer-events-none"></div>
                    
                    @foreach($dayActivities as $activity)
                    @php
                        $typeConfig = [
                            'note.created'     => ['emoji' => '<i class="ph-fill ph-pencil-simple text-lg"></i>', 'color' => 'bg-white/10 border border-white/10 text-slate-300'],
                            'paper.published'  => ['emoji' => '<i class="ph-fill ph-file-text text-lg"></i>', 'color' => 'bg-emerald-500/10 border border-emerald-500/20 text-emerald-400'],
                            'paper.created'    => ['emoji' => '<i class="ph-fill ph-pen-nib text-lg"></i>', 'color' => 'bg-blue-500/10 border border-blue-500/20 text-blue-400'],
                            'quiz.generated'   => ['emoji' => '<i class="ph-fill ph-flask text-lg"></i>', 'color' => 'bg-amber-500/10 border border-amber-500/20 text-amber-500'],
                            'material.uploaded'=> ['emoji' => '<i class="ph-fill ph-books text-lg"></i>', 'color' => 'bg-violet-500/10 border border-violet-500/20 text-violet-400'],
                        ];
                        $config = $typeConfig[$activity->type] ?? ['emoji' => '<i class="ph-fill ph-lightning text-lg"></i>', 'color' => 'bg-white/10 border border-white/10 text-slate-300'];
                        $typeLabels = ['note.created' => 'Coretan', 'paper.published' => 'Makalah', 'paper.created' => 'Makalah', 'quiz.generated' => 'Latihan', 'material.uploaded' => 'Materi'];
                        $typeLabel = $typeLabels[$activity->type] ?? ucfirst(str_replace('.', ' ', $activity->type));
                    @endphp
                    
                    <div class="p-5 sm:p-6 hover:bg-white/5 flex flex-col sm:flex-row sm:items-center gap-4 transition-colors group relative z-10">
                        
                        <div class="flex items-center gap-4 w-full">
                            {{-- Ikon --}}
                            <div class="w-12 h-12 rounded-2xl {{ $config['color'] }} flex items-center justify-center shrink-0 shadow-inner">
                                {!! $config['emoji'] !!}
                            </div>

                            {{-- Info --}}
                            <div class="flex-1 min-w-0">
                                <p class="text-[15px] font-bold text-white leading-snug truncate sm:whitespace-normal group-hover:text-amber-500 transition-colors">
                                    {{ $activity->description }}
                                </p>
                                <div class="flex items-center gap-2 mt-1.5">
                                    <span class="text-xs font-medium text-slate-400 flex items-center gap-1">
                                        <i class="ph-bold ph-clock"></i> {{ $activity->created_at->format('H:i') }} WIB
                                    </span>
                                    <span class="w-1 h-1 rounded-full bg-slate-600"></span>
                                    <span class="text-[11px] font-bold text-slate-500 uppercase tracking-widest">
                                        {{ $typeLabel }}
                                    </span>
                                </div>
                            </div>
                            
                            {{-- Badge tipe --}}
                            <div class="shrink-0 hidden sm:block">
                                <span class="text-[10px] font-bold px-3 py-1.5 rounded-lg {{ $config['color'] }} shadow-inner uppercase tracking-widest opacity-0 group-hover:opacity-100 transition-all duration-200">
                                    {{ $activity->type }}
                                </span>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
            @empty
            <div class="py-20 flex flex-col items-center justify-center text-center bg-black/40 backdrop-blur-xl border border-white/10 rounded-[24px] shadow-2xl relative overflow-hidden">
                <div class="absolute inset-0 bg-gradient-to-br from-white/5 to-transparent pointer-events-none"></div>
                <div class="w-20 h-20 bg-white/5 border border-white/10 rounded-full flex items-center justify-center mb-6 relative z-10 shadow-inner">
                    <i class="ph-fill ph-ghost text-4xl text-slate-500"></i>
                </div>
                <h3 class="text-xl font-bold text-white font-geist relative z-10">Belum ada jejak.</h3>
                <p class="text-[15px] text-slate-400 mt-2 max-w-md relative z-10">
                    Mulai kerjakan sesuatu di RUANG dan aktivitasmu akan muncul di sini.
                </p>
                <a href="{{ route('dashboard') }}" class="mt-8 px-6 py-3 bg-white/10 border border-white/10 text-white font-bold text-sm rounded-xl hover:bg-white/20 transition-all active:scale-95 shadow-sm relative z-10 flex items-center gap-2">
                    <i class="ph-bold ph-house"></i> Kembali ke Beranda
                </a>
            </div>
            @endforelse
        </div>

        {{-- Pagination --}}
        @if($activities->hasPages())
        <div class="flex justify-center pt-8">
            {{ $activities->links() }}
        </div>
        @endif

    </div>

    <style>
        .hide-scrollbar::-webkit-scrollbar {
            display: none;
        }
        .hide-scrollbar {
            -ms-overflow-style: none;
            scrollbar-width: none;
        }
    </style>
</x-app-layout>
