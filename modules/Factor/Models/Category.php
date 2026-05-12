<?php

namespace Modules\Factor\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Modules\Factor\Models\Factor;

class Category extends Model
{
    use HasFactory;

    protected $table = 'categories';

    public function factors()
    {
        return $this->hasMany(Factor::class);
    }


    protected $fillable = ['name','active'];
}
