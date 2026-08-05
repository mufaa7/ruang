<?php

namespace App\Http\Controllers;

use App\Models\Activity;
use Illuminate\Http\Request;
use Illuminate\View\View;

class JejakController extends Controller
{
    // Label per tipe aktivitas — asbun style 😎
    private array $activityTypes = [
        'note.created'      => ['emoji' => '<i class="fi fi-sr-pencil"></i>', 'label' => 'Coretan'],
        'paper.published'   => ['emoji' => '<i class="fi fi-sr-document"></i>', 'label' => 'Makalah'],
        'paper.created'     => ['emoji' => '<i class="fi fi-sr-pen-nib"></i>', 'label' => 'Nulis Baru'],
        'quiz.generated'    => ['emoji' => '<i class="fi fi-sr-flask"></i>', 'label' => 'Latihan'],
        'material.uploaded' => ['emoji' => '<i class="fi fi-sr-books"></i>', 'label' => 'Upload Materi'],
    ];

    public function index(Request $request): View
    {
        $user = auth()->user();

        $query = Activity::where('user_id', $user->id)->latest();

        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        $activities = $query->paginate(30)->withQueryString();

        $groupedActivities = $activities->getCollection()
            ->groupBy(fn ($a) => $a->created_at->format('Y-m-d'));

        $totalToday = Activity::where('user_id', $user->id)
            ->whereDate('created_at', today())
            ->count();

        $totalAll = Activity::where('user_id', $user->id)->count();

        return view('jejak.index', [
            'activities'         => $activities,
            'groupedActivities'  => $groupedActivities,
            'activityTypes'      => $this->activityTypes,
            'totalToday'         => $totalToday,
            'totalAll'           => $totalAll,
        ]);
    }
}
