<?php

namespace Modules\SmsPanel\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Modules\Stores\Models\Stores;

class SmsPanel extends Model
{
    use HasFactory;

    protected $table = 'sms_panels';

    public function store()
    {
        return $this->belongsTo(Stores::class , 'store_id');
    }

    protected $fillable = ['store_id','store_message','status', 'admin_message','campaign_name'];
}
