<?php

namespace App\Policies;

use App\Models\MakalahSubchapter;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class MakalahSubchapterPolicy
{
    public function update(User $user, MakalahSubchapter $makalahSubchapter): bool
    {
        return $user->id == $makalahSubchapter->chapter?->makalah?->user_id;
    }

    public function delete(User $user, MakalahSubchapter $makalahSubchapter): bool
    {
        return $user->id == $makalahSubchapter->chapter?->makalah?->user_id;
    }
}
