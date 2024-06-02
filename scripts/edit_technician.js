document.addEventListener('DOMContentLoaded', () => {
    const urlParams = new URLSearchParams(window.location.search);
    const technicianId = urlParams.get('id');

    if (technicianId) {
        fetch(`../fetch_technician.php?id=${technicianId}`)
            .then(response => response.json())
            .then(data => {
                document.getElementById('technician-id').value = data.id;
                document.getElementById('technician-name').value = data.name;
                document.getElementById('technician-specialty').value = data.specialty;
                document.getElementById('technician-schedule').value = data.schedule;
            })
            .catch(error => console.error('Błąd:', error));
    }
});
