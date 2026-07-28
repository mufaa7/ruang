<x-guest-layout>

    <div class="w-full max-w-md mx-auto">

        <!-- Badge -->
        <div class="inline-flex items-center gap-2 rounded-full border border-black/5 bg-black/5 px-4 py-2 shadow-sm">
            <div class="h-2 w-2 rounded-full bg-emerald-500"></div>
            <span class="text-xs uppercase tracking-[0.25em] text-[#7C756C] font-semibold">
                Warga Baru
            </span>
        </div>

        <!-- Heading -->
        <div class="mt-8">
            <h1 class="text-4xl font-cormorant font-bold tracking-tight text-[#1F1F1D]">
                Daftar Dulu Sini
            </h1>
            <p class="mt-4 leading-7 text-[#4F4A44]">
                Bikin akun bentar doang kok, biar ide sama tulisan lu aman kesimpen.
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
                <label for="name" class="mb-3 block text-sm font-medium text-[#7C756C]">
                    Nama Asli (boleh disingkat)
                </label>
                <input id="name" type="text" name="name" value="{{ old('name') }}" required autofocus autocomplete="name" placeholder="Siapa namamu nak?" class="h-14 w-full rounded-2xl border border-[#D6D0C4] bg-white/60 px-5 text-[#1F1F1D] placeholder:text-[#A39D93] outline-none transition duration-300 focus:border-[#1F1F1D] focus:bg-white focus:ring-0 shadow-sm" />
            </div>

            <!-- Email Address -->
            <div>
                <label for="email" class="mb-3 block text-sm font-medium text-[#7C756C]">
                    Email (yang bisa dihubungin)
                </label>
                <input id="email" type="email" name="email" value="{{ old('email') }}" required autocomplete="username" placeholder="nama@email.com" class="h-14 w-full rounded-2xl border border-[#D6D0C4] bg-white/60 px-5 text-[#1F1F1D] placeholder:text-[#A39D93] outline-none transition duration-300 focus:border-[#1F1F1D] focus:bg-white focus:ring-0 shadow-sm" />
            </div>

            <!-- Password -->
            <div x-data="{ show: false }">
                <label for="password" class="mb-3 block text-sm font-medium text-[#7C756C]">
                    Password super rahasia
                </label>
                <div class="relative">
                    <input id="password" :type="show ? 'text' : 'password'" name="password" required autocomplete="new-password" placeholder="Bikin password" class="h-14 w-full rounded-2xl border border-[#D6D0C4] bg-white/60 px-5 pr-14 text-[#1F1F1D] placeholder:text-[#A39D93] outline-none transition duration-300 focus:border-[#1F1F1D] focus:bg-white focus:ring-0 shadow-sm" />
                    <button type="button" @click="show = !show" class="absolute inset-y-0 right-4 flex items-center text-[#7C756C] transition hover:text-[#1F1F1D]">
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
                <label for="password_confirmation" class="mb-3 block text-sm font-medium text-[#7C756C]">
                    Ketik ulang passwordnya
                </label>
                <div class="relative">
                    <input id="password_confirmation" :type="show ? 'text' : 'password'" name="password_confirmation" required autocomplete="new-password" placeholder="Biar gak lupa" class="h-14 w-full rounded-2xl border border-[#D6D0C4] bg-white/60 px-5 pr-14 text-[#1F1F1D] placeholder:text-[#A39D93] outline-none transition duration-300 focus:border-[#1F1F1D] focus:bg-white focus:ring-0 shadow-sm" />
                    <button type="button" @click="show = !show" class="absolute inset-y-0 right-4 flex items-center text-[#7C756C] transition hover:text-[#1F1F1D]">
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
            <button type="submit" class="group relative flex h-14 w-full items-center justify-center overflow-hidden rounded-2xl bg-[#1F1F1D] font-semibold text-[#F7F5F1] transition duration-300 hover:scale-[1.02] shadow-lg hover:shadow-xl">
                <span class="absolute inset-0 -translate-x-full bg-gradient-to-r from-transparent via-white/10 to-transparent transition duration-700 group-hover:translate-x-full"></span>
                <span class="relative flex items-center gap-2">
                    Gas Daftar!
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 transition group-hover:translate-x-1" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M5 12h14m-5-5 5 5-5 5" />
                    </svg>
                </span>
            </button>

            <!-- Divider -->
            <div class="relative py-2">
                <div class="absolute inset-0 flex items-center">
                    <div class="w-full border-t border-[#D6D0C4]"></div>
                </div>
                <div class="relative flex justify-center">
                    <span class="bg-[#F7F5F1] px-4 text-xs uppercase tracking-[.25em] text-[#7C756C] font-semibold">atau</span>
                </div>
            </div>

            <!-- Login -->
            <div class="text-center">
                <p class="text-sm text-[#7C756C]">
                    Udah punya akun?
                    <a href="{{ route('login') }}" class="font-semibold text-[#1F1F1D] transition duration-300 hover:text-[#4F4A44] hover:underline underline-offset-4">
                        Masuk aja langsung
                    </a>
                </p>
            </div>
        </form>

        <!-- Footer -->
        <div class="mt-10 border-t border-[#D6D0C4] pt-6">
            <div class="flex items-center justify-between text-xs tracking-[.25em] uppercase font-semibold">
                <span class="text-[#A39D93]">Build 1.0</span>
                <span class="text-[#A39D93]">RUANG</span>
            </div>
        </div>

    </div>

</x-guest-layout>
