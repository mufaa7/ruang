<x-guest-layout>
    <div class="space-y-8">
        <div class="text-center">
            <h1 class="font-geist text-3xl font-bold tracking-tight">
                Lupa Password?
            </h1>
            <p class="mt-3 text-sm leading-6 text-slate-500 dark:text-slate-400">
                Tidak masalah. Masukkan email Anda dan kami akan mengirimkan tautan untuk mengatur ulang password Anda.
            </p>
        </div>

        <!-- Session Status -->
        <x-auth-session-status class="rounded-2xl bg-emerald-50 border border-emerald-200 text-emerald-700 px-4 py-3 text-sm" :status="session('status')" />

        <form method="POST" action="{{ route('password.email') }}" class="space-y-6">
            @csrf

            <!-- Email Address -->
            <div class="space-y-2">
                <x-input-label for="email" :value="__('Email')" />
                <x-text-input id="email" class="block w-full rounded-2xl h-12" type="email" name="email" :value="old('email')" placeholder="nama@email.com" required autofocus />
                <x-input-error :messages="$errors->get('email')" class="mt-1" />
            </div>

            <x-primary-button class="w-full h-12 rounded-2xl justify-center text-base font-semibold mt-8">
                {{ __('Kirim Tautan Reset →') }}
            </x-primary-button>
            
            <div class="text-center mt-6">
                <a class="text-sm text-neutral-900 hover:text-stone-600 transition py-2 px-4 rounded-xl active:scale-95 inline-block" href="{{ route('login') }}">
                    {{ __('Kembali ke Login') }}
                </a>
            </div>
        </form>
    </div>
</x-guest-layout>
