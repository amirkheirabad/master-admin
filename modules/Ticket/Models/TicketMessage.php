<?php

namespace Modules\Ticket\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Modules\Stores\Models\Stores;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;


class TicketMessage extends Model
{
    use HasFactory;
    use LogsActivity;
    protected $table = 'ticket_messages';

    protected $fillable = [
        'ticket_id',
        'messages',
        'sender_type',
        'attachments',
    ];

    protected static $recordEvents = ['updated'];

    public function ticket()
    {
        return $this->belongsTo(Ticket::class);
    }

    public function sender()
    {
        if ($this->sender_type == 0) {
            return $this->belongsTo(Stores::class, 'sender_id');
        }

    return null;

    }

    public function getActivityLogOptions() : LogOptions
    {
        return LogOptions::defaults()->logOnly(['ticket_id', 'messages', 'sender_type'])->useLogName('ticket_messages');
    }

}
