<?php

namespace App\Policies;

use App\Models\Material;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class MaterialPolicy
{
    public function view(User $user, Material $material): bool
    {
        return $this->checkAccess($user, $material);
    }

    public function update(User $user, Material $material): bool
    {
        return $this->checkAccess($user, $material);
    }

    public function delete(User $user, Material $material): bool
    {
        return $this->checkAccess($user, $material);
    }

    private function checkAccess(User $user, Material $material): bool
    {
        if ($user->isAdmin() || $user->id == $material->user_id) {
            return true;
        }

        if ($material->subject) {
            return $material->subject->created_by == $user->id || 
                   $material->subject->users()->where('users.id', $user->id)->exists();
        }

        return false;
    }
}
