<?php

namespace Modules\Team\Models;

use Illuminate\Database\Eloquent\Model;
use Modules\Ticket\Models\Ticket;
use Modules\User\Models\User;

class Team extends Model
{
    protected $fillable = ['name'];

    public function users()
    {
        return $this->hasMany(User::class);
    }

    public function tickets()
    {
        return $this->hasMany(Ticket::class);
    }
}
