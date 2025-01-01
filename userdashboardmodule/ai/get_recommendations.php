<?php
// Enable error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);

include('../../database-connection/db_connection.php');

// Check database connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$MID = 'MID42311602'; // Static MID for testing
$recommendations = [];

// Step 1: Get ISBNs of books issued to the user
$queryIssuedBooks = "SELECT ISBN FROM issue_book WHERE MID = ?";
$stmtIssuedBooks = $conn->prepare($queryIssuedBooks);
$stmtIssuedBooks->bind_param("s", $MID);

if (!$stmtIssuedBooks->execute()) {
    die("Error executing query: " . $stmtIssuedBooks->error);
}

$resultIssuedBooks = $stmtIssuedBooks->get_result();

$issuedISBNs = [];
while ($row = $resultIssuedBooks->fetch_assoc()) {
    $issuedISBNs[] = $row['ISBN'];
}

// Step 2: Get genres of issued books
if (!empty($issuedISBNs)) {
    $isbnList = implode("','", $issuedISBNs);
    $queryGenres = "SELECT DISTINCT genre FROM Books WHERE ISBN IN ('$isbnList')";
    $resultGenres = $conn->query($queryGenres);

    if (!$resultGenres) {
        die("Error executing query: " . $conn->error);
    }

    $genres = [];
    while ($row = $resultGenres->fetch_assoc()) {
        $genres[] = $row['genre'];
    }

    // Step 3: Find books of similar genres not issued by the user
    if (!empty($genres)) {
        $genreList = implode("','", $genres);
        $queryRecommendations = "SELECT title, author FROM Books WHERE genre IN ('$genreList') AND ISBN NOT IN ('$isbnList') LIMIT 5";
        $resultRecommendations = $conn->query($queryRecommendations);

        if (!$resultRecommendations) {
            die("Error executing query: " . $conn->error);
        }

        while ($row = $resultRecommendations->fetch_assoc()) {
            $recommendations[] = $row;
        }
    }
}

// Output JSON
header('Content-Type: application/json');
echo json_encode($recommendations);
?>
