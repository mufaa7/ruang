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

            {{-- Badge --}}
            <div class="inline-flex items-center gap-2 rounded-full
                        border border-white/20
                        bg-white/[0.025]
                        px-4 py-2
                        backdrop-blur-md">

                <span class="h-2 w-2 rounded-full bg-red-500
                             shadow-[0_0_10px_rgba(239,68,68,.8)]"></span>

                <span class="text-[10px] font-semibold uppercase
                             tracking-[.25em] text-amber-200/75">
                    Kembali Pulang
                </span>

            </div>


            {{-- Heading --}}
            <div class="mt-8">

                <h1 class="font-cormorant text-5xl font-bold
                           leading-[.9] tracking-tight text-white">
                    Hello,
                    <span class="block text-white/55">Hello, Hello!</span>
                </h1>

                <p class="mt-5 text-[15px] leading-7 text-white/45">
                    Lanjutkan dari tempat terakhir.
                    Ruangmu masih sama seperti saat kamu meninggalkannya.
                </p>

            </div>


            {{-- FORM --}}
            <form method="POST" action="{{ route('login') }}" class="mt-9 space-y-6">
                @csrf


                {{-- EMAIL ATAU USERNAME --}}
                <div>

                    <label
                        for="login"
                        class="mb-3 block text-xs font-semibold
                               uppercase tracking-[.2em] text-white/40">
                        Email atau Username
                    </label>

                    <div class="relative">

                        <input
                            id="login"
                            type="text"
                            name="login"
                            value="{{ old('login') }}"
                            required
                            autofocus
                            autocomplete="username"
                            placeholder="champagne@supernova.com"
                            class="h-14 w-full rounded-[19px]
                                   border border-white/20

                                   /* INI KUNCI */
                                   bg-white/[0.008]
                                   backdrop-blur-[10px]
                                   backdrop-saturate-[170%]

                                   px-5 text-white
                                   placeholder:text-white/25
                                   outline-none

                                   shadow-[inset_0_1px_0_rgba(255,255,255,.22),
                                           inset_0_-1px_0_rgba(255,255,255,.06)]

                                   transition-all duration-300

                                   hover:border-white/30

                                   focus:border-white/40
                                   focus:bg-white/[0.015]
                                   focus:ring-0"
                        />

                        <x-input-error :messages="$errors->get('login')" class="mt-2 text-amber-400" />
                    </div>
                </div>


                {{-- PASSWORD --}}
                <div x-data="{ show: false }">

                    <div class="mb-3 flex items-center justify-between">

                        <label
                            for="password"
                            class="text-xs font-semibold uppercase
                                   tracking-[.2em] text-white/40">
                            Kata Sandi
                        </label>

                        @if (Route::has('password.request'))
                            <a
                                href="{{ route('password.request') }}"
                                class="text-xs text-white/35
                                       transition hover:text-white">
                                Lupa password?
                            </a>
                        @endif

                    </div>


                    <div class="relative">

                        <input
                            id="password"
                            :type="show ? 'text' : 'password'"
                            name="password"
                            required
                            autocomplete="current-password"
                            placeholder="Masukkan password"
                            class="h-14 w-full rounded-[19px]
                                   border border-white/20

                                   bg-white/[0.008]
                                   backdrop-blur-[10px]
                                   backdrop-saturate-[170%]

                                   px-5 pr-14 text-white
                                   placeholder:text-white/25
                                   outline-none

                                   shadow-[inset_0_1px_0_rgba(255,255,255,.22),
                                           inset_0_-1px_0_rgba(255,255,255,.06)]

                                   transition-all duration-300

                                   hover:border-white/30

                                   focus:border-white/40
                                   focus:bg-white/[0.015]
                                   focus:ring-0"
                        />




                        <button
                            type="button"
                            @click="show = !show"
                            class="absolute inset-y-0 right-4
                                   flex items-center
                                   text-white/30
                                   hover:text-white/80"
                        >

                            <svg
                                x-show="!show"
                                xmlns="http://www.w3.org/2000/svg"
                                fill="none"
                                viewBox="0 0 24 24"
                                stroke-width="1.7"
                                stroke="currentColor"
                                class="h-5 w-5"
                            >
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    d="M2.458 12C3.732 7.943 7.523 5 12 5c4.477 0 8.268 2.943 9.542 7-1.274 4.057-5.065 7-9.542 7-4.477 0-8.268-2.943-9.542-7Z"
                                />
                                <circle cx="12" cy="12" r="3"/>
                            </svg>

                            <svg
                                x-show="show"
                                x-cloak
                                xmlns="http://www.w3.org/2000/svg"
                                fill="none"
                                viewBox="0 0 24 24"
                                stroke-width="1.7"
                                stroke="currentColor"
                                class="h-5 w-5"
                            >
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    d="M3 3l18 18"
                                />
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    d="M10.58 10.58A2 2 0 0013.42 13.42"
                                />
                            </svg>

                        </button>

                    </div>
                    
                    <x-input-error :messages="$errors->get('password')" class="mt-2 text-amber-400" />
                </div>


                {{-- REMEMBER --}}
                <div class="flex items-center justify-between pt-1">

                    <label class="inline-flex cursor-pointer items-center gap-3">

                        <input
                            type="checkbox"
                            name="remember"
                            class="h-4 w-4 rounded-md
                                   border-white/20
                                   bg-white/[0.02]
                                   text-white
                                   focus:ring-0"
                        >

                        <span class="text-sm text-white/40">
                            Tetap masuk
                        </span>

                    </label>

                    <span class="text-[10px] uppercase
                                 tracking-[.2em] text-white/25">
                        Aman & Privat
                    </span>

                </div>


                {{-- BUTTON --}}
                <button
                    type="submit"
                    class="group relative flex h-14 w-full
                           items-center justify-center
                           overflow-hidden rounded-[19px]

                           border border-white/30
                           bg-white/[0.07]

                           backdrop-blur-[15px]
                           backdrop-saturate-[180%]

                           text-white font-semibold

                           shadow-[inset_0_1px_0_rgba(255,255,255,.35),
                                   inset_0_-1px_0_rgba(255,255,255,.08),
                                   0_15px_40px_rgba(0,0,0,.18)]

                           transition-all duration-300

                           hover:bg-white/[0.11]
                           hover:border-white/40
                           hover:-translate-y-[1px]"
                >

                    {{-- reflection --}}
                    <span
                        class="absolute inset-y-0 -left-1/2 w-1/3
                               skew-x-[-20deg]
                               bg-gradient-to-r
                               from-transparent
                               via-white/20
                               to-transparent
                               transition-all duration-700
                               group-hover:left-[120%]"
                    ></span>

                    <span class="relative flex items-center gap-3">
                        Buka Ruang

                        <svg
                            xmlns="http://www.w3.org/2000/svg"
                            class="h-5 w-5 transition-transform
                                   group-hover:translate-x-1"
                            fill="none"
                            viewBox="0 0 24 24"
                            stroke="currentColor"
                            stroke-width="1.8"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                d="M5 12h14m-5-5 5 5-5 5"
                            />
                        </svg>
                    </span>

                </button>


                {{-- DIVIDER --}}
                <div class="relative py-3">

                    <div class="absolute inset-0 flex items-center">
                        <div class="w-full border-t border-white/10"></div>
                    </div>

                    <div class="relative flex justify-center">
                        <span
                            class="border border-white/15
                                   bg-transparent
                                   px-4 py-1
                                   text-[10px]
                                   uppercase
                                   tracking-[.25em]
                                   text-white/25
                                   backdrop-blur-md"
                        >
                            atau
                        </span>
                    </div>

                </div>


                {{-- REGISTER --}}
                @if (Route::has('register'))
                    <div class="text-center">

                        <p class="text-sm text-white/35">
                            Belum punya tempat?

                            <a
                                href="{{ route('register') }}"
                                class="ml-1 font-semibold
                                       text-white/70
                                       hover:text-white
                                       hover:underline
                                       underline-offset-4"
                            >
                                Buka Ruang Baru
                            </a>
                        </p>

                    </div>
                @endif

            </form>


            {{-- Footer --}}
            <div class="mt-8 border-t border-white/10 pt-5">

                <div class="flex items-center justify-between
                            text-[10px] font-semibold
                            uppercase tracking-[.22em]">

                    <span class="text-white/20">Build 0.1</span>
                    <span class="text-white/20">RUANG</span>

                </div>

            </div>

        </div>
    </div>

</div>

</x-guest-layout>