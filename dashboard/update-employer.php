<?php
session_start();
// Assume you have a database connection file
require_once '../config/db.php';

// Clear previous messages
unset($_SESSION['error']);
unset($_SESSION['success']);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Save form data to session in case of an error
    $_SESSION['old_data'] = $_POST;

    $errors = [];

    // Sanitize and validate input
    $id            = $_POST['id'] ?? null;
    $employer_type = trim($_POST['employer_type'] ?? '');
    $name          = trim($_POST['name'] ?? '');
    $email         = filter_var(trim($_POST['email'] ?? ''), FILTER_VALIDATE_EMAIL);
    $phone         = trim($_POST['phone'] ?? '');
    $pancard       = strtoupper(trim($_POST['pancard'] ?? ''));
    $aadhaar       = trim($_POST['aadhaar'] ?? '');
    $gstn          = strtoupper(trim($_POST['gstn'] ?? ''));

    // Check if the employer ID is valid
    if (!is_numeric($id) || $id <= 0) {
        $_SESSION['error'] = "Invalid employer ID.";
        header("Location: employers.php");
        exit();
    }

    // Basic server-side validation
    if (empty($employer_type) || empty($name) || empty($email) || empty($phone) || empty($pancard) || empty($aadhaar)) {
        $errors[] = "Please fill all required fields.";
    }

    if (!$email) {
        $errors[] = "Invalid email format.";
    }

    if (!preg_match('/^[0-9]{10}$/', $phone)) {
        $errors[] = "Invalid phone number.";
    }

    if (!preg_match('/^[A-Z]{5}[0-9]{4}[A-Z]{1}$/', $pancard)) {
        $errors[] = "Invalid PAN number format.";
    }

    if (!preg_match('/^[0-9]{12}$/', $aadhaar)) {
        $errors[] = "Invalid Aadhaar number format.";
    }

    if (!empty($gstn) && !preg_match('/^[0-9]{2}[A-Z]{5}[0-9]{4}[A-Z]{1}[1-9A-Z]{1}Z[0-9A-Z]{1}$/', $gstn)) {
        $errors[] = "Invalid GSTN format.";
    }

    // Fetch current employer details to get old file paths 
    $sql_fetch = "SELECT `pancard_path`, `aadhaar_path` FROM `employers` WHERE `id` = ?";
    $stmt_fetch = $conn->prepare($sql_fetch);
    $stmt_fetch->bind_param("i", $id);
    $stmt_fetch->execute();
    $result_fetch = $stmt_fetch->get_result();
    $current_employer = $result_fetch->fetch_assoc();
    $stmt_fetch->close();

    $pan_path_old = $current_employer['pancard_path'] ?? '';
    $aadhaar_path_old = $current_employer['aadhaar_path'] ?? '';
    
    // File upload handling
    $pan_path_new = $pan_path_old;
    $aadhaar_path_new = $aadhaar_path_old;

    // Handle PAN Card file upload
    if (isset($_FILES['pancard_path']) && $_FILES['pancard_path']['error'] === UPLOAD_ERR_OK) {
        $file_ext = pathinfo($_FILES['pancard_path']['name'], PATHINFO_EXTENSION);
        $new_file_name = $pancard . '_' . time() . '.' . $file_ext;
        $upload_dir = '../uploads/employer_pan/';
        $pan_path_new = $upload_dir . $new_file_name;
        if (!move_uploaded_file($_FILES['pancard_path']['tmp_name'], $pan_path_new)) {
            $errors[] = "Failed to move uploaded PAN Card file.";
        }
    }

    // Handle Aadhaar file upload
    if (isset($_FILES['aadhaar_path']) && $_FILES['aadhaar_path']['error'] === UPLOAD_ERR_OK) {
        $file_ext = pathinfo($_FILES['aadhaar_path']['name'], PATHINFO_EXTENSION);
        $new_file_name = $aadhaar . '_' . time() . '.' . $file_ext;
        $upload_dir = '../uploads/employer_aadhaar/';
        $aadhaar_path_new = $upload_dir . $new_file_name;
        if (!move_uploaded_file($_FILES['aadhaar_path']['tmp_name'], $aadhaar_path_new)) {
            $errors[] = "Failed to move uploaded Aadhaar Card file.";
        }
    }

    if (!empty($errors)) {
        $_SESSION['error'] = implode("<br>", $errors);
        //echo '<pre>'; print_r($_SESSION['error']); exit;
        header("Location: edit-employer.php?id=" . $id);
        exit();
    }

    // Transaction start to ensure all updates are successful or none are
    $conn->begin_transaction();

    try {
        // Check for duplicate records, excluding the current employer
        $checkSql = "SELECT e.id FROM employers e WHERE e.email = ? OR e.phone = ? OR e.aadhaar = ? OR e.pancard = ? OR e.gstn = ? AND e.id != ?";
        $checkStmt = $conn->prepare($checkSql);
        $checkStmt->bind_param("sssssi", $email, $phone, $aadhaar, $pancard, $gstn, $id);
        $checkStmt->execute();
        $checkStmt->store_result();
        if ($checkStmt->num_rows > 0) {
            $resultFetch = $checkStmt->get_result();
            $currentEmployer = $resultFetch->fetch_assoc();
            if($currentEmployer['id'] != $id){
                $message = "Duplicate record found. An employer with this email, phone, Aadhaar, PAN, or GSTN already exists.";
                $_SESSION['error'] = $message;
                //throw new Exception($message);
            }
        }
        $checkStmt->close();

        // 2. Update the employer details
        $sql = "UPDATE `employers` SET `employer_type` = ?, `name` = ?, `email` = ?, `phone` = ?, `pancard` = ?, `aadhaar` = ?, `gstn` = ?, `pancard_path` = ?, `aadhaar_path` = ? WHERE `id` = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sssssssssi", $employer_type, $name, $email, $phone, $pancard, $aadhaar, $gstn, $pan_path_new, $aadhaar_path_new, $id);
        if ($stmt->execute()) {
            $conn->commit();
            
            // Delete old files if new ones were uploaded successfully
            if ($pan_path_new !== $pan_path_old && file_exists($pan_path_old)) {
                unlink($pan_path_old);
            }
            if ($aadhaar_path_new !== $aadhaar_path_old && file_exists($aadhaar_path_old)) {
                unlink($aadhaar_path_old);
            }

            // Unset old data from session on success
            unset($_SESSION['old_data']);
            $_SESSION['success'] = "Employer details updated successfully!";
            $stmt->close();
            header("Location: edit-employer.php?id=" . $id); // Redirect on failure
            exit();
        } else {
            $message = "Error updating employer: " . $stmt->error;
            $_SESSION['error'] = $message;
            $stmt->close();
            header("Location: edit-employer.php?id=" . $id); // Redirect on failure
            exit();
        }

    } catch (Exception $e) {
        $conn->rollback();
        // Delete newly uploaded files if transaction fails
        if ($pan_path_new !== $pan_path_old && file_exists($pan_path_new)) {
            unlink($pan_path_new);
        }
        if ($aadhaar_path_new !== $aadhaar_path_old && file_exists($aadhaar_path_new)) {
            unlink($aadhaar_path_new);
        }
        $_SESSION['error'] = $e->getMessage();
        header("Location: edit-employer.php?id=" . $id); // Redirect on failure
        exit();
    }

} else {
    // If someone tries to access this page directly, redirect them
    header("Location: employers.php");
    exit();
}
?>
