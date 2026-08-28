<x-guest-layout>

    <div class="relative mx-auto w-full max-w-md animate-fadeIn">

        {{-- iOS 17/18 Liquid Frosted Glass Card --}}
        <div class="ios-liquid-card relative overflow-hidden rounded-[28px] p-7 sm:p-9">
            
            {{-- Header --}}
            <div class="mb-8">
                <div class="inline-flex items-center gap-2 rounded-full border border-white/15 bg-white/[0.04] px-3.5 py-1.5 backdrop-blur-xl mb-5 shadow-sm">
                    <span class="h-1.5 w-1.5 rounded-full bg-amber-400"></span>
                    <span class="text-[11px] font-medium tracking-wider text-slate-300">Kembali Pulang</span>
                </div>

                <h1 class="text-4xl sm:text-5xl font-normal font-serif text-white tracking-tight leading-[1.05]">
                    Hello,
                    <span class="block text-slate-400">Hello, Hello!</span>
                </h1>

                <p class="mt-4 text-[13px] sm:text-sm text-slate-400 leading-relaxed">
                    Lanjutkan, Ruang masih sama seperti saat terakhir kamu tinggalkan.
                </p>
            </div>

            {{-- FORM --}}
            <form method="POST" action="{{ route('login') }}" class="space-y-5">
                @csrf

                {{-- EMAIL ATAU USERNAME --}}
                <div>
                    <label for="login" class="block text-xs font-medium text-slate-300 mb-1.5">
                        Email atau Username
                    </label>

                    <input
                        id="login"
                        type="text"
                        name="login"
                        value="{{ old('login') }}"
                        required
                        autofocus
                        autocomplete="username"
                        placeholder="champagne@supernova.com"
                        class="ios-liquid-input w-full h-12 rounded-xl px-4 text-sm text-white placeholder:text-slate-500 outline-none"
                    />

                    <x-input-error :messages="$errors->get('login')" class="mt-2 text-xs text-amber-400" />
                </div>

                {{-- PASSWORD --}}
                <div x-data="{ show: false }">
                    <div class="flex items-center justify-between mb-1.5">
                        <label for="password" class="block text-xs font-medium text-slate-300">
                            Kata Sandi
                        </label>

                        @if (Route::has('password.request'))
                            <a href="{{ route('password.request') }}" class="text-xs text-slate-400 hover:text-amber-300 transition-colors">
                                Lupa password?
                            </a>
                        @endif
                    </div>

                    <div class="relative flex items-center">
                        <input
                            id="password"
                            :type="show ? 'text' : 'password'"
                            name="password"
                            required
                            autocomplete="current-password"
                            placeholder="Masukkan password"
                            class="ios-liquid-input w-full h-12 rounded-xl px-4 pr-12 text-sm text-white placeholder:text-slate-500 outline-none"
                        />

                        <button
                            type="button"
                            @click="show = !show"
                            class="absolute right-2 w-8 h-8 rounded-lg bg-white/5 hover:bg-white/10 border border-white/10 flex items-center justify-center transition-all active:scale-95 cursor-pointer"
                            :class="show ? 'text-amber-300 border-amber-400/30 bg-amber-400/10 shadow-[0_0_10px_rgba(251,191,36,0.15)]' : 'text-slate-400 hover:text-white'"
                            :title="show ? 'Sembunyikan kata sandi' : 'Tampilkan kata sandi'"
                        >
                            <i :class="show ? 'ph ph-eye' : 'ph ph-eye-slash'" class="text-[17px]"></i>
                        </button>
                    </div>

                    <x-input-error :messages="$errors->get('password')" class="mt-2 text-xs text-amber-400" />
                </div>

                {{-- REMEMBER ME --}}
                <div class="flex items-center justify-between pt-1">
                    <label class="inline-flex items-center gap-2.5 cursor-pointer">
                        <input
                            type="checkbox"
                            name="remember"
                            class="h-4 w-4 rounded-md border-white/20 bg-white/5 text-amber-400 focus:ring-0 focus:ring-offset-0"
                        >
                        <span class="text-xs text-slate-400">
                            Tetap masuk
                        </span>
                    </label>
                </div>

                {{-- SUBMIT BUTTON (Authentic Apple iOS Liquid Glass) --}}
                <div class="pt-2">
                    <button
                        type="submit"
                        class="ios-liquid-btn w-full h-12 text-white font-semibold text-sm flex items-center justify-center gap-2 cursor-pointer"
                    >
                        <span>Buka Ruang</span>
                        <i class="ph-bold ph-arrow-right text-sm"></i>
                    </button>
                </div>

                {{-- REGISTER LINK --}}
                @if (Route::has('register'))
                    <div class="pt-4 border-t border-white/[0.08] text-center">
                        <p class="text-xs text-slate-400">
                            Belum punya tempat?
                            <a href="{{ route('register') }}" class="font-semibold text-amber-300 hover:text-amber-200 transition-colors ml-1">
                                Buka Ruang Baru
                            </a>
                        </p>
                    </div>
                @endif

            </form>

        </div>

    </div>

</x-guest-layout>