<?php

namespace App\Services;

use App\Models\Note;
use App\Models\User;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

class NoteService
{
    /**
     * Ambil notes milik user (dengan pin di atas)
     */
    public function getUserNotes(User $user, array $filters = []): LengthAwarePaginator
    {
        $query = Note::with(['folder', 'subject', 'tags'])
            ->where('user_id', $user->id);

        if (!empty($filters['folder_id'])) {
            $query->where('folder_id', $filters['folder_id']);
        }

        if (!empty($filters['subject_id'])) {
            $query->where('subject_id', $filters['subject_id']);
        }

        if (!empty($filters['search'])) {
            $search = $filters['search'];
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('content', 'like', "%{$search}%");
            });
        }

        if (!empty($filters['tag'])) {
            $query->whereHas('tags', fn ($q) => $q->where('slug', $filters['tag']));
        }

        if (!empty($filters['hashtag'])) {
            $query->whereJsonContains('settings->hashtags', '#'.ltrim($filters['hashtag'], '#'));
        }

        if (isset($filters['is_coretan']) && $filters['is_coretan']) {
            $query->whereNull('subject_id');
        }

        if (!empty($filters['pinned'])) {
            $query->where('is_pinned', true);
        }

        if (!empty($filters['ai'])) {
            $query->where('is_ai_generated', true);
        }

        return $query
            ->orderByDesc('is_pinned')
            ->orderByDesc('last_edited_at')
            ->paginate(20);
    }

    /**
     * Buat note baru
     */
    public function createNote(User $user, array $data): Note
    {
        $note = Note::create([
            'user_id'        => $user->id,
            'folder_id'      => $data['folder_id'] ?? null,
            'subject_id'     => $data['subject_id'] ?? null,
            'title'          => $data['title'],
            'content'        => $data['content'] ?? null,
            'color'          => $data['color'] ?? '#ffffff',
            'visibility'     => $data['visibility'] ?? 'private',
            'last_edited_at' => now(),
        ]);

        if (!empty($data['tags'])) {
            $note->tags()->sync($data['tags']);
        }

        return $note->load(['folder', 'tags']);
    }

    /**
     * Update note
     */
    public function updateNote(Note $note, array $data): Note
    {
        $update = [
            'title'          => $data['title'] ?? $note->title,
            'content'        => $data['content'] ?? $note->content,
            'folder_id'      => $data['folder_id'] ?? $note->folder_id,
            'color'          => $data['color'] ?? $note->color,
            'visibility'     => $data['visibility'] ?? $note->visibility,
            'last_edited_at' => now(),
        ];

        if (isset($data['is_pinned'])) {
            $update['is_pinned'] = (bool) $data['is_pinned'];
        }

        // Simpan hashtags dan checklist ke kolom settings (JSON)
        $settings = is_array($note->settings) ? $note->settings : [];

        if (isset($data['hashtags_json'])) {
            $decoded = json_decode($data['hashtags_json'], true);
            $settings['hashtags'] = is_array($decoded) ? $decoded : [];
        }

        if (isset($data['checklist_json'])) {
            $decoded = json_decode($data['checklist_json'], true);
            $settings['checklist'] = is_array($decoded) ? $decoded : [];
        }

        if (isset($data['tagline'])) {
            $settings['tagline'] = $data['tagline'];
        }

        $update['settings'] = $settings;

        $note->update($update);

        if (isset($data['tags'])) {
            $note->tags()->sync($data['tags']);
        }

        return $note->refresh();
    }

    /**
     * Toggle pin note
     */
    public function togglePin(Note $note): Note
    {
        $note->update(['is_pinned' => !$note->is_pinned]);
        return $note;
    }

    /**
     * Hapus note (soft delete)
     */
    public function deleteNote(Note $note): void
    {
        $note->delete();
    }

    /**
     * Restore note dari trash
     */
    public function restoreNote(int $noteId, User $user): Note
    {
        $note = Note::withTrashed()
            ->where('id', $noteId)
            ->where('user_id', $user->id)
            ->firstOrFail();

        $note->restore();
        return $note;
    }

    /**
     * Ambil notes di trash
     */
    public function getTrashedNotes(User $user): Collection
    {
        return Note::onlyTrashed()
            ->where('user_id', $user->id)
            ->latest('deleted_at')
            ->get();
    }
}
