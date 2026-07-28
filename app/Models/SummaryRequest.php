<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SummaryRequest extends Model
{
    protected $fillable = [
        'user_id', 'subject_id', 'material_id', 'manual_text', 'status', 'note_id'
    ];

    public function user() { return $this->belongsTo(User::class); }
    public function subject() { return $this->belongsTo(Subject::class); }
    public function material() { return $this->belongsTo(Material::class); }
    public function note() { return $this->belongsTo(Note::class); }
}
