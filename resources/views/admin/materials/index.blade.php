@extends('layouts.admin')

@section('content')
<div class="space-y-6">
    <div class="flex items-center justify-between">
        <h2 class="text-lg font-bold text-black">Documents & Materials</h2>
    </div>

    @if(session('success'))
        <div class="bg-emerald-50 border border-emerald-500 text-emerald-700 px-4 py-3 text-sm font-bold">
            {{ session('success') }}
        </div>
    @endif

    <div class="dev-card overflow-x-auto">
        <table class="w-full text-left text-sm whitespace-nowrap min-w-max">
            <thead>
                <tr class="border-b border-black bg-[#f8f9fa] text-xs uppercase tracking-wider">
                    <th class="px-6 py-4 font-bold text-black border-r border-black">Date</th>
                    <th class="px-6 py-4 font-bold text-black border-r border-black">User</th>
                    <th class="px-6 py-4 font-bold text-black border-r border-black">Subject</th>
                    <th class="px-6 py-4 font-bold text-black border-r border-black">Material Title</th>
                    <th class="px-6 py-4 font-bold text-black border-r border-black text-center">Status (Click to Create)</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-black">
                @forelse($materials as $material)
                    <tr class="hover:bg-slate-50">
                        <td class="px-6 py-4 text-slate-500 border-r border-black">{{ $material->created_at->format('d M Y H:i') }}</td>
                        <td class="px-6 py-4 font-bold text-black border-r border-black">{{ $material->user->name ?? 'Unknown' }}</td>
                        <td class="px-6 py-4 text-black border-r border-black">{{ $material->subject->name ?? '-' }}</td>
                        <td class="px-6 py-4 font-bold text-black border-r border-black" title="{{ $material->title }}">
                            @if($material->file_path)
                                <a href="{{ asset('storage/' . $material->file_path) }}" target="_blank" class="hover:underline hover:text-indigo-600 flex items-center gap-1">
                                    <i class="ph ph-file-pdf"></i>
                                    {{ Str::limit($material->title, 40) }}
                                </a>
                            @else
                                <button type="button" onclick="alert('Isi Teks:\n\n' + {{ json_encode($material->content) }})" class="hover:underline hover:text-indigo-600 flex items-center gap-1">
                                    <i class="ph ph-text-t"></i>
                                    {{ Str::limit($material->title, 40) }}
                                </button>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-center space-y-1">
                            @if($material->quizzes->count() > 0)
                                <div class="inline-flex items-center px-2 py-0.5 bg-emerald-100 text-emerald-800 text-[10px] font-bold border border-emerald-300 rounded">
                                    ✅ Quiz ({{ $material->quizzes->count() }})
                                </div>
                            @else
                                <a href="{{ route('admin.quizzes.create', ['material_id' => $material->id]) }}" class="inline-flex items-center px-2 py-0.5 bg-rose-100 text-rose-800 text-[10px] font-bold border border-rose-300 rounded hover:bg-rose-200 transition-colors cursor-pointer">
                                    ❌ No Quiz (Create)
                                </a>
                            @endif

                            @if($material->flashcardSets->count() > 0)
                                <div class="inline-flex items-center px-2 py-0.5 bg-emerald-100 text-emerald-800 text-[10px] font-bold border border-emerald-300 rounded">
                                    ✅ Flashcard ({{ $material->flashcardSets->count() }})
                                </div>
                            @else
                                <a href="{{ route('admin.flashcards.create', ['material_id' => $material->id]) }}" class="inline-flex items-center px-2 py-0.5 bg-rose-100 text-rose-800 text-[10px] font-bold border border-rose-300 rounded hover:bg-rose-200 transition-colors cursor-pointer">
                                    ❌ No Flashcard (Create)
                                </a>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="px-6 py-8 text-center text-slate-500 font-medium">
                            No materials submitted yet.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    
    <div class="mt-4">
        {{ $materials->links() }}
    </div>
</div>
@endsection
