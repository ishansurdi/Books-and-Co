<?php
// return_book_process.php

// Database connection settings
$servername = "localhost";
$username = "root"; // Update as needed
$password = "123456789"; // Update as needed
$dbname = "library"; // Update as needed

// Create a new MySQLi connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    header('Content-Type: application/json');
    echo json_encode(['error' => 'Database connection failed: ' . $conn->connect_error]);
    exit;
}

// Function to get return details from the database
function getReturnDetails($refNumber) {
    global $conn;

    $sql = "SELECT i.MID, m.first_name, m.last_name, i.ISBN, b.name_of_book, i.expected_date_of_return
            FROM issue_books i
            JOIN membership_form m ON i.MID = m.membership_id
            JOIN Books b ON i.ISBN = b.ISBN
            WHERE i.reference_number = ?";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $refNumber);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        return $result->fetch_assoc();
    } else {
        return ['error' => 'No return details found for the given reference number.'];
    }
}

if (isset($_REQUEST['ref_number'])) { // Check for both GET and POST requests
    $refNumber = $_REQUEST['ref_number'];

    if (empty($refNumber)) {
        header('Content-Type: application/json');
        echo json_encode(['error' => 'Please enter a reference number.']);
        exit;
    }

    try {
        $returnDetails = getReturnDetails($refNumber);
        header('Content-Type: application/json');
        echo json_encode($returnDetails);
        exit;
    } catch (Exception $e) {
        header('Content-Type: application/json');
        echo json_encode(['error' => 'An error occurred: ' . $e->getMessage()]);
        exit;
    }
} else {
    header('Content-Type: application/json');
    echo json_encode(['error' => 'Invalid request.']);
    exit;
}

$conn->close();
?>