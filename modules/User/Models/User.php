<?php

namespace Modules\User\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Modules\Factor\Models\Factor as ModelsFactor;
use Spatie\Permission\Traits\HasRoles;
use Modules\Stores\Models\Stores;
use Modules\Factor\Models\Factor;

class User extends Authenticatable
{
    use HasFactory, HasRoles;
    use SoftDeletes;

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
    public function factors()
    {
        return $this->hasMany(Factor::class, 'user_id');
    }
}
