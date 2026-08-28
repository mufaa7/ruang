<x-guest-layout>

    <div class="relative mx-auto w-full max-w-md animate-fadeIn">

        {{-- iOS 17/18 Liquid Frosted Glass Card --}}
        <div class="ios-liquid-card relative overflow-hidden rounded-[28px] p-7 sm:p-9">
            
            {{-- Header --}}
            <div class="mb-8">
                <div class="inline-flex items-center gap-2 rounded-full border border-white/15 bg-white/[0.04] px-3.5 py-1.5 backdrop-blur-xl mb-5 shadow-sm">
                    <span class="h-1.5 w-1.5 rounded-full bg-emerald-400"></span>
                    <span class="text-[11px] font-medium tracking-wider text-slate-300">Pendaftaran</span>
                </div>

                <h1 class="text-3xl sm:text-4xl font-normal font-serif text-white tracking-tight leading-tight">
                    Bangun Ruangmu.
                </h1>

                <p class="mt-2 text-[13px] sm:text-sm text-slate-400 leading-relaxed">
                    Mulai simpan ide, makalah, dan catatan kuliah dalam satu tempat tenang.
                </p>
            </div>

            <!-- Validation Errors -->
            @if ($errors->any())
                <div class="mb-6 rounded-xl border border-rose-500/30 bg-rose-500/10 p-3.5 backdrop-blur-md">
                    <ul class="space-y-1 text-xs text-rose-300">
                        @foreach ($errors->all() as $error)
                            <li>• {{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            {{-- FORM --}}
            <form method="POST" action="{{ route('register') }}" class="space-y-4">
                @csrf

                <!-- Name -->
                <div>
                    <label for="name" class="block text-xs font-medium text-slate-300 mb-1.5">
                        Nama Lengkap
                    </label>
                    <input
                        id="name"
                        type="text"
                        name="name"
                        value="{{ old('name') }}"
                        required
                        autofocus
                        autocomplete="name"
                        placeholder="Nama kamu"
                        class="ios-liquid-input w-full h-12 rounded-xl px-4 text-sm text-white placeholder:text-slate-500 outline-none"
                    />
                </div>

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
                        autocomplete="username"
                        placeholder="nama@email.com"
                        class="ios-liquid-input w-full h-12 rounded-xl px-4 text-sm text-white placeholder:text-slate-500 outline-none"
                    />
                </div>

                <!-- Password -->
                <div x-data="{ show: false }">
                    <label for="password" class="block text-xs font-medium text-slate-300 mb-1.5">
                        Kata Sandi
                    </label>
                    <div class="relative">
                        <input
                            id="password"
                            :type="show ? 'text' : 'password'"
                            name="password"
                            required
                            autocomplete="new-password"
                            placeholder="Minimal 8 karakter"
                            class="ios-liquid-input w-full h-12 rounded-xl px-4 pr-11 text-sm text-white placeholder:text-slate-500 outline-none"
                        />
                        <button
                            type="button"
                            @click="show = !show"
                            class="absolute inset-y-0 right-3.5 flex items-center text-slate-400 hover:text-white transition-colors"
                        >
                            <i :class="show ? 'ph ph-eye-slash' : 'ph ph-eye'" class="text-base"></i>
                        </button>
                    </div>
                </div>

                <!-- Confirm Password -->
                <div x-data="{ show: false }">
                    <label for="password_confirmation" class="block text-xs font-medium text-slate-300 mb-1.5">
                        Ulangi Kata Sandi
                    </label>
                    <div class="relative">
                        <input
                            id="password_confirmation"
                            :type="show ? 'text' : 'password'"
                            name="password_confirmation"
                            required
                            autocomplete="new-password"
                            placeholder="Ketik ulang kata sandi"
                            class="ios-liquid-input w-full h-12 rounded-xl px-4 pr-11 text-sm text-white placeholder:text-slate-500 outline-none"
                        />
                        <button
                            type="button"
                            @click="show = !show"
                            class="absolute inset-y-0 right-3.5 flex items-center text-slate-400 hover:text-white transition-colors"
                        >
                            <i :class="show ? 'ph ph-eye-slash' : 'ph ph-eye'" class="text-base"></i>
                        </button>
                    </div>
                </div>

                {{-- SUBMIT BUTTON (Liquid Frosted Glass) --}}
                <div class="pt-3">
                    <button
                        type="submit"
                        class="ios-liquid-btn w-full h-12 text-white font-semibold text-sm flex items-center justify-center gap-2 cursor-pointer"
                    >
                        <span>Mulai Menulis</span>
                        <i class="ph-bold ph-arrow-right text-sm"></i>
                    </button>
                </div>

                {{-- LOGIN LINK --}}
                <div class="pt-4 border-t border-white/[0.08] text-center">
                    <p class="text-xs text-slate-400">
                        Sudah punya tempat?
                        <a href="{{ route('login') }}" class="font-semibold text-amber-300 hover:text-amber-200 transition-colors ml-1">
                            Kembali Pulang
                        </a>
                    </p>
                </div>

            </form>

        </div>

    </div>

</x-guest-layout>
