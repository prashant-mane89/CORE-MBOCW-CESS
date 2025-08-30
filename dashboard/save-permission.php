<?php

session_start();

// Ensure the user is logged in
if (!isset($_SESSION['user_id'])) {
    echo json_encode(['status' => 'error', 'message' => 'Unauthorized']);
    exit;
}

// Include your database connection file
require_once '../config/db.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $pemission = trim($_POST['name'] ?? '');
    // $description = trim($_POST['description'] ?? '');
    $is_active = trim($_POST['is_active'] ?? 1);
    // $permissions = $_POST['permissions'] ?? [];

    // Basic validation
    if (empty($pemission) || empty($is_active)) {
        $_SESSION['error'] = "Please fill in all required fields (Name, Status).";
        header("Location: permissions.php");
        exit;
    }


    try {
        $checkStmt = $conn->prepare("SELECT id FROM permissions WHERE name = ?");
        $checkStmt->bind_param("s", $pemission);
        $checkStmt->execute();
        $result = $checkStmt->get_result();

        if ($result->num_rows > 0) {
            $conn->rollback();
            $_SESSION['error'] = "Permission name already exists.";
            header("Location: permissions.php");
            exit;
        }
        $checkStmt->close();

        $insertRoleStmt = $conn->prepare("INSERT INTO permissions (name,is_active) VALUES (?,?)");
        $insertRoleStmt->bind_param("si", $pemission, $is_active);
        $insertRoleStmt->execute();
        $new_role_id = $conn->insert_id; 
        $insertRoleStmt->close();


        $conn->commit();
        $_SESSION['success'] .= " Permission is added.";
        header("Location: permissions.php");
        exit;

    } catch (mysqli_sql_exception $e) {
        $conn->rollback();
        echo json_encode(['status' => 'error', 'message' => 'Database error: ' . $e->getMessage()]);
    }
} else {
    echo json_encode(['status' => 'error', 'message' => 'Invalid request method.']);
}

$conn->close();
