<?php

namespace Modules\SmsPanel\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Modules\Stores\Models\Stores;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class SmsPanel extends Model
{
    use HasFactory;
    use LogsActivity;

    protected $table = 'sms_panels';

    public function store()
    {
        return $this->belongsTo(Stores::class , 'store_id');
    }

    protected $fillable = ['store_id','store_message','status', 'admin_message','campaign_name', 'external_id'];

    protected static $recordEvents = ['updated'];

    public function getActivityLogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly([
                'store_id',
                'campaign_name',
                'status',
            ])->useLogName('SmsPanel');
    }
}
