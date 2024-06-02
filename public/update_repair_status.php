<?php
require_once '../config/database.php';

// Sprawdź, czy formularz został przesłany
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Pobierz dane z formularza
    $id = $_POST['id'];
    $status = $_POST['status'];

    // Utwórz połączenie z bazą danych
    $conn = new mysqli(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME);

    // Sprawdź połączenie
    if ($conn->connect_error) {
        die("Błąd połączenia: " . $conn->connect_error);
    }

    // Aktualizuj status naprawy
    $sql_status = "UPDATE service_requests SET status = ? WHERE id = ?";
    $stmt_status = $conn->prepare($sql_status);
    $stmt_status->bind_param("si", $status, $id);
    if ($stmt_status->execute()) {
        echo "Status naprawy został pomyślnie zaktualizowany.";
    } else {
        echo "Błąd: " . $stmt_status->error;
    }
    $stmt_status->close();

    // Zamknij połączenie
    $conn->close();
} else {
    echo "Nieprawidłowe żądanie.";
}
?>
