<?php

namespace App\Policies;

use App\Models\MakalahReference;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class MakalahReferencePolicy
{
    public function update(User $user, MakalahReference $makalahReference): bool
    {
        return $user->id === $makalahReference->makalah?->user_id;
    }

    public function delete(User $user, MakalahReference $makalahReference): bool
    {
        return $user->id === $makalahReference->makalah?->user_id;
    }
}
