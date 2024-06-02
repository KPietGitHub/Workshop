<?php
require_once '../config/database.php';

$sql = "
SELECT sr.id, c.name AS client_name, v.make AS vehicle_make, v.model AS vehicle_model, sr.description, sr.preferred_date, sr.status, sr.category, 
       t.name AS technician_name
FROM service_requests sr
JOIN clients c ON sr.client_id = c.id
JOIN vehicles v ON sr.vehicle_id = v.id
LEFT JOIN assignments a ON sr.id = a.service_request_id
LEFT JOIN technicians t ON a.technician_id = t.id
";
$result = $conn->query($sql);

if (!$result) {
    echo "Błąd zapytania: " . $conn->error;
    exit;
}

$requests = [];
while ($row = $result->fetch_assoc()) {
    $requests[] = $row;
}

echo json_encode($requests);

$conn->close();
?>
