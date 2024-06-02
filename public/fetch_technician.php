<?php
require_once '../config/database.php';

$id = $_GET['id'];
$sql = "SELECT id, name, specialty, schedule FROM technicians WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$data = $result->fetch_assoc();

echo json_encode($data);

$stmt->close();
$conn->close();
?>
