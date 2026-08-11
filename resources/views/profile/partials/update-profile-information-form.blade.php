<section>
    <header>
        <h2 class="text-lg font-bold font-geist text-white">
            Informasi Profil
        </h2>

        <p class="mt-1 text-sm text-slate-400">
            Ubah nama dan alamat email akun lu di sini.
        </p>
    </header>

    <form id="send-verification" method="post" action="{{ route('verification.send') }}">
        @csrf
    </form>

    <form method="post" action="{{ route('profile.update') }}" class="mt-6 space-y-6">
        @csrf
        @method('patch')

        <div>
            <x-input-label for="name" value="Nama" />
            <x-text-input id="name" name="name" type="text" class="mt-1 block w-full" :value="old('name', $user->name)" required autofocus autocomplete="name" />
            <x-input-error class="mt-2" :messages="$errors->get('name')" />
        </div>

        <div>
            <x-input-label for="username" value="Username" />
            <x-text-input id="username" name="username" type="text" class="mt-1 block w-full" :value="old('username', $user->username)" placeholder="misal: mufar" autocomplete="username" />
            <x-input-error class="mt-2" :messages="$errors->get('username')" />
        </div>

        <div>
            <x-input-label for="bio" value="Bio" />
            <textarea id="bio" name="bio" rows="3" class="w-full rounded-xl border border-white/10 bg-white/5 px-4 py-3 text-sm text-white focus:border-amber-500/50 focus:ring-1 focus:ring-amber-500/50 transition-all shadow-inner placeholder:text-slate-500" placeholder="Ceritain dikit tentang lu...">{{ old('bio', $user->bio) }}</textarea>
            <x-input-error class="mt-2" :messages="$errors->get('bio')" />
        </div>

        <div>
            <x-input-label for="email" value="Email" />
            <x-text-input id="email" name="email" type="email" class="mt-1 block w-full" :value="old('email', $user->email)" required autocomplete="username" />
            <x-input-error class="mt-2" :messages="$errors->get('email')" />

            @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
                <div>
                    <p class="text-sm mt-2 text-slate-300">
                        Email lu belum diverifikasi nih.

                        <button form="send-verification" class="underline text-sm text-slate-400 hover:text-white rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-stone-800">
                            Klik di sini buat kirim ulang link verifikasi.
                        </button>
                    </p>

                    @if (session('status') === 'verification-link-sent')
                        <p class="mt-2 font-medium text-sm text-emerald-400">
                            Link verifikasi baru udah dikirim ke email lu. Cek inbox ya!
                        </p>
                    @endif
                </div>
            @endif
        </div>

        <div class="flex items-center gap-4">
            <x-primary-button>Simpan</x-primary-button>

            @if (session('status') === 'profile-updated')
                <p
                    x-data="{ show: true }"
                    x-show="show"
                    x-transition
                    x-init="setTimeout(() => show = false, 2000)"
                    class="text-sm font-medium text-emerald-400"
                >Berhasil disimpan.</p>
            @endif
        </div>
    </form>
</section>
