<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DuckDialogue extends Model
{
    protected $fillable = [
        'event',
        'mood',
        'content',
        'last_used_at'
    ];
    
    protected $casts = [
        'last_used_at' => 'datetime'
    ];
}
