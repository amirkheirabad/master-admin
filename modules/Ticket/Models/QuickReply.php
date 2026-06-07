<?php

namespace Modules\Ticket\Models;

use Illuminate\Database\Eloquent\Model;

class QuickReply extends Model
{
    protected $fillable = ['title', 'body'];
}