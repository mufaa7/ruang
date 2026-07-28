<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MakalahSubchapter extends Model
{
    protected $fillable = [
        'makalah_chapter_id',
        'title',
        'content',
        'order',
        'ai_generated',
    ];

    protected $casts = [
        'ai_generated' => 'boolean',
    ];

    public function chapter(): BelongsTo
    {
        return $this->belongsTo(MakalahChapter::class, 'makalah_chapter_id');
    }
}
