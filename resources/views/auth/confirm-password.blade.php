<x-guest-layout>
    <div class="relative mx-auto w-full max-w-md animate-fadeIn">
        <div class="ios-liquid-card relative overflow-hidden rounded-[28px] p-7 sm:p-9">
            
            <div class="mb-8">
                <div class="inline-flex items-center gap-2 rounded-full border border-white/15 bg-white/[0.04] px-3.5 py-1.5 backdrop-blur-xl mb-5 shadow-sm">
                    <span class="h-1.5 w-1.5 rounded-full bg-amber-400"></span>
                    <span class="text-[11px] font-medium tracking-wider text-slate-300">Keamanan</span>
                </div>

                <h1 class="text-3xl sm:text-4xl font-normal font-serif text-white tracking-tight leading-tight">
                    Konfirmasi Sandi.
                </h1>

                <p class="mt-2 text-[13px] sm:text-sm text-slate-400 leading-relaxed">
                    Konfirmasi dulu kata sandimu sebelum lanjut.
                </p>
            </div>

            <form method="POST" action="{{ route('password.confirm') }}" class="space-y-5">
                @csrf

                <!-- Password -->
                <div x-data="{ show: false }">
                    <label for="password" class="block text-xs font-medium text-slate-300 mb-1.5">
                        Kata Sandi
                    </label>
                    <div class="relative flex items-center">
                        <input id="password" class="ios-liquid-input w-full h-12 rounded-xl px-4 pr-12 text-sm text-white placeholder:text-slate-500 outline-none"
                               :type="show ? 'text' : 'password'"
                               name="password"
                               placeholder="Masukkan kata sandi"
                               required autocomplete="current-password" />
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

                <div class="pt-2">
                    <button type="submit" class="ios-liquid-btn w-full h-12 text-white font-semibold text-sm flex items-center justify-center gap-2 cursor-pointer">
                        <span>Konfirmasi</span>
                        <i class="ph-bold ph-arrow-right text-sm"></i>
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-guest-layout>
