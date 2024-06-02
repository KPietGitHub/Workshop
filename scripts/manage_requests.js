document.addEventListener('DOMContentLoaded', () => {
    fetch('../fetch_service_requests.php')
        .then(response => response.json())
        .then(data => {
            console.log('Otrzymane dane:', data);
            const tableBody = document.getElementById('service-requests');
            data.forEach(request => {
                const status = request.status ? request.status : 'Nie rozpoczęto';
                const technician = request.technician_name ? request.technician_name : 'Brak przypisanego technika';
                const row = document.createElement('tr');

                row.innerHTML = `
                    <td>${request.id}</td>
                    <td>${request.client_name}</td>
                    <td>${request.vehicle_make} ${request.vehicle_model}</td>
                    <td>${request.description}</td>
                    <td>${request.preferred_date}</td>
                    <td>${request.category || 'Brak'}</td>
                    <td>${technician}</td>
                    <td>${status}</td>
                    <td>
                        <a href="edit_service_request.html?id=${request.id}">Edytuj</a> |
                        <a href="../delete_service_request.php?id=${request.id}" onclick="return confirm('Czy na pewno chcesz usunąć?')">Usuń</a>
                    </td>
                `;
                tableBody.appendChild(row);
            });
        })
        .catch(error => console.error('Błąd:', error));
});
