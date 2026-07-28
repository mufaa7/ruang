<?php

namespace App\Http\Controllers;

use App\Models\Paper;
use App\Models\PaperSection;
use App\Services\PaperService;
use Illuminate\Http\Request;

class PaperSectionController extends Controller
{
    public function __construct(
        private readonly PaperService $paperService
    ) {}

    /**
     * Store a newly created section in storage.
     */
    public function store(Request $request, Paper $paper)
    {
        $this->authorize('update', $paper);

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'type'  => 'required|in:introduction,body,conclusion,references,appendix,custom',
        ]);

        $this->paperService->addSection($paper, $validated);

        return back()->with('success', 'Bagian baru berhasil ditambahkan! Silakan mulai menulis.');
    }

    /**
     * Update the specified section in storage.
     */
    public function update(Request $request, Paper $paper, PaperSection $section)
    {
        $this->authorize('update', $section);

        $validated = $request->validate([
            'title'   => 'sometimes|required|string|max:255',
            'type'    => 'sometimes|required|in:introduction,body,conclusion,references,appendix,custom',
            'content' => 'nullable|string',
            'order'   => 'sometimes|integer',
        ]);

        $this->paperService->updateSection($section, $validated);

        return back()->with('success', 'Konten bagian ini berhasil disimpan! ✅');
    }

    /**
     * Remove the specified section from storage.
     */
    public function destroy(Paper $paper, PaperSection $section)
    {
        $this->authorize('delete', $section);

        $this->paperService->deleteSection($section);

        return back()->with('success', 'Bagian dihapus. 🗑️');
    }
}
