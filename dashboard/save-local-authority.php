<?php
session_start();
require_once '../config/db.php'; // DB connection

// Security check: ensure user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.php");
    exit;
}

// Save old values in case of error
$_SESSION['old_values'] = $_POST;

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    // Collect & sanitize inputs
    $authority_name  = trim($_POST['authority_name'] ?? '');
    $authority_type  = intval($_POST['authority_type_id'] ?? 0);
    $state_id        = intval($_POST['state_id'] ?? 0);
    $district_id     = intval($_POST['district_id'] ?? 0);
    $taluka_id       = intval($_POST['taluka_id'] ?? 0);
    $village_id      = intval($_POST['village_id'] ?? 0);
    $address         = trim($_POST['address'] ?? '');
    $contact_email   = trim($_POST['contact_email'] ?? '');
    $contact_phone   = trim($_POST['contact_phone'] ?? '');

    // --- Validation ---
    if (empty($authority_name) || empty($authority_type) || empty($state_id) || empty($district_id)) {
        $_SESSION['error'] = "Authority name, type, state, and district are required.";
        header("Location: add-local-authority.php");
        exit;
    }

    if (!empty($contact_email) && !filter_var($contact_email, FILTER_VALIDATE_EMAIL)) {
        $_SESSION['error'] = "Invalid email format.";
        header("Location: add-local-authority.php");
        exit;
    }

    if (!empty($contact_phone) && !preg_match("/^[0-9]{10}$/", $contact_phone)) {
        $_SESSION['error'] = "Phone number must be 10 digits.";
        header("Location: add-local-authority.php");
        exit;
    }

    try {
        $conn->begin_transaction();

        // Duplicate check (same name + district)
        $check = $conn->prepare("SELECT id FROM local_authorities WHERE name = ? AND district_id = ?");
        $check->bind_param("si", $authority_name, $district_id);
        $check->execute();
        $check->store_result();

        if ($check->num_rows > 0) {
            $check->close();
            $_SESSION['error'] = "A local authority with this name already exists in the selected district.";
            header("Location: add-local-authority.php");
            exit;
        }
        $check->close();

        // Insert query
        $stmt = $conn->prepare("
            INSERT INTO local_authorities 
            (type_id, name, state_id, district_id, taluka_id, village_id, address, contact_email, contact_phone, created_at, updated_at) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, NOW(), NOW())
        ");
        $stmt->bind_param("isiiiisss", $authority_type, $authority_name, $state_id, $district_id, $taluka_id, $village_id, $address, $contact_email, $contact_phone);

        if ($stmt->execute()) {
            $conn->commit();
            $_SESSION['success'] = "Local Authority added successfully!";
            unset($_SESSION['old_values']); // clear old values after success
        } else {
            $conn->rollback();
            $_SESSION['error'] = "Failed to save local authority. Please try again.";
        }

        $stmt->close();
    } catch (Exception $e) {
        $conn->rollback();
        $_SESSION['error'] = "Database error: " . $e->getMessage();
    }

    $conn->close();
    header("Location: add-local-authority.php");
    exit;
}
?>
