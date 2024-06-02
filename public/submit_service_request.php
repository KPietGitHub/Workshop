<?php
require_once '../config/database.php';

/* function send_email($to, $subject, $message) {
    $headers = 'From: noreply@workshop.com' . "\r\n" .
               'Reply-To: noreply@workshop.com' . "\r\n" .
               'X-Mailer: PHP/' . phpversion();
    mail($to, $subject, $message, $headers);
} */

// Sprawdź, czy formularz został przesłany
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Pobierz dane z formularza
    $client_name = $_POST['client_name'];
    $client_phone = $_POST['client_phone'];
    $client_email = $_POST['client_email'];
    $vehicle_make = $_POST['vehicle_make'];
    $vehicle_model = $_POST['vehicle_model'];
    $vehicle_year = $_POST['vehicle_year'];
    $vehicle_license = $_POST['vehicle_license'];
    $problem_description = $_POST['problem_description'];
    $preferred_date = $_POST['preferred_date'];

    // Utwórz połączenie z bazą danych
    $conn = new mysqli(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME);

    // Sprawdź połączenie
    if ($conn->connect_error) {
        die("Błąd połączenia: " . $conn->connect_error);
    }

    // Wstaw dane klienta
    $sql_client = "INSERT INTO clients (name, phone, email) VALUES (?, ?, ?)";
    $stmt_client = $conn->prepare($sql_client);
    $stmt_client->bind_param("sss", $client_name, $client_phone, $client_email);
    $stmt_client->execute();
    $client_id = $stmt_client->insert_id;
    $stmt_client->close();

    // Wstaw dane pojazdu
    $sql_vehicle = "INSERT INTO vehicles (client_id, make, model, year, license) VALUES (?, ?, ?, ?, ?)";
    $stmt_vehicle = $conn->prepare($sql_vehicle);
    $stmt_vehicle->bind_param("issss", $client_id, $vehicle_make, $vehicle_model, $vehicle_year, $vehicle_license);
    $stmt_vehicle->execute();
    $vehicle_id = $stmt_vehicle->insert_id;
    $stmt_vehicle->close();

    // Wstaw dane zgłoszenia serwisowego
    $sql_service = "INSERT INTO service_requests (client_id, vehicle_id, description, preferred_date) VALUES (?, ?, ?, ?)";
    $stmt_service = $conn->prepare($sql_service);
    $stmt_service->bind_param("iiss", $client_id, $vehicle_id, $problem_description, $preferred_date);
    $stmt_service->execute();
    $stmt_service->close();

    // Wysyłanie powiadomienia email do klienta
    /* $subject = "Potwierdzenie zgłoszenia serwisowego";
    $message = "Dziękujemy za zgłoszenie serwisowe. Oto szczegóły:\n
                Imię i nazwisko: $client_name\n
                Telefon: $client_phone\n
                Email: $client_email\n
                Pojazd: $vehicle_make $vehicle_model, rok $vehicle_year, numer rejestracyjny $vehicle_license\n
                Opis problemu: $problem_description\n
                Preferowany termin naprawy: $preferred_date\n";
    send_email($client_email, $subject, $message); */

    // Zamknij połączenie
    $conn->close();

    echo "Zgłoszenie zostało pomyślnie zapisane.";
} else {
    echo "Nieprawidłowe żądanie.";
}
?>
