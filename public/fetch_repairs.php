<?php
require_once '../config/database.php';

$sql = "SELECT * FROM repairs";
$result = $conn->query($sql);

$repairs = [];
while ($row = $result->fetch_assoc()) {
    $repairs[] = $row;
}

echo json_encode($repairs);

$conn->close();
?>
