<?php
require_once '../config/database.php';

$sql = "SELECT t.id, t.name, t.specialty, t.schedule
        FROM technicians t
        LEFT JOIN assignments a ON t.id = a.technician_id
        GROUP BY t.id";
$result = $conn->query($sql);

$technicians = [];
while ($row = $result->fetch_assoc()) {
    $technicians[] = $row;
}

echo json_encode($technicians);

$conn->close();
?>
