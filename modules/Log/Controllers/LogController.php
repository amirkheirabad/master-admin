<?php

namespace Modules\Log\Controllers;

use Illuminate\Http\Request;
use Modules\Log\Repositories\InterfaceLog;

class LogController
{
    private InterfaceLog $log;
    public function __construct(InterfaceLog $log)
    {
        $this->log = $log;
    }
    public function indexFactor(Request $request)
    {
        $factors = $this->log->getFactors($request);
        return view('templates.log.factor', compact('factors'));
    }

    public function indexTicket()
    {
        $tickets = $this->log->getTickets();
        return view('templates.log.ticket', compact('tickets'));
    }

}
