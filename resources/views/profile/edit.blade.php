<x-app-layout>
    <x-slot name="pageTitle">Pengaturan</x-slot>
    <x-slot name="pageSubtitle">Atur profil, preferensi, dan keamanan akun lu di sini.</x-slot>

    <div x-data="{ 
            activeTab: 'tampilan',
            theme: localStorage.theme || 'system',
            setTheme(val) {
                this.theme = val;
                if (val === 'system') {
                    localStorage.removeItem('theme');
                    if (window.matchMedia('(prefers-color-scheme: dark)').matches) {
                        document.documentElement.classList.add('dark');
                    } else {
                        document.documentElement.classList.remove('dark');
                    }
                } else {
                    localStorage.theme = val;
                    if (val === 'dark') {
                        document.documentElement.classList.add('dark');
                    } else {
                        document.documentElement.classList.remove('dark');
                    }
                }
            }
        }" class="flex flex-col md:flex-row gap-8">
        
        {{-- Sidebar Pengaturan --}}
        <div class="w-full md:w-64 shrink-0 space-y-1">
            <button @click="activeTab = 'profil'" :class="activeTab === 'profil' ? 'bg-stone-100 dark:bg-neutral-900/10 text-neutral-900 dark:text-stone-500 font-semibold' : 'text-slate-600 dark:text-slate-400 hover:bg-slate-50 dark:bg-slate-900/50 dark:hover:bg-slate-800/50'" class="w-full text-left px-4 py-2.5 rounded-xl text-sm transition-all">Informasi Profil</button>
            <button @click="activeTab = 'tampilan'" :class="activeTab === 'tampilan' ? 'bg-stone-100 dark:bg-neutral-900/10 text-neutral-900 dark:text-stone-500 font-semibold' : 'text-slate-600 dark:text-slate-400 hover:bg-slate-50 dark:bg-slate-900/50 dark:hover:bg-slate-800/50'" class="w-full text-left px-4 py-2.5 rounded-xl text-sm transition-all">Tampilan & Tema</button>
            <button @click="activeTab = 'keamanan'" :class="activeTab === 'keamanan' ? 'bg-stone-100 dark:bg-neutral-900/10 text-neutral-900 dark:text-stone-500 font-semibold' : 'text-slate-600 dark:text-slate-400 hover:bg-slate-50 dark:bg-slate-900/50 dark:hover:bg-slate-800/50'" class="w-full text-left px-4 py-2.5 rounded-xl text-sm transition-all">Keamanan (Password)</button>
        </div>

        {{-- Konten Pengaturan --}}
        <div class="flex-1 max-w-2xl">
            
            {{-- Tab Profil --}}
            <div x-show="activeTab === 'profil'" style="display: none;" class="p-6 bg-white dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-2xl shadow-sm">
                @include('profile.partials.update-profile-information-form')
            </div>

            {{-- Tab Tampilan --}}
            <div x-show="activeTab === 'tampilan'" class="p-6 bg-white dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-2xl shadow-sm space-y-6">
                <header>
                    <h2 class="text-lg font-bold font-geist text-slate-900 dark:text-white">Tema Aplikasi</h2>
                    <p class="mt-1 text-sm text-slate-500 dark:text-slate-400">Pilih tema yang paling nyaman buat mata lu.</p>
                </header>
                
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                    <button @click="setTheme('light')" :class="theme === 'light' ? 'border-neutral-900 ring-1 ring-neutral-900 bg-stone-100/50 dark:bg-neutral-900/10' : 'border-slate-200 dark:border-slate-700 hover:border-stone-400'" class="flex flex-col items-center gap-3 p-4 border rounded-xl transition-all">
                        <div class="w-12 h-12 rounded-full bg-amber-100 flex items-center justify-center text-amber-600">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
                        </div>
                        <span class="font-semibold text-sm text-slate-900 dark:text-white">Terang</span>
                    </button>
                    
                    <button @click="setTheme('dark')" :class="theme === 'dark' ? 'border-neutral-900 ring-1 ring-neutral-900 bg-stone-100/50 dark:bg-neutral-900/10' : 'border-slate-200 dark:border-slate-700 hover:border-stone-400'" class="flex flex-col items-center gap-3 p-4 border rounded-xl transition-all">
                        <div class="w-12 h-12 rounded-full bg-stone-200 dark:bg-neutral-950/50 flex items-center justify-center text-neutral-900 dark:text-stone-500">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"/></svg>
                        </div>
                        <span class="font-semibold text-sm text-slate-900 dark:text-white">Gelap</span>
                    </button>
                    
                    <button @click="setTheme('system')" :class="theme === 'system' ? 'border-neutral-900 ring-1 ring-neutral-900 bg-stone-100/50 dark:bg-neutral-900/10' : 'border-slate-200 dark:border-slate-700 hover:border-stone-400'" class="flex flex-col items-center gap-3 p-4 border rounded-xl transition-all">
                        <div class="w-12 h-12 rounded-full bg-slate-100 dark:bg-slate-800 flex items-center justify-center text-slate-600 dark:text-slate-400">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                        </div>
                        <span class="font-semibold text-sm text-slate-900 dark:text-white">Ikut Sistem</span>
                    </button>
                </div>
            </div>

            {{-- Tab Keamanan --}}
            <div x-show="activeTab === 'keamanan'" style="display: none;" class="p-6 bg-white dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-2xl shadow-sm">
                @include('profile.partials.update-password-form')
            </div>

            
            
        </div>
    </div>
</x-app-layout>
