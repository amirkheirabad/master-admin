<?php

namespace Modules\Factor\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Modules\Stores\Models\Stores;
use Modules\Factor\Models\Category;

class Factor extends Model
{
    use HasFactory;

    protected $table = 'factors';


    protected $fillable = [
        'store_id',
        'category_id',
        'price',
        'description',
        'show_status',
        'price_status',
        'factor_date',
        'hash',
        'image',
        'name',
        'national_kod',
        'phone',
        'paid_factor_date',
    ];

    public function store()
    {
        return $this->belongsTo(Stores::class);
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }
}
