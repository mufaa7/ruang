<x-guest-layout>
    <div class="space-y-8">
        <div class="text-center">
            <h1 class="font-geist text-3xl font-bold tracking-tight">
                Keamanan Tambahan
            </h1>
            <p class="mt-3 text-sm leading-6 text-slate-500 dark:text-slate-400">
                Area ini aman. Harap konfirmasi password Anda sebelum melanjutkan.
            </p>
        </div>

        <form method="POST" action="{{ route('password.confirm') }}" class="space-y-6">
            @csrf

            <!-- Password -->
            <div class="space-y-2">
                <x-input-label for="password" :value="__('Password')" />
                <x-text-input id="password" class="block w-full rounded-2xl h-12"
                                type="password"
                                name="password"
                                placeholder="••••••••"
                                required autocomplete="current-password" />
                <x-input-error :messages="$errors->get('password')" class="mt-1" />
            </div>

            <x-primary-button class="w-full h-12 rounded-2xl justify-center text-base font-semibold mt-8">
                {{ __('Konfirmasi →') }}
            </x-primary-button>
        </form>
    </div>
</x-guest-layout>
