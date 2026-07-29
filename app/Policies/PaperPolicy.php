<?php

namespace App\Policies;

use App\Models\Paper;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class PaperPolicy
{
    public function view(?User $user, Paper $paper): bool
    {
        if ($paper->status === 'published') {
            return true;
        }

        return $user !== null && $user->id == $paper->user_id;
    }

    public function update(User $user, Paper $paper): bool
    {
        return $user->id == $paper->user_id;
    }

    public function delete(User $user, Paper $paper): bool
    {
        return $user->id == $paper->user_id;
    }

    public function publish(User $user, Paper $paper): bool
    {
        return $user->id == $paper->user_id;
    }
}
