<?php

namespace Modules\Ticket\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Modules\Stores\Models\Stores;


class TicketMessage extends Model
{
    use HasFactory;
    protected $table = 'ticket_messages';

    protected $fillable = [
        'ticket_id',
        'messages',
        'sender_type',
        'attachments',
    ];

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

}
