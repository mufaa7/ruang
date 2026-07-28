<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class PaperComment extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'paper_id',
        'user_id',
        'parent_id',
        'section_id',
        'content',
        'is_resolved',
    ];

    protected function casts(): array
    {
        return [
            'is_resolved' => 'boolean',
        ];
    }

    // ─── Relationships ────────────────────────────────────────────────────

    public function paper(): BelongsTo
    {
        return $this->belongsTo(Paper::class);
    }

    public function author(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function parent(): BelongsTo
    {
        return $this->belongsTo(PaperComment::class, 'parent_id');
    }

    public function replies(): HasMany
    {
        return $this->hasMany(PaperComment::class, 'parent_id');
    }

    public function section(): BelongsTo
    {
        return $this->belongsTo(PaperSection::class, 'section_id');
    }
}
