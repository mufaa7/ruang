<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AiUsageLog extends Model
{
    protected $fillable = [
        'provider',
        'prompt_tokens',
        'completion_tokens',
        'total_tokens',
        'estimated_cost',
        'model',
        'duration',
        'user_id',
        'feature_name',
        'status',
        'error_message',
        'ip_address'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
