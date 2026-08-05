<x-app-layout>
    <x-slot name="pageTitle">Beranda</x-slot>
    <x-slot name="pageSubtitle">hari ini, {{ strtolower(now()->translatedFormat('l, d F Y')) }}. semoga sehat selalu.</x-slot>


    <div class="animate-fadeIn">

        {{-- ================= HEADER ================= --}}
    <div class="mb-10 relative z-0">
        {{-- No shapes or vinyl here anymore, moved to app layout --}}

        <div class="flex flex-col md:flex-row md:items-start md:justify-between relative z-10 md:gap-4">
            <div class="w-full md:max-w-2xl mt-4 md:mt-0">
                <h1 id="dynamicGreeting" class="text-2xl sm:text-3xl font-semibold tracking-tight text-[#1F1F1D] dark:text-white pr-20 md:pr-0">
                    {!! $greeting !!}
                </h1>
                <blockquote class="mt-4 border-l-2 border-[#D6D0C4] pl-4 dark:border-slate-700/50 pr-2 md:pr-0">
                    <p class="quote text-sm sm:text-base text-neutral-600 leading-relaxed dark:text-slate-300">
                        "{{ $quote['text'] }}"
                    </p>
                    <footer class="mt-1 text-xs sm:text-sm font-medium text-[#A8A296]">
                        — {{ $quote['author'] }}
                    </footer>
                </blockquote>
            </div>

            {{-- Flip Clock --}}
            <div class="absolute top-0 right-0 md:relative md:top-auto md:right-auto flex flex-col items-end justify-start scale-[0.6] sm:scale-75 md:scale-100 origin-top-right md:origin-right shrink-0 mt-0 md:-mt-10 lg:mt-0">
                <div x-data="{
                        hours: '{{ now()->format('H') }}',
                        minutes: '{{ now()->format('i') }}',
                        init() {
                            setInterval(() => {
                                let d = new Date();
                                this.hours = String(d.getHours()).padStart(2, '0');
                                this.minutes = String(d.getMinutes()).padStart(2, '0');
                            }, 1000);
                        }
                    }"
                    class="relative font-mono font-extrabold text-4xl grid grid-cols-2 text-center text-white shadow-lg gap-x-px border-4 border-yellow-100 rounded-lg">

                    <!-- clock stand -->
                    <div class="absolute inset-x-0 -bottom-2 mx-auto flex justify-center">
                        <div class="w-3/4 h-2 bg-yellow-100 rounded-b"></div>
                    </div>

                    <!-- left timer (hours) -->
                    <div class="relative py-3 px-3">
                        <div class="absolute inset-0 w-full h-full grid grid-rows-2 rounded-l-md overflow-hidden">
                            <div class="bg-gradient-to-br from-gray-800 to-gray-900"></div>
                            <div class="bg-gradient-to-br from-gray-700 to-gray-900"></div>
                        </div>
                        <div class="relative" x-text="hours">00</div>
                        <div class="absolute inset-0 w-full h-full flex items-center justify-center">
                            <div class="h-px w-full bg-gray-800"></div>
                        </div>
                    </div>

                    <!-- right timer (minutes) -->
                    <div class="relative py-3 px-3">
                        <div class="absolute inset-0 w-full h-full grid grid-rows-2 rounded-r-md overflow-hidden">
                            <div class="bg-gradient-to-br from-gray-800 to-gray-900"></div>
                            <div class="bg-gradient-to-br from-gray-700 to-gray-900"></div>
                        </div>
                        <div class="relative" x-text="minutes">00</div>
                        <div class="absolute inset-0 w-full h-full flex items-center justify-center">
                            <div class="h-px w-full bg-gray-800"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- QUICK ACTIONS --}}
        <div class="mt-10 mb-2">
            <div class="flex flex-col sm:flex-row sm:items-center gap-4 sm:gap-8 py-2">
                <h3 class="text-sm font-medium text-[#A8A296] tracking-widest uppercase">ngapain.?</h3>
                <div class="flex flex-wrap gap-3 sm:gap-4">
                    <a href="{{ route('makalah.index') }}" class="dashboard-card flex items-center gap-2 px-5 py-2.5 text-[#7C756C] hover:text-stone-600 font-medium active:scale-95 transition-transform dark:text-slate-400">
                        <i class="ph ph-pencil-simple-line text-lg"></i>
                        <span>nugas</span>
                    </a>
                    <a href="{{ route('coretan.index') }}" class="dashboard-card flex items-center gap-2 px-5 py-2.5 text-[#7C756C] hover:text-emerald-600 font-medium active:scale-95 transition-transform dark:text-slate-400">
                        <i class="ph ph-notebook text-lg"></i>
                        <span>nyatet</span>
                    </a>
                    <a href="{{ route('subjects.index') }}" class="dashboard-card flex items-center gap-2 px-5 py-2.5 text-[#7C756C] hover:text-rose-600 font-medium active:scale-95 transition-transform dark:text-slate-400">
                        <i class="ph ph-target text-lg"></i>
                        <span>latihan</span>
                    </a>
                </div>
            </div>
        </div>

        {{-- BENTO GRID --}}
        <div class="grid grid-cols-1 md:grid-cols-12 gap-6 lg:gap-8 mt-12">

            {{-- Row 1: Hero & Focus --}}
            <div class="col-span-1 md:col-span-8 row-span-2">
                {{-- Continue Writing --}}
        @if(isset($activeDoc) && $activeDoc)
            <a href="{{ route('makalah.edit', $activeDoc->id) }}" class="dashboard-card group block p-6 sm:p-8 h-full flex flex-col justify-between transition-all active:scale-[0.98]">
                <span class="text-sm text-[#7C756C] dark:text-slate-400">
                    lanjutin yg tadi.
                </span>
                <h2 class="mt-3 text-3xl sm:text-4xl lg:text-5xl font-bold tracking-tight text-[#1F1F1D] leading-tight dark:text-white">
                    {{ $activeDoc->judul }}
                </h2>

                <div class="mt-8 flex flex-wrap items-center gap-3 sm:gap-4 text-xs sm:text-sm text-[#7C756C] dark:text-slate-400">
                    <span>{{ number_format($wordCount) }} kata</span>
                    <span class="hidden sm:inline">&middot;</span>
                    <span>terakhir dibuka {{ $activeDoc->updated_at ? $activeDoc->updated_at->diffForHumans() : 'Baru Saja' }}</span>
                </div>

                <hr class="my-6 border-[#D6D0C4] dark:border-slate-700/50">

                <div class="flex items-center gap-2 font-medium text-[#1F1F1D] text-sm sm:text-base dark:text-white">
                    lanjutin lagi
                    <i class="ph ph-arrow-right text-xl transition-transform duration-300 group-hover:translate-x-1"></i>
                </div>
            </a>
        @else
            <div class="dashboard-card border-dashed border-stone-300 p-6 sm:p-8 h-full flex flex-col justify-center text-center dark:border-slate-700">
                <h2 class="text-xl sm:text-2xl font-semibold text-[#1F1F1D] dark:text-white">
                    belom ada yg dikerjain.
                </h2>
                <p class="mt-2 text-sm sm:text-base text-[#7C756C] dark:text-slate-400">
                    bikin makalah pertama dulu.
                </p>
                <div class="mt-6">
                    <a href="{{ route('makalah.create') }}" class="inline-flex rounded-xl bg-[#1F1F1D] px-6 min-h-11 items-center justify-center text-sm font-medium text-white transition hover:bg-[#34302C] active:scale-95">
                        mulai nulis
                    </a>
                </div>
            </div>
        @endif
            </div>

            <div class="col-span-1 md:col-span-4 row-span-2">
                {{-- Focus --}}

                <div x-data class="dashboard-card h-full flex flex-col items-center justify-center group overflow-hidden relative" :class="$store.pomodoro.isRunning ? 'is-running' : ''">
                    <p class="absolute top-6 left-6 text-sm text-[#7C756C] dark:text-slate-400">
                        fokus.
                    </p>
                    <div class="relative flex items-center justify-center py-10 sm:py-8">
                        {{-- Timer --}}
                        <div class="pomodoro-timer scale-90 sm:scale-100" :style="$store.pomodoro.isRunning ? 'transform: rotate(' + (-3 - ($store.pomodoro.timeLeft % 60) * 0.1) + 'deg)' : ''">
                            <div class="pomodoro-timer-inner">
                                <span class="pomodoro-time" x-text="$store.pomodoro.formattedTime">
                                    25:00
                                </span>
                                <p class="pomodoro-mode" x-text="$store.pomodoro.mode === 'focus' ? 'work.' : ($store.pomodoro.mode === 'break' ? 'break.' : 'idle.')">
                                    work.
                                </p>
                            </div>
                        </div>

                        {{-- Knob --}}
                        <div class="pomodoro-timer-knob" @click="$store.pomodoro.toggle()" :title="$store.pomodoro.isRunning ? 'Pause' : 'Mulai Fokus'">
                            <div class="pomodoro-knob-grip"></div>
                            <span class="absolute right-[-4px] text-[8px] tracking-widest uppercase font-medium text-[#A8A296] rotate-90 opacity-0 group-hover:opacity-100 transition"
                                  style="transform: rotate(90deg) translateY(-25px); transform-origin: left top;"
                                  x-text="$store.pomodoro.isRunning ? 'PAUSE' : 'PLAY'">
                            </span>
                        </div>
                    </div>

                    <button @click="$store.pomodoro.reset()" class="absolute bottom-6 text-[10px] uppercase tracking-widest text-[#A8A296] hover:text-stone-600 transition active:scale-95 p-2">
                        reset
                    </button>
                </div>
            </div>

            <div class="col-span-1 md:col-span-5 flex flex-col gap-6 lg:gap-8">
                {{-- Music --}}
                <div class="dashboard-card p-6 sm:p-8">
                   {{-- Premium Music Widget --}}
