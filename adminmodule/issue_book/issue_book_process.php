<?php
// FILE TO ISSUE BOOK
session_start();
include '../../mailsystem/index.php';

// Check if the AID session variable is set
if (!isset($_SESSION['aid']) || empty($_SESSION['aid'])) {
    header("Location: ../adminlogin/index.php");
    exit;
}

// Process the form submission
$AID = $_SESSION['aid'];
$MID = $_POST['mid'];
$ISBN = $_POST['isbn'];

// Database connection
$conn = new mysqli('localhost', 'root', '123456789', 'library');
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$conn->autocommit(FALSE); // Turn off auto-commit mode

try {
    // Check if the member is suspended
    $memberStatusSql = "SELECT account_status FROM member_details WHERE mid = ?";
    $memberStatusStmt = $conn->prepare($memberStatusSql);
    $memberStatusStmt->bind_param("s", $MID);
    $memberStatusStmt->execute();
    $memberStatusStmt->bind_result($account_status);
    $memberStatusStmt->fetch();
    $memberStatusStmt->close();

    // If account status is "suspended", display alert and prevent book issue
    if ($account_status === 'Suspended') {
        echo "<script>alert('Book cannot be issued! Member account is suspended.'); window.history.back();</script>";
        exit;
    }

    // Fetch book details
    $sql = "SELECT * FROM books_availability WHERE ISBN = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $ISBN);
    $stmt->execute();
    $bookResult = $stmt->get_result();
    $book = $bookResult->fetch_assoc();

    if ($book && $book['status'] === 'Available' && $book['quantities'] > $book['borrowed']) {
        // Generate a reference number
        $reference_number = substr(str_shuffle("0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ"), 0, 15);
        $date_of_book_issue = date('Y-m-d H:i:s');
        $time_of_book_issue = date('H:i:s');
        $expected_date_of_return = date('Y-m-d', strtotime('+15 days'));

        // Insert the issue record
        $issueSql = "INSERT INTO issue_books (reference_number, MID, AID, ISBN, name_of_book, date_of_book_issue, time_of_book_issue, expected_date_of_return) 
                     VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
        $issueStmt = $conn->prepare($issueSql);
        $issueStmt->bind_param("ssssssss", $reference_number, $MID, $AID, $ISBN, $book['name_of_book'], $date_of_book_issue, $time_of_book_issue, $expected_date_of_return);
        if (!$issueStmt->execute()) {
            throw new Exception("Error inserting issue record: " . $issueStmt->error);
        }
        $issueStmt->close();

        // Update the book availability
        $updateSql = "UPDATE books_availability 
                      SET quantities = quantities - 1, borrowed = borrowed + 1, 
                      status = IF(quantities - borrowed <= 0, 'Unavailable', 'Available') 
                      WHERE ISBN = ?";
        $updateStmt = $conn->prepare($updateSql);
        $updateStmt->bind_param("s", $ISBN);
        if (!$updateStmt->execute()) {
            throw new Exception("Error updating book availability: " . $updateStmt->error);
        }
        $updateStmt->close();

        // Update member statistics
        $statsSql = "UPDATE userstatistics SET Books_Borrowed = Books_Borrowed + 1 WHERE MID = ?";
        $statsStmt = $conn->prepare($statsSql);
        $statsStmt->bind_param("s", $MID);
        if (!$statsStmt->execute()) {
            throw new Exception("Error updating user statistics: " . $statsStmt->error);
        }
        $statsStmt->close();

        // Fetch member details for email
        $userSql = "SELECT email, first_name FROM membership_form WHERE membership_id = ?";
        $userStmt = $conn->prepare($userSql);
        $userStmt->bind_param("s", $MID);
        $userStmt->execute();
        $userStmt->bind_result($email, $first_name);
        if (!$userStmt->fetch()) {
            throw new Exception("Error fetching member details.");
        }
        $userStmt->close();

        // Send issue confirmation email
        if (!sendIssueConfirmationEmail($MID, $book['name_of_book'], $reference_number, $date_of_book_issue, $expected_date_of_return, $email, $first_name)) {
            // Log email sending failure but continue with transaction
            error_log("Failed to send email to $email");
        }

        // Commit transaction
        $conn->commit();

        echo "Book issued successfully.";
    } else {
        throw new Exception("Book is not available for issue.");
    }
} catch (Exception $e) {
    // Rollback transaction in case of error
    $conn->rollback();
    echo "Error: " . $e->getMessage();
}

$conn->close();
?>
