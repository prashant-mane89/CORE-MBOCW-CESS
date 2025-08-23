<?php
header('Content-Type: application/json');
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET");

// Database connection
require_once 'config/db.php';

// Read district_id from query string
$district_id = isset($_GET['district_id']) ? intval($_GET['district_id']) : 0;

if ($district_id <= 0) {
    echo json_encode([]);
    exit;
}

// Fetch talukas
$sql = "SELECT id, name FROM talukas WHERE district_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $district_id);
$stmt->execute();
$result = $stmt->get_result();

$talukas = [];
while ($row = $result->fetch_assoc()) {
    $talukas[] = $row;
}

echo json_encode($talukas);

$stmt->close();
$conn->close();
