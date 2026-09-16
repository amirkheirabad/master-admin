@extends('layouts.admin.master')

@section('css')
    <link rel="stylesheet" href="{{ asset('/css/apex-charts.css') }}">
@endsection

@section('content')
    <div class="row" id="ticket-reports" data-endpoint="{{ route('reports-tickets') }}">
        <div class="col-md-12">
            <h3>گزارش تیکت‌ها</h3>

            <div class="x_panel custom-radius mt-3">
                <h4>تعداد تیکت‌های ایجادشده بر اساس وضعیت فعلی</h4>
                <p class="text-muted">این نمودار وضعیت فعلی تیکت‌های ایجادشده در هر بازه را نشان می‌دهد و تاریخچه تغییر وضعیت نیست.</p>

                <div class="btn-group mb-3 mt-1" role="group" aria-label="گروه‌بندی گزارش وضعیت تیکت">
                    @foreach(['daily' => 'روزانه', 'weekly' => 'هفتگی', 'monthly' => 'ماهانه'] as $group => $label)
                        <button type="button"
                                class="btn report-group-button {{ request('status_group', 'daily') === $group ? 'btn-beta-solid' : 'btn-beta-outline' }}"
                                data-report="status" data-group="{{ $group }}"
                                aria-controls="ticket-status-chart"
                                aria-pressed="{{ request('status_group', 'daily') === $group ? 'true' : 'false' }}">
                            {{ $label }}
                        </button>
                    @endforeach
                </div>

                <div id="ticket-status-chart"></div>
            </div>

            <div class="x_panel custom-radius mt-3">
                <h4>تعداد تیکت‌های پاسخ‌داده‌شده توسط هر مدیر</h4>
                <p class="text-muted">هر تیکت برای هر مدیر در هر بازه زمانی فقط یک بار شمرده می‌شود.</p>

                <div class="btn-group mb-3 mt-1" role="group" aria-label="گروه‌بندی گزارش پاسخ مدیران">
                    @foreach(['daily' => 'روزانه', 'weekly' => 'هفتگی', 'monthly' => 'ماهانه'] as $group => $label)
                        <button type="button"
                                class="btn report-group-button {{ request('admin_group', 'daily') === $group ? 'btn-beta-solid' : 'btn-beta-outline' }}"
                                data-report="admin" data-group="{{ $group }}"
                                aria-controls="admin-response-chart"
                                aria-pressed="{{ request('admin_group', 'daily') === $group ? 'true' : 'false' }}">
                            {{ $label }}
                        </button>
                    @endforeach
                </div>

                <div id="admin-response-chart"></div>
            </div>
        </div>
    </div>
@endsection

@section('js')
    <script src="{{ asset('/js/apexcharts.js') }}"></script>
    <script type="application/json" id="ticket-status-data">@json($ticketStatusReport)</script>
    <script type="application/json" id="admin-response-data">@json($adminResponseReport)</script>
    <script src="{{ asset('/js/ticket-reports.js') }}"></script>
    <script>
        const ticketReports = document.getElementById('ticket-reports');
        const convertTicketReportDates = () => ticketReports.querySelectorAll('.apexcharts-xaxis-texts-g text tspan').forEach(el => {
            const converted = el.textContent.replace(/[0-9]/g, digit => '۰۱۲۳۴۵۶۷۸۹'[digit]);
            if (el.textContent !== converted) el.textContent = converted;
        });

        new MutationObserver(convertTicketReportDates).observe(ticketReports, { childList: true, characterData: true, subtree: true });
        convertTicketReportDates();
    </script>
@endsection
