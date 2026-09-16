const reportConfigs = {
    status: { selector: '#ticket-status-chart', dataId: 'ticket-status-data', groupParam: 'status_group' },
    admin: { selector: '#admin-response-chart', dataId: 'admin-response-data', groupParam: 'admin_group' }
};
const reportCharts = {};

function renderReportChart(reportName) {
    const config = reportConfigs[reportName];
    const report = JSON.parse(document.getElementById(config.dataId).textContent);

    reportCharts[reportName] = new ApexCharts(document.querySelector(config.selector), {
        chart: { type: 'bar', height: 380, toolbar: { show: true } },
        series: report.series,
        xaxis: { categories: report.categories },
        yaxis: { min: 0, forceNiceScale: true, title: { text: 'تعداد تیکت' } },
        dataLabels: { enabled: false },
        noData: { text: 'داده‌ای برای نمایش وجود ندارد' },
        legend: { position: 'bottom' }
    });
    reportCharts[reportName].render();
}

renderReportChart('status');
renderReportChart('admin');

document.querySelectorAll('.report-group-button').forEach(button => {
    button.addEventListener('click', async () => {
        const reportName = button.dataset.report;
        const config = reportConfigs[reportName];
        const reportButtons = document.querySelectorAll(`.report-group-button[data-report="${reportName}"]`);
        const url = new URL(document.getElementById('ticket-reports').dataset.endpoint, window.location.origin);

        new URLSearchParams(window.location.search).forEach((value, key) => url.searchParams.set(key, value));
        url.searchParams.set('report', reportName);
        url.searchParams.set(config.groupParam, button.dataset.group);
        reportButtons.forEach(item => item.disabled = true);

        try {
            const response = await fetch(url, {
                headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' }
            });
            if (!response.ok) throw new Error(`HTTP ${response.status}`);

            const report = await response.json();
            await reportCharts[reportName].updateOptions({
                series: report.series,
                xaxis: { categories: report.categories }
            });

            reportButtons.forEach(item => {
                const active = item === button;
                item.classList.toggle('btn-beta-solid', active);
                item.classList.toggle('btn-beta-outline', !active);
                item.setAttribute('aria-pressed', active ? 'true' : 'false');
            });

            const browserUrl = new URL(window.location.href);
            browserUrl.searchParams.set(config.groupParam, button.dataset.group);
            history.replaceState({}, '', browserUrl);
        } catch (error) {
            if (typeof showServerConnectionError === 'function') showServerConnectionError();
        } finally {
            reportButtons.forEach(item => item.disabled = false);
        }
    });
});
