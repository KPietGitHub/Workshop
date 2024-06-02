<?php
require_once '../config/database.php';

$start_date = $_GET['start_date'] ?? '';
$end_date = $_GET['end_date'] ?? '';

$conn = new mysqli(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME);

if ($conn->connect_error) {
    die("Błąd połączenia: " . $conn->connect_error);
}

$where_clauses = [];
$params = [];

if ($start_date) {
    $where_clauses[] = 'assigned_at >= ?';
    $params[] = $start_date;
}

if ($end_date) {
    $where_clauses[] = 'assigned_at <= ?';
    $params[] = $end_date;
}

$where_sql = '';
if (!empty($where_clauses)) {
    $where_sql = 'WHERE ' . implode(' AND ', $where_clauses);
}

// Pobieranie dostępności techników
$sql_availability = "SELECT t.name, COUNT(a.id) as assignments_count FROM technicians t LEFT JOIN assignments a ON t.id = a.technician_id $where_sql GROUP BY t.id";
$stmt_availability = $conn->prepare($sql_availability);
if ($params) {
    $stmt_availability->bind_param(str_repeat('s', count($params)), ...$params);
}
$stmt_availability->execute();
$result_availability = $stmt_availability->get_result();

$availability = [];
while ($row = $result_availability->fetch_assoc()) {
    $availability[] = $row;
}

// Pobieranie specjalizacji techników
$sql_specializations = "SELECT specialty, COUNT(*) as count FROM technicians GROUP BY specialty";
$result_specializations = $conn->query($sql_specializations);

$specializations = [];
while ($row = $result_specializations->fetch_assoc()) {
    $specializations[] = $row;
}

// Pobieranie wydajności techników
$sql_performance = "SELECT t.name, COUNT(a.id) as assignments_count FROM technicians t LEFT JOIN assignments a ON t.id = a.technician_id $where_sql GROUP BY t.id ORDER BY assignments_count DESC";
$stmt_performance = $conn->prepare($sql_performance);
if ($params) {
    $stmt_performance->bind_param(str_repeat('s', count($params)), ...$params);
}
$stmt_performance->execute();
$result_performance = $stmt_performance->get_result();

$performance = [];
while ($row = $result_performance->fetch_assoc()) {
    $performance[] = $row;
}

// Pobieranie historii przypisań zadań
$sql_assignments = "SELECT t.name, COUNT(a.id) as assignments_count, DATE(a.assigned_at) as date FROM technicians t LEFT JOIN assignments a ON t.id = a.technician_id $where_sql GROUP BY t.id, DATE(a.assigned_at)";
$stmt_assignments = $conn->prepare($sql_assignments);
if ($params) {
    $stmt_assignments->bind_param(str_repeat('s', count($params)), ...$params);
}
$stmt_assignments->execute();
$result_assignments = $stmt_assignments->get_result();

$assignments = [];
while ($row = $result_assignments->fetch_assoc()) {
    $assignments[] = $row;
}

$response = [
    'availability' => [
        'labels' => array_column($availability, 'name'),
        'counts' => array_column($availability, 'assignments_count'),
    ],
    'specializations' => [
        'labels' => array_column($specializations, 'specialty'),
        'counts' => array_column($specializations, 'count'),
    ],
    'performance' => [
        'labels' => array_column($performance, 'name'),
        'counts' => array_column($performance, 'assignments_count'),
    ],
    'assignments' => [
        'labels' => array_column($assignments, 'name'),
        'counts' => array_column($assignments, 'assignments_count'),
        'dates' => array_column($assignments, 'date'),
    ],
];

echo json_encode($response);

$stmt_availability->close();
$stmt_performance->close();
$stmt_assignments->close();
$conn->close();
?>
