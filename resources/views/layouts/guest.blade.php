<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">
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
            background: #000000;
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

<body class="text-white bg-black">

<div class="fixed inset-0 overflow-hidden pointer-events-none">
    <!-- Background: Pure OLED Black -->
    <div class="absolute inset-0 bg-black"></div>
    
    <!-- Subtle Neutral Ambient Glow -->
    <div class="absolute inset-0 bg-[radial-gradient(circle_at_50%_40%,rgba(255,255,255,0.03),transparent_70%)]"></div>

    <!-- Noel Gallagher's Guitar Background (single clean artwork) -->
    <div class="absolute -left-32 sm:-left-64 top-1/2 -translate-y-1/2 w-[600px] sm:w-[900px] opacity-[0.14] pointer-events-none">
        <img src="{{ asset('images/guitar.png') }}" alt="Guitar Background" class="w-full h-auto object-contain -rotate-[10deg]">
    </div>
</div>

<main class="relative z-10 min-h-screen flex items-center justify-center">
    <div class="w-full max-w-7xl px-4 sm:px-8 lg:px-14">
        <div class="grid w-full lg:grid-cols-[1.15fr_.85fr] gap-10 lg:gap-20 items-center">
            
            <!-- LEFT -->
            <section class="hidden lg:flex flex-col justify-center">
                <h1 class="text-[90px] xl:text-[110px] font-cormorant font-bold tracking-[-2px] leading-none text-white">
                    ruang.
                </h1>
                <p class="mt-8 max-w-xl text-[28px] xl:text-[32px] leading-tight font-light text-slate-300">
                    Tulis aja dulu. 
                    <span class="font-medium text-white">Jelek urusan belakangan,</span> yang penting kelar.
                </p>
                <div class="mt-16 space-y-7">
                    <div class="flex items-center gap-5">
                        <div class="w-10 h-px bg-white/20"></div>
                        <span class="text-slate-400 text-sm tracking-wide font-medium">Ngetik Makalah</span>
                    </div>
                    <div class="flex items-center gap-5">
                        <div class="w-10 h-px bg-white/20"></div>
                        <span class="text-slate-400 text-sm tracking-wide font-medium">Coret-coret Ide</span>
                    </div>
                    <div class="flex items-center gap-5">
                        <div class="w-10 h-px bg-white/20"></div>
                        <span class="text-slate-400 text-sm tracking-wide font-medium">Nanya AI Pas Mentok</span>
                    </div>
                    <div class="flex items-center gap-5">
                        <div class="w-10 h-px bg-white/20"></div>
                        <span class="text-slate-400 text-sm tracking-wide font-medium">Muter Playlist Andalan</span>
                    </div>
                </div>
            </section>

            <!-- RIGHT -->
            <section class="relative w-full">
                <!-- MOBILE HERO -->
                <div class="mb-8 lg:hidden text-center sm:text-left">
                    <h1 class="text-4xl sm:text-5xl font-cormorant font-bold tracking-[-1px] text-white">
                        ruang.
                    </h1>
                    <p class="mt-3 text-base sm:text-lg leading-relaxed text-slate-300">
                        Tulis aja dulu, jelek urusan belakangan.
                    </p>
                </div>

                {{ $slot }}

            </section>
        </div>
    </div>
</main>

</body>
</html>
