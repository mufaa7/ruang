<?php

namespace App\Services;

use App\Models\Makalah;
use App\Models\MakalahChapter;
use App\Models\MakalahReference;
use App\Models\MakalahSubchapter;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Auth;

class MakalahService
{
    // ── CRUD Makalah ──────────────────────────────────────────────────────────

    public function getAll(): Collection
    {
        return Auth::user()
            ->makalah()
            ->with(['chapters', 'references'])
            ->latest()
            ->get();
    }

    public function create(array $data): Makalah
    {
        $data['user_id'] = Auth::id();

        // Merge settings dari input dengan default
        $defaultSettings = (new Makalah())->getDefaultSettings();
        $data['settings'] = array_merge(
            $defaultSettings,
            $data['settings'] ?? []
        );

        $makalah = Makalah::create($data);

        // Buat chapter default: Kata Pengantar, Bab I Pendahuluan, Penutup
        $this->initDefaultChapters($makalah);

        return $makalah->fresh(['chapters', 'references']);
    }

    public function update(Makalah $makalah, array $data): Makalah
    {
        if (isset($data['settings']) && is_array($data['settings'])) {
            $data['settings'] = array_merge(
                $makalah->settings ?? [],
                $data['settings']
            );
        }

        $makalah->update($data);
        return $makalah->fresh();
    }

    public function delete(Makalah $makalah): void
    {
        $makalah->delete();
    }

    // ── Chapter management ────────────────────────────────────────────────────

    public function addChapter(Makalah $makalah, array $data): MakalahChapter
    {
        $maxOrder = $makalah->chapters()->max('order') ?? 0;

        $isBab  = ($data['type'] ?? 'bab') === 'bab';
        $babNum = $isBab ? $makalah->nextBabNumber() : null;

        return $makalah->chapters()->create([
            'title'      => $data['title'],
            'type'       => $data['type'] ?? 'bab',
            'content'    => $data['content'] ?? null,
            'bab_number' => $babNum,
            'order'      => $maxOrder + 1,
        ]);
    }

    public function updateChapter(MakalahChapter $chapter, array $data): MakalahChapter
    {
        $chapter->update($data);
        return $chapter->fresh();
    }

    public function deleteChapter(MakalahChapter $chapter): void
    {
        $chapter->delete();
        // Re-number bab after deletion
        $this->renumberBabs($chapter->makalah);
    }

    public function reorderChapters(Makalah $makalah, array $orderedIds): void
    {
        foreach ($orderedIds as $index => $id) {
            MakalahChapter::where('id', $id)
                ->where('makalah_id', $makalah->id)
                ->update(['order' => $index]);
        }
        $this->renumberBabs($makalah);
    }

    // ── Subchapter management ─────────────────────────────────────────────────

    public function addSubchapter(MakalahChapter $chapter, array $data): MakalahSubchapter
    {
        $maxOrder = $chapter->subchapters()->max('order') ?? 0;
        
        return $chapter->subchapters()->create([
            'title' => $data['title'],
            'order' => $maxOrder + 1,
        ]);
    }

    public function updateSubchapter(MakalahSubchapter $subchapter, array $data): MakalahSubchapter
    {
        $subchapter->update($data);
        return $subchapter->fresh();
    }

    public function deleteSubchapter(MakalahSubchapter $subchapter): void
    {
        $subchapter->delete();
    }

    public function updateSubchapterContent(MakalahSubchapter $subchapter, string $content = null): void
    {
        $subchapter->update(['content' => $content]);
    }

    // ── Reference management ──────────────────────────────────────────────────

    public function addReference(Makalah $makalah, array $data): MakalahReference
    {
        $maxOrder = $makalah->references()->max('order') ?? 0;
        $data['order'] = $maxOrder + 1;
        return $makalah->references()->create($data);
    }

    public function updateReference(MakalahReference $ref, array $data): MakalahReference
    {
        $ref->update($data);
        return $ref->fresh();
    }

    public function deleteReference(MakalahReference $ref): void
    {
        $ref->delete();
    }

    public function sortReferencesAlphabetically(Makalah $makalah): void
    {
        $refs = $makalah->references()->orderBy('penulis')->get();
        foreach ($refs as $i => $ref) {
            $ref->update(['order' => $i]);
        }
    }

    // ── Table of Contents ─────────────────────────────────────────────────────

    /**
     * Generate structure untuk daftar isi
     */
    public function generateToc(Makalah $makalah): array
    {
        $toc   = [];
        $pageN = 1; // estimasi halaman (tidak akurat, hanya untuk display)

        $chapters = $makalah->chapters()->orderBy('order')->get();

        foreach ($chapters as $ch) {
            if ($ch->type === 'cover') continue;

            $entry = [
                'title'    => $ch->type === 'bab'
                    ? $ch->bab_label . ' — ' . strtoupper($ch->title)
                    : strtoupper($ch->title),
                'type'     => $ch->type,
                'level'    => 1,
                'children' => [],
            ];

            // Sub-bab
            if ($ch->type === 'bab' && ! empty($ch->sub_sections)) {
                foreach ($ch->sub_sections as $sub) {
                    $entry['children'][] = [
                        'title' => ($ch->bab_number . '.' . ($sub['number'] ?? '?') . ' ' . $sub['title']),
                        'level' => 2,
                    ];
                }
            }

            $toc[] = $entry;
        }

        // Append Daftar Pustaka
        if ($makalah->references()->exists()) {
            $toc[] = ['title' => 'DAFTAR PUSTAKA', 'type' => 'pustaka', 'level' => 1, 'children' => []];
        }

        return $toc;
    }

    // ── Private helpers ───────────────────────────────────────────────────────

    private function initDefaultChapters(Makalah $makalah): void
    {
        $defaults = Makalah::DEFAULT_BAB;

        // Kata Pengantar
        $makalah->chapters()->create(['title' => 'Kata Pengantar', 'type' => 'kata_pengantar', 'order' => 0, 'bab_number' => null]);

        // Babs and Subbabs
        foreach ($defaults as $i => $bab) {
            $chapter = $makalah->chapters()->create([
                'title'      => $bab['judul'],
                'type'       => 'bab',
                'order'      => $i + 1,
                'bab_number' => $bab['nomor']
            ]);

            foreach ($bab['sub'] as $j => $subTitle) {
                $chapter->subchapters()->create([
                    'title' => $subTitle,
                    'order' => $j + 1
                ]);
            }
        }

        // Add default empty reference
        $makalah->references()->create([
            'penulis' => 'Pustaka',
            'judul' => '',
            'raw_citation' => '',
            'order' => 0
        ]);
    }

    private function renumberBabs(Makalah $makalah): void
    {
        $babs = $makalah->chapters()
            ->where('type', 'bab')
            ->orderBy('order')
            ->get();

        foreach ($babs as $i => $bab) {
            $bab->update(['bab_number' => $i + 1]);
        }
    }
}
