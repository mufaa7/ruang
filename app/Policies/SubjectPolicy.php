<?php

namespace App\Policies;

use App\Models\Subject;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class SubjectPolicy
{
    public function view(?User $user, Subject $subject): bool
    {
        return true; // Anyone can view a subject for now, as it's public in index/show
    }

    public function update(User $user, Subject $subject): bool
    {
        return $user->isAdmin() || 
               $user->id === $subject->created_by ||
               $subject->users()->where('users.id', $user->id)->exists();
    }

    public function delete(User $user, Subject $subject): bool
    {
        return $user->isAdmin() || 
               $user->id === $subject->created_by ||
               $subject->users()->where('users.id', $user->id)->exists();
    }
}
