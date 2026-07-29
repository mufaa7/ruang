@extends('layouts.admin')

@section('content')
<div class="space-y-6">

    {{-- 3 Column Masonry-like Grid --}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 items-start">
        
        {{-- KOLOM 1 --}}
        <div class="space-y-6">
            {{-- Request Realtime --}}
            <div class="dev-card p-5">
                <h2 class="text-[13px] font-bold text-black mb-4">Request realtime</h2>
                <div class="space-y-2.5 text-[12px]">
                    <div class="flex justify-between items-center">
                        <span class="text-slate-600">GET</span>
                        <span class="font-medium text-black">{{ $requests['GET'] ?? 0 }}</span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-slate-600">POST</span>
                        <span class="font-medium text-black">{{ $requests['POST'] ?? 0 }}</span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-slate-600">PATCH</span>
                        <span class="font-medium text-black">{{ $requests['PATCH'] ?? 0 }}</span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-slate-600">DELETE</span>
                        <span class="font-medium text-black">{{ $requests['DELETE'] ?? 0 }}</span>
                    </div>
                </div>
            </div>

            {{-- Recent Activity (Git Log Style) --}}
            <div class="dev-card p-5">
                <h2 class="text-[13px] font-bold text-black mb-4">Recent Activity</h2>
                <div class="space-y-3 text-[11px] font-mono">
                    @forelse($recentActivities as $index => $activity)
                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap-3">
                                <span class="text-slate-500">{{ $activity->created_at->format('H:i') }}</span>
                                <span class="font-bold text-black">{{ strtok($activity->user->name ?? 'System', ' ') }}</span>
                                <span class="text-slate-600 truncate w-24" title="{{ $activity->description }}">{{ str()->limit($activity->description, 15) }}</span>
                            </div>
                            <div class="flex items-center gap-2">
                                <span class="text-slate-500">{{ rand(1, 9) / 10 }}s</span>
                                <span class="text-black font-bold">✔</span>
                            </div>
                        </div>
                        @if(!$loop->last)
                            <div class="border-b border-dashed border-slate-300"></div>
                        @endif
                    @empty
                        <div class="text-slate-500 text-center italic">No recent activity</div>
                    @endforelse
                </div>
            </div>

            {{-- Bash Notes --}}
            <div class="dev-card p-5">
                <h2 class="text-[13px] font-bold text-black mb-4">Bash Notes</h2>
                <form action="{{ route('admin.dashboard.notes') }}" method="POST">
                    @csrf
                    <textarea name="admin_notes" rows="4" class="w-full text-[11px] font-mono border border-black p-2 bg-[#f8f9fa] focus:outline-none focus:ring-0 placeholder:text-slate-400" placeholder="Catat command bash penting di sini...">{{ $adminNotes ?? '' }}</textarea>
                    <div class="mt-2 text-right">
                        <button type="submit" class="bg-black text-white px-3 py-1 text-[11px] font-bold uppercase hover:bg-slate-800 border border-black">Save Notes</button>
                    </div>
                </form>
            </div>
        </div>

        {{-- KOLOM 2 --}}
        <div class="space-y-6">
            {{-- AI Cost --}}
            <div class="dev-card p-5">
                <div class="flex justify-between items-center mb-4">
                    <h2 class="text-[13px] font-bold text-black">AI Usage Monitor</h2>
                    <span class="text-[10px] bg-white text-black px-1.5 py-0.5 border border-black font-bold uppercase dark:bg-slate-900">Gemini</span>
                </div>
                
                <div class="space-y-3 text-[12px]">
                    <div class="flex justify-between items-center">
                        <span class="text-slate-600">Requests Today</span>
                        <span class="font-medium text-black">{{ $aiCost['requests'] }} / 1500</span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-slate-600">Tokens Today</span>
                        <span class="font-medium text-black">{{ $aiCost['tokens'] }}</span>
                    </div>
                    
                    <div class="pt-2">
                        <div class="flex justify-between items-center mb-1">
                            <span class="text-slate-600">Daily Quota</span>
                            <span class="font-bold {{ $aiCost['limit_val'] > 80 ? 'text-rose-600' : 'text-black' }}">{{ $aiCost['limit'] }}</span>
                        </div>
                        <div class="w-full bg-slate-200 h-2 border border-black overflow-hidden flex">
                            <div class="{{ $aiCost['limit_val'] > 80 ? 'bg-rose-500' : 'bg-black' }} h-full" style="width: {{ $aiCost['limit'] }}"></div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Analytics --}}
            <div class="dev-card p-5">
                <h2 class="text-[13px] font-bold text-black mb-4">Analytics</h2>
                
                <div class="flex justify-between items-end border-b border-black pb-3 mb-3">
                    <div>
                        <div class="text-[10px] text-slate-500 mb-0.5">Hari ini</div>
                        <div class="font-bold text-black text-xl leading-none">{{ $analytics['hari_ini'] }} <span class="text-[10px] font-normal text-slate-600">dokumen</span></div>
                    </div>
                    <div class="text-right">
                        <div class="text-[10px] text-slate-500">Kemarin</div>
                        <div class="font-medium text-black text-[12px]">{{ $analytics['kemarin'] }}</div>
                        <div class="{{ $analytics['percent'] >= 0 ? 'text-emerald-600' : 'text-rose-600' }} text-[10px] font-bold mt-0.5">
                            {{ $analytics['percent'] >= 0 ? '↑' : '↓' }} {{ abs($analytics['percent']) }}%
                        </div>
                    </div>
                </div>
                
                <div class="space-y-2.5 text-[12px]">
                    <div class="flex justify-between items-center">
                        <span class="text-slate-600">Export PDF</span>
                        <span class="font-medium text-black">{{ $analytics['pdf'] }}</span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-slate-600">Export DOCX</span>
                        <span class="font-medium text-black">{{ $analytics['docx'] }}</span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-slate-600">AI Generate</span>
                        <span class="font-medium text-black">{{ $analytics['ai'] }}</span>
                    </div>
                </div>
            </div>
            
            {{-- System Health --}}
            <div class="dev-card p-5">
                <h2 class="text-[13px] font-bold text-black mb-4">System Health</h2>
                <div class="space-y-3 text-[12px]">
                    <div class="flex justify-between items-center">
                        <span class="text-slate-600">Database</span>
                        <div class="flex items-center gap-2">
                            <span class="w-2 h-2 {{ $health['database'] === 'Connected' ? 'bg-emerald-500' : 'bg-rose-500' }} border border-black"></span>
                            <span class="font-medium text-black">{{ $health['database'] }}</span>
                        </div>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-slate-600">Redis</span>
                        <div class="flex items-center gap-2">
                            <span class="w-2 h-2 {{ str_contains($health['redis'], 'Ready') ? 'bg-emerald-500' : 'bg-slate-500' }} border border-black"></span>
                            <span class="font-medium text-black">{{ $health['redis'] }}</span>
                        </div>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-slate-600">Storage</span>
                        <div class="flex items-center gap-2">
                            <span class="w-2 h-2 {{ $health['storage'] === 'Healthy' ? 'bg-emerald-500' : 'bg-rose-500' }} border border-black"></span>
                            <span class="font-medium text-black">{{ $health['storage'] }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- KOLOM 3 --}}
        <div class="space-y-6">
            {{-- Server --}}
            <div class="dev-card p-5">
                <h2 class="text-[13px] font-bold text-black mb-4">Server</h2>
                <div class="space-y-2.5 text-[12px]">
                    <div class="flex justify-between items-center">
                        <span class="text-slate-600">CPU</span>
                        <span class="font-medium text-black">{{ $server['cpu'] }}</span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-slate-600">RAM</span>
                        <span class="font-medium text-black">{{ $server['ram'] }}</span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-slate-600">Disk</span>
                        <span class="font-medium text-black">{{ $server['disk'] }}</span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-slate-600">Temp</span>
                        <span class="font-medium text-black">{{ $server['temp'] }}</span>
                    </div>
                </div>
            </div>

            {{-- Environment & Exceptions --}}
            <div class="dev-card p-5 flex flex-col justify-between">
                <div>
                    <h2 class="text-[13px] font-bold text-black mb-4">Environment</h2>
                    <div class="space-y-2 text-[12px] text-slate-600">
                        <div class="flex justify-between"><span>APP_ENV</span><span class="text-black font-medium">{{ $env['app_env'] }}</span></div>
                        <div class="flex justify-between"><span>APP_DEBUG</span><span class="text-black font-medium">{{ $env['app_debug'] }}</span></div>
                        <div class="flex justify-between"><span>Queue</span><span class="text-black font-medium">{{ $env['queue'] }}</span></div>
                        <div class="flex justify-between"><span>Schedule</span><span class="text-black font-medium">{{ $env['schedule'] }}</span></div>
                        <div class="flex justify-between"><span>Timezone</span><span class="text-black font-medium">{{ $env['timezone'] }}</span></div>
                        <div class="flex justify-between"><span>Cache</span><span class="text-black font-medium">{{ $env['cache'] }}</span></div>
                        <div class="flex justify-between"><span>Laravel</span><span class="text-black font-medium">{{ $env['laravel'] }}</span></div>
                        <div class="flex justify-between"><span>PHP</span><span class="text-black font-medium">{{ $env['php'] }}</span></div>
                    </div>
                </div>
                
                <div class="mt-4 pt-4 border-t border-black">
                    <div class="flex items-center justify-between mb-1.5">
                        <h2 class="text-[12px] font-bold text-black flex items-center gap-2">
                            <span class="w-2 h-2 {{ $lastException === 'No recent exceptions found in log.' ? 'bg-emerald-500' : 'bg-rose-500' }} border border-black"></span>
                            Last Exception
                        </h2>
                    </div>
                    <p class="text-[11px] text-black line-clamp-2 bg-[#f8f9fa] p-2 border border-black" title="{{ $lastException }}">{{ $lastException }}</p>
                </div>
            </div>
        </div>

    </div>

    {{-- ROW 3: Terminal Console --}}
    <div class="dev-card p-0 overflow-hidden bg-white text-black font-mono text-[12px] flex flex-col h-[200px] dark:bg-slate-900">
        <div class="flex items-center justify-between px-4 py-2 border-b border-black bg-[#f8f9fa]">
            <div class="flex gap-2">
                <span class="w-3 h-3 border border-black bg-white dark:bg-slate-900"></span>
                <span class="w-3 h-3 border border-black bg-white dark:bg-slate-900"></span>
                <span class="w-3 h-3 border border-black bg-white dark:bg-slate-900"></span>
            </div>
            <span class="text-black font-bold text-[11px] uppercase">
                Terminal
            </span>
        </div>
        <div class="flex-1 p-4 overflow-y-auto console-scroll space-y-1.5">
            @forelse($terminal as $log)
                <div>
                    <span class="text-slate-500">[{{ $log['time'] }}]</span> 
                    <span class="{{ $log['level'] === 'ERR!' ? 'text-rose-600' : ($log['level'] === 'WARN' ? 'text-amber-500' : '') }} font-bold">{{ $log['level'] }}</span> 
                    <span class="{{ $log['level'] === 'ERR!' ? 'text-rose-600' : '' }}">{{ $log['message'] }}</span>
                </div>
            @empty
                <div><span class="text-slate-500">[{{ date('H:i:s') }}]</span> <span class="font-bold">INFO</span> No recent logs found.</div>
            @endforelse
            <div><span class="text-black font-bold">[{{ date('H:i:s') }}]</span> <span class="animate-pulse">_</span></div>
        </div>
    </div>

</div>
@endsection
