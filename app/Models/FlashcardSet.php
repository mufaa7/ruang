<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FlashcardSet extends Model
{
    protected $fillable = ['subject_id', 'material_id', 'title', 'type', 'user_id', 'quiz_id'];

    public function quiz()
    {
        return $this->belongsTo(Quiz::class, 'quiz_id');
    }

    public function flashcards()
    {
        return $this->hasMany(Flashcard::class);
    }

    public function subject()
    {
        return $this->belongsTo(Subject::class);
    }

    public function targets()
    {
        return $this->belongsToMany(User::class, 'flashcard_set_user_targets');
    }
}
