document.addEventListener('DOMContentLoaded', () => {
    const urlParams = new URLSearchParams(window.location.search);
    const repairId = urlParams.get('id');
    
    fetch(`../fetch_repair.php?id=${repairId}`)
        .then(response => response.json())
        .then(data => {
            if (data) {
                document.getElementById('repair-id').value = data.id;
                document.getElementById('description').value = data.description;
                document.getElementById('cost').value = data.cost;
                document.getElementById('revenue').value = data.revenue;

                fetch('../fetch_service_requests.php')
                    .then(response => response.json())
                    .then(requests => {
                        const serviceRequestSelect = document.getElementById('service-request-id');
                        requests.forEach(request => {
                            const option = document.createElement('option');
                            option.value = request.id;
                            option.text = `${request.client_name} - ${request.vehicle_make} ${request.vehicle_model}`;
                            if (request.id === data.service_request_id) {
                                option.selected = true;
                            }
                            serviceRequestSelect.appendChild(option);
                        });
                    });
            }
        })
        .catch(error => console.error('Błąd:', error));
});
