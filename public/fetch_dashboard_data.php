<?php
require_once '../config/database.php';

$start_date = $_GET['start_date'] ?? '';
$end_date = $_GET['end_date'] ?? '';
$category = $_GET['category'] ?? '';

$conn = new mysqli(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME);

if ($conn->connect_error) {
    die("Błąd połączenia: " . $conn->connect_error);
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

if ($category) {
    $where_clauses[] = 'category = ?';
    $params[] = $category;
}

$where_sql = '';
if (!empty($where_clauses)) {
    $where_sql = 'WHERE ' . implode(' AND ', $where_clauses);
}

$sql_service_requests = "SELECT COUNT(*) as count, DATE(created_at) as date FROM service_requests $where_sql GROUP BY DATE(created_at)";
$stmt_service_requests = $conn->prepare($sql_service_requests);
if ($params) {
    $stmt_service_requests->bind_param(str_repeat('s', count($params)), ...$params);
}
$stmt_service_requests->execute();
$result_service_requests = $stmt_service_requests->get_result();

$service_requests = [];
while ($row = $result_service_requests->fetch_assoc()) {
    $service_requests[] = $row;
}

$sql_categories = "SELECT category, COUNT(*) as count FROM service_requests $where_sql GROUP BY category";
$stmt_categories = $conn->prepare($sql_categories);
if ($params) {
    $stmt_categories->bind_param(str_repeat('s', count($params)), ...$params);
}
$stmt_categories->execute();
$result_categories = $stmt_categories->get_result();

$categories = [];
while ($row = $result_categories->fetch_assoc()) {
    $categories[] = $row;
}

$response = [
    'serviceRequests' => [
        'labels' => array_column($service_requests, 'date'),
        'counts' => array_column($service_requests, 'count'),
    ],
    'categories' => [
        'labels' => array_column($categories, 'category'),
        'counts' => array_column($categories, 'count'),
    ],
    'dates' => [
        'labels' => array_column($service_requests, 'date'),
        'counts' => array_column($service_requests, 'count'),
    ],
];

echo json_encode($response);

$stmt_service_requests->close();
$stmt_categories->close();
$conn->close();
?>
