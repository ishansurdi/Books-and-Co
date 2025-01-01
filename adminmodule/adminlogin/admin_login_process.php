<?php
session_start();
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'C:/xampp/htdocs/PBL_SourceCode/vendor/autoload.php';
include("../../database-connection/db_connection.php"); // Correct path

// Get form data
$aid = $_POST['aid'];
$password = $_POST['password'];

// Fetch admin record from the database
$sql = "SELECT * FROM admins WHERE aid = ? AND admin_password = ?"; // Adjust query based on actual column names
$stmt = $conn->prepare($sql);
$stmt->bind_param("ss", $aid, $password);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 1) {
    // Admin authenticated
    $_SESSION['aid'] = $aid; // Set session variable
    $login_time = date("Y-m-d H:i:s");

    // Insert login record
    $sql = "INSERT INTO login_logs (aid, login_time, session_status) VALUES (?, ?, 'active')";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ss", $aid, $login_time);
    $stmt->execute();

    // Redirect to admin dashboard or home page
    header("Location: ../adminmodule.php");
    exit;
} else {
    // Authentication failed
    echo "Invalid Admin ID or Password.";
    exit;
}
?>
