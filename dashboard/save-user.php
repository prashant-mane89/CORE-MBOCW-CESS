<?php
/**
 * save-user.php
 *
 * This script processes the user creation form submission from add-user.php.
 * It performs server-side validation, hashes the password,
 * and securely inserts the new user's data into the database,
 * matching the provided column order.
 */

session_start();
if (!isset($_SESSION['user_id'])) {
    // If the user is not logged in, redirect them to the login page.
    header("Location: ../login.php");
    exit;
}

// Include the database configuration file to establish a connection.
require_once '../config/db.php';

require_once '../vendor/autoload.php'; // Adjust the path if needed
use PHPMailer\PHPMailer\PHPMailer;

// Check if the form was submitted using the POST method.
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // --- 1. Server-Side Validation ---
    // Sanitize and validate all incoming form data.
    $name = trim($_POST['name']);
    $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
    $password = '123456'; // Default password for new users
    $phone = trim($_POST['phone']);
    $gender = trim($_POST['gender']);
    $state_id = filter_var($_POST['state_id'], FILTER_SANITIZE_NUMBER_INT);
    $district_id = filter_var($_POST['district_id'], FILTER_SANITIZE_NUMBER_INT);
    $taluka_id = filter_var($_POST['taluka_id'], FILTER_SANITIZE_NUMBER_INT);
    $village_id = filter_var($_POST['village_id'], FILTER_SANITIZE_NUMBER_INT);
    $address = trim($_POST['address']);
    $role_id = filter_var($_POST['role'], FILTER_SANITIZE_NUMBER_INT);
    $gstn = trim($_POST['gstn']);
    $pancard = trim($_POST['pancard']);
    $aadhaar = trim($_POST['aadhaar']);

    // Set a default value for is_active.
    $is_active_status = 1; 

    // Basic validation check. You can add more complex validation as needed.
    if (empty($name) || empty($email) || empty($password) || empty($phone)) {
        $_SESSION['error'] = "Please fill in all required fields (Name, Email, Password, Phone).";
        header("Location: add-user.php");
        exit;
    }

    // Hash the password for secure storage in the database.
    $hashed_password = md5($password);

    // --- 2. Database Insertion with Prepared Statements ---
    // Prepare the SQL statement to prevent SQL injection attacks.
    // The column order here now matches the order in your table screenshot.
    $sql = "INSERT INTO users (
        name, 
        email, 
        password, 
        phone, 
        gender, 
        state_id, 
        district_id, 
        taluka_id, 
        village_id, 
        address,
        role, 
        gstn, 
        pancard, 
        aadhaar,
        is_active
    ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
    
    $stmt = $conn->prepare($sql);

    if ($stmt === false) {
        $_SESSION['error'] = "Database error: " . $conn->error;
        header("Location: add-user.php");
        exit;
    }

    // Bind parameters to the prepared statement, ensuring order and data types match.
    // 's' for string, 'i' for integer.
    $stmt->bind_param("sssisiiisissssi", 
        $name, 
        $email, 
        $hashed_password,
        $phone, 
        $gender, 
        $state_id, 
        $district_id, 
        $taluka_id, 
        $village_id, 
        $address,
        $role_id, 
        $gstn, 
        $pancard, 
        $aadhaar,
        $is_active_status
    );

    // Execute the statement and check for success.
    if ($stmt->execute()) {
        $_SESSION['success'] = "User added successfully!";

        // --- 3. Send Welcome Email ---
        $mail = new PHPMailer(true);
        try {
            // Server settings for Gmail SMTP
            //$mail->SMTPDebug = SMTP::DEBUG_SERVER;  // Uncomment for detailed debugging
            $mail->isSMTP();                                           
            $mail->Host       = 'smtp.gmail.com';                     // Your SMTP server
            $mail->SMTPAuth   = true;                                   
            $mail->Username   = 'aaravprashantmane@gmail.com';               // Your SMTP username
            $mail->Password   = 'rpfbzhzfxomebmcq';                  // Your SMTP password
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;         // Or ENCRYPTION_SMTPS
            $mail->Port       = 587;                                    // Or 465 for SMTPS

            // Recipients
            $mail->setFrom('aaravprashantmane@gmail.com', 'MBOCWCESS');
            $mail->addAddress($email, $name);     // Add a recipient

            // Content
            $mail->isHTML(true);                                  
            $mail->Subject = 'Welcome to MBOCW CESS Portal!';

            // Check if the user's role is one that requires login details.
            // Role IDs 1 (Admin), 3 (Local Authority), and 7 (Engineer) based on the screenshot.
            if (in_array($role_id, [1, 3, 7])) {
                $mail->Body    = "
                <p>Hello **$name**,</p>
                <p>Your account has been successfully created. You can now log in using the following details:</p>
                <p><strong>Link:</strong> <a href='http://localhost/CORE-MBOCW-CESS/login.php'>click here to login</a></p>
                <p><strong>Email:</strong> $email</p>
                <p><strong>Password:</strong> $password</p>
                <p>We recommend changing your password after your first login.</p>
                <br>
                <p>Best regards,</p>
                <p>MBOCW CESS Team</p>
                ";
            } else {
                // This is the message for users who do not require a login.
                $mail->Body    = "
                <p>Hello **$name**,</p>
                <p>Your user profile has been created successfully in our system.</p>
                <br>
                <p>Best regards,</p>
                <p>The Application Team</p>
                ";
            }

            $mail->send();
            $_SESSION['success'] .= " A welcome email has been sent.";
        } catch (Exception $e) {
            // Log the error but don't stop the script.
            error_log("Message could not be sent. Mailer Error: {$mail->ErrorInfo}");
            $_SESSION['error'] = "User added, but the welcome email could not be sent. Please check mailer settings.";
        }
    } else {
        $_SESSION['error'] = "Failed to add user: " . $stmt->error;
    }

    // Close the statement and the database connection.
    $stmt->close();
    $conn->close();

    // Redirect back to the add user page.
    header("Location: add-user.php");
    exit;

} else {
    // If the request method is not POST, redirect to the user list page.
    header("Location: users.php");
    exit;
}
?>
