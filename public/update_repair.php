<?php
require_once '../config/database.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $repair_id = $_POST['repair_id'];
    $service_request_id = $_POST['service_request_id'];
    $description = $_POST['description'];
    $cost = $_POST['cost'];
    $revenue = $_POST['revenue'];

    $conn = new mysqli(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME);

    if ($conn->connect_error) {
        die("Błąd połączenia: " . $conn->connect_error);
    }

    $sql = "UPDATE repairs SET service_request_id = ?, description = ?, cost = ?, revenue = ? WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("isddi", $service_request_id, $description, $cost, $revenue, $repair_id);

    if ($stmt->execute()) {
        echo "Naprawa została zaktualizowana.";
    } else {
        echo "Błąd: " . $stmt->error;
    }

    $stmt->close();
    $conn->close();
} else {
    echo "Nieprawidłowe żądanie.";
}
?>
