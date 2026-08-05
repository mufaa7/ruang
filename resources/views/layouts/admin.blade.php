<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <title>Developer Dashboard</title>
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=IBM+Plex+Mono:wght@400;500;600;700&display=swap" rel="stylesheet">
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        <script type="module" src="https://unpkg.com/@hotwired/turbo@8.0.4"></script>
        <style>
            body { font-family: 'IBM Plex Mono', monospace; }
            /* Vercel/Win95 hybrid: Clean 1px solid black border, sharp edges */
            .dev-card {
                background-color: #fff;
                border: 1px solid #000;
                box-shadow: 2px 2px 0 rgba(0,0,0,0.05); /* very subtle solid shadow to ground it */
            }
            .dev-btn {
                border: 1px solid #000;
                background-color: #fff;
                transition: all 0.1s;
            }
            .dev-btn:hover {
                background-color: #f3f4f6;
            }
            .dev-btn:active {
                background-color: #e5e7eb;
            }
            /* Minimal scrollbar for console */
            .console-scroll::-webkit-scrollbar {
                width: 6px;
            }
            .console-scroll::-webkit-scrollbar-track {
                background: #000;
            }
            .console-scroll::-webkit-scrollbar-thumb {
                background: #444; 
            }
        </style>
    </head>
    <body class="antialiased min-h-screen bg-white text-slate-900 dark:bg-slate-900 dark:text-white">
        @if(session()->has('impersonated_by'))
        <div class="bg-amber-400 text-black px-4 py-2 text-center text-sm font-bold flex justify-center items-center gap-4 z-50 relative border-b border-black">
            <span><i class="ph ph-warning text-[1.1em] align-middle"></i>️ You are impersonating <strong>{{ auth()->user()->name }}</strong></span>
            <form action="{{ route('impersonate.leave') }}" method="POST" class="inline">
                @csrf
                <button type="submit" class="bg-black text-white px-3 py-1 text-xs border border-black hover:bg-slate-800">Leave Impersonate</button>
            </form>
        </div>
        @endif
        
        <div class="flex min-h-screen relative" id="admin-container">
            
            <!-- Mobile overlay -->
            <div id="mobile-overlay" class="fixed inset-0 bg-black/50 z-20 hidden lg:hidden" onclick="toggleSidebar()"></div>

            {{-- SIDEBAR --}}
            <aside id="sidebar" class="w-[240px] shrink-0 flex-col border-r border-black bg-white flex h-screen fixed lg:sticky top-0 left-0 z-30 transition-transform duration-200 -translate-x-full lg:translate-x-0 dark:bg-slate-900">
                <div class="p-5 border-b border-black">
                    <h1 class="font-bold text-sm tracking-tight flex items-center gap-2">
                        <span class="w-3 h-3 bg-black"></span>
                        RUANG_DEV
                    </h1>
                </div>

                <nav class="flex-1 px-4 py-5 space-y-1 overflow-y-auto">
                    @php
                        $navItems = [
                            ['label' => 'Overview',     'route' => 'admin.dashboard'],
                            ['label' => 'Users',        'route' => 'admin.users.index'],
                            ['label' => 'Quizzes & Flashcards', 'route' => 'admin.quizzes.index'],
                            ['label' => 'Documents',    'route' => 'admin.materials.index'],
                            ['label' => 'AI Monitor',   'route' => 'admin.monitor.index'],
                            ['label' => 'AI Settings',  'route' => 'admin.ai_settings.index'],
                            ['label' => 'Error Logs',   'route' => '#'],
                            ['label' => 'Queue Jobs',   'route' => '#'],
                            ['label' => 'Settings',     'route' => '#'],
                        ];
                    @endphp

                    @foreach($navItems as $item)
                        @php 
                            $route = Route::has($item['route']) ? route($item['route']) : '#';
                            $active = request()->routeIs(str_replace('index', '*', $item['route'])); 
                        @endphp
                        <a href="{{ $route }}" class="block px-3 py-1.5 text-[13px] border {{ $active ? 'bg-black text-white border-black' : 'border-transparent text-black hover:border-black' }} transition-colors">
                            {{ $item['label'] }}
                        </a>
                    @endforeach
                    <a href="{{ route('admin.summary_requests.index') }}" 
                       class="block px-3 py-1.5 text-[13px] border {{ request()->routeIs('admin.summary_requests.*') ? 'bg-black text-white border-black' : 'border-transparent text-black hover:border-black' }} transition-colors">
                        AI Requests
                    </a>
                </nav>

                <div class="p-4 border-t border-black bg-white space-y-2 dark:bg-slate-900">
                    <a href="{{ route('dashboard') }}" class="dev-btn block w-full px-3 py-1.5 text-[12px] text-center font-bold text-black">
                        ← Back to App
                    </a>
                    <form method="POST" action="{{ route('logout') }}" class="w-full">
                        @csrf
                        <button type="submit" class="w-full bg-black text-white px-3 py-1.5 text-[12px] font-bold border border-black hover:bg-slate-800 transition-colors">
                            Logout
                        </button>
                    </form>
                </div>
            </aside>

            {{-- MAIN CONTENT --}}
            <div class="flex-1 flex flex-col min-w-0 w-full overflow-x-hidden">
                <header class="bg-white border-b border-black px-4 lg:px-8 py-4 flex items-center justify-between sticky top-0 z-10 dark:bg-slate-900">
                    <div class="flex items-center gap-3">
                        <button onclick="toggleSidebar()" class="lg:hidden p-1.5 border border-black hover:bg-slate-100 bg-white dark:bg-slate-800 dark:hover:bg-slate-700">
                            <svg class="w-5 h-5 text-black dark:text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="square" stroke-linejoin="miter" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path></svg>
                        </button>
                        <div>
                            <h1 class="text-[15px] font-bold text-black">{{ $pageTitle ?? 'System Overview' }}</h1>
                            <div class="text-[11px] text-slate-500 mt-1 hidden sm:flex gap-4">
                                <span>{{ now()->format('l, d F Y') }}</span>
                                <span>{{ now()->timezone('Asia/Jakarta')->format('H:i:s') }} WIB</span>
                                <span class="text-black font-bold">Production</span>
                            </div>
                        </div>
                    </div>
                    <div class="flex items-center gap-2 text-[11px] text-black font-bold border border-black px-2 py-1 bg-white dark:bg-slate-900">
                        <span>Status: <span class="text-emerald-600 font-bold">● All Systems Nominal</span></span>
                    </div>
                </header>

                <main class="flex-1 p-4 md:p-8 w-full max-w-[1400px] mx-auto overflow-x-hidden">
                    @yield('content')
                </main>
            </div>
        </div>
        @stack('scripts')
        <script>
            function toggleSidebar() {
                const sidebar = document.getElementById('sidebar');
                const overlay = document.getElementById('mobile-overlay');
                sidebar.classList.toggle('-translate-x-full');
                overlay.classList.toggle('hidden');
            }
        </script>
    </body>
</html>
