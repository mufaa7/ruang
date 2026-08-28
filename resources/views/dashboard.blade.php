<x-app-layout>
    <x-slot name="pageTitle">Beranda</x-slot>
    <x-slot name="pageSubtitle">hari ini, {{ strtolower(now()->translatedFormat('l, d F Y')) }}. semoga sehat selalu.</x-slot>


    <div class="animate-fadeIn">

        {{-- ================= HEADER ================= --}}
    <div class="mb-10 relative z-0">
        {{-- No shapes or vinyl here anymore, moved to app layout --}}

        <div class="flex flex-col md:flex-row md:items-start md:justify-between relative z-10 md:gap-4">
            <div class="w-full md:max-w-2xl mt-4 md:mt-0">
                <h1 id="dynamicGreeting" class="text-2xl sm:text-3xl font-bold tracking-tight text-white font-geist pr-20 md:pr-0">
                    {!! $greeting !!}
                </h1>
                <blockquote class="mt-4 border-l-2 border-white/15 pl-4 pr-2 md:pr-0">
                    <p class="quote text-base sm:text-lg text-slate-200 leading-relaxed font-serif">
                        "{{ $quote['text'] }}"
                    </p>
                    <footer class="mt-2 flex items-center gap-1.5 text-xs text-slate-400 select-none tracking-widest font-mono uppercase">
                        <span class="text-amber-400/80 font-bold">/</span>
                        <span class="text-slate-300 font-semibold">{{ $quote['author'] }}</span>
                    </footer>
                </blockquote>
            </div>

            {{-- Flip Clock (Clean Floating Matte) --}}
            <div class="absolute -top-8 right-0 md:relative md:top-auto md:right-auto flex flex-col items-center justify-start scale-[0.6] sm:scale-75 md:scale-100 origin-top-right md:origin-center shrink-0 -mt-2 lg:-mt-6 lg:-mr-4">
                
                {{-- The Clock Box --}}
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
                    class="relative font-mono font-bold text-3xl sm:text-4xl flex items-center gap-2 text-center text-white px-3.5 py-2.5 bg-black/40 backdrop-blur-md border border-white/10 rounded-2xl shadow-lg">
                    
                    <!-- left timer (hours) -->
                    <div class="relative w-12 sm:w-14 h-14 sm:h-16 bg-white/[0.04] rounded-xl flex items-center justify-center border border-white/10 overflow-hidden">
                        <div class="absolute inset-x-0 top-1/2 h-px bg-black/60 z-10"></div>
                        <span class="relative z-0 text-white font-mono tabular-nums" x-text="hours">00</span>
                    </div>

                    <!-- blinker -->
                    <div class="flex flex-col gap-1.5 opacity-40">
                        <span class="w-1 h-1 rounded-full bg-white"></span>
                        <span class="w-1 h-1 rounded-full bg-white"></span>
                    </div>

                    <!-- right timer (minutes) -->
                    <div class="relative w-12 sm:w-14 h-14 sm:h-16 bg-white/[0.04] rounded-xl flex items-center justify-center border border-white/10 overflow-hidden">
                        <div class="absolute inset-x-0 top-1/2 h-px bg-black/60 z-10"></div>
                        <span class="relative z-0 text-white font-mono tabular-nums" x-text="minutes">00</span>
                    </div>
                </div>
            </div>
        </div>

        {{-- QUICK ACTIONS --}}
        <div class="mt-8 mb-2">
            <div class="flex flex-col sm:flex-row sm:items-center gap-4 sm:gap-8 py-2">
                <h3 class="text-xs font-semibold text-slate-400 tracking-widest uppercase">ngapain.?</h3>
                <div class="flex flex-wrap gap-2.5 sm:gap-3">
                    <a href="{{ route('makalah.index') }}" class="dashboard-card flex items-center gap-2 px-4 py-2 text-slate-300 hover:text-white text-sm font-medium transition-colors">
                        <i class="ph ph-pencil-simple-line text-base text-amber-300"></i>
                        <span>nugas</span>
                    </a>
                    <a href="{{ route('coretan.index') }}" class="dashboard-card flex items-center gap-2 px-4 py-2 text-slate-300 hover:text-white text-sm font-medium transition-colors">
                        <i class="ph ph-notebook text-base text-amber-300"></i>
                        <span>nyatet</span>
                    </a>
                    <a href="{{ route('subjects.index') }}" class="dashboard-card flex items-center gap-2 px-4 py-2 text-slate-300 hover:text-white text-sm font-medium transition-colors">
                        <i class="ph ph-target text-base text-amber-300"></i>
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
            <div class="dashboard-card p-6 sm:p-8 h-full flex flex-col justify-between">
                <div>
                    <span class="text-sm text-amber-300/80 font-medium">
                        lanjutin yg tadi.
                    </span>
                    <h2 class="mt-3 text-3xl sm:text-4xl lg:text-5xl font-bold tracking-tight text-white leading-tight select-text">
                        {{ $activeDoc->judul }}
                    </h2>

                    <div class="mt-8 flex flex-wrap items-center gap-3 sm:gap-4 text-xs sm:text-sm text-slate-400">
                        <span>{{ number_format($wordCount) }} kata</span>
                        <span class="hidden sm:inline">&middot;</span>
                        <span>terakhir dibuka {{ $activeDoc->updated_at ? $activeDoc->updated_at->diffForHumans() : 'Baru Saja' }}</span>
                    </div>
                </div>

                <div>
                    <hr class="my-6 border-white/10">

                    <a href="{{ route('makalah.edit', $activeDoc->id) }}" class="group inline-flex items-center gap-2 font-medium text-amber-300 hover:text-white text-sm sm:text-base transition-colors w-fit">
                        <span>lanjutin lagi</span>
                        <i class="ph ph-arrow-right text-xl transition-transform duration-300 group-hover:translate-x-1.5 text-amber-300 group-hover:text-white"></i>
                    </a>
                </div>
            </div>
        @else
            <div class="dashboard-card border-dashed border-white/15 p-6 sm:p-8 h-full flex flex-col justify-center text-center">
                <h2 class="text-xl sm:text-2xl font-bold text-white">
                    belom ada yg dikerjain.
                </h2>
                <p class="mt-2 text-sm text-slate-400">
                    bikin makalah pertama dulu.
                </p>
                <div class="mt-6">
                    <a href="{{ route('makalah.create') }}" class="inline-flex rounded-xl bg-white text-slate-950 font-semibold px-5 min-h-10 items-center justify-center text-sm transition hover:bg-slate-200 active:scale-95 shadow-sm">
                        mulai nulis
                    </a>
                </div>
            </div>
        @endif
            </div>

            <div class="col-span-1 md:col-span-4 row-span-2">
                {{-- Focus --}}

                <div x-data class="dashboard-card h-full flex flex-col items-center justify-center group overflow-hidden relative" :class="$store.pomodoro.isRunning ? 'is-running' : ''">
                    <p class="absolute top-6 left-6 text-sm text-amber-300/80">
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
                            <span class="absolute right-[-4px] text-[8px] tracking-widest uppercase font-medium text-slate-300 rotate-90 opacity-0 group-hover:opacity-100 transition"
                                  style="transform: rotate(90deg) translateY(-25px); transform-origin: left top;"
                                  x-text="$store.pomodoro.isRunning ? 'PAUSE' : 'PLAY'">
                            </span>
                        </div>
                    </div>

                    <button @click="$store.pomodoro.reset()" class="absolute bottom-6 text-[10px] uppercase tracking-widest text-slate-300 hover:text-white transition active:scale-95 p-2">
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
    <p class="text-sm text-amber-300/80">
        lagi muter.
    </p>

    <div class="flex items-center gap-3">
        <span
            class="lowercase text-[12px] tracking-[0.2em] text-white italic font-black"
            style="font-family:'Arial Black',sans-serif; transform:skewX(-6deg);">
            oasis
        </span>
        <div class="flex items-end gap-[2px] opacity-20">
            <span class="w-[2px] h-4 rounded-full bg-white/20"></span>
            <span class="w-[2px] h-7 rounded-full bg-white/20"></span>
            <span class="w-[2px] h-5 rounded-full bg-white/20"></span>
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
        transition: left 0.7s cubic-bezier(0.16, 1, 0.3, 1), transform 0.7s cubic-bezier(0.16, 1, 0.3, 1), opacity 0.3s ease !important;
    }

    /* Geser keluar & putar saat lagu sedang aktif dimainkan (Berlaku di Desktop & HP) */
    .ruang-vinyl.is-playing {
        left: 92% !important;
        animation: ruang-vinyl-spin 2s linear infinite !important;
    }

    /* Geser keluar & putar saat di-hover HANYA jika perangkat menggunakan mouse/pointer desktop (Mencegah sticky hover di HP) */
    @media (hover: hover) and (pointer: fine) {
        .ruang-music-widget.group:hover .ruang-vinyl {
            left: 92% !important;
            animation: ruang-vinyl-spin 2s linear infinite !important;
        }
        .ruang-music-widget.group:hover .ruang-cover {
            transform: translate(-6px, -2px) !important;
        }
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

<div class="ruang-music-widget group mt-5 flex flex-col sm:flex-row sm:items-start gap-4 sm:gap-6 pb-2" id="wonderwall-widget">
    <audio id="wonderwall-audio" src="{{ asset('audio/wonderwall.mp3') }}" preload="auto"></audio>
    
    {{-- Top / Left Row: Artwork & Track Info on Mobile --}}
    <div class="flex items-center sm:items-start gap-4 w-full sm:w-auto">
        {{-- Artwork --}}
        <div class="ruang-artwork relative z-20 h-24 w-24 sm:h-36 sm:w-36 shrink-0">
            {{-- Vinyl --}}
            <img
                src="{{ asset('images/oasis.png') }}"
                alt="Vinyl"
                class="ruang-vinyl absolute left-[70%] sm:left-[74%] top-1/2 z-10 h-[86px] w-[86px] sm:h-[125px] sm:w-[125px] -translate-x-1/2 -translate-y-1/2 rounded-full shadow-[0_15px_35px_rgba(0,0,0,.4)] object-cover pointer-events-none"
            />

            {{-- Cover album --}}
            <img
                src="{{ asset('images/wonderwall.jpg') }}"
                alt="Wonderwall"
                class="ruang-cover relative z-20 h-full w-full rounded-[12px] sm:rounded-[16px] object-cover shadow-[0_15px_35px_rgba(0,0,0,.3)] transition duration-700 group-hover:-translate-x-1.5 group-hover:-translate-y-0.5"
            />

            {{-- Lyrics typewriter --}}
            <div class="ruang-lyrics pointer-events-none absolute left-0 top-[calc(100%+8px)] sm:top-[calc(100%+20px)] z-30 w-[200px] sm:w-[250px] font-mono text-[9px] sm:text-[10px] text-slate-300 opacity-0 transition-opacity duration-300 group-hover:opacity-100">
            </div>
        </div>

        {{-- Mobile Track Info (Shown alongside artwork on small screens, hidden on desktop) --}}
        <div class="flex-1 min-w-0 sm:hidden flex flex-col justify-center pl-1.5">
            <h3 class="text-2xl font-normal font-serif text-white truncate leading-snug tracking-[0.03em]">
                Wonderwall
            </h3>
            <p class="text-xs font-medium text-slate-300 truncate mt-0.5">
                Oasis
            </p>
            <p class="text-[11px] text-slate-400 truncate mt-0.5 font-sans leading-tight">
                (What's the Story) Morning Glory?
            </p>
            <p class="text-[10px] text-slate-500 font-sans mt-0.5">
                1995
            </p>
        </div>
    </div>

    {{-- Details & Player (Desktop view + Controls on Mobile) --}}
    <div class="w-full flex-1 text-left min-w-0 flex flex-col justify-between">
        {{-- Desktop Track Info (Hidden on mobile) --}}
        <div class="hidden sm:block">
            <h3 class="text-3xl lg:text-4xl font-normal font-serif text-white leading-snug truncate tracking-[0.02em]">
                Wonderwall
            </h3>

            <p class="mt-1 text-sm font-medium text-slate-300">
                Oasis
            </p>

            <p class="mt-0.5 text-xs text-slate-400 font-sans">
                (What's the Story) Morning Glory? &middot; 1995
            </p>
        </div>

        {{-- Player Controls & Seekbar --}}
        <div class="mt-2 sm:mt-4 pb-1">
            <div class="mb-1.5 flex items-center justify-end">
                <button type="button" aria-label="Sukai lagu" class="text-base sm:text-lg text-rose-500 hover:scale-110 transition-transform active:scale-90">
                    &hearts;
                </button>
            </div>

            <div class="relative w-full">
                <div class="h-[3px] rounded-full bg-white/10"></div>
                <div class="ruang-progress absolute left-[41%] -top-[4.5px] h-3 w-3 rounded-full bg-white shadow-sm transition-all duration-[46s] ease-linear"></div>
            </div>

            <div class="mt-2 flex items-center justify-between text-[11px] text-slate-400 font-mono tabular-nums">
                <span>01:22</span>
                <span>02:08</span>
            </div>

            <div class="mt-3 flex items-center justify-center gap-6 sm:gap-7 text-slate-300">
                <button type="button" aria-label="Lagu sebelumnya" class="w-8 h-8 rounded-full bg-white/[0.04] border border-white/10 hover:bg-white/15 text-slate-300 hover:text-white flex items-center justify-center transition-all active:scale-75">
                    <i class="ph-bold ph-skip-back text-xs"></i>
                </button>
                <button type="button" id="wonderwall-play-btn" aria-label="Putar lagu" class="relative group/play w-10 h-10 rounded-full bg-white/[0.08] hover:bg-white/[0.14] border border-white/20 backdrop-blur-xl shadow-md flex items-center justify-center transition-all hover:scale-105 active:scale-90 duration-200 text-white">
                    <i id="wonderwall-play-icon" class="ph-fill ph-play text-base ml-0.5 text-white"></i>
                </button>
                <button type="button" aria-label="Lagu berikutnya" class="w-8 h-8 rounded-full bg-white/[0.04] border border-white/10 hover:bg-white/15 text-slate-300 hover:text-white flex items-center justify-center transition-all active:scale-75">
                    <i class="ph-fill ph-skip-forward text-xs"></i>
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
            vinyl.classList.remove('is-playing', 'left-[92%]');
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
                    vinyl.classList.add('is-playing', 'left-[92%]');
                    cover.classList.add('-translate-x-2', '-translate-y-1');
                    progress.classList.add('left-[100%]');
                    lyricsBox.classList.add('opacity-100');
                }).catch(err => alert('Gagal memutar lagu: ' + err.message));
            }
        });
        
        audio.addEventListener('ended', stopPlaying);
        audio.addEventListener('pause', () => {
            if (isPlaying) stopPlaying();
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

<div class="rounded-2xl border border-white/10 bg-white/5 p-8 sm:p-10 text-center text-slate-300">

    <div class="text-4xl sm:text-5xl opacity-50"><i class="ph ph-music-note text-[1.1em] align-middle"></i></div>

    <h3 class="mt-4 sm:mt-5 font-semibold text-white">
        Belum ada lagu.
    </h3>

    <p class="mt-2 text-sm text-slate-400">
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
                        <p class="text-sm text-amber-300/80">
                            kalender.
                        </p>

                        <h4 class="text-sm text-slate-200 font-medium dark:text-slate-200">
                            {{ $today->translatedFormat('F Y') }}
                        </h4>
                    </div>

                    {{-- Nama hari --}}
                    <div class="grid grid-cols-7 gap-1 sm:gap-2 mb-2">

                        @foreach(['Mon','Tue','Wed','Thu','Fri','Sat','Sun'] as $day)

                            <div class="text-center text-[11px] sm:text-xs uppercase tracking-widest sm:tracking-[.28em] font-medium {{ $day === 'Sun' ? 'text-red-500 dark:text-red-400' : 'text-slate-300' }}">
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
                                
                                $textColor = $isSunday ? 'text-red-500 dark:text-red-400 font-bold' : 'text-white dark:text-white';
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
                                    rounded-[20px] sm:rounded-[18px]
                                    p-1.5 sm:p-3
                                    transition-all
                                    duration-300
                                    cursor-pointer
                                    hover:-translate-y-[2px]
                                    hover:shadow-[0_1px_2px_rgba(0,0,0,.02)]
                                    hover:scale-[1.02]
                                    active:scale-95

                                    {{ $isToday
                                        ? 'border-2 border-amber-300/80 bg-amber-300/10 shadow-sm'
                                        : 'border border-white/10 bg-white/5 hover:border-amber-300/50 hover:bg-white/10'
                                    }}
                                 flex flex-col justify-center sm:justify-start items-center sm:items-start"
                            >

                                <span
                                    class="text-[16px] sm:text-[22px] leading-none flex items-center justify-center {{ $hasAgenda ? 'w-7 h-7 sm:w-9 sm:h-9 rounded-full bg-white/20 border border-white/20 shadow-sm' : '' }} {{ $textColor }}"
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
                                        <p class="text-[9px] text-blue-200 line-clamp-1 dark:text-slate-400" title="{{ $agenda->title }}">
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
                                        <span class="h-1.5 w-1.5 rounded-full bg-white/20"></span>
                                    @endif
                                </div>

                                @if($isToday)
                                    <span class="absolute bottom-1.5 sm:bottom-3 left-1/2 sm:left-3 -translate-x-1/2 sm:translate-x-0 h-[2px] w-4 sm:w-7 rounded-full bg-white/20"></span>
                                @elseif(!$agenda && !$deadline)
                                    <span class="absolute bottom-1.5 sm:bottom-3 left-1/2 sm:left-3 -translate-x-1/2 sm:translate-x-0 h-[2px] w-3 sm:w-5 rounded-full bg-white/10 transition group-hover:bg-amber-300/80"></span>
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
                                 class="absolute inset-0 bg-black/60 backdrop-blur-md"></div>

                            <div x-show="isModalOpen"
                                 x-transition:enter="transition ease-out duration-300"
                                 x-transition:enter-start="transform translate-y-full sm:translate-y-0 sm:scale-95 opacity-0"
                                 x-transition:enter-end="transform translate-y-0 sm:scale-100 opacity-100"
                                 x-transition:leave="transition ease-in duration-200"
                                 x-transition:leave-start="transform translate-y-0 sm:scale-100 opacity-100"
                                 x-transition:leave-end="transform translate-y-full sm:translate-y-0 sm:scale-95 opacity-0"
                                 class="relative overflow-hidden w-full max-w-md bg-slate-950/95 backdrop-blur-2xl border border-white/15 rounded-t-[32px] sm:rounded-[32px] mt-auto sm:mt-0 shadow-2xl p-6 sm:p-8 z-10">

                                <div class="pointer-events-none absolute inset-0 bg-gradient-to-tr from-transparent via-white/[0.04] to-transparent"></div>
                                
                                <div class="relative z-10">
                                <h3 class="text-xl sm:text-2xl font-bold text-white mb-6">Agenda</h3>

                                <!-- Existing Agendas -->
                                <template x-if="dayAgendas.length > 0">
                                    <div class="mb-6 space-y-2">
                                        <template x-for="agenda in dayAgendas" :key="agenda.id">
                                            <div class="px-4 py-3 bg-white/5 border border-white/10 rounded-2xl flex justify-between items-center group">
                                                <span class="text-sm font-medium text-white dark:text-white" x-text="agenda.title"></span>
                                                <form :action="'/agendas/' + agenda.id" method="POST" class="inline m-0">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="p-1.5 text-slate-300 hover:text-red-500 transition opacity-100 sm:opacity-0 sm:group-hover:opacity-100" title="Hapus Agenda">
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
                                            <label class="block text-sm font-medium text-slate-300 mb-2">Tambah Agenda Baru</label>
                                            <input type="text" name="title" required placeholder="Ngapain hari ini?"
                                                class="w-full px-4 sm:px-5 py-3 sm:py-3.5 bg-black/30 border-none rounded-xl sm:rounded-2xl text-white placeholder-white/40 focus:ring-2 focus:ring-blue-500 transition-colors text-sm sm:text-base">
                                        </div>
                                    </div>

                                    <div class="mt-8 flex gap-3">
                                        <button type="button" @click="closeModal()"
                                            class="flex-1 px-4 sm:px-5 py-3 sm:py-3.5 rounded-xl sm:rounded-2xl bg-white/10 text-white font-medium hover:bg-white/20 transition active:scale-[0.98] text-sm sm:text-base">
                                            batal.
                                        </button>
                                        <button type="submit"
                                            class="flex-1 px-4 sm:px-5 py-3 sm:py-3.5 rounded-xl sm:rounded-2xl bg-white text-black font-medium hover:bg-neutral-200 shadow-lg transition active:scale-[0.98] text-sm sm:text-base">
                                            tambah.
                                        </button>
                                    </div>
                                </form>
                                </div>
                            </div>
                        </div>
                    </template>

                </div>
            </div>

            <div class="col-span-1 md:col-span-4 row-span-2">
                {{-- Activity --}}
            <div class="dashboard-card p-6 sm:p-8 h-full">
                <p class="text-sm text-amber-300/80">
                    aktivitas.
                </p>
                <div class="mt-8 space-y-5 sm:space-y-6">
                    @forelse($activities as $activity)
                        <div class="flex justify-between items-center group cursor-pointer">
                            <div class="flex gap-4">
                                <div class="mt-1 h-10 w-10 shrink-0 rounded-full bg-white/5 border border-white/10 flex items-center justify-center text-slate-300 transition-all group-hover:scale-110 group-hover:bg-white/20 group-hover:border-white/30 group-hover:text-white">
                                    <i class="{{ $activity['icon'] }} text-lg"></i>
                                </div>
                                <div>
                                    <h4 class="font-medium text-sm sm:text-base text-white group-hover:text-white transition-colors">
                                        {{ $activity['title'] }}
                                    </h4>
                                    <p class="text-xs sm:text-sm text-blue-200 mt-0.5 sm:mt-1">
                                        {{ $activity['time'] }}
                                    </p>
                                </div>
                            </div>
                            <i class="ph ph-caret-right opacity-0 group-hover:opacity-100 transition-opacity text-slate-300"></i>
                        </div>
                    @empty
                        <div class="text-center py-6">
                            <i class="ph-thin ph-wind text-4xl text-neutral-300 dark:text-white mb-2"></i>
                            <h3 class="text-lg font-semibold text-white dark:text-white">
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
                        <p class="text-sm text-amber-300/80">
                            deadline.
                        </p>
                        <button @click="openCreateModal()" class="text-slate-300 hover:text-white transition active:scale-90 p-1" title="Tambah Deadline">
                            <i class="ph ph-plus text-xl"></i>
                        </button>
                    </div>

                    @forelse($deadlines as $task)
                        <div class="mt-2 group relative">
                            <div class="flex justify-between items-center">
                                <span class="text-[10px] sm:text-xs uppercase tracking-wider text-slate-300">
                                    {{ $task->due_date->diffForHumans() }}
                                </span>
                                <div class="flex items-center gap-1 sm:gap-2 opacity-100 sm:opacity-0 sm:group-hover:opacity-100 transition-opacity">
                                    <button @click="openEditModal({{ $task->id }}, '{{ addslashes($task->title) }}', '{{ $task->due_date->format('Y-m-d\TH:i') }}')" class="p-1 text-slate-300 hover:text-white transition">
                                        <i class="ph ph-pencil-simple text-sm sm:text-base"></i>
                                    </button>
                                    <form action="{{ route('deadlines.destroy', $task->id) }}" method="POST" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="p-1 text-slate-300 hover:text-red-500 transition" onclick="return confirm('Hapus deadline ini?')">
                                            <i class="ph ph-trash text-sm sm:text-base"></i>
                                        </button>
                                    </form>
                                    <span class="text-red-500 ml-1 sm:ml-2">
                                        <i class="ph ph-clock-countdown"></i>
                                    </span>
                                </div>
                            </div>
                            <h3 class="mt-1 text-lg sm:text-xl font-semibold text-white leading-tight dark:text-white">
                                {{ $task->title }}
                            </h3>
                        </div>

                        @unless($loop->last)
                            <hr class="my-4 border-[#1e293b] dark:border-slate-700/50">
                        @endunless
                    @empty
                        <div class="mt-6 sm:mt-8 text-center sm:text-left">
                            <i class="ph-thin ph-coffee text-4xl text-neutral-300 mb-2"></i>
                            <h3 class="text-lg sm:text-xl font-semibold text-white">
                                aman.
                            </h3>
                            <p class="mt-2 text-sm sm:text-base text-slate-400">
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

                            <div class="absolute inset-0 bg-black/60 backdrop-blur-md" @click="isOpen = false"></div>

                            <div x-show="isOpen"
                                 x-transition:enter="transition ease-out duration-300"
                                 x-transition:enter-start="transform translate-y-full sm:translate-y-0 sm:scale-95 opacity-0"
                                 x-transition:enter-end="transform translate-y-0 sm:scale-100 opacity-100"
                                 x-transition:leave="transition ease-in duration-200"
                                 x-transition:leave-start="transform translate-y-0 sm:scale-100 opacity-100"
                                 x-transition:leave-end="transform translate-y-full sm:translate-y-0 sm:scale-95 opacity-0"
                                 class="relative overflow-hidden w-full max-w-md bg-slate-950/95 backdrop-blur-2xl border border-white/15 rounded-t-[32px] sm:rounded-[32px] mt-auto sm:mt-0 shadow-2xl p-6 sm:p-8 z-10">
                                
                                <div class="pointer-events-none absolute inset-0 bg-gradient-to-tr from-transparent via-white/[0.04] to-transparent"></div>

                                <div class="relative z-10">
                                <h3 class="text-xl sm:text-2xl font-bold text-white mb-6" x-text="isEdit ? 'Edit Deadline' : 'Tambah Deadline'"></h3>

                                <form :action="formAction" method="POST">
                                    @csrf
                                    <template x-if="isEdit">
                                        <input type="hidden" name="_method" value="PUT">
                                    </template>

                                    <div class="mb-5">
                                        <label class="block text-sm font-medium text-slate-300 mb-2">Judul Tugas</label>
                                        <input type="text" name="title" x-model="form.title" class="w-full bg-black/30 border-none rounded-xl px-4 min-h-11 text-sm text-white placeholder-white/40 focus:ring-2 focus:ring-blue-500 transition-colors" placeholder="Judul tugas..." required>
                                    </div>

                                    <div class="mb-8">
                                        <label class="block text-sm font-medium text-slate-300 mb-2">Tenggat Waktu (Deadline)</label>
                                        <input type="text" name="due_date" x-model="form.due_date" x-init="flatpickr($el, { enableTime: true, dateFormat: 'Y-m-d\\TH:i', time_24hr: true, minDate: 'today' })" class="w-full bg-black/30 border-none rounded-xl px-4 min-h-11 text-sm text-white placeholder-white/40 focus:ring-2 focus:ring-blue-500 transition-colors" placeholder="Pilih tanggal & waktu..." required>
                                    </div>

                                    <div class="flex justify-end gap-3">
                                        <button type="button" @click="isOpen = false" class="px-5 min-h-11 rounded-xl font-medium text-sm bg-white/10 text-white hover:bg-white/20 transition active:scale-95">
                                            Batal
                                        </button>
                                        <button type="submit" class="px-5 min-h-11 rounded-xl font-medium text-sm bg-white text-black hover:bg-neutral-200 shadow-lg transition active:scale-95">
                                            Simpan
                                        </button>
                                    </div>
                                </form>
                                </div>
                            </div>
                        </div>
                    </template>
                </div>

                {{-- Target --}}
                <div class="dashboard-card p-6 sm:p-8">
                    <p class="text-sm text-amber-300/80">
                        target.
                    </p>
                    <div class="mt-6">
                        <h2 class="text-3xl sm:text-4xl font-bold text-white dark:text-white">
                            {{ number_format($todayWords) }}
                            <span class="text-base sm:text-lg font-normal text-slate-300">
                                / 1000
                            </span>
                        </h2>
                        @php
                            $percentage = min(100, ($todayWords / 1000) * 100);
                        @endphp
                        <div class="mt-5 h-2 rounded-full bg-white/10 overflow-hidden">
                            <div class="h-full rounded-full bg-amber-400 shadow-sm transition-all duration-1000 ease-out" style="width:{{ $percentage }}%"></div>
                        </div>
                        <p class="mt-3 text-xs sm:text-sm text-slate-400">
                            {{ round($percentage) }}% selesai
                        </p>
                    </div>
                </div>
            </div>

        </div>

        </div>

        <footer class="mt-12 text-center pb-8">
            <p class="text-[13px] text-slate-300/80">
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