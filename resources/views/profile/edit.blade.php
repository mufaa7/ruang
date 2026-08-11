<x-app-layout>
    <x-slot name="pageTitle">Pengaturan</x-slot>
    <x-slot name="pageSubtitle">Atur profil, preferensi, dan keamanan akun lu di sini.</x-slot>

    <div x-data="{ 
            activeTab: 'profil',
        }" class="flex flex-col md:flex-row gap-8 mt-6">
        
        {{-- Sidebar Pengaturan --}}
        <div class="w-full md:w-64 shrink-0 space-y-2">
            <button @click="activeTab = 'profil'" :class="activeTab === 'profil' ? 'bg-amber-500/10 border border-amber-500/20 text-amber-500 font-bold shadow-sm' : 'text-slate-400 hover:text-white hover:bg-white/5 border border-transparent'" class="w-full text-left px-5 py-3 rounded-xl text-[15px] transition-all flex items-center gap-3">
                <i class="ph-bold ph-user"></i> Informasi Profil
            </button>
            <button @click="activeTab = 'keamanan'" :class="activeTab === 'keamanan' ? 'bg-amber-500/10 border border-amber-500/20 text-amber-500 font-bold shadow-sm' : 'text-slate-400 hover:text-white hover:bg-white/5 border border-transparent'" class="w-full text-left px-5 py-3 rounded-xl text-[15px] transition-all flex items-center gap-3">
                <i class="ph-bold ph-lock-key"></i> Keamanan (Password)
            </button>
        </div>

        {{-- Konten Pengaturan --}}
        <div class="flex-1 max-w-2xl">
            
            {{-- Tab Profil --}}
            <div x-show="activeTab === 'profil'" style="display: none;" class="p-6 sm:p-8 bg-black/40 backdrop-blur-xl rounded-[24px] border border-white/10 shadow-2xl relative overflow-hidden animate-fadeIn">
                <div class="absolute inset-0 bg-gradient-to-br from-white/5 to-transparent pointer-events-none"></div>
                <div class="relative z-10">
                    @include('profile.partials.update-profile-information-form')
                </div>
            </div>

            {{-- Tab Keamanan --}}
            <div x-show="activeTab === 'keamanan'" style="display: none;" class="p-6 sm:p-8 bg-black/40 backdrop-blur-xl rounded-[24px] border border-white/10 shadow-2xl relative overflow-hidden animate-fadeIn">
                <div class="absolute inset-0 bg-gradient-to-br from-white/5 to-transparent pointer-events-none"></div>
                <div class="relative z-10">
                    @include('profile.partials.update-password-form')
                </div>
            </div>

        </div>
    </div>
</x-app-layout>
