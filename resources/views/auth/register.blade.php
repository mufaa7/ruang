<x-guest-layout>

    <div class="relative mx-auto w-full max-w-md">



        {{-- ACTUAL CLEAR GLASS --}}
        <div
            class="relative overflow-hidden rounded-[32px]
                   border border-white/20
                   bg-gradient-to-br from-white/[0.05] via-transparent to-transparent
                   backdrop-blur-[6px]
                   backdrop-saturate-[120%]
                   shadow-[0_30px_80px_rgba(0,0,0,0.4),
                           inset_0_1px_0_rgba(255,255,255,0.3),
                           inset_1px_0_0_rgba(255,255,255,0.1),
                           inset_-1px_0_0_rgba(255,255,255,0.05),
                           inset_0_-1px_0_rgba(255,255,255,0.1)]"
        >

            {{-- Subtle surface glare (Clear glass reflection) --}}
            <div class="pointer-events-none absolute inset-0 bg-gradient-to-tr from-transparent via-white/[0.04] to-transparent"></div>

            <div class="relative z-10 p-8">
                <div class="inline-flex items-center gap-2 rounded-full border border-white/20 bg-white/[0.025] px-4 py-2 backdrop-blur-md">
                    <span class="h-2 w-2 rounded-full bg-red-500 shadow-[0_0_10px_rgba(239,68,68,.8)]"></span>
                    <span class="text-[10px] font-semibold uppercase tracking-[0.25em] text-amber-200/75">
                        Ruang Baru
                    </span>
                </div>

                <!-- Heading -->
                <div class="mt-8">
                    <h1 class="font-cormorant text-5xl font-bold leading-[.9] tracking-tight text-white">
                        Bangun <span class="block text-white/55">Ruangmu.</span>
                    </h1>
                    <p class="mt-5 text-[15px] leading-7 text-white/45">
                        Amankan setiap ide dan catatanmu, biarkan mereka tumbuh di sini.
                    </p>
                </div>

        <!-- Validation Errors -->
        @if ($errors->any())
            <div class="mt-6 rounded-2xl border border-red-200 bg-red-50 p-4">
                <ul class="space-y-2 text-sm text-red-600">
                    @foreach ($errors->all() as $error)
                        <li>• {{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('register') }}" class="mt-10 space-y-7">
            @csrf

            <!-- Name -->
            <div>
                <label for="name" class="mb-3 block text-sm font-medium text-[#94a3b8]">
                    Nama Kamu
                </label>
                <input id="name" type="text" name="name" value="{{ old('name') }}" required autofocus autocomplete="name" placeholder="Nama yang ingin dipanggil" class="h-14 w-full rounded-2xl border border-white/10 bg-white/5 px-5 text-white placeholder:text-slate-400 outline-none transition duration-300 focus:border-white/30 focus:bg-white/10 focus:ring-0 shadow-sm" />
            </div>

            <!-- Email Address -->
            <div>
                <label for="email" class="mb-3 block text-sm font-medium text-[#94a3b8]">
                    Alamat Email
                </label>
                <input id="email" type="email" name="email" value="{{ old('email') }}" required autocomplete="username" placeholder="champagne@supernova.com" class="h-14 w-full rounded-2xl border border-white/10 bg-white/5 px-5 text-white placeholder:text-slate-400 outline-none transition duration-300 focus:border-white/30 focus:bg-white/10 focus:ring-0 shadow-sm" />
            </div>

            <!-- Password -->
            <div x-data="{ show: false }">
                <label for="password" class="mb-3 block text-sm font-medium text-[#94a3b8]">
                    Kata Sandi
                </label>
                <div class="relative">
                    <input id="password" :type="show ? 'text' : 'password'" name="password" required autocomplete="new-password" placeholder="Rahasiakan sandimu" class="h-14 w-full rounded-2xl border border-white/10 bg-white/5 px-5 pr-14 text-white placeholder:text-slate-400 outline-none transition duration-300 focus:border-white/30 focus:bg-white/10 focus:ring-0 shadow-sm" />
                    <button type="button" @click="show = !show" class="absolute inset-y-0 right-4 flex items-center text-[#94a3b8] transition hover:text-white">
                        <svg x-show="!show" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor" class="h-5 w-5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.477 0 8.268 2.943 9.542 7-1.274 4.057-5.065 7-9.542 7-4.477 0-8.268-2.943-9.542-7Z" />
                            <circle cx="12" cy="12" r="3" />
                        </svg>
                        <svg x-show="show" x-cloak xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor" class="h-5 w-5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3 3l18 18" />
                            <path stroke-linecap="round" stroke-linejoin="round" d="M10.58 10.58A2 2 0 0013.42 13.42" />
                        </svg>
                    </button>
                </div>
            </div>

            <!-- Confirm Password -->
            <div x-data="{ show: false }">
                <label for="password_confirmation" class="mb-3 block text-sm font-medium text-[#94a3b8]">
                    Ulangi Kata Sandi
                </label>
                <div class="relative">
                    <input id="password_confirmation" :type="show ? 'text' : 'password'" name="password_confirmation" required autocomplete="new-password" placeholder="Ketik sekali lagi" class="h-14 w-full rounded-2xl border border-white/10 bg-white/5 px-5 pr-14 text-white placeholder:text-slate-400 outline-none transition duration-300 focus:border-white/30 focus:bg-white/10 focus:ring-0 shadow-sm" />
                    <button type="button" @click="show = !show" class="absolute inset-y-0 right-4 flex items-center text-[#94a3b8] transition hover:text-white">
                        <svg x-show="!show" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor" class="h-5 w-5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.477 0 8.268 2.943 9.542 7-1.274 4.057-5.065 7-9.542 7-4.477 0-8.268-2.943-9.542-7Z" />
                            <circle cx="12" cy="12" r="3" />
                        </svg>
                        <svg x-show="show" x-cloak xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor" class="h-5 w-5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3 3l18 18" />
                            <path stroke-linecap="round" stroke-linejoin="round" d="M10.58 10.58A2 2 0 0013.42 13.42" />
                        </svg>
                    </button>
                </div>
            </div>

            <!-- Button -->
            <button type="submit" class="group relative flex h-14 w-full items-center justify-center overflow-hidden rounded-2xl bg-white/10 font-semibold text-white transition duration-300 hover:scale-[1.02] hover:bg-white/20 shadow-[0_0_20px_rgba(255,255,255,0.05)] border border-white/10">
                <span class="absolute inset-0 -translate-x-full bg-gradient-to-r from-transparent via-white/10 to-transparent transition duration-700 group-hover:translate-x-full"></span>
                <span class="relative flex items-center gap-2">
                    Mulai Menulis
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 transition group-hover:translate-x-1" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M5 12h14m-5-5 5 5-5 5" />
                    </svg>
                </span>
            </button>

            <!-- Divider -->
            <div class="relative py-3">
                <div class="absolute inset-0 flex items-center">
                    <div class="w-full border-t border-white/10"></div>
                </div>
                <div class="relative flex justify-center">
                    <span class="border border-white/15 bg-transparent px-4 py-1 text-[10px] uppercase tracking-[.25em] text-white/25 backdrop-blur-md">atau</span>
                </div>
            </div>

            <!-- Login -->
            <div class="text-center">
                <p class="text-sm text-white/35">
                    Sudah punya tempat? 
                    <a href="{{ route('login') }}" class="ml-1 font-semibold text-white/70 hover:text-white transition duration-300 hover:underline underline-offset-4">
                        Kembali Pulang
                    </a>
                </p>
            </div>
        </form>

        {{-- Footer --}}
        <div class="mt-8 border-t border-white/10 pt-5">
            <div class="flex items-center justify-between text-[10px] font-semibold uppercase tracking-[.22em]">
                <span class="text-white/20">Build 0.1</span>
                <span class="text-white/20">RUANG</span>
            </div>
        </div>

        </div> <!-- End z-10 p-8 -->
        </div> <!-- End ACTUAL GLASS -->
    </div> <!-- End relative mx-auto -->

</x-guest-layout>
