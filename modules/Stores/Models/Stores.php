<?php

namespace Modules\Stores\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Modules\SmsPanel\Models\SmsPanel;
use Modules\Factor\Models\Factor;

class Stores extends Model
{
    use HasFactory;

    protected $table = 'stores';

    protected $fillable = [
        'store_name',
        'manager_name',
        'link',
        'phone',
        'province',
        'city',
        'slogan',
        'location',
        'code_posty',
        'latitude',
        'longitude',
        'about',
        'token',
    ];

    public function smsPanel()
    {
        return $this->hasMany(SmsPanel::class, 'store_id');
    }

    public function factors()
    {
        return $this->hasMany(Factor::class);
    }
}
