<?php
require_once '../config/database.php';

// Sprawdź, czy formularz został przesłany
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Pobierz dane z formularza
    $id = $_POST['id'];
    $name = $_POST['name'];
    $specialty = $_POST['specialty'];
    $schedule = $_POST['schedule'];

    // Utwórz połączenie z bazą danych
    $conn = new mysqli(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME);

    // Sprawdź połączenie
    if ($conn->connect_error) {
        die("Błąd połączenia: " . $conn->connect_error);
    }

    // Aktualizuj dane technika
    $sql_technician = "UPDATE technicians SET name = ?, specialty = ?, schedule = ? WHERE id = ?";
    $stmt_technician = $conn->prepare($sql_technician);
    $stmt_technician->bind_param("sssi", $name, $specialty, $schedule, $id);
    if ($stmt_technician->execute()) {
        echo "Dane technika zostały pomyślnie zaktualizowane.";
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
