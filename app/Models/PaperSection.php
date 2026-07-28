<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class PaperSection extends Model
{
    use HasFactory;

    protected $fillable = [
        'paper_id',
        'title',
        'content',
        'order',
        'type',
        'snapshot',
    ];

    protected function casts(): array
    {
        return [
            'snapshot' => 'array',
            'order'    => 'integer',
        ];
    }

    // ─── Relationships ────────────────────────────────────────────────────

    public function paper(): BelongsTo
    {
        return $this->belongsTo(Paper::class);
    }

    public function comments(): HasMany
    {
        return $this->hasMany(PaperComment::class, 'section_id');
    }
}
