<?php
require_once '../vendor/autoload.php';
require_once '../config/database.php';

use Dompdf\Dompdf;
use Dompdf\Options;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $start_date = $_POST['start_date'];
    $end_date = $_POST['end_date'];
    $report_type = $_POST['report_type'];
    $report_format = $_POST['report_format'];

    $conn = new mysqli(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME);

    if ($conn->connect_error) {
        die("Błąd połączenia: " . $conn->connect_error);
    }

    if ($report_type == 'service_requests') {
        $sql = "SELECT * FROM service_requests WHERE created_at BETWEEN ? AND ?";
    } elseif ($report_type == 'repairs') {
        $sql = "SELECT * FROM repairs WHERE created_at BETWEEN ? AND ?";
    } elseif ($report_type == 'technicians') {
        $sql = "SELECT t.name AS technician_name, COUNT(a.id) AS assignments_count
                FROM technicians t
                JOIN assignments a ON t.id = a.technician_id
                WHERE a.assigned_at BETWEEN ? AND ?
                GROUP BY t.id";
    } elseif ($report_type == 'financial') {
        $sql = "SELECT SUM(r.cost) AS total_cost, SUM(r.revenue) AS total_revenue
                FROM repairs r
                WHERE r.completed_at BETWEEN ? AND ?";
    }

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ss", $start_date, $end_date);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($report_format == 'pdf') {
        $html = '<h1>Raport</h1>';
        if ($report_type == 'service_requests') {
            $html .= '<h2>Zgłoszenia serwisowe</h2><table><tr><th>ID</th><th>Klient</th><th>Pojazd</th><th>Opis</th><th>Status</th><th>Data</th></tr>';
            while ($row = $result->fetch_assoc()) {
                $html .= "<tr><td>{$row['id']}</td><td>{$row['client_id']}</td><td>{$row['vehicle_id']}</td><td>{$row['description']}</td><td>{$row['status']}</td><td>{$row['created_at']}</td></tr>";
            }
            $html .= '</table>';
        } elseif ($report_type == 'repairs') {
            $html .= '<h2>Naprawy</h2><table><tr><th>ID</th><th>Opis</th><th>Koszt</th><th>Przychód</th><th>Data</th></tr>';
            while ($row = $result->fetch_assoc()) {
                $html .= "<tr><td>{$row['id']}</td><td>{$row['description']}</td><td>{$row['cost']}</td><td>{$row['revenue']}</td><td>{$row['created_at']}</td></tr>";
            }
            $html .= '</table>';
        } elseif ($report_type == 'technicians') {
            $html .= '<h2>Wydajność techników</h2><table><tr><th>Technik</th><th>Liczba zadań</th></tr>';
            while ($row = $result->fetch_assoc()) {
                $html .= "<tr><td>{$row['technician_name']}</td><td>{$row['assignments_count']}</td></tr>";
            }
            $html .= '</table>';
        } elseif ($report_type == 'financial') {
            $row = $result->fetch_assoc();
            $html .= '<h2>Raport finansowy</h2><table><tr><th>Łączny koszt</th><th>Łączny przychód</th></tr>';
            $html .= "<tr><td>{$row['total_cost']}</td><td>{$row['total_revenue']}</td></tr>";
            $html .= '</table>';
        }

        $options = new Options();
        $options->set('isHtml5ParserEnabled', true);
        $options->set('isFontSubsettingEnabled', true);
        $options->set('defaultFont', 'DejaVu Sans');
        $dompdf = new Dompdf($options);

        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'landscape');
        $dompdf->render();
        $dompdf->stream("raport.pdf", array("Attachment" => 0));
    } elseif ($report_format == 'csv') {
        $csv = fopen('php://output', 'w');
        header('Content-Type: text/csv');
        header('Content-Disposition: attachment; filename="raport.csv"');

        if ($report_type == 'service_requests') {
            fputcsv($csv, ['ID', 'Klient', 'Pojazd', 'Opis', 'Status', 'Data']);
            while ($row = $result->fetch_assoc()) {
                fputcsv($csv, [$row['id'], $row['client_id'], $row['vehicle_id'], $row['description'], $row['status'], $row['created_at']]);
            }
        } elseif ($report_type == 'repairs') {
            fputcsv($csv, ['ID', 'Opis', 'Koszt', 'Przychód', 'Data']);
            while ($row = $result->fetch_assoc()) {
                fputcsv($csv, [$row['id'], $row['description'], $row['cost'], $row['revenue'], $row['created_at']]);
            }
        } elseif ($report_type == 'technicians') {
            fputcsv($csv, ['Technik', 'Liczba zadań']);
            while ($row = $result->fetch_assoc()) {
                fputcsv($csv, [$row['technician_name'], $row['assignments_count']]);
            }
        } elseif ($report_type == 'financial') {
            fputcsv($csv, ['Łączny koszt', 'Łączny przychód']);
            $row = $result->fetch_assoc();
            fputcsv($csv, [$row['total_cost'], $row['total_revenue']]);
        }

        fclose($csv);
    }

    $stmt->close();
    $conn->close();
} else {
    echo "Nieprawidłowe żądanie.";
}
?>
