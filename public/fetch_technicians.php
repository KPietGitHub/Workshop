<?php
require_once '../config/database.php';

$sql = "SELECT id, name, specialty FROM technicians";
$result = $conn->query($sql);

$technicians = [];
while ($row = $result->fetch_assoc()) {
    $technicians[] = $row;
}

echo json_encode($technicians);

$conn->close();
?>
