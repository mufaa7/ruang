<?php

namespace App\Policies;

use App\Models\Deadline;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class DeadlinePolicy
{
    public function update(User $user, Deadline $deadline): bool
    {
        return $user->isAdmin() || $user->id === $deadline->user_id;
    }

    public function delete(User $user, Deadline $deadline): bool
    {
        return $user->isAdmin() || $user->id === $deadline->user_id;
    }
}
