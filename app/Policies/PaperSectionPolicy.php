<?php

namespace App\Policies;

use App\Models\PaperSection;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class PaperSectionPolicy
{
    public function update(User $user, PaperSection $paperSection): bool
    {
        return $user->id == $paperSection->paper?->user_id;
    }

    public function delete(User $user, PaperSection $paperSection): bool
    {
        return $user->id == $paperSection->paper?->user_id;
    }
}
