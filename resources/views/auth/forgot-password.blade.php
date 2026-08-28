<x-guest-layout>

    <div class="relative mx-auto w-full max-w-md animate-fadeIn">

        {{-- iOS 17/18 Liquid Frosted Glass Card --}}
        <div class="ios-liquid-card relative overflow-hidden rounded-[28px] p-7 sm:p-9">
            
            {{-- Header --}}
            <div class="mb-8">
                <div class="inline-flex items-center gap-2 rounded-full border border-white/15 bg-white/[0.04] px-3.5 py-1.5 backdrop-blur-xl mb-5 shadow-sm">
                    <span class="h-1.5 w-1.5 rounded-full bg-amber-400"></span>
                    <span class="text-[11px] font-medium tracking-wider text-slate-300">Tenang, Nggak Apa-apa</span>
                </div>

                <h1 class="text-3xl sm:text-4xl font-normal font-serif text-white tracking-tight leading-tight">
                    Lupa kunci masuk?
                </h1>

                <p class="mt-2 text-[13px] sm:text-sm text-slate-400 leading-relaxed">
                    Tulis email kamu di bawah, nanti kami kirimkan tautan buat buka pintu Ruang lagi.
                </p>
            </div>

            <!-- Session Status -->
            <x-auth-session-status class="mb-4 rounded-xl border border-emerald-500/30 bg-emerald-500/10 p-3.5 text-xs text-emerald-300 backdrop-blur-md" :status="session('status')" />

            {{-- FORM --}}
            <form method="POST" action="{{ route('password.email') }}" class="space-y-5">
                @csrf

                <!-- Email Address -->
                <div>
                    <label for="email" class="block text-xs font-medium text-slate-300 mb-1.5">
                        Alamat Email
                    </label>
                    <input
                        id="email"
                        type="email"
                        name="email"
                        value="{{ old('email') }}"
                        required
                        autofocus
                        placeholder="champagne@supernova.com"
                        class="ios-liquid-input w-full h-12 rounded-xl px-4 text-base sm:text-sm text-white placeholder:text-slate-500 outline-none"
                    />
                    <x-input-error :messages="$errors->get('email')" class="mt-2 text-xs text-amber-400" />
                </div>

                {{-- SUBMIT BUTTON --}}
                <div class="pt-2">
                    <button
                        type="submit"
                        class="ios-liquid-btn w-full h-12 text-white font-semibold text-sm flex items-center justify-center gap-2 cursor-pointer"
                    >
                        <span>Kirim Tautan Masuk</span>
                        <i class="ph-bold ph-paper-plane-tilt text-sm"></i>
                    </button>
                </div>

                {{-- BACK TO LOGIN --}}
                <div class="pt-4 border-t border-white/[0.08] text-center">
                    <p class="text-xs text-slate-400">
                        Udah inget kuncinya?
                        <a href="{{ route('login') }}" class="font-semibold text-amber-300 hover:text-amber-200 transition-colors ml-1">
                            Kembali Pulang
                        </a>
                    </p>
                </div>

            </form>

        </div>

    </div>

</x-guest-layout>
