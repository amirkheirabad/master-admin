<?php

namespace Modules\Log\Controllers;

use Illuminate\Http\Request;
use Modules\Log\Repositories\InterfaceLog;
use Modules\Log\Requests\StoreChecklistLogsRequest;

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

    public function indexSmsPanel()
    {
        $smsPanels = $this->log->getSmsPanels();
        return view('templates.log.smsPanel', compact('smsPanels'));

    }

    public function ticketReports(Request $request)
    {
        $request->validate([
            'report' => [$request->expectsJson() ? 'required' : 'nullable', 'in:status,admin'],
            'status_group' => ['nullable', 'in:daily,weekly,monthly'],
            'admin_group' => ['nullable', 'in:daily,weekly,monthly'],
            'status_from' => ['nullable', 'jdate:Y/m/d'],
            'status_to' => array_filter(['nullable', 'jdate:Y/m/d', $request->filled('status_from') ? 'after_or_equal:status_from' : null]),
            'admin_from' => ['nullable', 'jdate:Y/m/d'],
            'admin_to' => array_filter(['nullable', 'jdate:Y/m/d', $request->filled('admin_from') ? 'after_or_equal:admin_from' : null]),
        ]);

        if ($request->expectsJson()) {
            return response()->json($request->report === 'status'
                ? $this->log->getTicketStatusReport($request)
                : $this->log->getAdminResponseReport($request));
        }

        $ticketStatusReport = $this->log->getTicketStatusReport($request);
        $adminResponseReport = $this->log->getAdminResponseReport($request);

        return view('templates.log.ticket-reports', compact('ticketStatusReport', 'adminResponseReport'));
    }

    public function storeChecklistLogs(StoreChecklistLogsRequest $request)
    {
        $logs = $this->log->getStoreChecklistLogs($request);
        $stores = $this->log->getStoresForChecklistReport();

        return view('templates.log.store-checklist-logs', compact('logs', 'stores'));
    }

}
