<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Quiz extends Model
{
    protected $fillable = [
        'subject_id',
        'material_id',
        'title',
        'time_limit_minutes',
        'type',
        'user_id',
    ];

    public function subject(): BelongsTo
    {
        return $this->belongsTo(Subject::class);
    }

    public function material(): BelongsTo
    {
        return $this->belongsTo(Material::class);
    }

    public function questions(): HasMany
    {
        return $this->hasMany(QuizQuestion::class);
    }

    public function attempts(): HasMany
    {
        return $this->hasMany(QuizAttempt::class);
    }

    public function targets()
    {
        return $this->belongsToMany(User::class, 'quiz_user_targets');
    }

    public function flashcardSet()
    {
        return $this->hasOne(FlashcardSet::class, 'quiz_id');
    }
}
