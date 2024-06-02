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

    // Usuń powiązane rekordy z tabeli assignments
    $sql_delete_assignments = "DELETE FROM assignments WHERE service_request_id = ?";
    $stmt_delete_assignments = $conn->prepare($sql_delete_assignments);
    $stmt_delete_assignments->bind_param("i", $id);
    $stmt_delete_assignments->execute();
    $stmt_delete_assignments->close();

    // Usuń zgłoszenie serwisowe
    $sql_delete_service = "DELETE FROM service_requests WHERE id = ?";
    $stmt_delete_service = $conn->prepare($sql_delete_service);
    $stmt_delete_service->bind_param("i", $id);
    if ($stmt_delete_service->execute()) {
        echo "Zgłoszenie zostało pomyślnie usunięte.";
    } else {
        echo "Błąd: " . $stmt_delete_service->error;
    }
    $stmt_delete_service->close();

    // Zamknij połączenie
    $conn->close();
} else {
    echo "Nieprawidłowe żądanie.";
}
?>
