<x-app-layout>
    <x-slot name="pageTitle">Jejak</x-slot>

    <div class="space-y-6 sm:space-y-8 animate-fadeIn pb-10">

        {{-- Header --}}
        <div class="flex flex-col sm:flex-row sm:items-end justify-between gap-6 pb-6 border-b border-stone-200 dark:border-stone-800">
            <div class="max-w-2xl">
                <h1 class="text-2xl sm:text-3xl font-bold text-stone-900 dark:text-white font-geist tracking-tight">
                    Rekam jejak lu, biar kelihatan rajin. <i class="ph-fill ph-calendar text-xl"></i>
                </h1>
                <p class="text-sm sm:text-base text-stone-500 dark:text-stone-400 mt-2">
                    Semua aktivitas lu di RUANG tercatat di sini. Termasuk yang iseng-iseng.
                </p>
            </div>

            {{-- Stats Singkat --}}
            <div class="flex items-center gap-6 shrink-0 bg-white dark:bg-stone-900 border border-stone-200 dark:border-stone-800 rounded-2xl p-4 shadow-sm w-full sm:w-auto justify-around sm:justify-start">
                <div class="text-center">
                    <p class="text-2xl sm:text-3xl font-bold text-stone-900 dark:text-white font-geist leading-none">{{ $totalToday }}</p>
                    <p class="text-[10px] sm:text-[11px] font-bold text-stone-500 dark:text-stone-400 uppercase tracking-widest mt-1">Hari Ini</p>
                </div>
                <div class="w-px h-10 bg-stone-200 dark:bg-stone-700"></div>
                <div class="text-center">
                    <p class="text-2xl sm:text-3xl font-bold text-stone-900 dark:text-white font-geist leading-none">{{ $totalAll }}</p>
                    <p class="text-[10px] sm:text-[11px] font-bold text-stone-500 dark:text-stone-400 uppercase tracking-widest mt-1">Total</p>
                </div>
            </div>
        </div>

        {{-- Filter Tipe (Scrollable di Mobile) --}}
        <div class="flex items-center gap-2 overflow-x-auto pb-2 -mx-4 px-4 sm:mx-0 sm:px-0 hide-scrollbar">
            <a href="{{ route('jejak.index') }}" 
               class="shrink-0 px-4 py-2 sm:py-2.5 rounded-xl text-sm font-semibold transition-all active:scale-95 {{ !request('type') ? 'bg-stone-900 dark:bg-white text-white dark:text-stone-900 shadow-sm' : 'bg-white dark:bg-stone-900 text-stone-600 dark:text-stone-400 border border-stone-200 dark:border-stone-800 hover:border-stone-300 dark:hover:border-stone-700' }}">
                Semua
            </a>
            @foreach($activityTypes as $type => $label)
            <a href="{{ route('jejak.index', ['type' => $type]) }}" 
               class="shrink-0 px-4 py-2 sm:py-2.5 rounded-xl text-sm font-semibold transition-all active:scale-95 {{ request('type') === $type ? 'bg-stone-900 dark:bg-white text-white dark:text-stone-900 shadow-sm' : 'bg-white dark:bg-stone-900 text-stone-600 dark:text-stone-400 border border-stone-200 dark:border-stone-800 hover:border-stone-300 dark:hover:border-stone-700' }}">
                {!! $label['emoji'] !!} {{ $label['label'] }}
            </a>
            @endforeach
        </div>

        {{-- Timeline Aktivitas --}}
        <div class="space-y-8">
            @forelse($groupedActivities as $date => $dayActivities)
            <div class="relative">
                {{-- Tanggal Header --}}
                <div class="sticky top-0 z-10 bg-[#fafafa] dark:bg-[#0f172a] py-3 flex items-center gap-3 mb-2">
                    <div class="px-3 py-1.5 bg-stone-100 dark:bg-stone-800/80 rounded-lg backdrop-blur-sm">
                        <span class="text-[10px] sm:text-[11px] font-bold uppercase tracking-widest text-stone-600 dark:text-stone-300">
                            @if($date === now()->format('Y-m-d'))
                                <i class="ph-fill ph-lightning"></i> Hari Ini
                            @elseif($date === now()->subDay()->format('Y-m-d'))
                                Kemarin
                            @else
                                {{ \Carbon\Carbon::parse($date)->translatedFormat('l, d F Y') }}
                            @endif
                        </span>
                    </div>
                    <div class="flex-1 h-px bg-stone-200 dark:bg-stone-800"></div>
                    <span class="text-[10px] sm:text-[11px] font-bold text-stone-400">{{ $dayActivities->count() }} aktivitas</span>
                </div>

                {{-- List per hari --}}
                <div class="bg-white dark:bg-stone-900 border border-stone-200 dark:border-stone-800 rounded-2xl sm:rounded-[24px] divide-y divide-stone-100 dark:divide-stone-800 overflow-hidden shadow-sm">
                    @foreach($dayActivities as $activity)
                    @php
                        $typeConfig = [
                            'note.created'     => ['emoji' => '<i class="ph-fill ph-pencil-simple"></i>', 'color' => 'bg-stone-100 dark:bg-neutral-950/30 text-neutral-900 dark:text-stone-500'],
                            'paper.published'  => ['emoji' => '<i class="ph-fill ph-file-text"></i>', 'color' => 'bg-emerald-50 dark:bg-emerald-900/30 text-emerald-600 dark:text-emerald-400'],
                            'paper.created'    => ['emoji' => '<i class="ph-fill ph-pen-nib"></i>', 'color' => 'bg-blue-50 dark:bg-blue-900/30 text-blue-600 dark:text-blue-400'],
                            'quiz.generated'   => ['emoji' => '<i class="ph-fill ph-flask"></i>', 'color' => 'bg-orange-50 dark:bg-orange-900/30 text-neutral-900 dark:text-orange-400'],
                            'material.uploaded'=> ['emoji' => '<i class="ph-fill ph-books"></i>', 'color' => 'bg-violet-50 dark:bg-violet-900/30 text-violet-600 dark:text-violet-400'],
                        ];
                        $config = $typeConfig[$activity->type] ?? ['emoji' => '<i class="ph-fill ph-lightning"></i>', 'color' => 'bg-stone-50 dark:bg-stone-800 text-stone-600 dark:text-stone-400'];
                        $typeLabels = ['note.created' => 'Coretan', 'paper.published' => 'Makalah', 'paper.created' => 'Makalah', 'quiz.generated' => 'Latihan', 'material.uploaded' => 'Materi'];
                        $typeLabel = $typeLabels[$activity->type] ?? ucfirst(str_replace('.', ' ', $activity->type));
                    @endphp
                    
                    <div class="p-4 sm:p-5 hover:bg-stone-50 dark:hover:bg-stone-800/50 flex flex-col sm:flex-row sm:items-center gap-3 sm:gap-4 transition-colors group">
                        
                        <div class="flex items-center gap-3 sm:gap-4 w-full">
                            {{-- Ikon --}}
                            <div class="w-10 h-10 sm:w-12 sm:h-12 rounded-xl sm:rounded-2xl {{ $config['color'] }} flex items-center justify-center shrink-0 text-lg sm:text-xl">
                                {!! $config['emoji'] !!}
                            </div>

                            {{-- Info --}}
                            <div class="flex-1 min-w-0">
                                <p class="text-sm sm:text-base font-medium text-stone-900 dark:text-white leading-snug truncate sm:whitespace-normal">
                                    {{ $activity->description }}
                                </p>
                                <div class="flex items-center gap-2 mt-1">
                                    <span class="text-[11px] sm:text-xs font-medium text-stone-500 dark:text-stone-400">
                                        {{ $activity->created_at->format('H:i') }} WIB
                                    </span>
                                    <span class="w-1 h-1 rounded-full bg-stone-300 dark:bg-stone-600"></span>
                                    <span class="text-[11px] sm:text-xs font-medium text-stone-500 dark:text-stone-400">
                                        {{ $typeLabel }}
                                    </span>
                                </div>
                            </div>
                            
                            {{-- Badge tipe (Muncul selalu di mobile, fade di desktop) --}}
                            <div class="shrink-0 hidden sm:block">
                                <span class="text-[10px] font-bold px-2.5 py-1.5 rounded-lg {{ $config['color'] }} uppercase tracking-widest opacity-0 group-hover:opacity-100 transition-all duration-200">
                                    {{ $activity->type }}
                                </span>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
            @empty
            <div class="py-20 flex flex-col items-center justify-center text-center border-2 border-dashed border-stone-200 dark:border-stone-800 rounded-3xl">
                <div class="w-16 h-16 bg-stone-100 dark:bg-stone-800 rounded-full flex items-center justify-center mb-4">
                    <svg class="w-8 h-8 text-stone-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
                <h3 class="text-lg font-bold text-stone-900 dark:text-white font-geist">Belum ada jejak.</h3>
                <p class="text-sm text-stone-500 dark:text-stone-400 mt-1 max-w-sm">
                    Mulai kerjakan sesuatu di RUANG dan aktivitasmu akan muncul di sini.
                </p>
            </div>
            @endforelse
        </div>

        {{-- Pagination --}}
        @if($activities->hasPages())
        <div class="flex justify-center pt-4">
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
