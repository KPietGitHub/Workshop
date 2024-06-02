document.addEventListener('DOMContentLoaded', () => {
    const urlParams = new URLSearchParams(window.location.search);
    const requestId = urlParams.get('id');

    if (requestId) {
        fetch(`../fetch_service_request.php?id=${requestId}`)
            .then(response => response.json())
            .then(data => {
                document.getElementById('request-id').value = data.id;
                document.getElementById('status').value = data.status || 'Nie rozpoczęto';
            })
            .catch(error => console.error('Błąd:', error));
    }
});
