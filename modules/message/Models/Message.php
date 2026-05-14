<?php

namespace Modules\Message\Models;

use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
    protected $table = 'messages';
    
    protected $fillable = [
        'title',
        'content',
        'is_active',
        'order',
    ];
    
    protected $casts = [
        'is_active' => 'boolean',
    ];
}