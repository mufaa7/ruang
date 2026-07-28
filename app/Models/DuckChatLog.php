<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DuckChatLog extends Model
{
    protected $fillable = ['user_id', 'user_message', 'duck_response'];
}
