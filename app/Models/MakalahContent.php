<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MakalahContent extends Model
{
    protected $fillable = [
        'makalah_id',
        'bab',
        'sub',
        'content',
    ];

    public function makalah()
    {
        return $this->belongsTo(Makalah::class);
    }
}
