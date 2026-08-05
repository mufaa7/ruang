<?php

namespace App\Policies;

use App\Models\Material;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class MaterialPolicy
{
    public function view(User $user, Material $material): bool
    {
        // Viewers can view the material if they joined the subject, or if they own it
        if ($user->isAdmin() || $user->id == $material->user_id) {
            return true;
        }

        if ($material->subject) {
            return $material->subject->created_by == $user->id || 
                   $material->subject->users()->where('users.id', $user->id)->exists();
        }

        return false;
    }

    public function update(User $user, Material $material): bool
    {
        return $this->checkEditAccess($user, $material);
    }

    public function delete(User $user, Material $material): bool
    {
        return $this->checkEditAccess($user, $material);
    }

    private function checkEditAccess(User $user, Material $material): bool
    {
        if ($user->isAdmin() || $user->id == $material->user_id) {
            return true;
        }

        if ($material->subject) {
            return $material->subject->created_by == $user->id || 
                   $material->subject->users()->where('users.id', $user->id)->wherePivot('role', 'owner')->exists();
        }

        return false;
    }
}
