<?php
require_once '../config/database.php';

// Sprawdź, czy formularz został przesłany
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Pobierz dane z formularza
    $service_request_id = $_POST['service_request_id'];
    $technician_id = $_POST['technician_id'];

    // Utwórz połączenie z bazą danych
    $conn = new mysqli(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME);

    // Sprawdź połączenie
    if ($conn->connect_error) {
        die("Błąd połączenia: " . $conn->connect_error);
    }

    // Sprawdź, czy istnieje już przypisanie technika do danego zgłoszenia serwisowego
    $sql_check = "SELECT id FROM assignments WHERE service_request_id = ?";
    $stmt_check = $conn->prepare($sql_check);
    $stmt_check->bind_param("i", $service_request_id);
    $stmt_check->execute();
    $stmt_check->store_result();

    if ($stmt_check->num_rows > 0) {
        // Jeśli istnieje, zaktualizuj przypisanie technika
        $sql_update = "UPDATE assignments SET technician_id = ? WHERE service_request_id = ?";
        $stmt_update = $conn->prepare($sql_update);
        $stmt_update->bind_param("ii", $technician_id, $service_request_id);
        if ($stmt_update->execute()) {
            echo "Technik został pomyślnie zaktualizowany dla zgłoszenia serwisowego.";
        } else {
            echo "Błąd: " . $stmt_update->error;
        }
        $stmt_update->close();
    } else {
        // Jeśli nie istnieje, wstaw nowe przypisanie technika
        $sql_insert = "INSERT INTO assignments (service_request_id, technician_id) VALUES (?, ?)";
        $stmt_insert = $conn->prepare($sql_insert);
        $stmt_insert->bind_param("ii", $service_request_id, $technician_id);
        if ($stmt_insert->execute()) {
            echo "Technik został pomyślnie przypisany do zgłoszenia serwisowego.";
        } else {
            echo "Błąd: " . $stmt_insert->error;
        }
        $stmt_insert->close();
    }
    $stmt_check->close();

    // Zamknij połączenie
    $conn->close();
} else {
    echo "Nieprawidłowe żądanie.";
}
?>
