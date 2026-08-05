<x-app-layout>
    <x-slot name="pageTitle">Dengerin</x-slot>

    <x-slot name="pageHeader">
        <div class="mb-8">
            <h1 class="text-2xl font-bold text-neutral-900 font-geist flex items-center gap-2 dark:text-white">
                <i class="ph-fill ph-headphones text-3xl text-neutral-900 dark:text-slate-100"></i> Lagu ke-17, tugas masih judul doang.
            </h1>
            <p class="text-sm text-neutral-500 mt-1 dark:text-slate-400">jangan sampe albumnya tamat duluan.</p>
        </div>
    </x-slot>

    <div id="dengerin-grid" class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        {{-- Tempat kosong untuk Global Player (Main Playlist) --}}
        <div id="player-placeholder-main" class="lg:col-span-2"></div>
        
        {{-- Tempat kosong untuk Alt Playlists --}}
        <div id="player-placeholder-alt" class="lg:col-span-1"></div>
    </div>
</x-app-layout>
