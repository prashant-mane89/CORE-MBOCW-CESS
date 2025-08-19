<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.php");
    exit;
}
require_once '../config/db.php';

if (isset($_GET['id'])) {
    $bill_id = intval($_GET['id']);
    $deleted_at = date("Y-m-d H:i:s");

    try {
        $stmt = $conn->prepare("UPDATE bills SET deleted_at = ? WHERE id = ?");
        $stmt->bind_param("si", $deleted_at, $bill_id);

        if ($stmt->execute()) {
            $_SESSION['success'] = "Bill deleted successfully.";
            header("Location: bills.php");
        } else {
            throw new Exception("Failed to deleting record:" . $stmt->error);
        }

        $stmt->close();
    } catch (Exception $e) {
        // Rollback everything on error
        $conn->rollback();
        error_log("Error in delete-bill.php: " . $e->getMessage());
        $_SESSION['error'] = "Operation failed: " . $e->getMessage();
    }
}
$conn->close();
