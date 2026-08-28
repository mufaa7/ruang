@extends('layouts.admin')

@section('content')
<div class="space-y-6">
    <div class="flex items-center justify-between">
        <h2 class="text-lg font-bold text-black">Study Materials</h2>
        <a href="{{ route('admin.quizzes.create') }}" class="bg-black text-white px-4 py-2 text-sm font-bold border border-black hover:bg-slate-800 transition-colors">
            + New Quiz
        </a>
    </div>

    {{-- Tabs --}}
    <div class="flex border-b border-black text-sm font-bold">
        <a href="{{ route('admin.quizzes.index') }}" class="px-4 md:px-6 py-2 bg-black text-white border-t border-l border-r border-black whitespace-nowrap">
            Quizzes
        </a>
        <a href="{{ route('admin.flashcards.index') }}" class="px-4 md:px-6 py-2 bg-white text-slate-600 border-b border-black hover:bg-slate-50 transition-colors whitespace-nowrap">
            Flashcards
        </a>
        <div class="flex-1 border-b border-black"></div>
    </div>

    @if(session('success'))
        <div class="bg-emerald-50 border border-emerald-500 text-emerald-700 px-4 py-3 text-sm font-bold">
            {{ session('success') }}
        </div>
    @endif

    <div class="dev-card overflow-x-auto">
        <table class="w-full text-left text-sm whitespace-nowrap">
            <thead>
                <tr class="border-b border-black bg-[#f8f9fa] text-xs uppercase tracking-wider">
                    <th class="px-6 py-4 font-bold text-black border-r border-black">Title</th>
                    <th class="px-6 py-4 font-bold text-black border-r border-black">Subject</th>
                    <th class="px-6 py-4 font-bold text-black border-r border-black">Source Material</th>
                    <th class="px-6 py-4 font-bold text-black border-r border-black text-center">Targets</th>
                    <th class="px-6 py-4 font-bold text-black border-r border-black">Time</th>
                    <th class="px-6 py-4 font-bold text-black border-r border-black">Created</th>
                    <th class="px-6 py-4 font-bold text-black">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-black">
                @forelse($quizzes as $quiz)
                    <tr class="hover:bg-slate-50">
                        <td class="px-6 py-4 font-bold text-black border-r border-black">{{ $quiz->title }}</td>
                        <td class="px-6 py-4 text-black border-r border-black">{{ $quiz->subject->name ?? '-' }}</td>
                        <td class="px-6 py-4 text-black border-r border-black text-xs">
                            @if($quiz->material)
                                <span class="font-bold">{{ Str::limit($quiz->material->title, 30) }}</span>
                                <br><span class="text-slate-400">(oleh {{ $quiz->material->user->name ?? '?' }})</span>
                            @else
                                <span class="text-slate-400 italic">— semua materi</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-center border-r border-black">
                            @if($quiz->targets_count > 0)
                                <span class="inline-flex items-center px-2 py-0.5 bg-stone-200 text-neutral-950 text-xs font-bold border border-stone-300">
                                    {{ $quiz->targets_count }} user
                                </span>
                            @else
                                <span class="text-slate-400 text-xs">Publik</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-black border-r border-black">{{ $quiz->time_limit_minutes }} mnt</td>
                        <td class="px-6 py-4 text-slate-500 border-r border-black">{{ $quiz->created_at->format('d M Y') }}</td>
                        <td class="px-6 py-4 font-medium">
                            <div class="flex items-center gap-3">
                                <a href="{{ route('admin.quizzes.edit', $quiz) }}" class="text-blue-600 hover:underline font-bold">Edit</a>
                                <form action="{{ route('admin.quizzes.destroy', $quiz) }}" method="POST" onsubmit="return confirm('Delete this quiz?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-rose-600 hover:underline font-bold">Delete</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="px-6 py-8 text-center text-slate-500 font-medium">
                            No quizzes found. Click "+ New Quiz" to create one.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    
    <div class="mt-4">
        {{ $quizzes->links() }}
    </div>
</div>
@endsection
