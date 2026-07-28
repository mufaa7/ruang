<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\ActivityService;

class DeadlineController extends Controller
{
    public function __construct(
        private readonly ActivityService $activityService
    ) {}

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'due_date' => 'required|date',
        ]);

        $deadline = $request->user()->deadlines()->create($validated);

        $this->activityService->log(
            $request->user(),
            'deadline_created',
            "Nambahin deadline: {$deadline->title}",
            $deadline
        );

        return back()->with('success', 'Deadline berhasil ditambahkan.');
    }

    public function update(Request $request, \App\Models\Deadline $deadline)
    {
        $this->authorize('update', $deadline);

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'due_date' => 'required|date',
        ]);

        $deadline->update($validated);

        return back()->with('success', 'Deadline berhasil diperbarui.');
    }

    public function destroy(Request $request, \App\Models\Deadline $deadline)
    {
        $this->authorize('delete', $deadline);

        $deadline->delete();

        return back()->with('success', 'Deadline berhasil dihapus.');
    }
}
