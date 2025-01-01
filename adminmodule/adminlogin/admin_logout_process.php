<?php
session_start();
include('../db_connection.php'); // Correct path

if (isset($_SESSION['aid'])) {
    $aid = $_SESSION['aid'];
    $logout_time = date("Y-m-d H:i:s");

    // Update logout record
    $sql = "UPDATE login_logs SET logout_time = ?, session_status = 'ended' WHERE aid = ? AND session_status = 'active' ORDER BY login_time DESC LIMIT 1";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ss", $logout_time, $aid);
    $stmt->execute();

    // Destroy session
    session_unset();
    session_destroy();

    // Redirect to login page
    header("Location: index.php");
    exit;
} else {
    // Not logged in
    header("Location: index.php");
    exit;
}
?>
