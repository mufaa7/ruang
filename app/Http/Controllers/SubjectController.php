<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreSubjectRequest;
use App\Http\Requests\UpdateSubjectRequest;
use App\Models\Subject;
use App\Services\ActivityService;
use App\Services\SubjectService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class SubjectController extends Controller
{
    public function __construct(
        private readonly SubjectService  $subjectService,
        private readonly ActivityService $activityService,
    ) {}

    public function index(Request $request): View
    {
        $subjects = $this->subjectService->getAllSubjects($request->all());
        $mySubjects = auth()->check()
            ? $this->subjectService->getUserSubjects(auth()->user())
            : collect();

        return view('subjects.index', compact('subjects', 'mySubjects'));
    }

    public function show(Subject $subject): View
    {
        $subject->load([
            'creator',
            'users',
            'papers' => fn ($q) => $q->published()->public()->with(['author', 'tags'])->latest()->limit(6),
        ]);

        return view('subjects.show', compact('subject'));
    }

    public function create(): View
    {
        return view('subjects.create');
    }

    public function store(StoreSubjectRequest $request): RedirectResponse
    {
        $subject = $this->subjectService->createSubject(auth()->user(), $request->validated());

        $this->activityService->log(
            auth()->user(),
            'subject.created',
            "Bikin subject baru: {$subject->name}",
            $subject
        );

        return redirect()
            ->route('subjects.show', $subject)
            ->with('success', 'Subject berhasil dibuat! 📚');
    }

    public function edit(Subject $subject): View
    {
        $this->authorize('update', $subject);
        return view('subjects.edit', compact('subject'));
    }

    public function update(UpdateSubjectRequest $request, Subject $subject): RedirectResponse
    {
        $this->authorize('update', $subject);
        $data = $request->validated();
        
        $this->subjectService->updateSubject($subject, $data);

        return redirect()->route('subjects.index')->with('success', 'Mata kuliah berhasil diubah!');
    }

    public function destroy(Subject $subject): RedirectResponse
    {
        $this->authorize('delete', $subject);
        $subject->delete();
        return redirect()->route('subjects.index')->with('success', 'Mata kuliah dihapus!');
    }

    public function join(Subject $subject): RedirectResponse
    {
        $this->subjectService->joinSubject(auth()->user(), $subject);

        return back()->with('success', "Berhasil join {$subject->name}! 🎉");
    }

    public function leave(Subject $subject): RedirectResponse
    {
        $this->subjectService->leaveSubject(auth()->user(), $subject);

        return back()->with('success', "Udah leave {$subject->name}");
    }
}
