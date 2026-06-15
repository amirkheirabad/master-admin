<?php

namespace Modules\Factor\Models;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Modules\Stores\Models\Stores;
use Modules\Factor\Models\Category;
use Modules\User\Models\User;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class Factor extends Model
{
    use HasFactory;
    use SoftDeletes;
    use LogsActivity;

    protected $table = 'factors';


    protected $fillable = [
        'store_id',
        'category_id',
        'user_id',
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
    public function customer()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function getActivityLogOptions() : logOptions
    {
        return LogOptions::defaults()
            ->logOnly([
                'show_status',
                'price_status',
                'category_id',
                'price',
                'description',
                'factor_date',
                'paid_factor_date',
                'image',
                'store_id',
                'user_id',
            ])
            ->useLogName('factor');
    }
}
