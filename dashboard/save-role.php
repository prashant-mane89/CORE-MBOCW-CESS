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


    try {
        $checkStmt = $conn->prepare("SELECT id FROM roles WHERE name = ?");
        $checkStmt->bind_param("s", $role_name);
        $checkStmt->execute();
        $result = $checkStmt->get_result();

        if ($result->num_rows > 0) {
            $conn->rollback();
            $_SESSION['error'] = "Role name already exists.";
            header("Location: roles.php");
            exit;
        }
        $checkStmt->close();

        $insertRoleStmt = $conn->prepare("INSERT INTO roles (name,description,is_active) VALUES (?,?,?)");
        $insertRoleStmt->bind_param("ssi", $role_name, $description, $is_active);
        $insertRoleStmt->execute();
        $new_role_id = $conn->insert_id; 
        $insertRoleStmt->close();

        if (!empty($permissions)) {
            $insertPermissionStmt = $conn->prepare("INSERT INTO role_permissions (role_id, permission_id) VALUES (?, ?)");
            foreach ($permissions as $permission_id) {
                $insertPermissionStmt->bind_param("ii", $new_role_id, $permission_id);
                $insertPermissionStmt->execute();
            }
            $insertPermissionStmt->close();
        }

        $conn->commit();
        $_SESSION['success'] .= " Role is added.";
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
