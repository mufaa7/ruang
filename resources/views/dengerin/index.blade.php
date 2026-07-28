<x-app-layout>
    <x-slot name="pageTitle">Dengerin</x-slot>

    <x-slot name="pageHeader">
        <div class="mb-8">
            <h1 class="text-2xl font-bold text-neutral-900 font-geist flex items-center gap-2 dark:text-white">
                <i class="ph-fill ph-headphones text-3xl text-neutral-800 dark:text-slate-100"></i> Lagu ke-17, tugas masih judul doang.
            </h1>
            <p class="text-sm text-neutral-500 mt-1 dark:text-slate-400">jangan sampe albumnya tamat duluan.</p>
        </div>
    </x-slot>

    <div id="dengerin-grid" class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        {{-- Tempat kosong untuk Global Player (Main Playlist) --}}
        <div id="player-placeholder-main" class="lg:col-span-2 hidden lg:block"></div>
        
        <div class="lg:col-span-1">
            <div x-data class="bg-neutral-900 text-white rounded-[24px] p-8 shadow-xl relative overflow-hidden flex flex-col justify-center min-h-[300px]">
                
                {{-- Decorative Background --}}
                <div class="absolute -top-10 -right-10 w-40 h-40 bg-stone-500/10 rounded-full blur-3xl pointer-events-none"></div>
                <div class="absolute -bottom-10 -left-10 w-40 h-40 bg-stone-500/10 rounded-full blur-3xl pointer-events-none"></div>

                <div class="relative z-10 text-center space-y-8">
                    <h3 class="text-[11px] font-bold uppercase tracking-widest text-neutral-400 flex items-center justify-center gap-2">
                        <span class="w-2 h-2 rounded-full animate-pulse" :class="$store.pomodoro.mode === 'focus' ? 'bg-white' : ($store.pomodoro.mode === 'break' ? 'bg-emerald-400' : 'bg-neutral-500')"></span>
                        Pomodoro Timer
                    </h3>
                    
                    <div class="font-mono text-6xl md:text-7xl font-bold tracking-tighter" x-text="$store.pomodoro.formattedTime">
                        25:00
                    </div>

                    <div class="flex items-center justify-center gap-4">
                        <button @click="$store.pomodoro.reset()" class="w-12 h-12 rounded-full bg-white/10 hover:bg-white/20 text-white flex items-center justify-center transition-all active:scale-95" title="Reset">
                            <i class="ph-bold ph-arrow-counter-clockwise text-xl"></i>
                        </button>
                        <button @click="$store.pomodoro.toggle()" 
                                class="w-16 h-16 rounded-full flex items-center justify-center shadow-lg transition-all active:scale-95"
                                :class="$store.pomodoro.isRunning ? 'bg-stone-100 hover:bg-stone-200 text-neutral-900 shadow-stone-100/20' : 'bg-white hover:bg-stone-100 text-neutral-900 shadow-white/20' dark:text-white">
                            <i x-show="!$store.pomodoro.isRunning" class="ph-fill ph-play text-3xl ml-1"></i>
                            <i x-show="$store.pomodoro.isRunning" class="ph-fill ph-pause text-3xl"></i>
                        </button>
                        <button @click="$store.pomodoro.skipSession()" class="w-12 h-12 rounded-full bg-white/10 hover:bg-white/20 text-white flex items-center justify-center transition-all active:scale-95" title="Skip Session">
                            <i class="ph-bold ph-skip-forward text-xl"></i>
                        </button>
                    </div>

                    <div class="pt-6 border-t border-white/10 flex flex-wrap justify-center gap-4 text-[11px] font-bold uppercase tracking-wider text-neutral-400">
                        <button @click="$store.pomodoro.setMode(25)" class="min-h-[44px] px-2 active:scale-95 transition-all" :class="$store.pomodoro.mode === 'focus' ? 'text-white border-b-2 border-white pb-1' : 'hover:text-neutral-300'">Fokus (25m)</button>
                        <button @click="$store.pomodoro.setMode(5)" class="min-h-[44px] px-2 active:scale-95 transition-all" :class="$store.pomodoro.mode === 'break' ? 'text-white border-b-2 border-emerald-400 pb-1' : 'hover:text-neutral-300'">Rehat (5m)</button>
                    </div>
                </div>
            </div>
            
            {{-- Tips Section --}}
            <div class="mt-6 p-5 bg-stone-50 rounded-[20px] border border-stone-200 dark:bg-slate-900/50 dark:border-slate-700/50">
                <h4 class="text-sm font-bold text-neutral-900 flex items-center gap-2 mb-2 dark:text-white">
                    <i class="ph-fill ph-lightbulb text-neutral-500 dark:text-slate-400"></i>
                    Tips Fokus
                </h4>
                <p class="text-xs text-neutral-600 leading-relaxed font-medium dark:text-slate-300">
                    Teknik Pomodoro idealnya terdiri dari 4 siklus kerja (25 menit fokus, 5 menit istirahat). Setelah siklus ke-4, lu boleh ambil istirahat panjang (15-30 menit).
                </p>
            </div>
        </div>
        
        {{-- Tempat kosong untuk Alt Playlists --}}
        <div id="player-placeholder-alt" class="lg:col-span-3 hidden lg:block"></div>
    </div>
</x-app-layout>
