<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePaperRequest;
use App\Http\Requests\UpdatePaperRequest;
use App\Models\Paper;
use App\Models\Subject;
use App\Services\ActivityService;
use App\Services\PaperService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PaperController extends Controller
{
    public function __construct(
        private readonly PaperService   $paperService,
        private readonly ActivityService $activityService,
    ) {}

    public function index(Request $request): View
    {
        $papers   = $this->paperService->getPublicPapers($request->all());
        $subjects = Subject::active()->get();

        return view('papers.index', compact('papers', 'subjects'));
    }

    public function myPapers(Request $request): View
    {
        $papers = $this->paperService->getUserPapers(auth()->user(), $request->all());

        return view('papers.my-papers', compact('papers'));
    }

    public function create(): View
    {
        $subjects = Subject::active()->get();
        $tags = \App\Models\Tag::all();
        return view('papers.create', compact('subjects', 'tags'));
    }

    public function store(StorePaperRequest $request): RedirectResponse
    {
        $paper = $this->paperService->createPaper(auth()->user(), $request->validated());

        $this->activityService->log(
            auth()->user(),
            'paper.created',
            "Bikin paper baru: {$paper->title}",
            $paper
        );

        return redirect()
            ->route('papers.edit', $paper)
            ->with('success', 'Paper berhasil dibuat! Sekarang tinggal nulis kontennya 🚀');
    }

    public function show(Paper $paper): View
    {
        $this->authorize('view', $paper);

        $this->paperService->incrementView($paper);

        $paper->load(['author', 'sections', 'tags', 'subject', 'collaborators']);

        return view('papers.show', compact('paper'));
    }

    public function edit(Paper $paper): View
    {
        $this->authorize('update', $paper);

        $paper->load(['sections', 'tags', 'subject']);
        $subjects = Subject::active()->get();
        $tags = \App\Models\Tag::all();

        return view('papers.edit', compact('paper', 'subjects', 'tags'));
    }

    public function update(UpdatePaperRequest $request, Paper $paper): RedirectResponse
    {
        $paper = $this->paperService->updatePaper($paper, $request->validated());

        $this->activityService->log(
            auth()->user(),
            'paper.updated',
            "Update paper: {$paper->title}",
            $paper
        );

        return back()->with('success', 'Paper berhasil diupdate! ✅');
    }

    public function publish(Paper $paper): RedirectResponse
    {
        $this->authorize('publish', $paper);

        $this->paperService->publishPaper($paper);

        $this->activityService->log(
            auth()->user(),
            'paper.published',
            "Publish paper: {$paper->title}",
            $paper
        );

        return back()->with('success', 'Paper udah live! Congrats bestie 🎉');
    }

    public function destroy(Paper $paper): RedirectResponse
    {
        $this->authorize('delete', $paper);

        $this->paperService->deletePaper($paper);

        return redirect()
            ->route('papers.my')
            ->with('success', 'Paper dipindah ke trash 🗑️');
    }
}
