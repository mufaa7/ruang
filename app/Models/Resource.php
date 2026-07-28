<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Resource extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'title',
        'description',
        'type',
        'url',
        'file_path',
        'mime_type',
        'file_size',
        'is_public',
        'download_count',
    ];

    protected function casts(): array
    {
        return [
            'is_public'      => 'boolean',
            'file_size'      => 'integer',
            'download_count' => 'integer',
        ];
    }

    // ─── Relationships ────────────────────────────────────────────────────

    public function uploader(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function papers(): BelongsToMany
    {
        return $this->belongsToMany(Paper::class, 'resource_papers')->withTimestamps();
    }

    // ─── Helpers ─────────────────────────────────────────────────────────

    public function getFormattedSizeAttribute(): string
    {
        if (!$this->file_size) return 'N/A';
        $bytes = $this->file_size;
        $units = ['B', 'KB', 'MB', 'GB'];
        $i = 0;
        while ($bytes >= 1024 && $i < count($units) - 1) {
            $bytes /= 1024;
            $i++;
        }
        return round($bytes, 2) . ' ' . $units[$i];
    }
}
