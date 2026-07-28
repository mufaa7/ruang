<?php

namespace App\Services;

use App\Models\Activity;
use App\Models\User;
use Illuminate\Support\Collection;

class ActivityService
{
    /**
     * Log aktivitas user
     */
    public function log(
        User $user,
        string $type,
        string $description,
        mixed $subject = null,
        array $properties = []
    ): Activity {
        return Activity::create([
            'user_id'      => $user->id,
            'type'         => $type,
            'description'  => $description,
            'subject_type' => $subject ? get_class($subject) : null,
            'subject_id'   => $subject?->id,
            'properties'   => $properties,
            'ip_address'   => request()->ip(),
        ]);
    }

    /**
     * Ambil aktivitas terbaru user
     */
    public function getRecentActivities(User $user, int $limit = 20, array $excludeTypes = []): Collection
    {
        $query = Activity::with(['subject'])
            ->where('user_id', $user->id);
            
        if (!empty($excludeTypes)) {
            $query->whereNotIn('type', $excludeTypes);
        }

        return $query->latest()
            ->limit($limit)
            ->get();
    }
}
