<?php
// update-invoice-status.php
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

    $permission = trim($_POST['name'] ?? '');
    // $description = trim($_POST['description'] ?? '');
    $is_active = trim($_POST['is_active'] ?? 1);
    // $permissions = $_POST['permissions'] ?? [];

    // Basic validation
    if (empty($permission)) {
        $_SESSION['error'] = "Please fill in all required fields (Name).";
        header("Location: permissions.php");
        exit;
    }

    $permission_id = $_POST['permission_id'];

    try {

        $checkStmt = $conn->prepare("SELECT id FROM permissions WHERE name = ? and id != ? ");
        $checkStmt->bind_param("si", $permission, $permission_id);
        $checkStmt->execute();
        $result = $checkStmt->get_result();

        if ($result->num_rows > 0) {
            $conn->rollback();
            $_SESSION['error'] = "Permission name already exists.";
            header("Location: permissions.php");
            exit;
        }

        $updateRoleStmt = $conn->prepare("UPDATE permissions SET name=?, is_active=? WHERE id = ? ");
        $updateRoleStmt->bind_param("sii", $permission, $is_active, $permission_id);
        $updateRoleStmt->execute();

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
