<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>RUANG - masuk dulu</title>
    <link rel="icon" type="image/png" href="{{ asset('favicon.png') }}">

    @vite(['resources/css/app.css','resources/js/app.js'])

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:ital,wght@0,300;0,400;0,500;0,600;0,700;1,400&family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

    <style>
        html,
        body {
            font-family: "Inter", sans-serif;
            background: #020617;
            color: #ffffff;
            overflow-x: hidden;
        }

        .font-cormorant {
            font-family: "Cormorant Garamond", serif;
        }

        .vinyl {
            transform: translateY(-50%);
        }
    </style>
</head>

<body class="text-white">

<div class="fixed inset-0 overflow-hidden pointer-events-none">
    <!-- Background -->
    <div class="absolute inset-0 bg-[#020617]"></div>
    
    <!-- Subtle Gradient blobs for dark mode -->
    <div class="absolute inset-0 bg-[radial-gradient(circle_at_15%_30%,rgba(185,28,28,0.06),transparent_35%),radial-gradient(circle_at_100%_100%,rgba(217,119,6,0.06),transparent_40%)]"></div>

    <!-- Film Grain Overlay for 90s Oasis Vibe -->
    <div class="absolute inset-0 opacity-[0.25] mix-blend-overlay" style="background-image: url('data:image/svg+xml,%3Csvg viewBox=%220 0 200 200%22 xmlns=%22http://www.w3.org/2000/svg%22%3E%3Cfilter id=%22noise%22%3E%3CfeTurbulence type=%22fractalNoise%22 baseFrequency=%220.8%22 numOctaves=%224%22 stitchTiles=%22stitch%22/%3E%3C/filter%3E%3Crect width=%22100%25%22 height=%22100%25%22 filter=%22url(%23noise)%22/%3E%3C/svg%3E');"></div>

    <!-- Noel Gallagher's Guitar Background -->
    <div class="absolute -left-32 sm:-left-64 top-1/2 -translate-y-1/2 w-[600px] sm:w-[900px] opacity-[0.15] mix-blend-lighten pointer-events-none">
        <img src="{{ asset('images/guitar.png') }}" alt="Guitar Background" class="w-full h-auto object-contain -rotate-[10deg]">
    </div>
</div>

<main class="relative z-10 min-h-screen flex items-center justify-center">
    <div class="w-full max-w-7xl px-4 sm:px-8 lg:px-14">
        <div class="grid w-full lg:grid-cols-[1.15fr_.85fr] gap-10 lg:gap-20 items-center">
            
            <!-- LEFT -->
            <section class="hidden lg:flex flex-col justify-center">
                <span class="text-xs uppercase tracking-[.35em] text-amber-300/80 font-semibold">
                    Tempat Nongkrong Ide
                </span>
                <h1 class="mt-6 text-[90px] xl:text-[110px] font-cormorant font-bold tracking-[-2px] leading-none text-white">
                    ruang.
                </h1>
                <p class="mt-10 max-w-xl text-[28px] xl:text-[32px] leading-tight font-light text-slate-300">
                    Gak usah overthinking, tulis aja dulu. 
                    <span class="font-medium text-white">Jelek urusan belakangan,</span> yang penting kelar.
                </p>
                <div class="mt-20 space-y-8">
                    <div class="flex items-center gap-5">
                        <div class="w-12 h-px bg-white/20"></div>
                        <span class="text-slate-400 tracking-wide font-medium">Ngetik Makalah (sks time)</span>
                    </div>
                    <div class="flex items-center gap-5">
                        <div class="w-12 h-px bg-white/20"></div>
                        <span class="text-slate-400 tracking-wide font-medium">Coret-coret Gaje</span>
                    </div>
                    <div class="flex items-center gap-5">
                        <div class="w-12 h-px bg-white/20"></div>
                        <span class="text-slate-400 tracking-wide font-medium">Nanya AI Pas Mentok</span>
                    </div>
                    <div class="flex items-center gap-5">
                        <div class="w-12 h-px bg-white/20"></div>
                        <span class="text-slate-400 tracking-wide font-medium">Muter Playlist Andalan</span>
                    </div>
                </div>
            </section>

            <!-- RIGHT -->
            <section class="relative w-full">
                <!-- MOBILE HERO -->
                <div class="mb-10 lg:hidden text-center sm:text-left">
                    <span class="text-[10px] sm:text-xs uppercase tracking-[.35em] text-amber-300/80 font-semibold">
                        Tempat Nongkrong Ide
                    </span>
                    <h1 class="mt-3 text-4xl sm:text-5xl font-cormorant font-bold tracking-[-1px] text-white">
                        ruang.
                    </h1>
                    <p class="mt-4 text-base sm:text-lg leading-7 sm:leading-8 text-slate-300">
                        Tulis aja dulu, jelek urusan belakangan.
                    </p>
                </div>

                {{ $slot }}

            </section>
        </div>
    </div>
</main>

<!-- Desktop Footer -->
<div class="pointer-events-none fixed bottom-8 left-14 hidden items-center gap-3 lg:flex z-10">
    <div class="h-2 w-2 rounded-full bg-red-600 shadow-[0_0_12px_rgba(220,38,38,.4)]"></div>
    <span class="text-xs uppercase tracking-[0.25em] text-slate-400 font-medium">Udah Siap Dipake</span>
</div>
<div class="pointer-events-none fixed bottom-8 right-14 hidden lg:block z-10">
    <span class="text-xs uppercase tracking-[0.25em] text-slate-400 font-medium">RUANG VER 1.0 (BETA)</span>
</div>

</body>
</html>
