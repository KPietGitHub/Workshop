document.addEventListener('DOMContentLoaded', () => {
    fetch('../fetch_technicians_with_schedule.php')
        .then(response => response.json())
        .then(data => {
            const tableBody = document.getElementById('technicians');
            data.forEach(technician => {
                const row = document.createElement('tr');

                row.innerHTML = `
                    <td>${technician.id}</td>
                    <td>${technician.name}</td>
                    <td>${technician.specialty}</td>
                    <td>${technician.schedule}</td>
                    <td>
                        <a href="edit_technician.html?id=${technician.id}">Edytuj</a> |
                        <a href="../delete_technician.php?id=${technician.id}" onclick="return confirm('Czy na pewno chcesz usunąć?')">Usuń</a>
                    </td>
                `;
                tableBody.appendChild(row);
            });
        })
        .catch(error => console.error('Błąd:', error));


});
