@extends('layouts.admin')

@section('content')
<div class="max-w-4xl mx-auto space-y-6">
    <div class="flex items-center gap-4 mb-6">
        <a href="{{ route('admin.summary_requests.index') }}" class="w-10 h-10 bg-white border border-black flex items-center justify-center hover:bg-slate-50 transition-colors">
            &larr;
        </a>
        <h2 class="text-xl font-bold text-black">Fulfill Summary Request</h2>
    </div>

    {{-- Request Info --}}
    <div class="dev-card p-6 bg-slate-50">
        <h3 class="font-bold text-black mb-4">Request Information</h3>
        <div class="grid grid-cols-2 gap-4 text-sm">
            <div>
                <span class="block text-slate-500 mb-1">User</span>
                <span class="font-bold text-black">{{ $summaryRequest->user->name ?? 'Unknown' }}</span>
            </div>
            <div>
                <span class="block text-slate-500 mb-1">Subject</span>
                <span class="font-bold text-black">{{ $summaryRequest->subject->name ?? 'Unknown' }}</span>
            </div>
            <div class="col-span-2">
                <span class="block text-slate-500 mb-1">Source Text to Summarize</span>
                <div class="p-4 bg-white text-black border border-slate-200 mt-2 max-h-60 overflow-y-auto whitespace-pre-wrap text-[13px] font-serif">
@if($summaryRequest->material)
{{ $summaryRequest->material->content ?: '(File PDF/Doc, silakan cek manual materinya: ' . $summaryRequest->material->title . ')' }}
@else
{{ $summaryRequest->manual_text }}
@endif
                </div>
            </div>
        </div>
    </div>

    {{-- Fulfillment Form --}}
    <form action="{{ route('admin.summary_requests.storeNote', $summaryRequest->id) }}" method="POST" class="space-y-6">
        @csrf
        <div class="dev-card p-6">
            <h3 class="font-bold text-black mb-4 border-b border-black pb-2">Buatkan Rangkuman (Kirim sebagai Catatan ke User)</h3>
            
            <div class="space-y-4">
                <div>
                    <label class="block text-xs font-bold text-black mb-1">Judul Catatan</label>
                    <input type="text" name="title" value="Rangkuman: {{ $summaryRequest->material ? $summaryRequest->material->title : 'Catatan AI' }}" required class="w-full border border-black p-2 text-sm text-black bg-white focus:outline-none focus:border-black placeholder:text-slate-400">
                </div>

                <div>
                    <label class="block text-xs font-bold text-black mb-1">Isi Rangkuman</label>
                    <textarea name="content" rows="12" required placeholder="Ketik hasil rangkuman di sini..." class="w-full border border-black p-4 text-sm text-black bg-white focus:outline-none focus:border-black font-serif-editor leading-relaxed placeholder:text-slate-400"></textarea>
                </div>
            </div>
        </div>

        <div class="flex justify-end gap-3">
            <a href="{{ route('admin.summary_requests.index') }}" class="px-6 py-2 bg-white text-black font-bold border border-black hover:bg-slate-50 transition-colors">
                Batal
            </a>
            <button type="submit" class="px-6 py-2 bg-black text-white font-bold border border-black hover:bg-slate-800 transition-colors">
                Kirim Rangkuman
            </button>
        </div>
    </form>
</div>
@endsection
