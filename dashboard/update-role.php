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

    $role_name = trim($_POST['name'] ?? '');
    $description = trim($_POST['description'] ?? '');
    $is_active = trim($_POST['is_active'] ?? 1);
    $permissions = $_POST['permissions'] ?? [];

    // Basic validation
    if (empty($role_name) || count($permissions) == 0 || empty($description)) {
        $_SESSION['error'] = "Please fill in all required fields (Name, Permission).";
        header("Location: roles.php");
        exit;
    }

    $role_id = $_POST['role_id'];
    $conn->begin_transaction();

    try {

        $checkStmt = $conn->prepare("SELECT id FROM roles WHERE name = ? and id != ? ");
        $checkStmt->bind_param("si", $role_name, $role_id);
        $checkStmt->execute();
        $result = $checkStmt->get_result();

        if ($result->num_rows > 0) {
            $conn->rollback();
            $_SESSION['error'] = "Role name already exists.";
            header("Location: roles.php");
            exit;
        }

        $updateRoleStmt = $conn->prepare("UPDATE roles SET name=?, description=?, is_active=? WHERE id = ? ");
        $updateRoleStmt->bind_param("ssii", $role_name, $description, $is_active, $role_id);
        $updateRoleStmt->execute();

        $deleteStmt = $conn->prepare("DELETE FROM role_permissions WHERE role_id = ?");
        $deleteStmt->bind_param("i", $role_id);
        $deleteStmt->execute();
        $deleteStmt->close();

        $insertStmt = $conn->prepare("INSERT INTO role_permissions (role_id, permission_id) VALUES (?, ?)");

        foreach ($_POST['permissions'] as $permission_id) {
            $insertStmt->bind_param("ii", $role_id, $permission_id);
            $insertStmt->execute();
        }

        $conn->commit();
        $insertStmt->close();

        header("Location: roles.php");
        exit;

    } catch (mysqli_sql_exception $e) {
        $conn->rollback();
        echo json_encode(['status' => 'error', 'message' => 'Database error: ' . $e->getMessage()]);
    }
} else {
    echo json_encode(['status' => 'error', 'message' => 'Invalid request method.']);
}

$conn->close();
