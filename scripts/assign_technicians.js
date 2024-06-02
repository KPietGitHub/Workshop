document.addEventListener('DOMContentLoaded', () => {
    fetch('../fetch_service_requests.php')
        .then(response => response.json())
        .then(data => {
            const serviceRequestSelect = document.getElementById('service-request');
            if (!serviceRequestSelect) {
                console.error('Element select o id "service-request" nie został znaleziony.');
                return;
            }
            data.forEach(request => {
                const option = document.createElement('option');
                option.value = request.id;
                option.text = `${request.client_name} - ${request.vehicle_make} ${request.vehicle_model}`;
                serviceRequestSelect.appendChild(option);
            });
        })
        .catch(error => console.error('Błąd:', error));

    fetch('../fetch_technicians.php')
        .then(response => response.json())
        .then(data => {
            const technicianSelect = document.getElementById('technician');
            if (!technicianSelect) {
                console.error('Element select o id "technician" nie został znaleziony.');
                return;
            }
            data.forEach(technician => {
                const option = document.createElement('option');
                option.value = technician.id;
                option.text = `${technician.name} - ${technician.specialty}`;
                technicianSelect.appendChild(option);
            });
        })
        .catch(error => console.error('Błąd:', error));
});
