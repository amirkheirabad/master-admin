<?php

namespace Modules\Ticket\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Modules\SmsPanel\Models\SmsPanel;
use Modules\Stores\Models\Stores;
use Modules\Ticket\Models\TicketMessage;

class Ticket extends Model
{
    use HasFactory;

    protected $table = 'tickets';


    protected $fillable = [
        'title',
        'status',
        'priority',
        'contact_name',
        'is_seen',
        'store_id',
    ];
    protected $casts = [
    'priority' => 'integer',   // اضافه کن
    ];

//    public function sender()
//    {
//        return $this->belongsTo(Stores::class);
//    }

    public function store()
    {
        return $this->belongsTo(Stores::class);
    }


    public function messages()
    {
        return $this->hasMany(TicketMessage::class);
    }


}
