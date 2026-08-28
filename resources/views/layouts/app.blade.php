<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <script>
            // Prevent FOUC
            if (localStorage.theme === 'dark') {
                document.documentElement.classList.add('dark')
            } else {
                document.documentElement.classList.remove('dark')
            }
        </script>

        <title>{{ config('app.name', 'RUANG') }} — @yield('title', 'Workspace')</title>
        
        <!-- Favicon -->
        <link rel="icon" type="image/png" href="{{ asset('favicon.png') }}">

        <!-- Fonts: Geist (Headings) + Inter (Body) -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link rel="preconnect" href="https://cdn.jsdelivr.net" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Instrument+Serif:ital@0;1&family=JetBrains+Mono:wght@400;500;600&display=swap" rel="stylesheet">
        
        <!-- Preload Geist font agar tidak ada FOUT -->
        <link rel="preload" as="font" type="font/woff2" crossorigin
              href="https://cdn.jsdelivr.net/npm/@fontsource/geist-sans@5.0.1/files/geist-sans-latin-600-normal.woff2">
        <link rel="preload" as="font" type="font/woff2" crossorigin
              href="https://cdn.jsdelivr.net/npm/@fontsource/geist-sans@5.0.1/files/geist-sans-latin-700-normal.woff2">
        <style>
            @font-face {
                font-family: 'Geist';
                src: url('https://cdn.jsdelivr.net/npm/@fontsource/geist-sans@5.0.1/files/geist-sans-latin-600-normal.woff2') format('woff2');
                font-weight: 600;
                font-style: normal;
                font-display: swap;
            }
            @font-face {
                font-family: 'Geist';
                src: url('https://cdn.jsdelivr.net/npm/@fontsource/geist-sans@5.0.1/files/geist-sans-latin-700-normal.woff2') format('woff2');
                font-weight: 700;
                font-style: normal;
                font-display: swap;
            }
            .font-geist { font-family: 'Geist', 'Inter', sans-serif; }
        </style>

        @vite(['resources/css/app.css', 'resources/js/app.js'])
        
        <!-- Hotwire Turbo for SPA-like navigation -->
        <script type="module" src="https://unpkg.com/@hotwired/turbo@8.0.4"></script>
        <style>
            .vinyl { transform: translateY(-50%); }
            .turbo-progress-bar {
                height: 2px;
                background-color: #6366f1;
            }
        </style>
        <!-- defer: tidak blocking parse HTML -->
        <script src="https://unpkg.com/@phosphor-icons/web" defer></script>
        
        <!-- Flatpickr for iOS style date time picker -->
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
        <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/themes/dark.css">
        <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    </head>
    
    <body class="antialiased min-h-screen font-sans opacity-0 text-white dark:text-white relative overflow-x-hidden">
        @if(session()->has('impersonated_by'))
        <div class="bg-amber-400 text-slate-950 px-4 py-2 text-center text-sm font-bold flex justify-center items-center gap-4 z-50 relative border-b border-amber-500/30">
            <span>⚠️ You are impersonating <strong>{{ auth()->user()->name }}</strong></span>
            <form action="{{ route('impersonate.leave') }}" method="POST" class="inline">
                @csrf
                <button type="submit" class="bg-slate-950 text-white px-3 py-1 rounded-lg hover:bg-slate-800 text-xs font-semibold">Leave Impersonate</button>
            </form>
        </div>
        @endif

        <div class="fixed inset-0 overflow-hidden pointer-events-none -z-50 bg-black">
            <!-- Subtle neutral ambient glow for crystal clear glass refraction -->
            <div class="absolute inset-0 bg-[radial-gradient(circle_at_50%_30%,rgba(255,255,255,0.03),transparent_70%)]"></div>

            <!-- Custom Background Image (faint texture, responsive) -->
            <img src="{{ asset('images/background.png') }}" alt="Background" class="absolute top-1/2 -translate-y-1/2 object-contain opacity-25 mix-blend-screen pointer-events-none max-w-none w-[500px] h-[500px] left-1/2 -translate-x-1/2 md:w-[1060px] md:h-[1060px] md:left-auto md:translate-x-0 md:right-[-150px]" />
        </div>

        {{-- Dummy Data Simulasi (Nanti diganti dengan variabel dari Controller/ViewComposer) --}}
        @php
            $stats = [
                'documents_count' => 3,
                'notes_count' => 12,
                'is_pomodoro_running' => true,
                'pomodoro_time_left' => '24:59',
                'focus_time_today' => '2j 15m',
                'words_written_today' => '1,250'
            ];
        @endphp

        <turbo-frame id="app-body" data-turbo-action="advance" class="contents">
            <div x-data="{ 
                    sidebarOpen: window.innerWidth >= 1024,
                    isMobile: window.innerWidth < 1024,

                    updateScreen() {
                        this.isMobile = window.innerWidth < 1024;
                        if (!this.isMobile) {
                            this.sidebarOpen = true;
                            document.body.style.overflow = '';
                        } else {
                            document.body.style.overflow = this.sidebarOpen ? 'hidden' : '';
                        }
                    },

                    toggleSidebar() {
                        const btn = this.$refs.vinylBtn;
                        
                        btn.classList.remove(
                            'playing',
                            'spinning',
                            'stopping'
                        );

                        // Trigger reflow to restart CSS animations
                        void btn.offsetWidth;

                        if(this.sidebarOpen){
                            btn.classList.add('stopping');
                            setTimeout(()=>{
                                btn.classList.remove('stopping');
                            }, 600);
                        } else {
                            btn.classList.add('spinning');
                            setTimeout(()=>{
                                btn.classList.remove('spinning');
                                btn.classList.add('playing');
                            }, 450);
                        }

                        this.sidebarOpen = !this.sidebarOpen;
                        
                        // Lock scroll on mobile
                        if (this.isMobile) {
                            document.body.style.overflow = this.sidebarOpen ? 'hidden' : '';
                        }
                    },
                    
                    init() {
                        if (this.isMobile && this.sidebarOpen) {
                            document.body.style.overflow = 'hidden';
                        } else {
                            document.body.style.overflow = '';
                        }
                    }
                 }" 
                 @resize.window="updateScreen()"
                 class="flex min-h-screen relative">

                <!-- Backdrop Mobile -->
                <div x-show="sidebarOpen && isMobile" 
                     x-transition.opacity 
                     @click="toggleSidebar()"
                     style="display:none;"
                     class="fixed inset-0 bg-slate-900/40 backdrop-blur-sm z-30 lg:hidden"></div>

                <!-- Vinyl Button Toggle Wrapper -->
                <div class="fixed left-5 lg:left-[42px] top-6 lg:top-[34px] z-50 transition-transform duration-300 ease-[cubic-bezier(.22,1,.36,1)]"
                     :style="sidebarOpen ? 'transform: translateX(192px)' : 'transform: translateX(0)'">
                    <button
                        x-ref="vinylBtn"
                        @click="toggleSidebar()"
                        aria-label="Toggle Sidebar"
                        class="vinyl-menu flex items-center justify-center rounded-full overflow-hidden active:scale-95"
                    >
                        <span class="scratch"></span>
                    </button>
                </div>

            {{-- SIDEBAR (Fixed 280px) --}}
            <aside :class="sidebarOpen ? 'translate-x-0' : '-translate-x-[120%]'" class="w-[280px] shrink-0 flex-col rounded-r-[32px] border-r border-white/10 bg-white/5 backdrop-blur-xl flex fixed inset-y-0 left-0 z-40 shadow-[4px_0_24px_rgba(0,0,0,0.2)] transition-transform duration-300 ease-[cubic-bezier(.22,1,.36,1)]">
                
                {{-- Brand --}}
                <div class="px-6 pt-8 pb-6 border-b border-white/10" x-data>
                    <h1 class="text-[34px] font-bold tracking-tight text-white dark:text-white group cursor-default relative inline-flex items-center" style="font-family: 'Cormorant Garamond', serif;">
                        ruang.
                        <span x-show="$store.pomodoro && $store.pomodoro.isRunning" x-cloak class="absolute -right-3 top-1.5 w-2 h-2 rounded-full bg-emerald-500 shadow-[0_0_8px_rgba(16,185,129,0.5)]"></span>
                    </h1>

                    <div class="mt-5">
                        <p class="text-[13px] md:text-[14px] text-slate-400 leading-[1.8] tracking-wide">
                            masuk. denger lagu. pulang.
                        </p>
                    </div>
                </div>

                {{-- Search --}}
                <div class="px-6 py-5 border-b border-white/10">
                    <button
                        type="button"
                        class="group w-full h-11 rounded-xl border border-white/10 bg-black/20 px-4 flex items-center justify-between transition-all duration-200 hover:bg-white/10 hover:border-white/30">

                        <div class="flex items-center gap-3 text-sm text-slate-400 group-hover:text-slate-200 transition-colors">
                            <i class="ph ph-magnifying-glass text-lg"></i>
                            <span>cari apa aja...</span>
                        </div>

                        <kbd class="hidden lg:flex items-center rounded-md border border-white/10 bg-white/5 px-2 py-1 text-[11px] text-slate-300 font-sans font-medium shadow-sm">
                            Ctrl K
                        </kbd>

                    </button>
                </div>

                {{-- Bagian Tengah: Navigation --}}
                <nav class="flex-1 px-4 space-y-2 overflow-y-auto mt-4">
                    @php
                        $navItems = [
                            ['label' => 'beranda',  'icon' => 'ph-house', 'route' => 'dashboard', 'badge' => null],
                            ['label' => 'nugas',    'icon' => 'ph-pencil-simple-line', 'route' => 'makalah.index', 'badge' => $stats['documents_count']],
                            ['label' => 'belajar',  'icon' => 'ph-brain', 'route' => 'subjects.index', 'badge' => null],
                            ['label' => 'coretan',  'icon' => 'ph-notebook', 'route' => 'coretan.index', 'badge' => $stats['notes_count']],
                            ['label' => 'jurnal',   'icon' => 'ph-book-open', 'route' => 'papers.my', 'badge' => null],
                            ['label' => 'dengerin', 'icon' => 'ph-music-notes', 'route' => 'dengerin.index', 'badge' => null],
                            ['label' => 'jejak',    'icon' => 'ph-clock-counter-clockwise', 'route' => 'jejak.index', 'badge' => null],
                            ['label' => 'kotak ide', 'icon' => 'ph-lightbulb', 'route' => 'ide.index', 'badge' => null],
                            ['label' => 'tentang',  'icon' => 'ph-info', 'route' => 'about.index', 'badge' => null],
                        ];
                    @endphp

                    @foreach($navItems as $item)
                        @php 
                            $route = Route::has($item['route']) ? route($item['route']) : '#';
                            $active = request()->routeIs($item['route']); 
                        @endphp
                        <a href="{{ $route }}" title="{{ ucwords($item['label']) }}" class="group w-full flex items-center justify-between px-3 h-11 text-[14px] font-medium transition-colors duration-150 rounded-r-lg {{ $active ? 'bg-white/10 text-amber-300 border-l-[3px] border-amber-300 shadow-[inset_2px_0_10px_rgba(252,211,77,0.05)]' : 'text-slate-300 border-l-[3px] border-transparent hover:border-white/30 hover:bg-white/5 hover:text-white' }}">
                            <div class="flex items-center gap-3 transition-transform duration-150 group-hover:translate-x-[2px]">
                                <i class="ph {{ $item['icon'] }} text-[18px]"></i>
                                <span class="capitalize">{{ $item['label'] }}</span>
                            </div>
                            
                            {{-- Badge --}}
                            @if($item['badge'])
                                <span class="text-[11px] px-2 py-0.5 rounded-full font-semibold {{ $active ? 'bg-amber-300/20 text-amber-300' : 'bg-white/10 text-slate-300 group-hover:bg-white/20' }}">
                                    {{ $item['badge'] }}
                                </span>
                            @endif
                        </a>
                    @endforeach

                    @if(auth()->check() && auth()->user()->role === 'admin')
                        <div class="pt-2">
                            <a href="{{ route('admin.dashboard') }}" class="group w-full flex items-center justify-between px-3 h-11 text-[14px] font-bold bg-amber-400/10 text-amber-300 border border-amber-400/30 rounded-xl hover:bg-amber-400/20 transition-all">
                                <div class="flex items-center gap-3">
                                    <i class="ph ph-shield-check text-[18px]"></i>
                                    <span>Admin Panel</span>
                                </div>
                                <span class="text-[10px] bg-amber-300 text-black px-1.5 py-0.5 rounded font-mono font-bold">DEV</span>
                            </a>
                        </div>
                    @endif
                </nav>

                {{-- Bagian Bawah: Widget & Footer --}}
                <div class="p-4 border-t border-white/10 space-y-3 bg-transparent">
                    
                    {{-- Pomodoro Widget (Alpine.js) --}}
                    <div id="pomodoro-widget" data-turbo-permanent x-data x-cloak>
                        {{-- State: Idle --}}
                        <div x-show="$store.pomodoro.mode === 'idle'" class="bg-white/5 border border-white/10 p-3 rounded-xl flex items-center justify-between shadow-sm cursor-pointer hover:bg-white/10 hover:border-amber-300/50 transition-colors" @click="$store.pomodoro.startFocus()">
                            <div class="flex items-center gap-2">
                                <span class="text-white/50">⏱️</span>
                                <span class="text-xs font-semibold text-white">Mulai Pomodoro</span>
                            </div>
                            <span class="font-mono text-sm font-bold text-white/50">25:00</span>
                        </div>

                        {{-- State: Fokus / Istirahat --}}
                        <div x-show="$store.pomodoro.mode !== 'idle'" :class="$store.pomodoro.mode === 'focus' ? 'bg-indigo-500/10 border-indigo-500/20' : 'bg-emerald-500/10 border-emerald-500/20'" class="border p-3 rounded-xl flex items-center justify-between shadow-inner">
                            <div class="flex items-center gap-2.5">
                                <span class="relative flex h-2.5 w-2.5">
                                    <span x-show="$store.pomodoro.isRunning" :class="$store.pomodoro.mode === 'focus' ? 'bg-indigo-500' : 'bg-emerald-500'" class="animate-ping absolute inline-flex h-full w-full rounded-full opacity-75"></span>
                                    <span :class="$store.pomodoro.mode === 'focus' ? 'bg-indigo-500' : 'bg-emerald-500'" class="relative inline-flex rounded-full h-2.5 w-2.5 shadow-[0_0_8px_currentColor]"></span>
                                </span>
                                <div class="flex flex-col">
                                    <span class="text-[10px] font-bold uppercase tracking-wider" :class="$store.pomodoro.mode === 'focus' ? 'text-indigo-400' : 'text-emerald-400'" x-text="$store.pomodoro.mode === 'focus' ? 'Fokus Aktif' : 'Waktu Rehat'"></span>
                                    <div class="flex gap-1.5 mt-0.5">
                                        <button @click="$store.pomodoro.toggle()" class="text-[9px] font-medium opacity-70 hover:opacity-100 transition-opacity" :class="$store.pomodoro.mode === 'focus' ? 'text-indigo-300' : 'text-emerald-300'" x-text="$store.pomodoro.isRunning ? 'Pause' : 'Resume'"></button>
                                        <button @click="$store.pomodoro.reset()" class="text-[9px] font-medium opacity-70 hover:opacity-100 text-rose-400 transition-opacity">Stop</button>
                                    </div>
                                </div>
                            </div>
                            <span class="font-mono text-lg font-bold tracking-tight" :class="$store.pomodoro.mode === 'focus' ? 'text-indigo-400' : 'text-emerald-400'" x-text="$store.pomodoro.formattedTime"></span>
                        </div>
                    </div>



                    {{-- Settings Link --}}
                    <a href="{{ Route::has('profile.edit') ? route('profile.edit') : '#' }}" class="w-full flex items-center gap-3 px-3 h-10 mt-1 rounded-xl text-[13px] font-medium text-slate-300 hover:bg-white/10 hover:text-white transition-colors">
                        <svg class="w-[18px] h-[18px] shrink-0 text-slate-400 group-hover:text-white transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><circle cx="12" cy="12" r="3"></circle><path d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 0 1 0 2.83 2 2 0 0 1-2.83 0l-.06-.06a1.65 1.65 0 0 0-1.82-.33 1.65 1.65 0 0 0-1 1.51V21a2 2 0 0 1-2 2 2 2 0 0 1-2-2v-.09A1.65 1.65 0 0 0 9 19.4a1.65 1.65 0 0 0-1.82.33l-.06.06a2 2 0 0 1-2.83 0 2 2 0 0 1 0-2.83l.06-.06a1.65 1.65 0 0 0 .33-1.82 1.65 1.65 0 0 0-1.51-1H3a2 2 0 0 1-2-2 2 2 0 0 1 2-2h.09A1.65 1.65 0 0 0 4.6 9a1.65 1.65 0 0 0-.33-1.82l-.06-.06a2 2 0 0 1 0-2.83 2 2 0 0 1 2.83 0l.06.06a1.65 1.65 0 0 0 1.82.33H9a1.65 1.65 0 0 0 1-1.51V3a2 2 0 0 1 2-2 2 2 0 0 1 2 2v.09a1.65 1.65 0 0 0 1 1.51 1.65 1.65 0 0 0 1.82-.33l.06-.06a2 2 0 0 1 2.83 0 2 2 0 0 1 0 2.83l-.06.06a1.65 1.65 0 0 0-.33 1.82V9a1.65 1.65 0 0 0 1.51 1H21a2 2 0 0 1 2 2 2 2 0 0 1-2 2h-.09a1.65 1.65 0 0 0-1.51 1z"></path></svg>
                        Ruangku & Pengaturan
                    </a>

                    {{-- Logout Form & Button --}}
                    <form method="POST" action="{{ route('logout') }}" data-turbo="false" class="w-full mt-1">
                        @csrf
                        <button type="submit" class="w-full flex items-center gap-3 px-3 h-10 rounded-xl text-[13px] font-medium text-rose-400 hover:bg-rose-500/10 hover:text-rose-300 transition-colors">
                            <i class="ph ph-sign-out text-[18px]"></i>
                            pulang
                        </button>
                    </form>
                </div>
            </aside>

            {{-- MAIN CONTENT --}}
            <div class="flex-1 flex flex-col min-w-0 transition-all duration-300" :class="sidebarOpen ? 'lg:ml-[280px]' : 'ml-0'">
                
                {{-- HEADER PAGE --}}
                @if(isset($pageTitle) || isset($headerActions))
                <div class="sticky top-0 z-10 pt-4 px-4 sm:px-6 lg:px-8">
                    <header class="backdrop-nav bg-white/5 backdrop-blur-xl border border-white/10 rounded-[24px] py-4 px-6 flex flex-wrap gap-4 items-center justify-between transition-all duration-300 shadow-[0_4px_30px_rgba(0,0,0,0.2)]"
                            :class="!sidebarOpen ? 'pl-20 sm:pl-24' : ''">
                    <div>
                        @if(isset($pageTitle))
                        <h1 class="text-xl font-bold font-geist text-white dark:text-white">{{ $pageTitle }}</h1>
                        @endif
                        @if(isset($pageSubtitle))
                        <p class="text-sm text-blue-200 dark:text-slate-400 mt-1">{{ $pageSubtitle }}</p>
                        @endif
                    </div>
                    @if(isset($headerActions))
                    <div class="flex items-center gap-3">
                        {{ $headerActions }}
                    </div>
                    @endif
                    </header>
                </div>
                @endif

                {{-- PAGE CONTENT SLOT --}}
                <main class="flex-1 max-w-7xl w-full mx-auto px-4 sm:px-6 lg:px-8 py-6 lg:py-8" x-data>
                    {{ $pageHeader ?? '' }}
                    
                    {{ $slot ?? '' }}
                    @yield('content')
                </main>
            </div>
            </div>
        </turbo-frame>

        {{-- GLOBAL MUSIC PLAYER (OUTSIDE TURBO FRAME) --}}
        <div id="global-music-player" data-turbo-permanent 
             x-data="{
                init() {
                    // Update position smoothly
                    this.updatePosition = () => {
                        if (!this.$store.musicPlayer.isMaximized) {
                            // Reset styles for mini player
                            this.$el.style.position = 'fixed';
                            this.$el.style.top = 'auto';
                            this.$el.style.left = 'auto';
                            this.$el.style.bottom = '1.5rem';
                            this.$el.style.right = '1.5rem';
                            this.$el.style.width = '380px';
                            this.$el.style.height = 'auto';
                            this.$el.style.transform = this.$store.musicPlayer.isMinimized ? 'translateY(calc(100% - 60px))' : 'translateY(0)';
                            return;
                        }

                        // Maximized mode: map to full dengerin-grid
                        const gridEl = document.getElementById('dengerin-grid');
                        if (gridEl) {
                            const rect = gridEl.getBoundingClientRect();
                            this.$el.style.position = 'absolute';
                            this.$el.style.top = (rect.top + window.scrollY) + 'px';
                            this.$el.style.left = (rect.left + window.scrollX) + 'px';
                            this.$el.style.width = rect.width + 'px';
                            this.$el.style.height = rect.height + 'px';
                            this.$el.style.transform = 'translateY(0)';
                            
                            // Samakan tinggi placeholder dengan player masing-masing
                            const mainEl = document.getElementById('music-player-main');
                            const altEl = document.getElementById('music-player-alt');
                            if(mainEl) document.getElementById('player-placeholder-main').style.minHeight = mainEl.offsetHeight + 'px';
                            if(altEl) document.getElementById('player-placeholder-alt').style.minHeight = altEl.offsetHeight + 'px';
                        }
                    };

                    // Initial position
                    this.updatePosition();
                    
                    // Watchers
                    this.$watch('$store.musicPlayer.isMaximized', () => setTimeout(this.updatePosition, 50));
                    this.$watch('$store.musicPlayer.isMinimized', () => this.updatePosition());
                    
                    // Listeners
                    window.addEventListener('resize', this.updatePosition);
                    document.addEventListener('turbo:load', () => setTimeout(this.updatePosition, 100));
                    document.addEventListener('turbo:frame-load', () => setTimeout(this.updatePosition, 100));
                }
             }"
             x-show="$store.musicPlayer.isMaximized || $store.musicPlayer.miniPlayerVisible"
             class="shadow-2xl transition-all duration-300"
             :class="$store.musicPlayer.isMaximized ? 'z-[20] space-y-6 block' : 'z-[60] w-[calc(100vw-3rem)] sm:w-[380px] max-w-[380px]'">
             
             {{-- Mini Player Header (Only visible when not maximized) --}}
             <div x-show="!$store.musicPlayer.isMaximized" 
                  @click="$store.musicPlayer.toggleMinimize()"
                  class="bg-black/80 backdrop-blur-xl border border-white/10 rounded-t-2xl p-4 flex items-center justify-between cursor-pointer hover:bg-white/5 transition-colors"
                  :class="$store.musicPlayer.isMinimized ? 'rounded-b-2xl' : 'border-b-0'">
                 <h2 class="font-bold text-sm text-white flex items-center gap-2">
                     <i class="ph-fill ph-spotify-logo text-[#1DB954] text-xl"></i>
                     <span class="truncate">Ruang Musik</span>
                 </h2>
                 <div class="flex items-center gap-2 shrink-0">
                     <span class="flex h-3 w-3 relative">
                        <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-[#1DB954] opacity-75"></span>
                        <span class="relative inline-flex rounded-full h-3 w-3 bg-[#1DB954]"></span>
                     </span>
                     <i class="ph-bold text-white transition-transform" :class="$store.musicPlayer.isMinimized ? 'ph-caret-up' : 'ph-caret-down'"></i>
                     {{-- Tombol Close --}}
                     <button @click.stop="$store.musicPlayer.closeMiniPlayer()"
                             class="w-6 h-6 flex items-center justify-center rounded-full hover:bg-white/15 transition-colors ml-1"
                             title="Tutup player">
                         <i class="ph-bold ph-x text-white/50 hover:text-white text-xs"></i>
                     </button>
                 </div>
             </div>

             {{-- Playlists Container --}}
             <div class="transition-all duration-300"
                  :class="(!$store.musicPlayer.isMaximized) ? 'bg-black/80 backdrop-blur-xl border border-white/10 border-t-0 rounded-b-2xl p-4 shadow-xl overflow-y-auto max-h-[400px] space-y-4 pointer-events-auto' : 'grid grid-cols-1 lg:grid-cols-3 gap-8 pointer-events-none'"
                  x-show="$store.musicPlayer.isMaximized || !$store.musicPlayer.isMinimized">
                 
                 {{-- Main Playlist Area --}}
                 <div id="music-player-main" class="pointer-events-auto transition-all duration-300" :class="$store.musicPlayer.isMaximized ? 'lg:col-span-2 space-y-6' : ''">
                     
                     {{-- Warning (Only show when maximized) --}}
                     <div x-show="$store.musicPlayer.isMaximized" class="bg-emerald-500/10 text-emerald-400 p-4 rounded-xl text-sm font-medium flex gap-3 items-start border border-emerald-500/20 shadow-inner">
                         <i class="ph-fill ph-info text-xl shrink-0 mt-0.5"></i>
                         <p>Spotify Web Player memutar pratinjau 30 detik. Klik logo Spotify untuk mendengarkan versi penuh di aplikasi kamu.</p>
                     </div>

                     {{-- Main Playlist --}}
                     <div class="bg-black/40 backdrop-blur-xl rounded-[24px] border border-white/10 p-6 shadow-2xl relative group overflow-hidden transition-all duration-300 hover:border-[#1DB954]/40"
                          :class="!$store.musicPlayer.isMaximized ? 'p-0 border-0 shadow-none hover:shadow-none hover:border-0 rounded-xl bg-transparent' : ''">
                         <div x-show="$store.musicPlayer.isMaximized" class="absolute top-0 right-0 w-64 h-64 bg-[#1DB954]/5 rounded-full blur-3xl pointer-events-none group-hover:bg-[#1DB954]/10 transition-all duration-500"></div>
                         
                         <div x-show="$store.musicPlayer.isMaximized" class="flex items-center justify-between mb-5 relative z-10">
                             <h2 class="font-bold text-xl text-white flex items-center gap-2 font-display tracking-tight">
                                 <i class="ph-fill ph-spotify-logo text-[#1DB954] text-2xl"></i>
                                 Biar Keliatan Sibuk
                             </h2>
                             <span class="px-3 py-1 bg-white/5 text-slate-300 text-[10px] font-bold uppercase tracking-wider rounded-full border border-white/10">Curated</span>
                         </div>
                         
                         <div class="relative z-10 w-full transition-all duration-300" :class="$store.musicPlayer.isMaximized ? 'h-[352px]' : 'h-[152px]'">
                              {{-- Iframe: hanya mount setelah user klik Mulai Dengerin --}}
                              <template x-if="$store.musicPlayer.iframesLoaded">
                                  <iframe style="border-radius:16px; background-color: #121212; width: 100%; height: 100%;" src="https://open.spotify.com/embed/playlist/47toVQa6ljj6fj2YZmWyAK?si=SvdUzh0qSgq4BJ8KpZapbg&theme=0" frameBorder="0" allowfullscreen="" allow="autoplay; clipboard-write; encrypted-media; fullscreen; picture-in-picture"></iframe>
                              </template>
                              {{-- Placeholder di halaman /dengerin: loading state --}}
                              <template x-if="!$store.musicPlayer.iframesLoaded && $store.musicPlayer.isMaximized">
                                  <div class="w-full h-full rounded-[16px] bg-[#181818] border border-stone-800 flex flex-col items-center justify-center gap-3">
                                      <i class="ph-fill ph-spotify-logo text-[#1DB954] text-4xl opacity-50 animate-pulse"></i>
                                      <span class="text-blue-200 text-xs">Memuat playlist...</span>
                                  </div>
                              </template>
                              {{-- Placeholder di mini player (halaman lain) --}}
                              <template x-if="!$store.musicPlayer.iframesLoaded && !$store.musicPlayer.isMaximized">
                                  <div class="w-full h-full rounded-[16px] bg-white/5 flex flex-col items-center justify-center gap-2 border border-white/10 shadow-inner">
                                      <i class="ph-fill ph-spotify-logo text-[#1DB954] text-3xl opacity-50"></i>
                                      <span class="text-slate-400 text-xs font-medium">Klik ▲ untuk mulai dengerin</span>
                                  </div>
                              </template>
                          </div>
                     </div>
                 </div>

                 {{-- Empty Area for Pomodoro --}}
                 <div class="lg:col-span-1 hidden lg:block"></div>

                 {{-- Alt Playlists Grid --}}
                 <div id="music-player-alt" class="pointer-events-auto transition-all duration-300" :class="$store.musicPlayer.isMaximized ? 'lg:col-span-3 grid grid-cols-1 md:grid-cols-2 gap-8' : 'space-y-4'">
                     <div class="bg-black/40 backdrop-blur-xl rounded-[24px] border border-white/10 p-4 shadow-2xl hover:border-[#1DB954]/40 hover:shadow-[#1DB954]/10 transition-all duration-300 relative overflow-hidden group"
                          :class="!$store.musicPlayer.isMaximized ? 'p-0 border-0 shadow-none hover:shadow-none hover:border-0 rounded-xl bg-transparent' : ''">
                         <div x-show="$store.musicPlayer.isMaximized" class="absolute -top-10 -right-10 w-32 h-32 bg-[#1DB954]/10 rounded-full blur-2xl pointer-events-none group-hover:bg-[#1DB954]/20 transition-all"></div>
                         <div class="relative z-10 w-full h-[152px]">
                              <template x-if="$store.musicPlayer.iframesLoaded">
                                  <iframe style="border-radius:12px; background-color: #121212; width: 100%; height: 100%;" src="https://open.spotify.com/embed/playlist/5ku80zJnMiB9BdC20dU5av?si=ZOBTC7YiTLCjPSXnPeOlsA&theme=0" frameBorder="0" allowfullscreen="" allow="autoplay; clipboard-write; encrypted-media; fullscreen; picture-in-picture"></iframe>
                              </template>
                              <template x-if="!$store.musicPlayer.iframesLoaded">
                                  <div class="w-full h-full rounded-[12px] bg-[#181818] border border-stone-800 flex items-center justify-center">
                                      <i class="ph-fill ph-spotify-logo text-[#1DB954] text-2xl opacity-40"></i>
                                  </div>
                              </template>
                          </div>
                     </div>
                     <div class="bg-black/40 backdrop-blur-xl rounded-[24px] border border-white/10 p-4 shadow-2xl hover:border-[#1DB954]/40 hover:shadow-[#1DB954]/10 transition-all duration-300 relative overflow-hidden group"
                          :class="!$store.musicPlayer.isMaximized ? 'p-0 border-0 shadow-none hover:shadow-none hover:border-0 rounded-xl bg-transparent' : ''">
                         <div x-show="$store.musicPlayer.isMaximized" class="absolute -top-10 -right-10 w-32 h-32 bg-[#1DB954]/10 rounded-full blur-2xl pointer-events-none group-hover:bg-[#1DB954]/20 transition-all"></div>
                         <div class="relative z-10 w-full h-[152px]">
                              <template x-if="$store.musicPlayer.iframesLoaded">
                                  <iframe style="border-radius:12px; background-color: #121212; width: 100%; height: 100%;" src="https://open.spotify.com/embed/playlist/5yrG1vCRbRheSMivUmwdOk?si=ZEF-1htWQuCJwnZQ7BZdcQ&theme=0" frameBorder="0" allowfullscreen="" allow="autoplay; clipboard-write; encrypted-media; fullscreen; picture-in-picture"></iframe>
                              </template>
                              <template x-if="!$store.musicPlayer.iframesLoaded">
                                  <div class="w-full h-full rounded-[12px] bg-[#181818] border border-stone-800 flex items-center justify-center">
                                      <i class="ph-fill ph-spotify-logo text-[#1DB954] text-2xl opacity-40"></i>
                                  </div>
                              </template>
                          </div>
                     </div>
                 </div>
             </div>
        </div>

        {{-- Duck Mascot Component --}}
        <x-duck />

        @stack('scripts')
        <script>
            function showPage() {
                document.body.classList.remove('opacity-0');
                document.body.classList.add('duration-300', 'transition-opacity');
            }
            // DOMContentLoaded: jauh lebih cepat dari window.load
            document.addEventListener('DOMContentLoaded', showPage);
            // turbo:load dihandle di app.js (tidak duplikat di sini)
        </script>
    </body>
</html>