<?php

namespace Modules\Stores\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Modules\SmsPanel\Models\SmsPanel;
use Modules\Factor\Models\Factor;
use Modules\User\Models\User;

class Stores extends Model
{
    use HasFactory;

    protected $table = 'stores';

    protected $fillable = [
        'store_name',
        'link',
        'user_id',
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
        'logo_path',
        'enamd_expiration_date',
        'domain_expiration_date',
    ];

    public function smsPanel()
    {
        return $this->hasMany(SmsPanel::class, 'store_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function factors()
    {
        return $this->hasMany(Factor::class);
    }

    public function checkLists()
    {
        return $this->belongsToMany(CheckList::class, 'store_check_lists', 'store_id', 'check_list_id');
    }
}
