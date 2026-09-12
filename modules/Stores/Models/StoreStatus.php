<?php

namespace Modules\Stores\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class StoreStatus extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = ['name'];

    public function stores()
    {
        return $this->hasMany(Stores::class, 'status_id');
    }
}
