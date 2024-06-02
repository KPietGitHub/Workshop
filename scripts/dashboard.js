let serviceRequestsChart, categoriesChart, datesChart;

document.addEventListener('DOMContentLoaded', () => {
    fetchData();
});

function fetchData(startDate = '', endDate = '', category = '') {
    fetch(`../fetch_dashboard_data.php?start_date=${startDate}&end_date=${endDate}&category=${category}`)
        .then(response => response.json())
        .then(data => {
            console.log('Otrzymane dane:', data); // Debugowanie odpowiedzi JSON
            updateCharts(data);
        })
        .catch(error => console.error('Błąd:', error));
}

function filterData() {
    const startDate = document.getElementById('start-date').value;
    const endDate = document.getElementById('end-date').value;
    const category = document.getElementById('category').value;
    fetchData(startDate, endDate, category);
}

function resetCanvas(canvasId) {
    const canvas = document.getElementById(canvasId);
    const parent = canvas.parentElement;
    parent.removeChild(canvas);

    const newCanvas = document.createElement('canvas');
    newCanvas.id = canvasId;
    parent.appendChild(newCanvas);
}

function updateCharts(data) {
    // Ensure existing charts are destroyed before creating new ones
    if (serviceRequestsChart) {
        serviceRequestsChart.destroy();
    }
    if (categoriesChart) {
        categoriesChart.destroy();
    }
    if (datesChart) {
        datesChart.destroy();
    }

    // Reset canvases
    resetCanvas('serviceRequestsChart');
    resetCanvas('categoriesChart');
    resetCanvas('datesChart');

    // Get new contexts
    const serviceRequestsChartCtx = document.getElementById('serviceRequestsChart').getContext('2d');
    const categoriesChartCtx = document.getElementById('categoriesChart').getContext('2d');
    const datesChartCtx = document.getElementById('datesChart').getContext('2d');

    // Create new charts
    serviceRequestsChart = new Chart(serviceRequestsChartCtx, {
        type: 'bar',
        data: {
            labels: data.serviceRequests.labels,
            datasets: [{
                label: 'Liczba zgłoszeń serwisowych',
                data: data.serviceRequests.counts,
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

    categoriesChart = new Chart(categoriesChartCtx, {
        type: 'pie',
        data: {
            labels: data.categories.labels,
            datasets: [{
                label: 'Kategorie napraw',
                data: data.categories.counts,
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

    datesChart = new Chart(datesChartCtx, {
        type: 'line',
        data: {
            labels: data.dates.labels,
            datasets: [{
                label: 'Terminy zgłoszeń',
                data: data.dates.counts,
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
}
