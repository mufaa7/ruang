<x-guest-layout>
    <div class="space-y-8">
        <div class="text-center">
            <h1 class="font-geist text-3xl font-bold tracking-tight">
                Reset Password
            </h1>
            <p class="mt-3 text-sm leading-6 text-slate-500 dark:text-slate-400">
                Silakan buat password baru Anda di bawah ini.
            </p>
        </div>

        <form method="POST" action="{{ route('password.store') }}" class="space-y-6">
            @csrf

            <!-- Password Reset Token -->
            <input type="hidden" name="token" value="{{ $request->route('token') }}">

            <!-- Email Address -->
            <div class="space-y-2">
                <x-input-label for="email" :value="__('Email')" />
                <x-text-input id="email" class="block w-full rounded-2xl h-12" type="email" name="email" :value="old('email', $request->email)" required autofocus autocomplete="username" />
                <x-input-error :messages="$errors->get('email')" class="mt-1" />
            </div>

            <!-- Password -->
            <div class="space-y-2">
                <x-input-label for="password" :value="__('Password Baru')" />
                <x-text-input id="password" class="block w-full rounded-2xl h-12" type="password" name="password" placeholder="••••••••" required autocomplete="new-password" />
                <x-input-error :messages="$errors->get('password')" class="mt-1" />
            </div>

            <!-- Confirm Password -->
            <div class="space-y-2">
                <x-input-label for="password_confirmation" :value="__('Konfirmasi Password')" />
                <x-text-input id="password_confirmation" class="block w-full rounded-2xl h-12"
                                    type="password"
                                    name="password_confirmation" placeholder="••••••••" required autocomplete="new-password" />
                <x-input-error :messages="$errors->get('password_confirmation')" class="mt-1" />
            </div>

            <x-primary-button class="w-full h-12 rounded-2xl justify-center text-base font-semibold mt-8">
                {{ __('Reset Password →') }}
            </x-primary-button>
        </form>
    </div>
</x-guest-layout>
