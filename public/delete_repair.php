<?php
require_once '../config/database.php';

if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // Utwórz połączenie z bazą danych
    $conn = new mysqli(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME);

    // Sprawdź połączenie
    if ($conn->connect_error) {
        die("Błąd połączenia: " . $conn->connect_error);
    }

    // Usuń technika
    $sql_technician = "DELETE FROM repairs WHERE id = ?";
    $stmt_technician = $conn->prepare($sql_technician);
    $stmt_technician->bind_param("i", $id);
    if ($stmt_technician->execute()) {
        echo "Naprawa została pomyślnie usunięta.";
    } else {
        echo "Błąd: " . $stmt_technician->error;
    }
    $stmt_technician->close();

    // Zamknij połączenie
    $conn->close();
} else {
    echo "Nieprawidłowe żądanie.";
}
?>
