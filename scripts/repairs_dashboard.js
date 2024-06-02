document.addEventListener('DOMContentLoaded', () => {
    fetchData();
});

function fetchData(startDate = '', endDate = '') {
    fetch(`../fetch_repairs_dashboard_data.php?start_date=${startDate}&end_date=${endDate}`)
        .then(response => response.json())
        .then(data => {
            console.log('Otrzymane dane:', data);  // Debugowanie odpowiedzi JSON
            updateCharts(data);
        })
        .catch(error => console.error('Błąd:', error));
}

function filterData() {
    const startDate = document.getElementById('start-date').value;
    const endDate = document.getElementById('end-date').value;
    fetchData(startDate, endDate);
}

function updateCharts(data) {
    const progressChartCtx = document.getElementById('progressChart').getContext('2d');
    const statusChartCtx = document.getElementById('statusChart').getContext('2d');
    const partsChartCtx = document.getElementById('partsChart').getContext('2d');
    const costsChartCtx = document.getElementById('costsChart').getContext('2d');
    const diagnosticsChartCtx = document.getElementById('diagnosticsChart').getContext('2d');
    const testsChartCtx = document.getElementById('testsChart').getContext('2d');

    new Chart(progressChartCtx, {
        type: 'line',
        data: {
            labels: data.progress.labels,
            datasets: [{
                label: 'Postępy napraw',
                data: data.progress.counts,
                backgroundColor: 'rgba(75, 192, 192, 0.2)',
                borderColor: 'rgba(75, 192, 192, 1)',
                borderWidth: 1
            }]
        },
        options: {
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });

    new Chart(statusChartCtx, {
        type: 'pie',
        data: {
            labels: data.status.labels,
            datasets: [{
                label: 'Statusy napraw',
                data: data.status.counts,
                backgroundColor: [
                    'rgba(255, 99, 132, 0.2)',
                    'rgba(54, 162, 235, 0.2)',
                    'rgba(255, 206, 86, 0.2)'
                ],
                borderColor: [
                    'rgba(255, 99, 132, 1)',
                    'rgba(54, 162, 235, 1)',
                    'rgba(255, 206, 86, 1)'
                ],
                borderWidth: 1
            }]
        }
    });

    new Chart(partsChartCtx, {
        type: 'bar',
        data: {
            labels: data.parts.labels,
            datasets: [{
                label: 'Wykorzystane części',
                data: data.parts.counts,
                backgroundColor: 'rgba(153, 102, 255, 0.2)',
                borderColor: 'rgba(153, 102, 255, 1)',
                borderWidth: 1
            }]
        },
        options: {
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });

    new Chart(costsChartCtx, {
        type: 'bar',
        data: {
            labels: data.costs.labels,
            datasets: [{
                label: 'Koszty napraw',
                data: data.costs.counts,
                backgroundColor: 'rgba(255, 159, 64, 0.2)',
                borderColor: 'rgba(255, 159, 64, 1)',
                borderWidth: 1
            }]
        },
        options: {
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });

    new Chart(diagnosticsChartCtx, {
        type: 'bar',
        data: {
            labels: data.diagnostics.labels,
            datasets: [{
                label: 'Wyniki diagnostyki',
                data: data.diagnostics.counts,
                backgroundColor: 'rgba(54, 162, 235, 0.2)',
                borderColor: 'rgba(54, 162, 235, 1)',
                borderWidth: 1
            }]
        },
        options: {
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });

    new Chart(testsChartCtx, {
        type: 'bar',
        data: {
            labels: data.tests.labels,
            datasets: [{
                label: 'Wyniki testów',
                data: data.tests.counts,
                backgroundColor: 'rgba(255, 206, 86, 0.2)',
                borderColor: 'rgba(255, 206, 86, 1)',
                borderWidth: 1
            }]
        },
        options: {
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });
}
