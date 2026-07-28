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
            background: #F7F5F1;
            color: #1F1F1D;
            overflow-x: hidden;
        }

        .font-cormorant {
            font-family: "Cormorant Garamond", serif;
        }

        .vinyl {
            transform: translateY(-50%);
        }

        .glass {
            backdrop-filter: blur(24px);
            -webkit-backdrop-filter: blur(24px);
        }
    </style>
</head>

<body class="text-neutral-900 dark:text-white">

<div class="fixed inset-0 overflow-hidden pointer-events-none">
    <!-- Background -->
    <div class="absolute inset-0 bg-[#F7F5F1]"></div>
    
    <!-- Subtle Gradient blobs for light mode -->
    <div class="absolute inset-0 bg-[radial-gradient(circle_at_15%_30%,rgba(168,85,247,0.03),transparent_35%),radial-gradient(circle_at_100%_100%,rgba(99,102,241,0.03),transparent_40%)]"></div>

    <!-- Vinyl (Light Theme Shadow) -->
    <div class="vinyl absolute -left-[420px] top-1/2 w-[1100px] h-[1100px] opacity-[0.06] blur-sm mix-blend-multiply">
        <div class="relative w-full h-full rounded-full bg-black shadow-[0_0_120px_rgba(0,0,0,0.9)]">
            <div class="absolute inset-[40px] rounded-full border border-neutral-800"></div>
            <div class="absolute inset-[80px] rounded-full border border-neutral-800"></div>
            <div class="absolute inset-[120px] rounded-full border border-neutral-800"></div>
            <div class="absolute inset-[160px] rounded-full border border-neutral-800"></div>
            <div class="absolute inset-[200px] rounded-full border border-neutral-800"></div>
            <div class="absolute inset-[240px] rounded-full border border-neutral-800"></div>
            <div class="absolute inset-[280px] rounded-full border border-neutral-800"></div>
            <div class="absolute left-1/2 top-1/2 w-36 h-36 rounded-full -translate-x-1/2 -translate-y-1/2 bg-neutral-700 border-[14px] border-neutral-900"></div>
        </div>
    </div>
</div>

<main class="relative z-10 min-h-screen flex items-center justify-center">
    <div class="w-full max-w-7xl px-4 sm:px-8 lg:px-14">
        <div class="grid w-full lg:grid-cols-[1.15fr_.85fr] gap-10 lg:gap-20 items-center">
            
            <!-- LEFT -->
            <section class="hidden lg:flex flex-col justify-center">
                <span class="text-xs uppercase tracking-[.35em] text-[#7C756C] font-semibold">
                    Tempat Nongkrong Ide
                </span>
                <h1 class="mt-6 text-[90px] xl:text-[110px] font-cormorant font-bold tracking-[-2px] leading-none text-[#1F1F1D]">
                    ruang.
                </h1>
                <p class="mt-10 max-w-xl text-[28px] xl:text-[32px] leading-tight font-light text-[#4F4A44]">
                    Gak usah overthinking, tulis aja dulu. 
                    <span class="font-medium text-[#1F1F1D]">Jelek urusan belakangan,</span> yang penting kelar.
                </p>
                <div class="mt-20 space-y-8">
                    <div class="flex items-center gap-5">
                        <div class="w-12 h-px bg-[#D6D0C4]"></div>
                        <span class="text-[#7C756C] tracking-wide font-medium">Ngetik Makalah (sks time)</span>
                    </div>
                    <div class="flex items-center gap-5">
                        <div class="w-12 h-px bg-[#D6D0C4]"></div>
                        <span class="text-[#7C756C] tracking-wide font-medium">Coret-coret Gaje</span>
                    </div>
                    <div class="flex items-center gap-5">
                        <div class="w-12 h-px bg-[#D6D0C4]"></div>
                        <span class="text-[#7C756C] tracking-wide font-medium">Nanya AI Pas Mentok</span>
                    </div>
                    <div class="flex items-center gap-5">
                        <div class="w-12 h-px bg-[#D6D0C4]"></div>
                        <span class="text-[#7C756C] tracking-wide font-medium">Muter Playlist Andalan</span>
                    </div>
                </div>
            </section>

            <!-- RIGHT -->
            <section class="relative w-full">
                <div class="glass rounded-[32px] sm:rounded-[40px] border border-white/40 bg-white/40 p-8 sm:p-12 shadow-[0_20px_80px_rgba(0,0,0,0.03)]">
                    
                    <!-- MOBILE HERO -->
                    <div class="mb-10 lg:hidden text-center sm:text-left">
                        <span class="text-[10px] sm:text-xs uppercase tracking-[.35em] text-[#7C756C] font-semibold">
                            Tempat Nongkrong Ide
                        </span>
                        <h1 class="mt-3 text-4xl sm:text-5xl font-cormorant font-bold tracking-[-1px] text-[#1F1F1D]">
                            ruang.
                        </h1>
                        <p class="mt-4 text-base sm:text-lg leading-7 sm:leading-8 text-[#4F4A44]">
                            Tulis aja dulu, jelek urusan belakangan.
                        </p>
                    </div>

                    {{ $slot }}

                </div>
            </section>
        </div>
    </div>
</main>

<!-- Desktop Footer -->
<div class="pointer-events-none fixed bottom-8 left-14 hidden items-center gap-3 lg:flex z-10">
    <div class="h-2 w-2 rounded-full bg-emerald-500 shadow-[0_0_12px_rgba(16,185,129,.4)]"></div>
    <span class="text-xs uppercase tracking-[0.25em] text-[#7C756C] font-medium">Udah Siap Dipake</span>
</div>
<div class="pointer-events-none fixed bottom-8 right-14 hidden lg:block z-10">
    <span class="text-xs uppercase tracking-[0.25em] text-[#7C756C] font-medium">RUANG VER 1.0 (BETA)</span>
</div>

</body>
</html>

