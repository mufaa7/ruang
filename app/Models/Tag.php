<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Tag extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'color',
    ];

    // ─── Relationships ────────────────────────────────────────────────────

    public function papers(): BelongsToMany
    {
        return $this->belongsToMany(Paper::class, 'paper_tags')->withTimestamps();
    }

    public function notes(): BelongsToMany
    {
        return $this->belongsToMany(Note::class, 'note_tags')->withTimestamps();
    }
}
