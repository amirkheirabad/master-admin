<?php

namespace Modules\Ticket\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Modules\User\Models\User;
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
        'sender_id',
    ];

    protected static $recordEvents = ['updated'];

    public function ticket()
    {
        return $this->belongsTo(Ticket::class);
    }

    public function sender()
    {
        return $this->belongsTo(User::class, 'sender_id');
    }

    public function getActivityLogOptions() : LogOptions
    {
        return LogOptions::defaults()->logOnly(['ticket_id', 'messages', 'sender_type'])->useLogName('ticket_messages');
    }

}
