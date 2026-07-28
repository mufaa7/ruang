<?php

namespace App\Services;

use App\Models\Subject;
use App\Models\User;
use Illuminate\Support\Str;

class SubjectService
{
    /**
     * Ambil semua subject aktif
     */
    public function getAllSubjects(array $filters = [])
    {
        $query = Subject::with(['creator'])
            ->active()
            ->withCount(['papers', 'notes', 'users']);

        if (!empty($filters['search'])) {
            $search = $filters['search'];
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('code', 'like', "%{$search}%");
            });
        }

        if (!empty($filters['semester'])) {
            $query->where('semester', $filters['semester']);
        }

        return $query->latest()->paginate(15);
    }

    /**
     * Ambil subject yang diikuti user
     */
    public function getUserSubjects(User $user)
    {
        return $user->subjects()
            ->active()
            ->withCount(['papers', 'notes', 'materials', 'quizzes'])
            ->get();
    }

    /**
     * Buat subject baru
     */
    public function createSubject(User $user, array $data): Subject
    {
        $subject = Subject::create([
            'name'       => $data['name'],
            'slug'       => Str::slug($data['name']) . '-' . Str::random(4),
            'code'       => $data['code'] ?? null,
            'lecturer'   => $data['lecturer'] ?? null,
            'description' => $data['description'] ?? null,
            'icon'       => $data['icon'] ?? '📚',
            'color'      => $data['color'] ?? '#6366f1',
            'semester'   => $data['semester'] ?? null,
            'created_by' => $user->id,
        ]);

        // Auto-join creator sebagai owner
        $subject->users()->attach($user->id, ['role' => 'owner']);

        return $subject;
    }

    /**
     * Join subject
     */
    public function joinSubject(User $user, Subject $subject): void
    {
        if (!$user->subjects()->where('subject_id', $subject->id)->exists()) {
            $subject->users()->attach($user->id, ['role' => 'viewer']);
        }
    }

    /**
     * Leave subject
     */
    public function leaveSubject(User $user, Subject $subject): void
    {
        $subject->users()->detach($user->id);
    }

    /**
     * Update subject
     */
    public function updateSubject(Subject $subject, array $data): Subject
    {
        $subject->update([
            'name'        => $data['name'] ?? $subject->name,
            'code'        => $data['code'] ?? $subject->code,
            'lecturer'    => $data['lecturer'] ?? $subject->lecturer,
            'description' => $data['description'] ?? $subject->description,
            'icon'        => $data['icon'] ?? $subject->icon,
            'color'       => $data['color'] ?? $subject->color,
            'semester'    => $data['semester'] ?? $subject->semester,
        ]);

        return $subject->refresh();
    }
}
