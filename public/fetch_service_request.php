<?php
require_once '../config/database.php';

$id = $_GET['id'];
$sql = "SELECT sr.id, c.name AS client_name, c.phone AS client_phone, c.email AS client_email, 
               v.make AS vehicle_make, v.model AS vehicle_model, v.year AS vehicle_year, v.license AS vehicle_license, 
               sr.description, sr.preferred_date, sr.category
        FROM service_requests sr
        JOIN clients c ON sr.client_id = c.id
        JOIN vehicles v ON sr.vehicle_id = v.id
        WHERE sr.id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$data = $result->fetch_assoc();

echo json_encode($data);

$stmt->close();
$conn->close();
?>
