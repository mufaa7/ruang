<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreNoteRequest;
use App\Http\Requests\UpdateNoteRequest;
use App\Models\Note;
use App\Services\ActivityService;
use App\Services\NoteService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class NoteController extends Controller
{
    public function __construct(
        private readonly NoteService     $noteService,
        private readonly ActivityService $activityService,
    ) {}

    public function index(Request $request): View
    {
        $filters = $request->all();

        // Terjemahin filter UI ke filter NoteService
        if ($request->filter === 'pinned') {
            $filters['pinned'] = true;
        } elseif ($request->filter === 'ai') {
            $filters['ai'] = true;
        }

        // Coretan murni: Jangan tampilkan catatan yang terikat dengan mata kuliah
        $filters['is_coretan'] = true;

        $notes   = $this->noteService->getUserNotes(auth()->user(), $filters);
        $trashed = $this->noteService->getTrashedNotes(auth()->user());

        $allHashtags = \App\Models\Note::where('user_id', auth()->id())
            ->whereNull('subject_id')
            ->pluck('settings')
            ->flatMap(fn($settings) => is_array($settings) ? ($settings['hashtags'] ?? []) : [])
            ->unique()
            ->values()
            ->all();

        return view('notes.index', compact('notes', 'trashed', 'allHashtags'));
    }

    public function store(StoreNoteRequest $request): RedirectResponse
    {
        $note = $this->noteService->createNote(auth()->user(), $request->validated());

        $this->activityService->log(
            auth()->user(),
            'note.created',
            "Bikin note baru: {$note->title}",
            $note
        );

        return redirect()->route('coretan.index', ['note' => $note->id])
            ->with('success', 'Coretan baru udah kebuat! ✏️');
    }

    public function edit(Note $note): View
    {
        $this->authorize('update', $note);
        $note->load(['folder', 'tags', 'subject']);

        return view('notes.edit', compact('note'));
    }

    public function update(UpdateNoteRequest $request, Note $note): RedirectResponse
    {
        $this->authorize('update', $note);
        $this->noteService->updateNote($note, $request->validated());

        return redirect()->route('coretan.index', ['note' => $note->id])
            ->with('success', 'Tersimpan! 💾');
    }

    public function togglePin(Note $note): RedirectResponse
    {
        $this->authorize('update', $note);

        $note = $this->noteService->togglePin($note);
        $status = $note->is_pinned ? 'dipin' : 'dilepas';

        return back()->with('success', "Note berhasil {$status} 📌");
    }

    public function destroy(Note $note): RedirectResponse
    {
        $this->authorize('delete', $note);

        $this->noteService->deleteNote($note);

        return back()->with('success', 'Note masuk trash 🗑️')->with('tab', 'catatan');
    }

    public function restore(int $id): RedirectResponse
    {
        $note = \App\Models\Note::withTrashed()->findOrFail($id);
        $this->authorize('restore', $note);
        $note = $this->noteService->restoreNote($id, auth()->user());

        return back()->with('success', 'Note berhasil di-restore! 🔄');
    }

    public function generateAi(Request $request, \App\Models\Subject $subject): RedirectResponse
    {
        $validated = $request->validate([
            'ai_source' => ['required', 'in:materi,manual'],
            'material_id' => ['required_if:ai_source,materi', 'nullable', 'exists:materials,id'],
            'manual_text' => ['required_if:ai_source,manual', 'nullable', 'string', 'min:10'],
        ]);

        $material = null;
        if ($validated['ai_source'] === 'materi') {
            $material = \App\Models\Material::where('subject_id', $subject->id)
                ->where('id', $validated['material_id'])
                ->firstOrFail();
        }

        \App\Models\SummaryRequest::create([
            'user_id' => auth()->id(),
            'subject_id' => $subject->id,
            'material_id' => $material ? $material->id : null,
            'manual_text' => $validated['manual_text'] ?? null,
            'status' => 'pending',
        ]);

        return redirect()->back()
            ->with('success', 'Sinyal AI lagi putus-putus ke masa depan 🤖. Request kamu sudah dikirim ke Admin untuk dirangkum secara manual. Kurang lebih sejam lagi akan muncul otomatis. Ditunggu ya!')
            ->with('tab', 'catatan');
    }
}
