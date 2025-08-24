<?php
session_start();
require_once 'config/db.php'; // connection to your MySQL DB

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = trim($_POST["email"]);
    $password = trim($_POST["password"]);

    if (empty($email) || empty($password)) {
        $_SESSION['error'] = "Please enter both email and password.";
        header("Location: login.php");
        exit;
    }

    $stmt = $conn->prepare("
        SELECT u.id, u.name, u.email, u.password, u.role, r.name as role_name
        FROM users u
        LEFT JOIN roles r ON u.role = r.id
        WHERE u.email = ?
    ");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();

    // Check if user exists
    if ($stmt->num_rows == 1) {
        $stmt->bind_result($id, $name, $userEmail, $hashedPassword, $role, $roleName);
        $stmt->fetch();

        if (md5($password) == $hashedPassword) {
            // Set session variables
            $_SESSION["user_id"] = $id;
            $_SESSION["user_name"] = $name;
            $_SESSION["user_email"] = $userEmail;
            $_SESSION["user_role"] = $role;
            $_SESSION["user_role_name"] = $roleName;

            header("Location: dashboard/dashboard.php");
            exit;
        } else {
            $_SESSION['error'] = "Incorrect password.";
            header("Location: login.php");
            exit;
        }
    } else {
        $_SESSION['error'] = "No user found with this email.";
        header("Location: login.php");
        exit;
    }
} else {
    header("Location: login.php");
    exit;
}
?>
