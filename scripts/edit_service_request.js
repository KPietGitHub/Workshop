document.addEventListener('DOMContentLoaded', () => {
    const urlParams = new URLSearchParams(window.location.search);
    const requestId = urlParams.get('id');

    if (requestId) {
        fetch(`../fetch_service_request.php?id=${requestId}`)
            .then(response => response.json())
            .then(data => {
                document.getElementById('request-id').value = data.id;
                document.getElementById('client-name').value = data.client_name;
                document.getElementById('client-phone').value = data.client_phone;
                document.getElementById('client-email').value = data.client_email;
                document.getElementById('vehicle-make').value = data.vehicle_make;
                document.getElementById('vehicle-model').value = data.vehicle_model;
                document.getElementById('vehicle-year').value = data.vehicle_year;
                document.getElementById('vehicle-license').value = data.vehicle_license;
                document.getElementById('problem-description').value = data.description;
                document.getElementById('preferred-date').value = data.preferred_date;
                document.getElementById('category').value = data.category;
            })
            .catch(error => console.error('Błąd:', error));
    }
});
