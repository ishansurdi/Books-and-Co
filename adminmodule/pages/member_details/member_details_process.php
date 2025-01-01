<?php
header('Content-Type: application/json'); // Set content type to JSON

// Enable error reporting for debugging
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Retrieve MID from the GET request
$mid = $_GET['mid'] ?? '';

if (empty($mid)) {
    echo json_encode(['error' => 'No Member ID provided']);
    exit();
}

// Database connection
$conn = new mysqli('localhost', 'root', '123456789', 'library');

// Check if the connection is successful
if ($conn->connect_error) {
    echo json_encode(['error' => 'Connection failed: ' . $conn->connect_error]);
    exit();
}

// Prepare and execute SQL query
$sql = "SELECT membership_id, first_name, last_name, email FROM membership_form WHERE membership_id = ?";
$stmt = $conn->prepare($sql);
if ($stmt === false) {
    echo json_encode(['error' => 'Database query preparation failed']);
    exit();
}

$stmt->bind_param('s', $mid);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    // Fetch member details
    $member = $result->fetch_assoc();
    echo json_encode($member); // Output member details in JSON format
} else {
    echo json_encode(['error' => 'No member found']);
}

// Close statement and connection
$stmt->close();
$conn->close();
?>
