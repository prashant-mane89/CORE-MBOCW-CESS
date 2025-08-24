<?php
session_start();
require_once 'config/db.php';

// Clear previous messages
unset($_SESSION['error']);
unset($_SESSION['success']);
unset($_SESSION['old']);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Store old input
    $_SESSION['old'] = $_POST;

    $errors = [];

    $local_authority_name = trim($_POST['local_authority_name']);
    $local_authority_type = intval($_POST['local_authority_type']);
    $cafo_name            = trim($_POST['cafo_name']);
    $cafo_email           = trim($_POST['cafo_email']);
    $cafo_mobile          = trim($_POST['cafo_mobile']);
    $cafo_gender          = trim($_POST['cafo_gender']);
    $cafo_address         = trim($_POST['cafo_address']);
    $aadhaar              = trim($_POST['aadhaar_no']);
    $pan                  = strtoupper(trim($_POST['pan_no']));
    $gstn                 = trim($_POST['gstn']);
    $state_id             = intval($_POST['state']);
    $district_id          = intval($_POST['district']);
    $taluka_id            = intval($_POST['taluka']);
    $village_id           = intval($_POST['village']);

    // ===== Validation =====
    if (empty($local_authority_name) || empty($local_authority_type) || empty($cafo_name) ||
        empty($cafo_email) || empty($cafo_mobile) || empty($cafo_gender) || empty($cafo_address) ||
        empty($aadhaar) || empty($pan)) {
        $_SESSION['error'] = "Please fill all required fields.";
        header("Location: register.php"); exit;
    }

    if (!preg_match('/^\d{12}$/', $aadhaar)) {
        $_SESSION['error'] = "Invalid Aadhaar number.";
        header("Location: register.php"); exit;
    }

    if (!preg_match('/^[A-Z]{5}[0-9]{4}[A-Z]{1}$/', $pan)) {
        $_SESSION['error'] = "Invalid PAN number.";
        header("Location: register.php"); exit;
    }

    if (!empty($gstn) && !preg_match('/^[0-9]{2}[A-Z]{5}[0-9]{4}[A-Z]{1}[1-9A-Z]{1}Z[0-9A-Z]{1}$/', $gstn)) {
        $_SESSION['error'] = "Invalid GSTN.";
        header("Location: register.php"); exit;
    }

    if (!preg_match('/^\d{10}$/', $cafo_mobile)) {
        $_SESSION['error'] = "Invalid Mobile Number.";
        header("Location: register.php"); exit;
    }

    $conn->begin_transaction();

    try {

        // Duplicate check
        if (!empty($gstn)) {
            $stmt = $conn->prepare("SELECT id FROM users WHERE email=? OR phone=? OR aadhaar=? OR pancard=? OR gstn=? LIMIT 1");
            $stmt->bind_param("sssss", $cafo_email, $cafo_mobile, $aadhaar, $pan, $gstn);
        } else {
            $stmt = $conn->prepare("SELECT id FROM users WHERE email=? OR phone=? OR aadhaar=? OR pancard=? LIMIT 1");
            $stmt->bind_param("ssss", $cafo_email, $cafo_mobile, $aadhaar, $pan);
        }
        $stmt->execute();
        $stmt->store_result();
        if ($stmt->num_rows > 0) {
            throw new Exception("Duplicate User (Email / Mobile / Aadhaar / PAN / GSTN).");
        }
        $stmt->close();

        // Insert into users
        $stmt = $conn->prepare("INSERT INTO users (name, email, password, phone, gender, state_id, district_id, taluka_id, village_id, address, role, gstn, pancard, aadhaar, is_active) 
                                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $password = md5("123456"); 
        $role = 3; // CAFO role 
        $is_active = 1;
        $local_authority_id = 0; 

        $stmt->bind_param("sssssiiiisisssi", $cafo_name, $cafo_email, $password, $cafo_mobile, $cafo_gender, $state_id, $district_id, $taluka_id, $village_id, $cafo_address, $role, $gstn, $pan, $aadhaar, $is_active);

        if ($stmt->execute()) {
            $user_id = $stmt->insert_id;
            $stmt->close();

            // Duplicate check
            $stmt = $conn->prepare("SELECT id FROM local_authorities WHERE type_id=? AND name=? AND district_id=? LIMIT 1");
            $stmt->bind_param("isi", $local_authority_type, $local_authority_name, $district_id);
            $stmt->execute();
            $stmt->store_result();
            if ($stmt->num_rows > 0) {
                throw new Exception("Duplicate Local Authority (local_authority_type / local_authority_name / district_id).");
            }else{
                $stmt = $conn->prepare("INSERT INTO local_authorities (type_id, name, state_id, district_id, taluka_id, village_id, address) VALUES (?, ?, ?, ?, ?, ?, ?)");
                $stmt->bind_param("isiiiis", $local_authority_type, $local_authority_name, $state_id, $district_id, $taluka_id, $village_id, $cafo_address);

                if ($stmt->execute()) {
                    $local_authority_id = $stmt->insert_id;
                    $stmt->close();

                    // Duplicate check for local_authorities_users
                    $checkSql = "SELECT id FROM local_authorities_users WHERE local_authority_id=? AND user_id=? LIMIT 1";
                    $checkStmt = $conn->prepare($checkSql);
                    $checkStmt->bind_param("ii", $local_authority_id, $user_id);
                    $checkStmt->execute();
                    $checkStmt->store_result();

                    if ($checkStmt->num_rows > 0) {
                        throw new Exception("Duplicate entry: This user is already assigned to the selected Local Authority.");
                    }
                    $checkStmt->close();

                    // Insert into local_authorities_users
                    $sql2 = "INSERT INTO local_authorities_users (local_authority_id, user_id, is_active)
                            VALUES (?, ?, ?)";
                    $stmt2 = $conn->prepare($sql2);
                    $stmt2->bind_param("iii", $local_authority_id, $user_id, $is_active);
                    $stmt2->execute();

                    $conn->commit();
                    $_SESSION['success'] = "Registration successful!";
                    unset($_SESSION['old']); // clear old inputs
                } else {
                    throw new Exception("Failed to save Local Authority.");
                }
            }
        } else {
            throw new Exception("Failed to save User.");
        }
    } catch (Exception $e) {
        $conn->rollback();
        $_SESSION['error'] = $e->getMessage();
    }
    header("Location: register.php"); exit;
}

?>