@if(isset($currentTrack) && $currentTrack)

<div class="flex justify-between items-center">
    <p class="text-sm text-[#7C756C] dark:text-slate-400">
        lagi muter.
    </p>

    <div class="flex items-center gap-3">
        <span
            class="lowercase text-[12px] tracking-[0.2em] text-[#141414] italic font-black"
            style="font-family:'Arial Black',sans-serif; transform:skewX(-6deg);">
            oasis
        </span>
        <div class="flex items-end gap-[2px] opacity-20">
            <span class="w-[2px] h-4 rounded-full bg-[#2D2A28]"></span>
            <span class="w-[2px] h-7 rounded-full bg-[#2D2A28]"></span>
            <span class="w-[2px] h-5 rounded-full bg-[#2D2A28]"></span>
        </div>
    </div>
</div>

{{-- RUANG static music widget --}}
<style>
    @keyframes ruang-vinyl-spin {
        0% {
            transform: translate(-50%, -50%) rotate(0deg);
        }
        100% {
            transform: translate(-50%, -50%) rotate(360deg);
        }
    }

    .ruang-vinyl {
        transition-property: left, width, height, margin, padding, opacity !important;
    }

    .ruang-music-widget.group:hover .ruang-vinyl {
        animation: ruang-vinyl-spin 4s linear infinite !important;
    }

    .ruang-lyrics p {
        min-height: 1.2em;
        white-space: nowrap;
        overflow: hidden;
    }

    .ruang-lyrics .caret::after {
        content: '|';
        margin-left: 2px;
        opacity: 1;
        animation: ruang-caret-blink 0.6s step-end infinite;
    }

    @keyframes ruang-caret-blink {
        from, to { opacity: 1; }
        50%      { opacity: 0; }
    }
