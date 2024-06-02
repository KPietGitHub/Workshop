<?php
require_once '../config/database.php';

// Sprawdź, czy formularz został przesłany
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Pobierz dane z formularza

    $name = $_POST['technican-name'];
    $speciality = $_POST['technican-specialty'];
    $schedule = $_POST['technican-schedule'];

    // Utwórz połączenie z bazą danych
    $conn = new mysqli(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME);

    // Sprawdź połączenie
    if ($conn->connect_error) {
        die("Błąd połączenia: " . $conn->connect_error);
    }

   $sql_service = "INSERT INTO technicians (id, name, specialty, schedule) VALUES (?,?,?,?)";
   $stmt_service = $conn->prepare($sql_service);
   $stmt_service->bind_param("isss", $technican_id, $name, $speciality, $schedule);
   $stmt_service->execute();
    $stmt_service->close();


    // Zamknij połączenie
    $conn->close();

    echo "Zgłoszenie zostało pomyślnie zapisane.";
} else {
    echo "Nieprawidłowe żądanie.";
}
?>
