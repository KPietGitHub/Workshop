<?php
require_once '../config/database.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $service_request_id = $_POST['service_request_id'];
    $description = $_POST['description'];
    $cost = $_POST['cost'];
    $revenue = $_POST['revenue'];

    $conn = new mysqli(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME);

    if ($conn->connect_error) {
        die("Błąd połączenia: " . $conn->connect_error);
    }

    $sql = "INSERT INTO repairs (service_request_id, description, cost, revenue) VALUES (?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("isdd", $service_request_id, $description, $cost, $revenue);

    if ($stmt->execute()) {
        echo "Naprawa została pomyślnie zapisana.";
    } else {
        echo "Błąd: " . $stmt->error;
    }

    $stmt->close();
    $conn->close();
} else {
    echo "Nieprawidłowe żądanie.";
}
?>
