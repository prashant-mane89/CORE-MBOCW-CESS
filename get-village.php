<?php
header('Content-Type: application/json');
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET");

// Database connection
require_once 'config/db.php';

// Read taluka_id from query string
$taluka_id = isset($_GET['taluka_id']) ? intval($_GET['taluka_id']) : 0;

if ($taluka_id <= 0) {
    echo json_encode([]);
    exit;
}

// Fetch villages
$sql = "SELECT id, name FROM villages WHERE taluka_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $taluka_id);
$stmt->execute();
$result = $stmt->get_result();

$villages = [];
while ($row = $result->fetch_assoc()) {
    $villages[] = $row;
}

echo json_encode($villages);

$stmt->close();
$conn->close();
