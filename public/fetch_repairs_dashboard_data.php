<?php
require_once '../config/database.php';

$start_date = $_GET['start_date'] ?? '';
$end_date = $_GET['end_date'] ?? '';

$conn = new mysqli(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME);

if ($conn->connect_error) {
    die(json_encode(["error" => "Błąd połączenia: " . $conn->connect_error]));
}

$where_clauses = [];
$params = [];

if ($start_date) {
    $where_clauses[] = 'created_at >= ?';
    $params[] = $start_date;
}

if ($end_date) {
    $where_clauses[] = 'created_at <= ?';
    $params[] = $end_date;
}

$where_sql = '';
if (!empty($where_clauses)) {
    $where_sql = 'WHERE ' . implode(' AND ', $where_clauses);
}

// Pobieranie postępów napraw
$sql_progress = "SELECT COUNT(*) as count, DATE(created_at) as date FROM repairs $where_sql GROUP BY DATE(created_at)";
$stmt_progress = $conn->prepare($sql_progress);
if ($params) {
    $stmt_progress->bind_param(str_repeat('s', count($params)), ...$params);
}
$stmt_progress->execute();
$result_progress = $stmt_progress->get_result();

$progress = [];
while ($row = $result_progress->fetch_assoc()) {
    $progress[] = $row;
}

// Pobieranie statusów napraw
$sql_status = "SELECT status, COUNT(*) as count FROM service_requests $where_sql GROUP BY status";
$stmt_status = $conn->prepare($sql_status);
if ($params) {
    $stmt_status->bind_param(str_repeat('s', count($params)), ...$params);
}
$stmt_status->execute();
$result_status = $stmt_status->get_result();

$status = [];
while ($row = $result_status->fetch_assoc()) {
    $status[] = $row;
}

// Pobieranie wykorzystanych części
$sql_parts = "SELECT description, COUNT(*) as count FROM repairs $where_sql GROUP BY description";
$stmt_parts = $conn->prepare($sql_parts);
if ($params) {
    $stmt_parts->bind_param(str_repeat('s', count($params)), ...$params);
}
$stmt_parts->execute();
$result_parts = $stmt_parts->get_result();

$parts = [];
while ($row = $result_parts->fetch_assoc()) {
    $parts[] = $row;
}

// Pobieranie kosztów napraw
$sql_costs = "SELECT description, SUM(cost) as total_cost FROM repairs $where_sql GROUP BY description";
$stmt_costs = $conn->prepare($sql_costs);
if ($params) {
    $stmt_costs->bind_param(str_repeat('s', count($params)), ...$params);
}
$stmt_costs->execute();
$result_costs = $stmt_costs->get_result();

$costs = [];
while ($row = $result_costs->fetch_assoc()) {
    $costs[] = $row;
}

// Pobieranie wyników diagnostyki
$sql_diagnostics = "SELECT diagnostic_results as description, COUNT(*) as count FROM service_requests WHERE diagnostic_results IS NOT NULL AND diagnostic_results != '' $where_sql GROUP BY diagnostic_results";
$stmt_diagnostics = $conn->prepare($sql_diagnostics);
if ($params) {
    $stmt_diagnostics->bind_param(str_repeat('s', count($params)), ...$params);
}
$stmt_diagnostics->execute();
$result_diagnostics = $stmt_diagnostics->get_result();

$diagnostics = [];
while ($row = $result_diagnostics->fetch_assoc()) {
    $diagnostics[] = $row;
}

// Pobieranie wyników testów
$sql_tests = "SELECT test_results as description, COUNT(*) as count FROM service_requests WHERE test_results IS NOT NULL AND test_results != '' $where_sql GROUP BY test_results";
$stmt_tests = $conn->prepare($sql_tests);
if ($params) {
    $stmt_tests->bind_param(str_repeat('s', count($params)), ...$params);
}
$stmt_tests->execute();
$result_tests = $stmt_tests->get_result();

$tests = [];
while ($row = $result_tests->fetch_assoc()) {
    $tests[] = $row;
}

$response = [
    'progress' => [
        'labels' => array_column($progress, 'date'),
        'counts' => array_column($progress, 'count'),
    ],
    'status' => [
        'labels' => array_column($status, 'status'),
        'counts' => array_column($status, 'count'),
    ],
    'parts' => [
        'labels' => array_column($parts, 'description'),
        'counts' => array_column($parts, 'count'),
    ],
    'costs' => [
        'labels' => array_column($costs, 'description'),
        'counts' => array_column($costs, 'total_cost'),
    ],
    'diagnostics' => [
        'labels' => array_column($diagnostics, 'description'),
        'counts' => array_column($diagnostics, 'count'),
    ],
    'tests' => [
        'labels' => array_column($tests, 'description'),
        'counts' => array_column($tests, 'count'),
    ],
];

// Debugowanie JSON przed zwróceniem
header('Content-Type: application/json');
echo json_encode($response, JSON_PRETTY_PRINT);

$stmt_progress->close();
$stmt_status->close();
$stmt_parts->close();
$stmt_costs->close();
$stmt_diagnostics->close();
$stmt_tests->close();
$conn->close();
?>
