document.addEventListener('DOMContentLoaded', () => {
    fetchData();
});

function fetchData(startDate = '', endDate = '') {
    fetch(`../fetch_technicians_dashboard_data.php?start_date=${startDate}&end_date=${endDate}`)
        .then(response => response.json())
        .then(data => {
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
    const availabilityChartCtx = document.getElementById('availabilityChart').getContext('2d');
    const specializationsChartCtx = document.getElementById('specializationsChart').getContext('2d');
    const performanceChartCtx = document.getElementById('performanceChart').getContext('2d');
    const assignmentsChartCtx = document.getElementById('assignmentsChart').getContext('2d');

    new Chart(availabilityChartCtx, {
        type: 'bar',
        data: {
            labels: data.availability.labels,
            datasets: [{
                label: 'Dostępność techników',
                data: data.availability.counts,
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

    new Chart(specializationsChartCtx, {
        type: 'pie',
        data: {
            labels: data.specializations.labels,
            datasets: [{
                label: 'Specjalizacje techników',
                data: data.specializations.counts,
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

    new Chart(performanceChartCtx, {
        type: 'line',
        data: {
            labels: data.performance.labels,
            datasets: [{
                label: 'Wydajność techników',
                data: data.performance.counts,
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

    new Chart(assignmentsChartCtx, {
        type: 'bar',
        data: {
            labels: data.assignments.labels,
            datasets: [{
                label: 'Przypisane zadania',
                data: data.assignments.counts,
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
}
