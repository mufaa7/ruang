<?php

namespace App\Policies;

use App\Models\Makalah;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class MakalahPolicy
{
    public function view(User $user, Makalah $makalah): bool
    {
        return $user->id === $makalah->user_id;
    }

    public function update(User $user, Makalah $makalah): bool
    {
        return $user->id === $makalah->user_id;
    }

    public function delete(User $user, Makalah $makalah): bool
    {
        return $user->id === $makalah->user_id;
    }

    public function export(User $user, Makalah $makalah): bool
    {
        return $user->id === $makalah->user_id;
    }

    public function generateAi(User $user, Makalah $makalah): bool
    {
        return $user->id === $makalah->user_id;
    }
}
