<x-guest-layout>
    <div class="relative mx-auto w-full max-w-md animate-fadeIn">
        <div class="ios-liquid-card relative overflow-hidden rounded-[28px] p-7 sm:p-9">
            
            <div class="mb-8">
                <div class="inline-flex items-center gap-2 rounded-full border border-white/15 bg-white/[0.04] px-3.5 py-1.5 backdrop-blur-xl mb-5 shadow-sm">
                    <span class="h-1.5 w-1.5 rounded-full bg-emerald-400"></span>
                    <span class="text-[11px] font-medium tracking-wider text-slate-300">Verifikasi Akun</span>
                </div>

                <h1 class="text-3xl sm:text-4xl font-normal font-serif text-white tracking-tight leading-tight">
                    Verifikasi Email.
                </h1>

                <p class="mt-2 text-[13px] sm:text-sm text-slate-400 leading-relaxed">
                    Cek email kamu sebentar, ada tautan verifikasi yang baru saja kami kirim.
                </p>
            </div>

            @if (session('status') == 'verification-link-sent')
                <div class="mb-6 rounded-xl border border-emerald-500/30 bg-emerald-500/10 p-3.5 text-xs text-emerald-300 backdrop-blur-md">
                    Tautan verifikasi baru sudah meluncur ke email kamu.
                </div>
            @endif

            <div class="space-y-4">
                <form method="POST" action="{{ route('verification.send') }}">
                    @csrf
                    <button type="submit" class="ios-liquid-btn w-full h-12 text-white font-semibold text-sm flex items-center justify-center gap-2 cursor-pointer">
                        <span>Kirim Ulang Email Verifikasi</span>
                        <i class="ph-bold ph-paper-plane-tilt text-sm"></i>
                    </button>
                </form>

                <form method="POST" action="{{ route('logout') }}" class="text-center pt-2">
                    @csrf
                    <button type="submit" class="text-xs text-slate-400 hover:text-white transition-colors py-2 px-4 rounded-xl active:scale-95 inline-block">
                        Keluar (Log Out)
                    </button>
                </form>
            </div>
        </div>
    </div>
</x-guest-layout>
