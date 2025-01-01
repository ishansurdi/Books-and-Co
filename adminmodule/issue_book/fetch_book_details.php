<?php
header('Content-Type: application/json');
error_reporting(E_ALL);
ini_set('display_errors', 1);

$isbn = $_GET['isbn'] ?? '';

if (empty($isbn)) {
    echo json_encode(['error' => 'No ISBN provided']);
    exit();
}

$conn = new mysqli('localhost', 'root', '123456789', 'library');

if ($conn->connect_error) {
    echo json_encode(['error' => 'Connection failed: ' . $conn->connect_error]);
    exit();
}

$sql = "SELECT ISBN, library_id, name_of_book, status FROM books_availability WHERE ISBN = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param('s', $isbn);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $book = $result->fetch_assoc();
    echo json_encode($book);
} else {
    echo json_encode(['error' => 'No book found']);
}

$stmt->close();
$conn->close();
?>
