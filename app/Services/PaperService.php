<?php

namespace App\Services;

use App\Models\Paper;
use App\Models\PaperSection;
use App\Models\User;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Str;

class PaperService
{
    /**
     * Ambil semua paper publik dengan filter
     */
    public function getPublicPapers(array $filters = []): LengthAwarePaginator
    {
        $query = Paper::with(['author', 'subject', 'tags'])
            ->published()
            ->public();

        if (!empty($filters['search'])) {
            $search = $filters['search'];
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('abstract', 'like', "%{$search}%");
            });
        }

        if (!empty($filters['subject_id'])) {
            $query->where('subject_id', $filters['subject_id']);
        }

        if (!empty($filters['tag'])) {
            $query->whereHas('tags', fn ($q) => $q->where('slug', $filters['tag']));
        }

        $sortBy = $filters['sort'] ?? 'published_at';
        $query->orderByDesc($sortBy);

        return $query->paginate(12);
    }

    /**
     * Ambil paper milik user
     */
    public function getUserPapers(User $user, array $filters = []): LengthAwarePaginator
    {
        $query = Paper::with(['subject', 'tags'])
            ->where('user_id', $user->id);

        if (!empty($filters['status'])) {
            $query->where('status', $filters['status']);
        }

        return $query->latest()->paginate(15);
    }

    /**
     * Buat paper baru
     */
    public function createPaper(User $user, array $data): Paper
    {
        $paper = Paper::create([
            'user_id'    => $user->id,
            'subject_id' => $data['subject_id'] ?? null,
            'title'      => $data['title'],
            'slug'       => $this->generateUniqueSlug($data['title']),
            'abstract'   => $data['abstract'] ?? null,
            'visibility' => $data['visibility'] ?? 'private',
            'status'     => 'draft',
        ]);

        // Buat section default
        PaperSection::create([
            'paper_id' => $paper->id,
            'title'    => 'Pendahuluan',
            'type'     => 'introduction',
            'order'    => 0,
        ]);

        // Attach tags
        if (!empty($data['tags'])) {
            $tagIds = [];
            foreach ($data['tags'] as $tagName) {
                if (trim($tagName) === '') continue;
                $tag = \App\Models\Tag::firstOrCreate(
                    ['slug' => \Illuminate\Support\Str::slug($tagName)],
                    [
                        'name' => trim($tagName),
                        'color' => '#' . str_pad(dechex(mt_rand(0, 0xFFFFFF)), 6, '0', STR_PAD_LEFT)
                    ]
                );
                $tagIds[] = $tag->id;
            }
            $paper->tags()->sync($tagIds);
        }

        return $paper->load(['sections', 'tags']);
    }

    /**
     * Update paper
     */
    public function updatePaper(Paper $paper, array $data): Paper
    {
        $paper->update([
            'title'      => $data['title'] ?? $paper->title,
            'abstract'   => $data['abstract'] ?? $paper->abstract,
            'subject_id' => $data['subject_id'] ?? $paper->subject_id,
            'visibility' => $data['visibility'] ?? $paper->visibility,
        ]);

        if (isset($data['tags'])) {
            $tagIds = [];
            foreach ($data['tags'] as $tagName) {
                if (trim($tagName) === '') continue;
                $tag = \App\Models\Tag::firstOrCreate(
                    ['slug' => \Illuminate\Support\Str::slug($tagName)],
                    [
                        'name' => trim($tagName),
                        'color' => '#' . str_pad(dechex(mt_rand(0, 0xFFFFFF)), 6, '0', STR_PAD_LEFT)
                    ]
                );
                $tagIds[] = $tag->id;
            }
            $paper->tags()->sync($tagIds);
        }

        return $paper->refresh();
    }

    /**
     * Publish paper
     */
    public function publishPaper(Paper $paper): Paper
    {
        $paper->update([
            'status'       => 'published',
            'published_at' => now(),
        ]);

        return $paper;
    }

    /**
     * Archive paper
     */
    public function archivePaper(Paper $paper): Paper
    {
        $paper->update(['status' => 'archived']);
        return $paper;
    }

    /**
     * Hapus paper (soft delete)
     */
    public function deletePaper(Paper $paper): void
    {
        $paper->delete();
    }

    /**
     * Increment view count
     */
    public function incrementView(Paper $paper): void
    {
        $paper->increment('view_count');
    }

    // ── Section management ──────────────────────────────────────────────────

    public function addSection(Paper $paper, array $data): PaperSection
    {
        $maxOrder = $paper->sections()->max('order') ?? -1;

        $section = $paper->sections()->create([
            'title' => $data['title'],
            'type'  => $data['type'],
            'order' => $maxOrder + 1,
            'content' => '',
        ]);

        $paper->touch(); // Update updated_at
        return $section;
    }

    public function updateSection(PaperSection $section, array $data): PaperSection
    {
        $section->update($data);
        $section->paper->touch();
        
        return $section->fresh();
    }

    public function deleteSection(PaperSection $section): void
    {
        $paper = $section->paper;
        $section->delete();
        $this->reorderRemainingSections($paper);
        $paper->touch();
    }

    private function reorderRemainingSections(Paper $paper): void
    {
        $sections = $paper->sections()->orderBy('order')->get();
        foreach ($sections as $index => $s) {
            $s->update(['order' => $index]);
        }
    }

    /**
     * Generate slug unik
     */
    private function generateUniqueSlug(string $title): string
    {
        $slug = Str::slug($title);
        $count = Paper::where('slug', 'like', "{$slug}%")->count();
        return $count > 0 ? "{$slug}-{$count}" : $slug;
    }
}
