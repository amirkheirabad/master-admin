document.addEventListener('DOMContentLoaded', function () {
    var canvas = document.getElementById('factorsChart');
    if (!canvas || typeof Chart === 'undefined') {
        return;
    }

    var labels = [];
    var data = [];

    try {
        labels = JSON.parse(canvas.getAttribute('data-labels') || '[]');
        data = JSON.parse(canvas.getAttribute('data-values') || '[]');
    } catch (e) {
        return;
    }

    var ctx = canvas.getContext('2d');
    new Chart(ctx, {
        type: 'line',
        data: {
            labels: labels,
            datasets: [{
                label: 'تعداد فاکتور',
                data: data,
                borderColor: '#133c6d',
                backgroundColor: 'rgba(19, 60, 109, 0.08)',
                borderWidth: 2,
                pointBackgroundColor: '#133c6d',
                pointRadius: 4,
                pointHoverRadius: 6,
                fill: true,
                tension: 0.35
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            legend: { display: false },
            scales: {
                yAxes: [{
                    ticks: {
                        beginAtZero: true,
                        precision: 0,
                        fontFamily: 'inherit'
                    },
                    gridLines: { color: 'rgba(0,0,0,0.06)' }
                }],
                xAxes: [{
                    ticks: { fontFamily: 'inherit' },
                    gridLines: { display: false }
                }]
            },
            tooltips: {
                rtl: true,
                callbacks: {
                    label: function (item) {
                        return ' تعداد: ' + item.yLabel;
                    }
                }
            }
        }
    });
});
