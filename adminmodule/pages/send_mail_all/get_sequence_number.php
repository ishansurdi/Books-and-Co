<?php
// Database connection
$conn = new mysqli('localhost', 'root', '123456789', 'library'); // Update credentials as necessary
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch next sequence number
$sql = "SELECT IFNULL(MAX(sequence_number), 0) + 1 AS next_sequence FROM announcements";
$result = $conn->query($sql);
$row = $result->fetch_assoc();

$response = array('sequence_number' => $row['next_sequence']);
echo json_encode($response);

// Close connection
$conn->close();
?>
