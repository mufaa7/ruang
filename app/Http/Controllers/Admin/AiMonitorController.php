<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\AiUsageLog;

class AiMonitorController extends Controller
{
    public function index()
    {
        $todayLogs = AiUsageLog::whereDate('created_at', today());
        
        $stats = [
            'total_requests' => AiUsageLog::count(),
            'total_tokens' => AiUsageLog::sum('total_tokens'),
            'today_requests' => $todayLogs->count(),
            'today_tokens' => $todayLogs->sum('total_tokens'),
            'avg_tokens_per_request' => AiUsageLog::count() > 0 ? round(AiUsageLog::sum('total_tokens') / AiUsageLog::count()) : 0,
            'quota_percentage' => min(100, round(($todayLogs->count() / 1500) * 100))
        ];

        // Group by feature_name (endpoint) to see average tokens and duration
        $endpointStats = AiUsageLog::selectRaw('
            feature_name, 
            COUNT(*) as total_requests,
            AVG(total_tokens) as avg_tokens,
            AVG(duration) as avg_duration
        ')->groupBy('feature_name')->get();

        $logs = AiUsageLog::with('user')->latest()->paginate(50);

        return view('admin.monitor.index', compact('stats', 'endpointStats', 'logs'));
    }
}
