<x-app-layout>
    <x-slot name="pageTitle">Beranda</x-slot>
    <x-slot name="pageSubtitle">hari ini, {{ strtolower(now()->translatedFormat('l, d F Y')) }}. semoga sehat selalu.</x-slot>

    <style>
        @import url('https://fonts.googleapis.com/css2?family=Source+Serif+4:ital,opsz,wght@0,8..60,400;0,8..60,600;1,8..60,400&display=swap');
        .quote-editor { font-family: 'Source Serif 4', Georgia, serif; }
    </style>

    <div class="animate-fadeIn">

        {{-- ================= HEADER ================= --}}
    <div class="mb-10 relative z-0">
        {{-- No shapes or vinyl here anymore, moved to app layout --}}

        <div class="flex items-start justify-between relative z-10">
            <div class="max-w-2xl">
                <h1 id="dynamicGreeting" class="text-2xl sm:text-3xl font-semibold tracking-tight text-neutral-900 dark:text-white">
                    {!! $greeting !!}
                </h1>
                <blockquote class="mt-4 border-l-2 border-stone-200 pl-4 dark:border-slate-700/50">
                    <p class="quote text-sm sm:text-base text-neutral-600 leading-relaxed dark:text-slate-300">
                        "{{ $quote['text'] }}"
                    </p>
                    <footer class="mt-1 text-xs sm:text-sm font-medium text-neutral-400">
                        — {{ $quote['author'] }}
                    </footer>
                </blockquote>
            </div>

            {{-- Flip Clock --}}
            <div class="hidden lg:flex flex-col items-end justify-start">
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
                <h3 class="text-sm font-medium text-neutral-400 tracking-widest uppercase">ngapain.?</h3>
                <div class="flex flex-wrap gap-3 sm:gap-4">
                    <a href="{{ route('makalah.index') }}" class="dashboard-card flex items-center gap-2 px-5 py-2.5 text-neutral-500 hover:text-indigo-600 font-medium active:scale-95 transition-transform dark:text-slate-400">
                        <i class="ph ph-pencil-simple-line text-lg"></i>
                        <span>nugas</span>
                    </a>
                    <a href="#" class="dashboard-card flex items-center gap-2 px-5 py-2.5 text-neutral-500 hover:text-emerald-600 font-medium active:scale-95 transition-transform dark:text-slate-400">
                        <i class="ph ph-notebook text-lg"></i>
                        <span>nyatet</span>
                    </a>
                    <a href="#" class="dashboard-card flex items-center gap-2 px-5 py-2.5 text-neutral-500 hover:text-rose-600 font-medium active:scale-95 transition-transform dark:text-slate-400">
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
            <a href="{{ route('makalah.edit', $activeDoc->id) }}" class="dashboard-card group block p-6 sm:p-8 lg:p-10 h-full flex flex-col justify-between transition-all active:scale-[0.98]">
                <span class="text-sm text-neutral-500 dark:text-slate-400">
                    lanjutin yg tadi.
                </span>
                <h2 class="mt-3 text-3xl sm:text-4xl lg:text-5xl font-bold tracking-tight text-neutral-900 leading-tight dark:text-white">
                    {{ $activeDoc->judul }}
                </h2>

                <div class="mt-8 flex flex-wrap items-center gap-3 sm:gap-4 text-xs sm:text-sm text-neutral-500 dark:text-slate-400">
                    <span>{{ number_format($wordCount) }} kata</span>
                    <span class="hidden sm:inline">&middot;</span>
                    <span>terakhir dibuka {{ $activeDoc->updated_at ? $activeDoc->updated_at->diffForHumans() : 'Baru Saja' }}</span>
                </div>

                <hr class="my-6 border-stone-200 dark:border-slate-700/50">

                <div class="flex items-center gap-2 font-medium text-neutral-900 text-sm sm:text-base dark:text-white">
                    lanjutin lagi
                    <i class="ph ph-arrow-right text-xl transition-transform duration-300 group-hover:translate-x-1"></i>
                </div>
            </a>
        @else
            <div class="dashboard-card border-dashed border-stone-300 p-6 sm:p-8 lg:p-10 h-full flex flex-col justify-center text-center dark:border-slate-700">
                <h2 class="text-xl sm:text-2xl font-semibold text-neutral-900 dark:text-white">
                    belom ada yg dikerjain.
                </h2>
                <p class="mt-2 text-sm sm:text-base text-neutral-500 dark:text-slate-400">
                    bikin makalah pertama dulu.
                </p>
                <div class="mt-6">
                    <a href="{{ route('makalah.create') }}" class="inline-flex rounded-xl bg-neutral-900 px-6 min-h-11 items-center justify-center text-sm font-medium text-white transition hover:bg-neutral-800 active:scale-95">
                        mulai nulis
                    </a>
                </div>
            </div>
        @endif
            </div>

            <div class="col-span-1 md:col-span-4 row-span-2">
                {{-- Focus --}}
                <style>
                    .pomodoro-timer {
                        width: 220px;
                        height: 220px;
                        border-radius: 999px;
                        background: #fff;
                        border: 2px solid #ece8e2;
                        position: relative;
                        display: flex;
                        justify-content: center;
                        align-items: center;
                        box-shadow: 0 10px 25px rgba(0,0,0,.04);
                        z-index: 10;
                        transition: .4s;
                    }
                    .pomodoro-timer-inner {
                        width: 170px;
                        height: 170px;
                        border-radius: 999px;
                        border: 1px solid #ececec;
                        display: flex;
                        flex-direction: column;
                        justify-content: center;
                        align-items: center;
                        background: #fff;
                        z-index: 10;
                    }
                    .pomodoro-time {
                        font-family: 'Cormorant Garamond', serif;
                        font-size: 64px;
                        line-height: 1;
                        color: #171717;
                    }
                    .pomodoro-mode {
                        margin-top: 8px;
                        letter-spacing: .3em;
                        text-transform: uppercase;
                        color: #888;
                        font-size: 11px;
                    }
                    /* The realistic 60 ticks using repeating conic gradient */
                    .pomodoro-timer::before {
                        content: "";
                        position: absolute;
                        inset: 4px;
                        border-radius: 999px;
                        background: repeating-conic-gradient(from 0deg, #d9d5ce 0deg 2deg, transparent 2deg 6deg);
                        opacity: .7;
                        z-index: 1;
                    }
                    .pomodoro-timer-knob {
                        position: absolute;
                        right: -52px;
                        width: 64px;
                        height: 120px;
                        background: #f5f4f2;
                        border-radius: 0 60px 60px 0;
                        border: 2px solid #e5e2dc;
                        display: flex;
                        align-items: center;
                        justify-content: center;
                        transform: translateX(-45px);
                        opacity: 0;
                        transition: .45s cubic-bezier(.22,1,.36,1);
                        z-index: 0;
                        cursor: pointer;
                    }
                    /* For touch devices, make knob slightly visible or fully functional */
                    @media (hover: none) {
                        .pomodoro-timer-knob {
                            transform: translateX(-15px);
                            opacity: 1;
                        }
                    }
                    .group:hover .pomodoro-timer-knob,
                    .is-running .pomodoro-timer-knob {
                        transform: translateX(0);
                        opacity: 1;
                    }
                    @media (hover: none) {
                        .is-running .pomodoro-timer-knob {
                            transform: translateX(0) !important;
                        }
                    }
                    .is-running .pomodoro-timer-knob {
                        transform: translateX(-15px) !important;
                    }
                    .pomodoro-knob-grip {
                        width: 14px;
                        height: 72px;
                        border-radius: 999px;
                        background: repeating-linear-gradient(90deg, #bbb, #bbb 2px, transparent 2px, transparent 4px);
                    }
                    .group:hover .pomodoro-timer {
                        transform: rotate(-3deg);
                        box-shadow: 0 20px 45px rgba(0,0,0,.08);
                    }
                    /* Running pulse animation */
                    @keyframes pulse-timer {
                        0% { box-shadow: 0 10px 25px rgba(0,0,0,.04); border-color: #ece8e2; }
                        50% { box-shadow: 0 15px 35px rgba(16,185,129,.15); border-color: #a7f3d0; }
                        100% { box-shadow: 0 10px 25px rgba(0,0,0,.04); border-color: #ece8e2; }
                    }
                    .is-running .pomodoro-timer {
                        animation: pulse-timer 2s infinite ease-in-out;
                    }
                </style>
                <div x-data class="dashboard-card h-full flex flex-col items-center justify-center group overflow-hidden relative" :class="$store.pomodoro.isRunning ? 'is-running' : ''">
                    <p class="absolute top-6 left-6 text-sm text-neutral-500 dark:text-slate-400">
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
                            <span class="absolute right-[-4px] text-[8px] tracking-widest uppercase font-medium text-neutral-400 rotate-90 opacity-0 group-hover:opacity-100 transition"
                                  style="transform: rotate(90deg) translateY(-25px); transform-origin: left top;"
                                  x-text="$store.pomodoro.isRunning ? 'PAUSE' : 'PLAY'">
                            </span>
                        </div>
                    </div>

                    <button @click="$store.pomodoro.reset()" class="absolute bottom-6 text-[10px] uppercase tracking-widest text-neutral-400 hover:text-neutral-600 transition active:scale-95 p-2">
                        reset
                    </button>
                </div>
            </div>

            <div class="col-span-1 md:col-span-5 flex flex-col gap-6 lg:gap-8">
                {{-- Music --}}
                <div class="dashboard-card p-4 sm:p-8 lg:p-10">
                   {{-- Premium Music Widget --}}
@if(isset($currentTrack) && $currentTrack)

<div class="flex justify-between items-center">
    <p class="text-sm text-neutral-500 dark:text-slate-400">
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

<div class="ruang-music-widget group mt-6 flex flex-row items-center gap-4 sm:items-start sm:gap-6">
    {{-- Artwork --}}
    <div class="relative h-32 w-32 shrink-0 sm:h-40 sm:w-40">
        {{-- Vinyl --}}
        <div
            class="ruang-vinyl absolute left-[68%] top-1/2 z-0 h-[100px] w-[100px] -translate-x-1/2 -translate-y-1/2 rounded-full shadow-[0_22px_55px_rgba(0,0,0,.12)] transition-all duration-700 ease-out group-hover:left-[92%] sm:h-[130px] sm:w-[130px]"
            style="background: radial-gradient(circle at center, #e8ddc4 0 11%, #141414 12%), repeating-radial-gradient(circle, #151515 0, #151515 .8px, #202020 .8px, #202020 1.6px);"
        >
            <div class="absolute left-4 top-4 h-8 w-20 rounded-full bg-white/10 blur-xl"></div>

            <div class="absolute inset-0 flex items-center justify-center">
                <div class="flex h-8 w-8 flex-col items-center justify-center rounded-full bg-[#e8ddc4] leading-none shadow-inner sm:h-10 sm:w-10">
                    <span class="text-[4px] font-bold tracking-[0.18em] text-neutral-700 dark:text-slate-200">
                        OASIS
                    </span>
                    <span class="mt-[2px] text-[3px] text-neutral-500 dark:text-slate-400">
                        Wonderwall
                    </span>
                    <span class="mt-[1px] text-[3px] text-neutral-500 dark:text-slate-400">
                        1995
                    </span>
                    <span class="mt-[2px] h-1.5 w-1.5 rounded-full bg-neutral-900"></span>
                </div>
            </div>
        </div>

        {{-- Cover album --}}
        <img
            src="{{ asset('images/wonderwall.jpg') }}"
            alt="Wonderwall"
            class="relative z-20 h-full w-full rounded-[12px] object-cover shadow-[0_22px_55px_rgba(0,0,0,.12)] transition duration-700 group-hover:-translate-x-2 group-hover:-translate-y-1 sm:rounded-[16px]"
        />

        {{-- Lyrics typewriter --}}
        <div class="ruang-lyrics pointer-events-none absolute left-0 top-[calc(100%+1rem+18px)] z-30 w-[200px] font-mono text-[9px] text-neutral-500 opacity-0 transition-opacity duration-300 group-hover:opacity-100 dark:text-neutral-400 sm:w-[250px] sm:text-[11px]">
            <p class="ruang-line" data-text="Because maybeee,"></p>
            <p class="ruang-line mt-0.5" data-text="you're gonna be the one that saves me"></p>
            <p class="ruang-line mt-0.5" data-text="And after all, you're my wonderwall"></p>
        </div>
    </div>

    {{-- Details --}}
    <div class="w-full flex-1 text-left">
        <div class="flex items-start justify-between">
            <div>
                <h3
                    class="text-[20px] font-normal leading-none text-neutral-900 dark:text-white sm:text-[36px]"
                    style="font-family: 'Cormorant Garamond', serif;"
                >
                    Wonderwall
                </h3>

                <p class="mt-1 text-[14px] font-light text-neutral-500 dark:text-slate-400 sm:mt-2 sm:text-[18px]">
                    Oasis
                </p>

                <p class="mt-0.5 text-[10px] tracking-[0.01em] text-neutral-400 sm:mt-1 sm:text-[13px]">
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
                <div class="h-[2px] rounded-full bg-stone-200 dark:bg-slate-800"></div>
                <div class="absolute left-[52%] -top-[5px] h-3 w-3 rounded-full bg-neutral-900 dark:bg-white"></div>
            </div>

            <div class="mt-3 flex items-center justify-between text-xs text-neutral-400 sm:text-sm">
                <span>01:43</span>
                <span>02:35</span>
            </div>

            <div class="mt-4 flex items-center justify-center gap-6 text-neutral-400">
                <button type="button" aria-label="Lagu sebelumnya">
                    <i class="ph-fill ph-skip-back text-[15px]"></i>
                </button>

                <button type="button" aria-label="Putar lagu" class="text-neutral-900 dark:text-white">
                    <i class="ph-fill ph-play text-[20px]"></i>
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

    const lineEls = Array.from(widget.querySelectorAll('.ruang-line'));
    const CHAR_DELAY = 45;
    const LINE_GAP = 200;

    let runId = 0;

    function resetLines() {
        lineEls.forEach(function (el) {
            el.textContent = '';
            el.classList.remove('caret');
        });
    }

    function typeLine(el, myRun) {
        return new Promise(function (resolve) {
            const text = el.dataset.text;
            let i = 0;
            el.classList.add('caret');

            (function step() {
                if (myRun !== runId) return;
                el.textContent = text.slice(0, i);
                i++;
                if (i <= text.length) {
                    setTimeout(step, CHAR_DELAY);
                } else {
                    el.classList.remove('caret');
                    resolve();
                }
            })();
        });
    }

    async function runSequence(myRun) {
        resetLines();
        for (let idx = 0; idx < lineEls.length; idx++) {
            if (myRun !== runId) return;
            await typeLine(lineEls[idx], myRun);
            if (myRun !== runId) return;
            if (idx === lineEls.length - 1) {
                lineEls[idx].classList.add('caret');
            }
            await new Promise(function (r) { setTimeout(r, LINE_GAP); });
        }
    }

    widget.addEventListener('mouseenter', function () {
        runId++;
        runSequence(runId);
    });

    widget.addEventListener('mouseleave', function () {
        runId++;
        resetLines();
    });
});
</script>

@else

<div class="rounded-2xl bg-stone-100 p-8 sm:p-10 text-center dark:bg-slate-900/80">

    <div class="text-4xl sm:text-5xl">🎵</div>

    <h3 class="mt-4 sm:mt-5 font-semibold text-neutral-900 dark:text-white">
        Belum ada lagu.
    </h3>

    <p class="mt-2 text-sm text-neutral-500 dark:text-slate-400">
        Putar sesuatu dulu.
    </p>

</div>

@endif

                </div>

                {{-- Kalender --}}
                <div class="dashboard-card p-4 sm:p-6 pb-6 sm:pb-8" x-data="calendarManager()">

                    @php
                        // use Carbon\Carbon; // Ensure no redeclaration if used elsewhere

                        $today = \Carbon\Carbon::now();

                        $startOfMonth = $today->copy()->startOfMonth();
                        $daysInMonth = $today->daysInMonth;

                        // Senin = 0
                        $offset = $startOfMonth->dayOfWeekIso - 1;
                    @endphp

                    <div class="flex items-center justify-between mb-6">
                        <p class="text-sm text-neutral-500 dark:text-slate-400">
                            kalender.
                        </p>

                        <h4 class="text-sm text-neutral-700 font-medium dark:text-slate-200">
                            {{ $today->translatedFormat('F Y') }}
                        </h4>
                    </div>

                    {{-- Nama hari --}}
                    <div class="grid grid-cols-7 gap-1 sm:gap-2 mb-2">

                        @foreach(['Sen','Sel','Rab','Kam','Jum','Sab','Min'] as $day)

                            <div class="text-center text-[9px] sm:text-[10px] uppercase tracking-widest sm:tracking-[.28em] font-medium text-neutral-400">
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
                                $agenda = $agendas->get($day)?->first();
                                $deadline = $deadlinesByDay->get($day)?->first();

                                $dateStr = $today->copy()->setDay($day)->format('Y-m-d');
                            @endphp

                            <div
                                @click="openModal('{{ $dateStr }}')"
                                class="
                                    group
                                    relative
                                    aspect-[0.92]
                                    rounded-xl sm:rounded-[18px]
                                    p-1 sm:p-3
                                    transition-all
                                    duration-300
                                    cursor-pointer
                                    hover:-translate-y-[2px]
                                    hover:shadow-[0_1px_2px_rgba(0,0,0,.02)]
                                    hover:scale-[1.02]
                                    active:scale-95

                                    {{ $isToday
                                        ? 'border-2 border-neutral-900 bg-white shadow-sm'
                                        : 'border border-[#ececec] bg-white hover:border-neutral-400'
                                    }}
                                 dark:bg-slate-900"
                            >

                                <span
                                    class="text-[12px] sm:text-[18px] leading-none text-neutral-900 flex justify-center sm:justify-start dark:text-white"
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
                                        <p class="text-[9px] text-neutral-500 line-clamp-1 dark:text-slate-400" title="{{ $agenda->title }}">
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
                                        <span class="h-1.5 w-1.5 rounded-full bg-neutral-900"></span>
                                    @endif
                                </div>

                                @if($isToday)
                                    <span class="absolute bottom-1.5 sm:bottom-3 left-1/2 sm:left-3 -translate-x-1/2 sm:translate-x-0 h-[2px] w-4 sm:w-7 rounded-full bg-neutral-900"></span>
                                @elseif(!$agenda && !$deadline)
                                    <span class="absolute bottom-1.5 sm:bottom-3 left-1/2 sm:left-3 -translate-x-1/2 sm:translate-x-0 h-[2px] w-3 sm:w-5 rounded-full bg-neutral-200 transition group-hover:bg-neutral-400"></span>
                                @endif

                            </div>

                        @endfor

                    </div>

                    <!-- Modal Tambah Agenda -->
                    <div x-show="isModalOpen"
                         style="display: none;"
                         class="fixed inset-0 z-[100] flex items-center justify-center p-4 sm:p-6">

                        <div x-show="isModalOpen"
                             x-transition.opacity
                             @click="closeModal()"
                             class="absolute inset-0 bg-neutral-900/40 backdrop-blur-sm"></div>

                        <div x-show="isModalOpen"
                             x-transition.scale.95
                             class="relative w-full max-w-md bg-white rounded-[24px] sm:rounded-[32px] shadow-2xl p-6 sm:p-8 z-10 dark:bg-slate-800">

                            <h3 class="text-xl sm:text-2xl font-bold text-neutral-900 mb-6 dark:text-white">Tambah Agenda</h3>

                            <form action="{{ route('agendas.store') }}" method="POST">
                                @csrf
                                <input type="hidden" name="date" x-model="selectedDate">

                                <div class="space-y-4">
                                    <div>
                                        <label class="block text-sm font-medium text-neutral-500 mb-2 dark:text-slate-400">Tanggal</label>
                                        <input type="text" x-model="selectedDate" readonly class="w-full px-4 sm:px-5 py-3 sm:py-3.5 bg-neutral-100 border-none rounded-xl text-neutral-500 cursor-not-allowed">
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-neutral-500 mb-2 dark:text-slate-400">Judul Agenda</label>
                                        <input type="text" name="title" required placeholder="Ngapain hari ini?"
                                            class="w-full px-4 sm:px-5 py-3 sm:py-3.5 bg-white border-2 border-neutral-200 rounded-xl sm:rounded-2xl text-neutral-900 placeholder-neutral-400 focus:border-neutral-900 focus:ring-0 transition-colors text-sm sm:text-base dark:bg-slate-900 dark:border-slate-700 dark:text-white">
                                    </div>
                                </div>

                                <div class="mt-8 flex gap-3">
                                    <button type="button" @click="closeModal()"
                                        class="flex-1 px-4 sm:px-5 py-3 sm:py-3.5 rounded-xl sm:rounded-2xl border-2 border-neutral-200 text-neutral-600 font-medium hover:border-neutral-300 hover:bg-neutral-50 transition active:scale-[0.98] text-sm sm:text-base dark:border-slate-700 dark:text-slate-300">
                                        batal.
                                    </button>
                                    <button type="submit"
                                        class="flex-1 px-4 sm:px-5 py-3 sm:py-3.5 rounded-xl sm:rounded-2xl bg-neutral-900 text-white font-medium hover:bg-neutral-800 transition active:scale-[0.98] text-sm sm:text-base">
                                        simpan.
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>

                </div>
            </div>

            <div class="col-span-1 md:col-span-4 row-span-2">
                {{-- Activity --}}
            <div class="dashboard-card p-6 sm:p-8 lg:p-10 h-full">
                <p class="text-sm text-neutral-500 dark:text-slate-400">
                    aktivitas.
                </p>
                <div class="mt-8 space-y-5 sm:space-y-6">
                    @forelse($activities as $activity)
                        <div class="flex gap-4 group cursor-pointer">
                            <div class="mt-1 h-10 w-10 shrink-0 rounded-full bg-stone-100 flex items-center justify-center text-neutral-600 transition-transform group-hover:scale-110 group-hover:bg-stone-200 dark:bg-slate-900/80 dark:text-slate-300">
                                <i class="{{ $activity['icon'] }} text-lg"></i>
                            </div>
                            <div>
                                <h4 class="font-medium text-sm sm:text-base text-neutral-900 group-hover:text-indigo-600 transition-colors dark:text-white">
                                    {{ $activity['title'] }}
                                </h4>
                                <p class="text-xs sm:text-sm text-neutral-500 mt-0.5 sm:mt-1 dark:text-slate-400">
                                    {{ $activity['time'] }}
                                </p>
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-6">
                            <h3 class="text-lg font-semibold text-neutral-900 dark:text-white">
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
                        <p class="text-sm text-neutral-500 dark:text-slate-400">
                            deadline.
                        </p>
                        <button @click="openCreateModal()" class="text-neutral-400 hover:text-neutral-900 transition active:scale-90 p-1" title="Tambah Deadline">
                            <i class="ph ph-plus text-xl"></i>
                        </button>
                    </div>

                    @forelse($deadlines as $task)
                        <div class="mt-2 group relative">
                            <div class="flex justify-between items-center">
                                <span class="text-[10px] sm:text-xs uppercase tracking-wider text-neutral-400">
                                    {{ $task->due_date->diffForHumans() }}
                                </span>
                                <div class="flex items-center gap-1 sm:gap-2 opacity-100 sm:opacity-0 sm:group-hover:opacity-100 transition-opacity">
                                    <button @click="openEditModal({{ $task->id }}, '{{ addslashes($task->title) }}', '{{ $task->due_date->format('Y-m-d\TH:i') }}')" class="p-1 text-neutral-400 hover:text-indigo-600 transition">
                                        <i class="ph ph-pencil-simple text-sm sm:text-base"></i>
                                    </button>
                                    <form action="{{ route('deadlines.destroy', $task->id) }}" method="POST" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="p-1 text-neutral-400 hover:text-red-500 transition" onclick="return confirm('Hapus deadline ini?')">
                                            <i class="ph ph-trash text-sm sm:text-base"></i>
                                        </button>
                                    </form>
                                    <span class="text-red-500 ml-1 sm:ml-2">
                                        <i class="ph ph-clock-countdown"></i>
                                    </span>
                                </div>
                            </div>
                            <h3 class="mt-1 text-lg sm:text-xl font-semibold text-neutral-900 leading-tight dark:text-white">
                                {{ $task->title }}
                            </h3>
                        </div>

                        @unless($loop->last)
                            <hr class="my-4 border-stone-200 dark:border-slate-700/50">
                        @endunless
                    @empty
                        <div class="mt-6 sm:mt-8">
                            <h3 class="text-lg sm:text-xl font-semibold text-neutral-900 dark:text-white">
                                aman.
                            </h3>
                            <p class="mt-2 text-sm sm:text-base text-neutral-500 dark:text-slate-400">
                                belom ada deadline.
                            </p>
                        </div>
                    @endforelse

                    <!-- Modal Deadline -->
                    <div x-show="isOpen"
                         style="display: none;"
                         class="fixed inset-0 z-[100] flex items-center justify-center p-4 bg-black/50 backdrop-blur-sm"
                         x-transition.opacity>

                        <div class="bg-white rounded-2xl w-full max-w-md p-6 sm:p-8 shadow-2xl relative dark:bg-slate-900"
                             @click.outside="isOpen = false">
                            <h3 class="text-xl sm:text-2xl font-bold text-neutral-900 mb-6 dark:text-white" x-text="isEdit ? 'Edit Deadline' : 'Tambah Deadline'"></h3>

                            <form :action="formAction" method="POST">
                                @csrf
                                <template x-if="isEdit">
                                    <input type="hidden" name="_method" value="PUT">
                                </template>

                                <div class="mb-5">
                                    <label class="block text-sm font-medium text-neutral-500 mb-2 dark:text-slate-400">Judul Tugas</label>
                                    <input type="text" name="title" x-model="form.title" class="w-full rounded-xl border-stone-200 bg-stone-50 px-4 min-h-11 text-sm text-neutral-900 focus:border-indigo-500 focus:ring-indigo-500 dark:border-slate-700/50 dark:bg-slate-900/50 dark:text-white" required>
                                </div>

                                <div class="mb-8">
                                    <label class="block text-sm font-medium text-neutral-500 mb-2 dark:text-slate-400">Tenggat Waktu (Deadline)</label>
                                    <input type="datetime-local" name="due_date" x-model="form.due_date" class="w-full rounded-xl border-stone-200 bg-stone-50 px-4 min-h-11 text-sm text-neutral-900 focus:border-indigo-500 focus:ring-indigo-500 dark:border-slate-700/50 dark:bg-slate-900/50 dark:text-white" required>
                                </div>

                                <div class="flex justify-end gap-3">
                                    <button type="button" @click="isOpen = false" class="px-5 min-h-11 rounded-xl font-medium text-sm text-neutral-500 hover:bg-stone-100 transition active:scale-95 dark:text-slate-400">
                                        Batal
                                    </button>
                                    <button type="submit" class="px-5 min-h-11 rounded-xl font-medium text-sm bg-neutral-900 text-white hover:bg-neutral-800 transition active:scale-95">
                                        Simpan
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                {{-- Target --}}
                <div class="dashboard-card p-6 sm:p-8">
                    <p class="text-sm text-neutral-500 dark:text-slate-400">
                        target.
                    </p>
                    <div class="mt-6">
                        <h2 class="text-3xl sm:text-4xl font-bold text-neutral-900 dark:text-white">
                            {{ number_format($todayWords) }}
                            <span class="text-base sm:text-lg font-normal text-neutral-400">
                                / 1000
                            </span>
                        </h2>
                        @php
                            $percentage = min(100, ($todayWords / 1000) * 100);
                        @endphp
                        <div class="mt-5 h-2 rounded-full bg-stone-200 overflow-hidden dark:bg-slate-800">
                            <div class="h-full rounded-full bg-indigo-600 transition-all duration-1000 ease-out" style="width:{{ $percentage }}%"></div>
                        </div>
                        <p class="mt-3 text-xs sm:text-sm text-neutral-500 dark:text-slate-400">
                            {{ round($percentage) }}% selesai
                        </p>
                    </div>
                </div>
            </div>

        </div>

        </div>

        <footer class="mt-12 text-center pb-8">
            <p class="text-[13px] text-neutral-400/80">
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
                openModal(dateStr) {
                    this.selectedDate = dateStr;
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