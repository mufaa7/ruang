<x-guest-layout>
    <div class="space-y-8">
        <div class="text-center">
            <h1 class="font-geist text-3xl font-bold tracking-tight">
                Verifikasi Email
            </h1>
            <p class="mt-3 text-sm leading-6 text-slate-500 dark:text-slate-400">
                Terima kasih telah bergabung! Silakan periksa kotak masuk email Anda dan klik tautan verifikasi. Jika tidak menerima email, kami dapat mengirimkannya lagi.
            </p>
        </div>

        @if (session('status') == 'verification-link-sent')
            <div class="rounded-2xl bg-emerald-50 border border-emerald-200 text-emerald-700 px-4 py-3 text-sm text-center">
                Tautan verifikasi baru telah dikirimkan ke email Anda.
            </div>
        @endif

        <div class="space-y-4 mt-8">
            <form method="POST" action="{{ route('verification.send') }}">
                @csrf
                <x-primary-button class="w-full h-12 rounded-2xl justify-center text-base font-semibold">
                    {{ __('Kirim Ulang Email Verifikasi') }}
                </x-primary-button>
            </form>

            <form method="POST" action="{{ route('logout') }}" class="text-center mt-6">
                @csrf
                <button type="submit" class="text-sm text-slate-500 hover:text-white transition py-2 px-4 rounded-xl active:scale-95 inline-block">
                    {{ __('Keluar (Log Out)') }}
                </button>
            </form>
        </div>
    </div>
</x-guest-layout>
