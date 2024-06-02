<?php
require_once '../config/database.php';

// Sprawdź, czy formularz został przesłany
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Pobierz dane z formularza
    $id = $_POST['id'];
    $client_name = $_POST['client_name'];
    $client_phone = $_POST['client_phone'];
    $client_email = $_POST['client_email'];
    $vehicle_make = $_POST['vehicle_make'];
    $vehicle_model = $_POST['vehicle_model'];
    $vehicle_year = $_POST['vehicle_year'];
    $vehicle_license = $_POST['vehicle_license'];
    $problem_description = $_POST['problem_description'];
    $preferred_date = $_POST['preferred_date'];
    $category = $_POST['category'];

    // Utwórz połączenie z bazą danych
    $conn = new mysqli(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME);

    // Sprawdź połączenie
    if ($conn->connect_error) {
        die("Błąd połączenia: " . $conn->connect_error);
    }

    // Aktualizuj dane klienta
    $sql_client = "UPDATE clients SET name = ?, phone = ?, email = ? WHERE id = (SELECT client_id FROM service_requests WHERE id = ?)";
    $stmt_client = $conn->prepare($sql_client);
    $stmt_client->bind_param("sssi", $client_name, $client_phone, $client_email, $id);
    $stmt_client->execute();
    $stmt_client->close();

    // Aktualizuj dane pojazdu
    $sql_vehicle = "UPDATE vehicles SET make = ?, model = ?, year = ?, license = ? WHERE id = (SELECT vehicle_id FROM service_requests WHERE id = ?)";
    $stmt_vehicle = $conn->prepare($sql_vehicle);
    $stmt_vehicle->bind_param("ssssi", $vehicle_make, $vehicle_model, $vehicle_year, $vehicle_license, $id);
    $stmt_vehicle->execute();
    $stmt_vehicle->close();

    // Aktualizuj dane zgłoszenia serwisowego
    $sql_service = "UPDATE service_requests SET description = ?, preferred_date = ?, category = ? WHERE id = ?";
    $stmt_service = $conn->prepare($sql_service);
    $stmt_service->bind_param("sssi", $problem_description, $preferred_date, $category, $id);
    $stmt_service->execute();
    $stmt_service->close();

    // Zamknij połączenie
    $conn->close();

    echo "Zgłoszenie zostało pomyślnie zaktualizowane.";
} else {
    echo "Nieprawidłowe żądanie.";
}
?>
