<?php
// fetch_data.php

session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.php");
    exit;
}
require_once '../config/db.php';

$response = [];

if (isset($_POST['type']) && isset($_POST['id'])) {
    $type = $_POST['type'];
    $id = intval($_POST['id']);

    if ($type === 'talukas') {
        $sql = "SELECT id, name FROM talukas WHERE district_id = ? ORDER BY name";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $response[] = $row;
            }
        }
        $stmt->close();

    } elseif ($type === 'villages') {
        $sql = "SELECT id, name FROM villages WHERE taluka_id = ? ORDER BY name";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $response[] = $row;
            }
        }
        $stmt->close();
    }
}

$conn->close();

header('Content-Type: application/json');
echo json_encode($response);
?>