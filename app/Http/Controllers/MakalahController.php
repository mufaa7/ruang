<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreMakalahRequest;
use App\Http\Requests\StoreMakalahReferenceRequest;
use App\Http\Requests\UpdateMakalahRequest;
use App\Http\Requests\UpdateMakalahReferenceRequest;
use App\Models\Makalah;
use App\Models\MakalahChapter;
use App\Models\MakalahReference;
use App\Services\MakalahExportService;
use App\Services\MakalahService;
use App\Services\ActivityService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class MakalahController extends Controller
{
    public function __construct(
        private readonly MakalahService $makalahService,
        private readonly MakalahExportService $exportService,
        private readonly ActivityService $activityService
    ) {}

    public function index(): View
    {
        $makalahs = $this->makalahService->getAll();
        return view('makalah.index', compact('makalahs'));
    }

    public function create(): View
    {
        return view('makalah.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'judul'       => 'required|string|max:255',
            'sub_judul'   => 'nullable|string|max:255',
            'mata_kuliah' => 'nullable|string|max:255',
            'nama_dosen'  => 'nullable|string|max:255',
        ]);

        $validated['nama_penulis'] = auth()->user()->name;
        $validated['universitas'] = 'Universitas Trilogi';
        $validated['tahun'] = date('Y');
        $validated['logo_path'] = 'Universitas_Trilogi_logo.png';

        $makalah = $this->makalahService->create($validated);
        
        $this->activityService->log(
            auth()->user(),
            'makalah_created',
            "Makalah '{$makalah->judul}' dibuat",
            $makalah
        );
        
        return redirect()->route('makalah.edit', $makalah);
    }

    public function edit(Makalah $makalah): View
    {
        // Authorization
        $this->authorize('update', $makalah);
        
        $makalah->load([
            'chapters'              => fn($q) => $q->orderBy('order'),
            'chapters.subchapters' => fn($q) => $q->orderBy('order'),
            'references'           => fn($q) => $q->orderBy('order'),
        ]);
        return view('makalah.edit', compact('makalah'));
    }

    public function update(Request $request, Makalah $makalah)
    {
        $this->authorize('update', $makalah);
        
        $this->makalahService->update($makalah, $request->except('raw_references'));
        
        if ($request->has('raw_references')) {
            $ref = $makalah->references()->first();
            if ($ref) {
                $ref->update(['raw_citation' => $request->input('raw_references')]);
            } else {
                $makalah->references()->create(['raw_citation' => $request->input('raw_references'), 'order' => 0]);
            }
        }
        
        $this->activityService->log(
            auth()->user(),
            'makalah_updated',
            "Pengaturan makalah '{$makalah->judul}' diperbarui",
            $makalah
        );
        
        if ($request->wantsJson()) {
            return response()->json(['success' => true]);
        }
        return back()->with('success', 'Pengaturan makalah berhasil disimpan.');
    }

    public function destroy(Makalah $makalah): RedirectResponse
    {
        $this->authorize('delete', $makalah);
        
        $this->makalahService->delete($makalah);
        return redirect()->route('makalah.index')->with('success', 'Makalah dihapus.');
    }

    // ── Chapters ──────────────────────────────────────────────────────────────

    public function storeChapter(Request $request, Makalah $makalah): RedirectResponse
    {
        $this->authorize('update', $makalah);
        
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'type'  => 'required|in:kata_pengantar,bab,penutup,lampiran',
        ]);
        
        $this->makalahService->addChapter($makalah, $validated);
        return back()->with('success', 'Bab baru ditambahkan.');
    }

    public function updateChapter(Request $request, Makalah $makalah, MakalahChapter $chapter): RedirectResponse
    {
        $this->authorize('update', $chapter);
        
        $validated = $request->validate([
            'title'        => 'sometimes|required|string|max:255',
            'bab_label'    => 'nullable|string|max:255',
            'content'      => 'nullable|string',
            'sub_sections' => 'nullable|array',
        ]);
        
        $this->makalahService->updateChapter($chapter, $validated);
        
        // Return JSON if ajax? We'll just redirect for now.
        if ($request->wantsJson()) {
            return response()->json(['success' => true]);
        }
        
        return back()->with('success', 'Bab berhasil disimpan.');
    }

    public function destroyChapter(Makalah $makalah, MakalahChapter $chapter): RedirectResponse
    {
        $this->authorize('delete', $chapter);
        
        $this->makalahService->deleteChapter($chapter);
        return back()->with('success', 'Bab dihapus.');
    }

    public function updateContent(Request $request, Makalah $makalah)
    {
        $this->authorize('update', $makalah);

        $validated = $request->validate([
            'subchapter_id' => 'required|exists:makalah_subchapters,id',
            'content'       => 'nullable|string',
        ]);

        $subchapter = \App\Models\MakalahSubchapter::where('id', $validated['subchapter_id'])
            ->whereHas('chapter', function($q) use ($makalah) {
                $q->where('makalah_id', $makalah->id);
            })->firstOrFail();

        $this->makalahService->updateSubchapterContent($subchapter, $validated['content']);

        $this->activityService->log(
            auth()->user(),
            'makalah_updated',
            "Isi makalah '{$makalah->judul}' (Bab: {$subchapter->title}) diperbarui",
            $makalah
        );

        return response()->json(['success' => true]);
    }

    // ── Subchapters ───────────────────────────────────────────────────────────

    public function storeSubchapter(Request $request, Makalah $makalah, MakalahChapter $chapter)
    {
        $this->authorize('update', $chapter);
        
        $validated = $request->validate([
            'title' => 'required|string|max:255',
        ]);
        
        $this->makalahService->addSubchapter($chapter, $validated);
        
        return back()->with('success', 'Sub-bab baru ditambahkan.');
    }

    public function updateSubchapter(Request $request, Makalah $makalah, \App\Models\MakalahSubchapter $subchapter)
    {
        $this->authorize('update', $subchapter);
        
        $validated = $request->validate([
            'title' => 'required|string|max:255',
        ]);
        
        $this->makalahService->updateSubchapter($subchapter, $validated);
        
        return back()->with('success', 'Sub-bab berhasil diperbarui.');
    }

    public function destroySubchapter(Makalah $makalah, \App\Models\MakalahSubchapter $subchapter)
    {
        $this->authorize('delete', $subchapter);
        
        $this->makalahService->deleteSubchapter($subchapter);
        
        return back()->with('success', 'Sub-bab dihapus.');
    }

    // ── References ────────────────────────────────────────────────────────────

    public function storeReference(StoreMakalahReferenceRequest $request, Makalah $makalah)
    {
        $this->authorize('update', $makalah);

        $validated = $request->validated();

        $this->makalahService->addReference($makalah, $validated);

        return back()->with('success', 'Referensi berhasil ditambahkan.');
    }

    public function updateReference(UpdateMakalahReferenceRequest $request, Makalah $makalah, MakalahReference $reference)
    {
        $this->authorize('update', $reference);

        $validated = $request->validated();

        $this->makalahService->updateReference($reference, $validated);

        return back()->with('success', 'Referensi berhasil diperbarui.');
    }

    public function destroyReference(Makalah $makalah, MakalahReference $reference)
    {
        $this->authorize('delete', $reference);

        $this->makalahService->deleteReference($reference);
        return back()->with('success', 'Referensi dihapus.');
    }

    // ── Export ────────────────────────────────────────────────────────────────

    public function exportPdf(Makalah $makalah)
    {
        $this->authorize('export', $makalah);
        
        $pdf = $this->exportService->exportPdf($makalah);
        return $pdf->stream('Makalah_' . \Str::slug($makalah->judul) . '.pdf');
    }

    public function exportWord(Makalah $makalah)
    {
        $this->authorize('export', $makalah);
        
        $tempPath = $this->exportService->exportWord($makalah);
        return response()->download($tempPath)->deleteFileAfterSend(true);
    }
}
