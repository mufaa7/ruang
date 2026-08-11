<x-app-layout>
    <x-slot name="pageTitle">Dengerin</x-slot>

    <x-slot name="pageHeader">
        <div class="mb-10 bg-black/40 backdrop-blur-xl border border-white/10 rounded-[24px] p-8 shadow-2xl relative overflow-hidden">
            <div class="absolute inset-0 bg-gradient-to-br from-emerald-500/10 to-transparent pointer-events-none"></div>
            <div class="relative z-10">
                <h1 class="text-2xl font-bold font-geist text-white flex items-center gap-3">
                    <i class="ph-fill ph-headphones text-3xl text-emerald-500 drop-shadow-[0_0_15px_rgba(16,185,129,0.5)]"></i> 
                    Lagu ke-17, tugas masih judul doang.
                </h1>
                <p class="text-[15px] font-medium text-slate-400 mt-3 ml-[46px]">jangan sampe albumnya tamat duluan.</p>
            </div>
        </div>
    </x-slot>

    <div id="dengerin-grid" class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        {{-- Tempat kosong untuk Global Player (Main Playlist) --}}
        <div id="player-placeholder-main" class="lg:col-span-2"></div>
        
        {{-- Tempat kosong untuk Alt Playlists --}}
        <div id="player-placeholder-alt" class="lg:col-span-1"></div>
    </div>
</x-app-layout>
