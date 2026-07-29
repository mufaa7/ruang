<?php

namespace App\Policies;

use App\Models\MakalahChapter;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class MakalahChapterPolicy
{
    public function update(User $user, MakalahChapter $makalahChapter): bool
    {
        return $user->id == $makalahChapter->makalah?->user_id;
    }

    public function delete(User $user, MakalahChapter $makalahChapter): bool
    {
        return $user->id == $makalahChapter->makalah?->user_id;
    }
}
