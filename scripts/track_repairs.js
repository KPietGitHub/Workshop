document.addEventListener('DOMContentLoaded', () => {
    fetch('../fetch_service_requests.php')
        .then(response => response.json())
        .then(data => {
            const serviceRequestSelect = document.getElementById('service-request-id');
            if (!serviceRequestSelect) {
                console.error('Element select o id "service-request-id" nie został znaleziony.');
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

    fetch('../fetch_repairs.php')
        .then(response => response.json())
        .then(data => {
            const tableBody = document.getElementById('repairs');
            if (!tableBody) {
                console.error('Element tbody o id "repairs" nie został znaleziony.');
                return;
            }
            data.forEach(repair => {
                const row = document.createElement('tr');

                row.innerHTML = `
                    <td>${repair.id}</td>
                    <td>${repair.service_request_id}</td>
                    <td>${repair.description}</td>
                    <td>${repair.cost}</td>
                    <td>${repair.revenue}</td>
                    <td>${repair.created_at}</td>
                    <td>
                        <a href="edit_repair.html?id=${repair.id}">Edytuj</a> |
                        <a href="../delete_repair.php?id=${repair.id}" onclick="return confirm('Czy na pewno chcesz usunąć?')">Usuń</a>
                    </td>
                `;
                tableBody.appendChild(row);
            });
        })
        .catch(error => console.error('Błąd:', error));
});
