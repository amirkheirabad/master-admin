<?php

namespace Modules\Ticket\Controllers\Api;

use Modules\Ticket\Requests\TicketStoreRequest;
use Modules\Ticket\Requests\TicketReplyRequest;
use Modules\Ticket\Repositories\InterfaceTicket;

class TicketController
{

    private InterfaceTicket $ticket;
    public function __construct(InterfaceTicket $ticket)
    {
        $this->ticket = $ticket;
    }


}