</style>

<div class="ruang-music-widget group mt-6 flex flex-row items-start gap-4 pb-8 sm:pb-0 sm:gap-6" id="wonderwall-widget">
    <audio id="wonderwall-audio" src="{{ asset('audio/wonderwall.mp3') }}" preload="auto"></audio>
    {{-- Artwork --}}
    <div class="ruang-artwork relative z-20 h-40 w-40 shrink-0">
        {{-- Vinyl --}}
        <div
            class="ruang-vinyl absolute left-[68%] top-1/2 -z-10 h-[130px] w-[130px] -translate-x-1/2 -translate-y-1/2 rounded-full shadow-[0_22px_55px_rgba(0,0,0,.12)] transition-all duration-700 ease-out group-hover:left-[92%]"
            style="background: radial-gradient(circle at center, #e8ddc4 0 11%, #141414 12%), repeating-radial-gradient(circle, #151515 0, #151515 .8px, #202020 .8px, #202020 1.6px);"
        >
            <div class="absolute left-4 top-4 h-8 w-20 rounded-full bg-white/10 blur-xl"></div>

            <div class="absolute inset-0 flex items-center justify-center">
                <div class="flex h-8 w-8 flex-col items-center justify-center rounded-full bg-[#e8ddc4] leading-none shadow-inner sm:h-10 sm:w-10">
                    <span class="text-[4px] font-bold tracking-[0.18em] text-[#4F4A44] dark:text-slate-200">
                        OASIS
                    </span>
                    <span class="mt-[2px] text-[3px] text-[#7C756C] dark:text-slate-400">
                        Wonderwall
                    </span>
                    <span class="mt-[1px] text-[3px] text-[#7C756C] dark:text-slate-400">
                        1995
                    </span>
                    <span class="mt-[2px] h-1.5 w-1.5 rounded-full bg-[#1F1F1D]"></span>
                </div>
            </div>
        </div>

        {{-- Cover album --}}
        <img
            src="{{ asset('images/wonderwall.jpg') }}"
            alt="Wonderwall"
            class="ruang-cover relative z-20 h-full w-full rounded-[12px] object-cover shadow-[0_22px_55px_rgba(0,0,0,.12)] transition duration-700 group-hover:-translate-x-2 group-hover:-translate-y-1 sm:rounded-[16px]"
        />

        {{-- Lyrics typewriter --}}
        <div class="ruang-lyrics pointer-events-none absolute left-0 top-[calc(100%+12px)] z-30 w-[220px] font-mono text-[10px] text-[#7C756C] opacity-0 transition-opacity duration-300 group-hover:opacity-100 dark:text-[#A8A296] sm:w-[250px] sm:top-[calc(100%+40px)] sm:text-[10px]">
        </div>
    </div>

    {{-- Details --}}
    <div class="w-full flex-1 text-left">
        <div class="flex items-start justify-between">
            <div>
                <h3
                    class="text-[20px] font-normal leading-none text-[#1F1F1D] dark:text-white sm:text-[36px]"
                    style="font-family: 'Cormorant Garamond', serif;"
                >
                    Wonderwall
                </h3>

                <p class="mt-1 text-[14px] font-light text-[#7C756C] dark:text-slate-400 sm:mt-2 sm:text-[18px]">
                    Oasis
                </p>

                <p class="mt-0.5 text-[10px] tracking-[0.01em] text-[#A8A296] sm:mt-1 sm:text-[13px]">
                    (What's the Story) Morning Glory?
                    <span class="mx-1.5 hidden sm:inline">&bull;</span>
                    <br class="sm:hidden" />
                    1995
                </p>
            </div>
        </div>

        {{-- Player --}}
        <div class="mt-3 pb-2 sm:mt-5 sm:pb-4">
            <div class="mb-2 flex items-center justify-end">
                <button type="button" aria-label="Sukai lagu" class="text-xl text-red-500">
                    &hearts;
                </button>
            </div>

            <div class="relative">
                <div class="h-[2px] rounded-full bg-[#D6D0C4] dark:bg-slate-800"></div>
                <div class="ruang-progress absolute left-[41%] -top-[5px] h-3 w-3 rounded-full bg-[#1F1F1D] dark:bg-white transition-all duration-[46s] ease-linear"></div>
            </div>

            <div class="mt-3 flex items-center justify-between text-xs text-[#A8A296] sm:text-sm">
                <span>01:22</span>
                <span>02:08</span>
            </div>

            <div class="mt-4 flex items-center justify-center gap-6 text-[#A8A296]">
                <button type="button" aria-label="Lagu sebelumnya">
                    <i class="ph-fill ph-skip-back text-[15px]"></i>
                </button>

                <button type="button" id="wonderwall-play-btn" aria-label="Putar lagu" class="text-[#1F1F1D] dark:text-white transition-transform active:scale-90">
                    <i id="wonderwall-play-icon" class="ph-fill ph-play text-[20px]"></i>
                </button>

                <button type="button" aria-label="Lagu berikutnya">
                    <i class="ph-fill ph-skip-forward text-[15px]"></i>
                </button>
            </div>
        </div>
    </div>
</div>

<script>
document.querySelectorAll('.ruang-music-widget').forEach(function (widget) {
    if (widget.dataset.lyricsBound === '1') return;
    widget.dataset.lyricsBound = '1';

    // Player Logic
    const audio = widget.querySelector('#wonderwall-audio');
    const playBtn = widget.querySelector('#wonderwall-play-btn');
    const playIcon = widget.querySelector('#wonderwall-play-icon');
    const vinyl = widget.querySelector('.ruang-vinyl');
    const cover = widget.querySelector('.ruang-cover');
    const progress = widget.querySelector('.ruang-progress');
    const lyricsBox = widget.querySelector('.ruang-lyrics');
    
    // Lyrics Data and Timings (in seconds)
    const lyricsData = [
        { 
            time: 0.1, 
            delay: 80,
            lines: [
                "And all the roads we have to walk", 
                "are winding",
                "And all the lights that lead us there",
                "are blinding"
            ] 
        },
        { 
            time: 11.5, 
            delay: 80,
            lines: [
                "There are many things that I", 
                "would like to say to you",
                "But I don't know how"
            ] 
        },
        { 
            time: 21.5, 
            delay: 120,
            lines: [
                "Because maybeeee", 
                "You're gonna be the one that saves me",
                "And after all",
                "You're my wonderwall"
            ] 
        }
    ];

    let typeRunId = 0;
    let activeBlock = -1;

    function typeLine(el, myRun, delay) {
        return new Promise(function (resolve) {
            const text = el.dataset.text;
            let i = 0;
            el.classList.add('caret');

            (function step() {
                if (myRun !== typeRunId) return;
                el.textContent = text.slice(0, i);
                i++;
                if (i <= text.length) {
                    setTimeout(step, delay);
                } else {
                    el.classList.remove('caret');
                    resolve();
                }
            })();
        });
    }

    function renderLyricsBlock(blockIndex, overrideDelay = null) {
        typeRunId++;
        const myRun = typeRunId;
        lyricsBox.innerHTML = '';
        const block = lyricsData[blockIndex];
        const lines = block.lines;
        const delay = overrideDelay !== null ? overrideDelay : (block.delay || 80);
        
        const els = lines.map((text, i) => {
            const p = document.createElement('p');
            p.className = 'ruang-line' + (i > 0 ? ' mt-0.5' : '');
            p.dataset.text = text;
            lyricsBox.appendChild(p);
            return p;
        });
        
        (async function() {
            for (let idx = 0; idx < els.length; idx++) {
                if (myRun !== typeRunId) return;
                await typeLine(els[idx], myRun, delay);
                if (myRun !== typeRunId) return;
                if (idx === els.length - 1) {
                    els[idx].classList.add('caret');
                }
                await new Promise(r => setTimeout(r, 200));
            }
        })();
    }

    if (playBtn && audio) {
        let isPlaying = false;
        
        const stopPlaying = () => {
            isPlaying = false;
            audio.pause();
            audio.currentTime = 0;
            playIcon.classList.replace('ph-pause', 'ph-play');
            
            // Revert animations
            vinyl.classList.remove('!animation-[ruang-vinyl-spin_4s_linear_infinite]', 'left-[92%]');
            cover.classList.remove('-translate-x-2', '-translate-y-1');
            progress.classList.remove('left-[100%]');
            lyricsBox.classList.remove('opacity-100');
            
            // Reset lyrics state
            activeBlock = -1;
            lyricsBox.innerHTML = '';
        };

        playBtn.addEventListener('click', (e) => {
            e.preventDefault();
            if (isPlaying) {
                stopPlaying();
            } else {
                audio.play().then(() => {
                    isPlaying = true;
                    playIcon.classList.replace('ph-play', 'ph-pause');
                    
                    // Trigger animations
                    vinyl.classList.add('!animation-[ruang-vinyl-spin_4s_linear_infinite]', 'left-[92%]');
                    cover.classList.add('-translate-x-2', '-translate-y-1');
                    progress.classList.add('left-[100%]');
                    lyricsBox.classList.add('opacity-100');
                }).catch(err => alert('Gagal memutar lagu: ' + err.message));
            }
        });
        
        audio.addEventListener('timeupdate', () => {
            if (isPlaying) {
                let newBlock = -1;
                for (let i = lyricsData.length - 1; i >= 0; i--) {
                    if (audio.currentTime >= lyricsData[i].time) {
                        newBlock = i;
                        break;
                    }
                }
                
                if (newBlock !== activeBlock && newBlock !== -1) {
                    activeBlock = newBlock;
                    renderLyricsBlock(newBlock);
                }
            }
        });

        audio.addEventListener('ended', stopPlaying);
    }
    
    // Hover logic when not playing
    widget.addEventListener('mouseenter', () => {
        if (audio && playBtn) {
            // Check if playing property is false (meaning not currently triggered by play button)
            if (playIcon.classList.contains('ph-play')) {
                // Selalu tampilkan lirik reff (block 2) dengan kecepatan khusus 60ms saat di-hover tanpa di-play
                renderLyricsBlock(2, 60);
            }
        }
    });

    widget.addEventListener('mouseleave', () => {
        if (audio && playBtn) {
            if (playIcon.classList.contains('ph-play')) {
                typeRunId++; // stop any ongoing typing
                lyricsBox.innerHTML = '';
                activeBlock = -1;
            }
        }
    });
});
</script>

@else

<div class="rounded-2xl bg-[#EFECE5] p-8 sm:p-10 text-center dark:bg-slate-900/80">

    <div class="text-4xl sm:text-5xl"><i class="ph ph-music-note text-[1.1em] align-middle"></i></div>

    <h3 class="mt-4 sm:mt-5 font-semibold text-[#1F1F1D] dark:text-white">
        Belum ada lagu.
    </h3>

    <p class="mt-2 text-sm text-[#7C756C] dark:text-slate-400">
        Putar sesuatu dulu.
    </p>

</div>

@endif

                </div>

                {{-- Kalender --}}
                <div class="dashboard-card p-6 sm:p-8" x-data="calendarManager()">

                    @php
                        // use Carbon\Carbon; // Ensure no redeclaration if used elsewhere

                        $today = \Carbon\Carbon::now();

                        $startOfMonth = $today->copy()->startOfMonth();
                        $daysInMonth = $today->daysInMonth;

                        // Senin = 0
                        $offset = $startOfMonth->dayOfWeekIso - 1;
                    @endphp

                    <div class="flex items-center justify-between mb-6">
                        <p class="text-sm text-[#7C756C] dark:text-slate-400">
                            kalender.
                        </p>

                        <h4 class="text-sm text-[#4F4A44] font-medium dark:text-slate-200">
                            {{ $today->translatedFormat('F Y') }}
                        </h4>
                    </div>

                    {{-- Nama hari --}}
                    <div class="grid grid-cols-7 gap-1 sm:gap-2 mb-2">

                        @foreach(['Mon','Tue','Wed','Thu','Fri','Sat','Sun'] as $day)

                            <div class="text-center text-[11px] sm:text-xs uppercase tracking-widest sm:tracking-[.28em] font-medium {{ $day === 'Sun' ? 'text-red-500 dark:text-red-400' : 'text-[#A8A296]' }}">
                                {{ $day }}
                            </div>

                        @endforeach

                    </div>

                    {{-- Grid --}}
                    <div class="grid grid-cols-7 gap-1 sm:gap-2">

                        {{-- Kosong --}}
                        @for($i=0;$i<$offset;$i++)
                            <div></div>
                        @endfor

                        {{-- Semua tanggal --}}
                        @for($day=1;$day<=$daysInMonth;$day++)

                            @php
                                $isToday = $day == $today->day;
                                $agendasForDay = $agendas->get($day) ?? collect();
                                $agenda = $agendasForDay->first();
                                $deadline = $deadlinesByDay->get($day)?->first();

                                $dateStr = $today->copy()->setDay($day)->format('Y-m-d');
                                $isSunday = ($offset + $day - 1) % 7 == 6;

                                $hasAgenda = $agendasForDay->isNotEmpty();
                                $agendasData = $agendasForDay->map(function($a) {
                                    return ['id' => $a->id, 'title' => $a->title];
                                })->toJson();
                                $base64Agendas = base64_encode($agendasData);
                                
                                $textColor = $isSunday ? 'text-red-500 dark:text-red-400 font-bold' : 'text-[#1F1F1D] dark:text-white';
                                if ($hasAgenda) {
                                    $textColor = 'text-white font-bold';
                                }
                            @endphp

                            <div
                                @click="openModal('{{ $dateStr }}', '{{ $base64Agendas }}')"
                                class="
                                    group
                                    relative
                                    aspect-square sm:aspect-[0.92]
                                    rounded-lg sm:rounded-[18px]
                                    p-1.5 sm:p-3
                                    transition-all
                                    duration-300
                                    cursor-pointer
                                    hover:-translate-y-[2px]
                                    hover:shadow-[0_1px_2px_rgba(0,0,0,.02)]
                                    hover:scale-[1.02]
                                    active:scale-95

                                    {{ $isToday
                                        ? 'border-2 border-[#1F1F1D] bg-white shadow-sm'
                                        : 'border border-[#ececec] bg-white hover:border-[#A8A296]'
                                    }}
                                 dark:bg-slate-900 flex flex-col justify-center sm:justify-start items-center sm:items-start"
                            >

                                <span
                                    class="text-[16px] sm:text-[22px] leading-none flex items-center justify-center {{ $hasAgenda ? 'w-7 h-7 sm:w-9 sm:h-9 rounded-full bg-stone-800 shadow-sm' : '' }} {{ $textColor }}"
                                    style="font-family:'Cormorant Garamond', serif;">
                                    {{ $day }}
                                </span>

                                <div class="hidden sm:block absolute bottom-4 left-3 right-2 overflow-hidden">
                                    @if($deadline)
                                        <p class="text-[9px] font-semibold text-red-500 line-clamp-1 mb-0.5" title="{{ $deadline->title }}">
                                            • {{ $deadline->title }}
                                        </p>
                                    @endif

                                    @if($agenda)
                                        <p class="text-[9px] text-[#7C756C] line-clamp-1 dark:text-slate-400" title="{{ $agenda->title }}">
                                            • {{ $agenda->title }}
                                        </p>
                                    @endif
                                </div>

                                {{-- Indikator titik untuk versi mobile --}}
                                <div class="flex sm:hidden absolute bottom-1.5 left-1/2 -translate-x-1/2 gap-0.5">
                                    @if($deadline)
                                        <span class="h-1.5 w-1.5 rounded-full bg-red-500"></span>
                                    @endif
                                    @if($agenda)
                                        <span class="h-1.5 w-1.5 rounded-full bg-[#1F1F1D]"></span>
                                    @endif
                                </div>

                                @if($isToday)
                                    <span class="absolute bottom-1.5 sm:bottom-3 left-1/2 sm:left-3 -translate-x-1/2 sm:translate-x-0 h-[2px] w-4 sm:w-7 rounded-full bg-[#1F1F1D]"></span>
                                @elseif(!$agenda && !$deadline)
                                    <span class="absolute bottom-1.5 sm:bottom-3 left-1/2 sm:left-3 -translate-x-1/2 sm:translate-x-0 h-[2px] w-3 sm:w-5 rounded-full bg-neutral-200 transition group-hover:bg-[#A8A296]"></span>
                                @endif

                            </div>

                        @endfor

                    </div>

                    <!-- Modal Tambah Agenda -->
                    <template x-teleport="body">
                        <div x-show="isModalOpen"
                             style="display: none;"
                             class="fixed inset-0 z-[100] flex items-end sm:items-center justify-center p-0 sm:p-6">

                            <div x-show="isModalOpen"
                                 x-transition.opacity
                                 @click="closeModal()"
                                 class="absolute inset-0 bg-[#1F1F1D]/40 backdrop-blur-sm"></div>

                            <div x-show="isModalOpen"
                                 x-transition:enter="transition ease-out duration-300"
                                 x-transition:enter-start="transform translate-y-full sm:translate-y-0 sm:scale-95 opacity-0"
                                 x-transition:enter-end="transform translate-y-0 sm:scale-100 opacity-100"
                                 x-transition:leave="transition ease-in duration-200"
                                 x-transition:leave-start="transform translate-y-0 sm:scale-100 opacity-100"
                                 x-transition:leave-end="transform translate-y-full sm:translate-y-0 sm:scale-95 opacity-0"
                                 class="relative w-full max-w-md bg-white rounded-t-[24px] sm:rounded-[32px] mt-auto sm:mt-0 shadow-2xl p-6 sm:p-8 z-10 dark:bg-slate-800">

                                <h3 class="text-xl sm:text-2xl font-bold text-[#1F1F1D] mb-6 dark:text-white">Agenda</h3>

                                <!-- Existing Agendas -->
                                <template x-if="dayAgendas.length > 0">
                                    <div class="mb-6 space-y-2">
                                        <template x-for="agenda in dayAgendas" :key="agenda.id">
                                            <div class="px-4 py-3 bg-[#F7F5F1] dark:bg-slate-900/50 border border-[#D6D0C4] dark:border-slate-700/50 rounded-xl flex justify-between items-center group">
                                                <span class="text-sm font-medium text-[#1F1F1D] dark:text-white" x-text="agenda.title"></span>
                                                <form :action="'/agendas/' + agenda.id" method="POST" class="inline m-0">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="p-1.5 text-[#A8A296] hover:text-red-500 transition opacity-0 group-hover:opacity-100" title="Hapus Agenda">
                                                        <i class="ph ph-trash text-lg"></i>
                                                    </button>
                                                </form>
                                            </div>
                                        </template>
                                    </div>
                                </template>

                                <form action="{{ route('agendas.store') }}" method="POST">
                                    @csrf
                                    <input type="hidden" name="date" :value="selectedDate">

                                    <div class="space-y-4">
                                        <div>
                                            <label class="block text-sm font-medium text-[#7C756C] mb-2 dark:text-slate-400">Tambah Agenda Baru</label>
                                            <input type="text" name="title" required placeholder="Ngapain hari ini?"
                                                class="w-full px-4 sm:px-5 py-3 sm:py-3.5 bg-white border-2 border-[#D6D0C4] rounded-xl sm:rounded-2xl text-[#1F1F1D] placeholder-[#A8A296] focus:border-stone-800 focus:ring-0 transition-colors text-sm sm:text-base dark:bg-slate-900 dark:border-slate-700 dark:text-white">
                                        </div>
                                    </div>

                                    <div class="mt-8 flex gap-3">
                                        <button type="button" @click="closeModal()"
                                            class="flex-1 px-4 sm:px-5 py-3 sm:py-3.5 rounded-xl sm:rounded-2xl border-2 border-[#D6D0C4] text-neutral-600 font-medium hover:border-stone-300 hover:bg-[#F7F5F1] transition active:scale-[0.98] text-sm sm:text-base dark:border-slate-700 dark:text-slate-300">
                                            batal.
                                        </button>
                                        <button type="submit"
                                            class="flex-1 px-4 sm:px-5 py-3 sm:py-3.5 rounded-xl sm:rounded-2xl bg-[#1F1F1D] text-white font-medium hover:bg-[#34302C] transition active:scale-[0.98] text-sm sm:text-base">
                                            tambah.
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </template>

                </div>
            </div>

            <div class="col-span-1 md:col-span-4 row-span-2">
                {{-- Activity --}}
            <div class="dashboard-card p-6 sm:p-8 h-full">
                <p class="text-sm text-[#7C756C] dark:text-slate-400">
                    aktivitas.
                </p>
                <div class="mt-8 space-y-5 sm:space-y-6">
                    @forelse($activities as $activity)
                        <div class="flex justify-between items-center group cursor-pointer">
                            <div class="flex gap-4">
                                <div class="mt-1 h-10 w-10 shrink-0 rounded-full bg-[#EFECE5] flex items-center justify-center text-neutral-600 transition-transform group-hover:scale-110 group-hover:bg-[#D6D0C4] dark:bg-slate-900/80 dark:text-slate-300">
                                    <i class="{{ $activity['icon'] }} text-lg"></i>
                                </div>
                                <div>
                                    <h4 class="font-medium text-sm sm:text-base text-[#1F1F1D] group-hover:text-stone-600 transition-colors dark:text-white">
                                        {{ $activity['title'] }}
                                    </h4>
                                    <p class="text-xs sm:text-sm text-[#7C756C] mt-0.5 sm:mt-1 dark:text-slate-400">
                                        {{ $activity['time'] }}
                                    </p>
                                </div>
                            </div>
                            <i class="ph ph-caret-right opacity-0 group-hover:opacity-100 transition-opacity text-[#A8A296]"></i>
                        </div>
                    @empty
                        <div class="text-center py-6">
                            <i class="ph-thin ph-wind text-4xl text-neutral-300 dark:text-slate-600 mb-2"></i>
                            <h3 class="text-lg font-semibold text-[#1F1F1D] dark:text-white">
                                belom ada apa apa.
                            </h3>
                        </div>
                    @endforelse
                </div>
            </div>
            </div>

            <div class="col-span-1 md:col-span-3 flex flex-col gap-6 lg:gap-8">
                {{-- Deadline --}}
                <div class="dashboard-card p-6 sm:p-8" x-data="deadlineManager()">
                    <div class="flex justify-between items-center mb-6">
                        <p class="text-sm text-[#7C756C] dark:text-slate-400">
                            deadline.
                        </p>
                        <button @click="openCreateModal()" class="text-[#A8A296] hover:text-stone-600 transition active:scale-90 p-1" title="Tambah Deadline">
                            <i class="ph ph-plus text-xl"></i>
                        </button>
                    </div>

                    @forelse($deadlines as $task)
                        <div class="mt-2 group relative">
                            <div class="flex justify-between items-center">
                                <span class="text-[10px] sm:text-xs uppercase tracking-wider text-[#A8A296]">
                                    {{ $task->due_date->diffForHumans() }}
                                </span>
                                <div class="flex items-center gap-1 sm:gap-2 opacity-100 sm:opacity-0 sm:group-hover:opacity-100 transition-opacity">
                                    <button @click="openEditModal({{ $task->id }}, '{{ addslashes($task->title) }}', '{{ $task->due_date->format('Y-m-d\TH:i') }}')" class="p-1 text-[#A8A296] hover:text-stone-600 transition">
                                        <i class="ph ph-pencil-simple text-sm sm:text-base"></i>
                                    </button>
                                    <form action="{{ route('deadlines.destroy', $task->id) }}" method="POST" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="p-1 text-[#A8A296] hover:text-red-500 transition" onclick="return confirm('Hapus deadline ini?')">
                                            <i class="ph ph-trash text-sm sm:text-base"></i>
                                        </button>
                                    </form>
                                    <span class="text-red-500 ml-1 sm:ml-2">
                                        <i class="ph ph-clock-countdown"></i>
                                    </span>
                                </div>
                            </div>
                            <h3 class="mt-1 text-lg sm:text-xl font-semibold text-[#1F1F1D] leading-tight dark:text-white">
                                {{ $task->title }}
                            </h3>
                        </div>

                        @unless($loop->last)
                            <hr class="my-4 border-[#D6D0C4] dark:border-slate-700/50">
                        @endunless
                    @empty
                        <div class="mt-6 sm:mt-8 text-center sm:text-left">
                            <i class="ph-thin ph-coffee text-4xl text-neutral-300 dark:text-slate-600 mb-2"></i>
                            <h3 class="text-lg sm:text-xl font-semibold text-[#1F1F1D] dark:text-white">
                                aman.
                            </h3>
                            <p class="mt-2 text-sm sm:text-base text-[#7C756C] dark:text-slate-400">
                                belom ada deadline.
                            </p>
                        </div>
                    @endforelse

                    <!-- Modal Deadline -->
                    <template x-teleport="body">
                        <div x-show="isOpen"
                             style="display: none;"
                             class="fixed inset-0 z-[100] flex items-end sm:items-center justify-center p-0 sm:p-6"
                             x-transition.opacity>

                            <div class="absolute inset-0 bg-[#1F1F1D]/40 backdrop-blur-sm" @click="isOpen = false"></div>

                            <div x-show="isOpen"
                                 x-transition:enter="transition ease-out duration-300"
                                 x-transition:enter-start="transform translate-y-full sm:translate-y-0 sm:scale-95 opacity-0"
                                 x-transition:enter-end="transform translate-y-0 sm:scale-100 opacity-100"
                                 x-transition:leave="transition ease-in duration-200"
                                 x-transition:leave-start="transform translate-y-0 sm:scale-100 opacity-100"
                                 x-transition:leave-end="transform translate-y-full sm:translate-y-0 sm:scale-95 opacity-0"
                                 class="bg-white rounded-t-[24px] sm:rounded-[32px] mt-auto sm:mt-0 w-full max-w-md p-6 sm:p-8 shadow-2xl relative dark:bg-slate-900">
                                <h3 class="text-xl sm:text-2xl font-bold text-[#1F1F1D] mb-6 dark:text-white" x-text="isEdit ? 'Edit Deadline' : 'Tambah Deadline'"></h3>

                                <form :action="formAction" method="POST">
                                    @csrf
                                    <template x-if="isEdit">
                                        <input type="hidden" name="_method" value="PUT">
                                    </template>

                                    <div class="mb-5">
                                        <label class="block text-sm font-medium text-[#7C756C] mb-2 dark:text-slate-400">Judul Tugas</label>
                                        <input type="text" name="title" x-model="form.title" class="w-full rounded-xl border-[#D6D0C4] bg-[#F7F5F1] px-4 min-h-11 text-sm text-[#1F1F1D] focus:border-neutral-800 focus:ring-stone-800 dark:border-slate-700/50 dark:bg-slate-900/50 dark:text-white" required>
                                    </div>

                                    <div class="mb-8">
                                        <label class="block text-sm font-medium text-[#7C756C] mb-2 dark:text-slate-400">Tenggat Waktu (Deadline)</label>
                                        <input type="datetime-local" name="due_date" x-model="form.due_date" class="w-full rounded-xl border-[#D6D0C4] bg-[#F7F5F1] px-4 min-h-11 text-sm text-[#1F1F1D] focus:border-neutral-800 focus:ring-stone-800 dark:border-slate-700/50 dark:bg-slate-900/50 dark:text-white" required>
                                    </div>

                                    <div class="flex justify-end gap-3">
                                        <button type="button" @click="isOpen = false" class="px-5 min-h-11 rounded-xl font-medium text-sm text-[#7C756C] hover:bg-[#EFECE5] transition active:scale-95 dark:text-slate-400">
                                            Batal
                                        </button>
                                        <button type="submit" class="px-5 min-h-11 rounded-xl font-medium text-sm bg-[#1F1F1D] text-white hover:bg-[#34302C] transition active:scale-95">
                                            Simpan
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </template>
                </div>

                {{-- Target --}}
                <div class="dashboard-card p-6 sm:p-8">
                    <p class="text-sm text-[#7C756C] dark:text-slate-400">
                        target.
                    </p>
                    <div class="mt-6">
                        <h2 class="text-3xl sm:text-4xl font-bold text-[#1F1F1D] dark:text-white">
                            {{ number_format($todayWords) }}
                            <span class="text-base sm:text-lg font-normal text-[#A8A296]">
                                / 1000
                            </span>
                        </h2>
                        @php
                            $percentage = min(100, ($todayWords / 1000) * 100);
                        @endphp
                        <div class="mt-5 h-2 rounded-full bg-[#D6D0C4] overflow-hidden dark:bg-slate-800">
                            <div class="h-full rounded-full bg-[#1F1F1D] transition-all duration-1000 ease-out" style="width:{{ $percentage }}%"></div>
                        </div>
                        <p class="mt-3 text-xs sm:text-sm text-[#7C756C] dark:text-slate-400">
                            {{ round($percentage) }}% selesai
                        </p>
                    </div>
                </div>
            </div>

        </div>

        </div>

        <footer class="mt-12 text-center pb-8">
            <p class="text-[13px] text-[#A8A296]/80">
                {{ $footers[array_rand($footers)] }}
            </p>
        </footer>

    </div>

    @push('scripts')
    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('calendarManager', () => ({
                isModalOpen: false,
                selectedDate: '',
                dayAgendas: [],
                openModal(dateStr, base64Agendas = '') {
                    this.selectedDate = dateStr;
                    try {
                        this.dayAgendas = base64Agendas ? JSON.parse(atob(base64Agendas)) : [];
                    } catch(e) {
                        this.dayAgendas = [];
                    }
                    this.isModalOpen = true;
                },
                closeModal() {
                    this.isModalOpen = false;
                }
            }));

            Alpine.data('deadlineManager', () => ({
                isOpen: false,
                isEdit: false,
                formAction: '',
                form: {
                    title: '',
                    due_date: ''
                },
                openCreateModal() {
                    this.isEdit = false;
                    this.formAction = '{{ route("deadlines.store") }}';
                    this.form.title = '';
                    this.form.due_date = '';
                    this.isOpen = true;
                },
                openEditModal(id, title, dueDate) {
                    this.isEdit = true;
                    this.formAction = '/deadlines/' + id;
                    this.form.title = title;
                    this.form.due_date = dueDate;
                    this.isOpen = true;
                }
            }));
        });
    </script>
    @endpush
</x-app-layout>