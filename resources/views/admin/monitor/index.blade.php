@extends('layouts.admin')

@section('content')
<div class="space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-3xl font-black text-black uppercase tracking-tight">AI Monitor</h1>
            <p class="text-slate-600 text-sm mt-1">Pemantauan riwayat penggunaan token AI Gemini secara real-time.</p>
        </div>
    </div>

    {{-- Stats Row --}}
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
        <div class="dev-card p-5">
            <h3 class="text-xs font-bold uppercase text-slate-500 mb-1">Total Requests</h3>
            <div class="text-3xl font-black text-black">{{ number_format($stats['total_requests']) }}</div>
            <div class="text-[10px] font-medium text-slate-600 mt-2 bg-slate-100 px-2 py-1 inline-block border border-black">
                TODAY: {{ number_format($stats['today_requests']) }}
            </div>
        </div>

        <div class="dev-card p-5">
            <h3 class="text-xs font-bold uppercase text-slate-500 mb-1">Total Tokens</h3>
            <div class="text-3xl font-black text-black">{{ number_format($stats['total_tokens']) }}</div>
            <div class="text-[10px] font-medium text-slate-600 mt-2 bg-slate-100 px-2 py-1 inline-block border border-black">
                TODAY: {{ number_format($stats['today_tokens']) }}
            </div>
        </div>

        <div class="dev-card p-5">
            <h3 class="text-xs font-bold uppercase text-slate-500 mb-1">Avg Tokens / Request</h3>
            <div class="text-3xl font-black text-black">{{ number_format($stats['avg_tokens_per_request']) }}</div>
            <div class="text-[10px] font-medium text-slate-600 mt-2 bg-slate-100 px-2 py-1 inline-block border border-black">
                AVERAGE
            </div>
        </div>

        <div class="dev-card p-5 bg-black text-white">
            <div class="flex justify-between items-center mb-1">
                <h3 class="text-xs font-bold uppercase text-slate-400">Daily Quota</h3>
                <span class="text-xs font-bold text-emerald-400">{{ $stats['quota_percentage'] }}%</span>
            </div>
            <div class="text-3xl font-black">{{ number_format($stats['today_requests']) }} <span class="text-lg text-slate-400 font-normal">/ 1500</span></div>
            <div class="w-full bg-slate-800 h-2 mt-3 overflow-hidden">
                <div class="{{ $stats['quota_percentage'] > 80 ? 'bg-rose-500' : 'bg-emerald-500' }} h-full" style="width: {{ $stats['quota_percentage'] }}%"></div>
            </div>
        </div>
    </div>

    {{-- Endpoint / Feature Stats --}}
    @if($endpointStats->count() > 0)
    <div class="dev-card mb-6">
        <div class="p-5 border-b border-black flex justify-between items-center bg-white">
            <h2 class="font-bold text-black uppercase">Statistik Per Fitur (Endpoint)</h2>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="border-b-2 border-black bg-slate-50">
                        <th class="p-3 text-xs font-bold text-black uppercase">Fitur / Endpoint</th>
                        <th class="p-3 text-xs font-bold text-black uppercase text-right">Total Request</th>
                        <th class="p-3 text-xs font-bold text-black uppercase text-right">Rata-rata Token</th>
                        <th class="p-3 text-xs font-bold text-black uppercase text-right">Rata-rata Durasi</th>
                    </tr>
                </thead>
                <tbody class="text-sm font-medium">
                    @foreach($endpointStats as $stat)
                        <tr class="border-b border-slate-200 hover:bg-slate-50 transition-colors">
                            <td class="p-3 border-r border-slate-200">
                                <span class="text-slate-700 font-bold uppercase">{{ $stat->feature_name ?? 'UNKNOWN' }}</span>
                            </td>
                            <td class="p-3 border-r border-slate-200 text-right">
                                {{ number_format($stat->total_requests) }}
                            </td>
                            <td class="p-3 border-r border-slate-200 text-right">
                                <span class="font-black text-black">{{ number_format($stat->avg_tokens) }}</span>
                                <span class="text-[10px] text-slate-500 ml-1">tokens</span>
                            </td>
                            <td class="p-3 text-right">
                                <span class="font-bold text-slate-700">{{ number_format($stat->avg_duration, 2) }}</span>
                                <span class="text-[10px] text-slate-500 ml-1">detik</span>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    @endif

    {{-- Data Table --}}
    <div class="dev-card">
        <div class="p-5 border-b border-black flex justify-between items-center bg-white">
            <h2 class="font-bold text-black uppercase">Riwayat Log</h2>
            <span class="text-xs font-bold bg-black text-white px-2 py-1">50 TERBARU</span>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="border-b-2 border-black bg-slate-50">
                        <th class="p-3 text-xs font-bold text-black uppercase">ID</th>
                        <th class="p-3 text-xs font-bold text-black uppercase">Status</th>
                        <th class="p-3 text-xs font-bold text-black uppercase">Timestamp</th>
                        <th class="p-3 text-xs font-bold text-black uppercase">User</th>
                        <th class="p-3 text-xs font-bold text-black uppercase">Feature</th>
                        <th class="p-3 text-xs font-bold text-black uppercase text-right">Durasi</th>
                        <th class="p-3 text-xs font-bold text-black uppercase text-right">Tokens</th>
                    </tr>
                </thead>
                <tbody class="text-sm font-medium">
                    @forelse($logs as $log)
                        <tr class="border-b border-slate-200 hover:bg-slate-50 transition-colors">
                            <td class="p-3 border-r border-slate-200 text-slate-500">#{{ $log->id }}</td>
                            <td class="p-3 border-r border-slate-200">
                                @if($log->status === 'success')
                                    <span class="bg-emerald-100 text-emerald-800 border border-emerald-200 px-2 py-0.5 text-xs font-bold">SUCCESS</span>
                                @else
                                    <span class="bg-rose-100 text-rose-800 border border-rose-200 px-2 py-0.5 text-xs font-bold cursor-help" title="{{ $log->error_message }}">FAILED</span>
                                @endif
                            </td>
                            <td class="p-3 border-r border-slate-200">
                                {{ $log->created_at->format('d M Y') }}
                                <span class="text-xs text-slate-500 ml-1">{{ $log->created_at->format('H:i:s') }}</span>
                            </td>
                            <td class="p-3 border-r border-slate-200">
                                @if($log->user)
                                    <span class="text-black font-bold">{{ $log->user->name }}</span>
                                @else
                                    <span class="text-slate-400 italic">System/Guest</span>
                                @endif
                                <div class="text-[10px] text-slate-500 mt-1">{{ $log->ip_address }}</div>
                            </td>
                            <td class="p-3 border-r border-slate-200">
                                <span class="text-slate-700 font-bold">{{ $log->feature_name ?? 'unknown' }}</span>
                                <div class="text-[10px] bg-slate-200 text-slate-600 px-1 py-0.5 inline-block mt-1 uppercase">{{ $log->model }}</div>
                            </td>
                            <td class="p-3 border-r border-slate-200 text-right">
                                @if($log->duration)
                                    <span class="font-bold text-slate-700">{{ number_format($log->duration, 2) }}s</span>
                                @else
                                    <span class="text-slate-400">-</span>
                                @endif
                            </td>
                            <td class="p-3 text-right">
                                <div class="font-black text-black">{{ number_format($log->total_tokens) }}</div>
                                <div class="text-[10px] text-slate-500 mt-1">
                                    {{ number_format($log->prompt_tokens) }} (P) + {{ number_format($log->completion_tokens) }} (C)
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="p-8 text-center text-slate-500 italic">Belum ada riwayat penggunaan AI.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        @if($logs->hasPages())
            <div class="p-4 border-t border-black bg-slate-50">
                {{ $logs->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
