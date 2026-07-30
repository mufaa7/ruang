<x-guest-layout>

    <div class="text-center mb-8">
        <div class="inline-flex items-center gap-2 rounded-full border border-[var(--border)] bg-[var(--surface)] px-4 py-2 mb-6">
            <i class="ph-fill ph-sparkle text-[var(--primary)]"></i>
            <span class="text-xs uppercase tracking-[0.2em] text-[var(--text-soft)] font-bold">Ruang Baru</span>
        </div>
        <h1 class="text-4xl font-display font-bold tracking-tight text-[var(--text)]">Bangun Ruangmu</h1>
        <p class="mt-3 text-[var(--text-soft)] leading-relaxed">Amankan setiap ide dan catatanmu, biarkan mereka tumbuh di sini.</p>
    </div>

    @if ($errors->any())
        <div class="alert alert-error">
            <ul class="space-y-1">
                @foreach ($errors->all() as $error)
                    <li>• {{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('register') }}">
        @csrf

        <div class="input-group">
            <label for="name" class="input-label">Nama Kamu</label>
            <input id="name" type="text" name="name" value="{{ old('name') }}" required autofocus autocomplete="name" placeholder="Nama yang ingin dipanggil" class="input-field" />
        </div>

        <div class="input-group">
            <label for="email" class="input-label">Alamat Email</label>
            <input id="email" type="email" name="email" value="{{ old('email') }}" required autocomplete="username" placeholder="nama@email.com" class="input-field" />
        </div>

        <div class="input-group" x-data="{ show: false }">
            <label for="password" class="input-label">Kata Sandi</label>
            <div class="password-wrap">
                <input id="password" :type="show ? 'text' : 'password'" name="password" required autocomplete="new-password" placeholder="Rahasiakan sandimu" class="input-field pr-12" />
                <button type="button" @click="show = !show" class="toggle-pass">
                    <i class="ph" :class="show ? 'ph-eye-slash' : 'ph-eye'"></i>
                </button>
            </div>
        </div>

        <div class="input-group" x-data="{ show: false }">
            <label for="password_confirmation" class="input-label">Ulangi Kata Sandi</label>
            <div class="password-wrap">
                <input id="password_confirmation" :type="show ? 'text' : 'password'" name="password_confirmation" required autocomplete="new-password" placeholder="Ketik sekali lagi" class="input-field pr-12" />
                <button type="button" @click="show = !show" class="toggle-pass">
                    <i class="ph" :class="show ? 'ph-eye-slash' : 'ph-eye'"></i>
                </button>
            </div>
        </div>

        <button type="submit" class="btn-primary">
            <span>Mulai Menulis</span>
            <i class="ph-bold ph-arrow-right"></i>
        </button>

        <div class="divider">atau</div>

        <p class="text-center text-sm text-[var(--text-soft)]">
            Sudah punya ruang?
            <a href="{{ route('login') }}" class="link">Masuk kembali</a>
        </p>
    </form>

</x-guest-layout>
