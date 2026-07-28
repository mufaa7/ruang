<?php

namespace App\Models;

use Database\Factories\NoteFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Note extends Model
{
    /** @use HasFactory<NoteFactory> */
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'user_id',
        'folder_id',
        'subject_id',
        'material_id',
        'title',
        'content',
        'color',
        'is_pinned',
        'is_ai_generated',
        'visibility',
        'settings',
        'last_edited_at',
    ];

    protected function casts(): array
    {
        return [
            'is_pinned'     => 'boolean',
            'settings'      => 'array',
            'last_edited_at' => 'datetime',
        ];
    }

    // ─── Relationships ────────────────────────────────────────────────────

    public function author(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function folder(): BelongsTo
    {
        return $this->belongsTo(NoteFolder::class, 'folder_id');
    }

    public function subject(): BelongsTo
    {
        return $this->belongsTo(Subject::class);
    }

    public function material(): BelongsTo
    {
        return $this->belongsTo(Material::class);
    }

    public function tags(): BelongsToMany
    {
        return $this->belongsToMany(Tag::class, 'note_tags')->withTimestamps();
    }

    // ─── Scopes ──────────────────────────────────────────────────────────

    public function scopePinned($query)
    {
        return $query->where('is_pinned', true);
    }

    public function scopePublic($query)
    {
        return $query->where('visibility', 'public');
    }

    // ─── Helpers ─────────────────────────────────────────────────────────

    public function isOwnedBy(User $user): bool
    {
        return $this->user_id === $user->id;
    }

    public function getExcerptAttribute(): string
    {
        return \Str::limit(strip_tags($this->content ?? ''), 150);
    }
}
