@extends('layouts.admin')

@section('content')
<div class="space-y-6">
    <div class="flex items-center justify-between">
        <h2 class="text-lg font-bold text-black">AI Summary Requests (Manual)</h2>
    </div>

    @if(session('success'))
        <div class="bg-emerald-50 border border-emerald-500 text-emerald-700 px-4 py-3 text-sm font-bold">
            {{ session('success') }}
        </div>
    @endif

    <div class="dev-card overflow-hidden">
        <table class="w-full text-left text-sm whitespace-nowrap">
            <thead>
                <tr class="border-b border-black bg-[#f8f9fa] text-xs uppercase tracking-wider">
                    <th class="px-6 py-4 font-bold text-black border-r border-black">Date</th>
                    <th class="px-6 py-4 font-bold text-black border-r border-black">User</th>
                    <th class="px-6 py-4 font-bold text-black border-r border-black">Subject</th>
                    <th class="px-6 py-4 font-bold text-black border-r border-black">Source</th>
                    <th class="px-6 py-4 font-bold text-black border-r border-black text-center">Status</th>
                    <th class="px-6 py-4 font-bold text-black text-center">Action</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-black">
                @forelse($requests as $req)
                    <tr class="hover:bg-slate-50">
                        <td class="px-6 py-4 text-slate-500 border-r border-black">{{ $req->created_at->format('d M Y H:i') }}</td>
                        <td class="px-6 py-4 font-bold text-black border-r border-black">{{ $req->user->name ?? 'Unknown' }}</td>
                        <td class="px-6 py-4 text-black border-r border-black">{{ $req->subject->name ?? '-' }}</td>
                        <td class="px-6 py-4 text-black border-r border-black" title="{{ $req->material ? $req->material->title : 'Manual Text' }}">
                            @if($req->material)
                                <span class="font-bold text-xs bg-blue-100 text-blue-800 px-2 py-1 rounded">Material</span> 
                                {{ Str::limit($req->material->title, 30) }}
                            @else
                                <span class="font-bold text-xs bg-amber-100 text-amber-800 px-2 py-1 rounded">Manual Text</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 border-r border-black text-center space-y-1">
                            @if($req->status === 'completed')
                                <div class="inline-flex items-center px-2 py-0.5 bg-emerald-100 text-emerald-800 text-[10px] font-bold border border-emerald-300 rounded">
                                    ✅ Selesai
                                </div>
                            @else
                                <div class="inline-flex items-center px-2 py-0.5 bg-rose-100 text-rose-800 text-[10px] font-bold border border-rose-300 rounded animate-pulse">
                                    ⏳ Menunggu
                                </div>
                            @endif
                        </td>
                        <td class="px-6 py-4 font-medium text-center">
                            @if($req->status === 'pending')
                                <a href="{{ route('admin.summary_requests.fulfill', $req->id) }}" class="inline-block bg-black text-white px-4 py-1.5 text-xs font-bold border border-black hover:bg-slate-800 transition-colors">
                                    Kerjakan
                                </a>
                            @else
                                <span class="text-xs text-slate-400">Done</span>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="px-6 py-8 text-center text-slate-500 font-medium">
                            Belum ada request rangkuman.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    
    <div class="mt-4">
        {{ $requests->links() }}
    </div>
</div>
@endsection
