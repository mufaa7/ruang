@extends('layouts.admin')

@section('content')
<div class="space-y-6">
    <div class="flex items-center justify-between">
        <h2 class="text-lg font-bold text-black">Study Materials</h2>
        <a href="{{ route('admin.flashcards.create') }}" class="bg-black text-white px-4 py-2 text-sm font-bold border border-black hover:bg-slate-800 transition-colors">
            + New Flashcard Set
        </a>
    </div>

    {{-- Tabs --}}
    <div class="flex border-b border-black text-sm font-bold">
        <a href="{{ route('admin.quizzes.index') }}" class="px-4 md:px-6 py-2 bg-white text-slate-600 border-b border-black hover:bg-slate-50 transition-colors whitespace-nowrap dark:bg-slate-900">
            Quizzes
        </a>
        <a href="{{ route('admin.flashcards.index') }}" class="px-4 md:px-6 py-2 bg-black text-white border-t border-l border-r border-black whitespace-nowrap">
            Flashcards
        </a>
        <div class="flex-1 border-b border-black"></div>
    </div>

    @if (session('success'))
        <div class="bg-emerald-50 border border-emerald-500 text-emerald-700 px-4 py-3 text-sm font-bold">
            {{ session('success') }}
        </div>
    @endif

    <div class="dev-card overflow-x-auto">
        <table class="w-full text-left text-sm whitespace-nowrap">
            <thead>
                <tr class="bg-[#f8f9fa] text-xs uppercase tracking-wider">
                    <th class="px-6 py-3 font-bold text-black border-r border-b border-black">Title</th>
                    <th class="px-6 py-3 font-bold text-black border-r border-b border-black">Subject</th>
                    <th class="px-6 py-3 font-bold text-black border-b border-black text-right">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-black">
                @forelse($flashcardSets as $set)
                    <tr class="hover:bg-slate-50">
                        <td class="px-6 py-4 font-bold text-black border-r border-black">
                            {{ $set->title }}
                        </td>
                        <td class="px-6 py-4 border-r border-black">
                            {{ $set->subject->name ?? '-' }}
                        </td>
                        <td class="px-6 py-4 text-right">
                            <a href="{{ route('admin.flashcards.edit', $set) }}" class="text-neutral-900 hover:text-stone-700 font-bold mr-3">Edit</a>
                            <form action="{{ route('admin.flashcards.destroy', $set) }}" method="POST" class="inline-block" onsubmit="return confirm('Delete this flashcard set?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-rose-600 hover:text-stone-700 font-bold">Delete</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="3" class="px-6 py-8 text-center text-slate-500 font-medium">
                            No flashcard sets found. Create one to get started.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4">
        {{ $flashcardSets->links() }}
    </div>
</div>
@endsection
