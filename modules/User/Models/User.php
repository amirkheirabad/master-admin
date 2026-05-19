<?php

namespace Modules\User\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Spatie\Permission\Traits\HasRoles;
use Modules\Stores\Models\Stores;

class User extends Authenticatable
{
    use HasFactory, HasRoles;

    protected $table = 'users';

    protected $guard_name = 'web';

    protected $fillable = [
        'name',
        'mobile',
        'password',
        'is_active',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function stores()
    {
        return $this->hasOne(Stores::class);
    }
}
