<?php
// Display errors for debugging
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
include '../../mailsystem/index.php';

// Database connection
$servername = "localhost";
$username = "root";
$password = "123456789";
$dbname = "library";

$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die(json_encode(["error" => "Connection failed: " . $conn->connect_error]));
}

// Ensure correct content type for JSON response
header('Content-Type: application/json');

// Get reference number from GET request
if (isset($_GET['ref_number'])) {
    $ref_number = $_GET['ref_number'];
    $actual_date_of_return = date('Y-m-d'); // Current date

    // Start transaction
    $conn->begin_transaction();

    try {
        // Fetch expected return date, MID, ISBN, and date_of_book_issue from issue_books
        $sql = "SELECT expected_date_of_return, MID, ISBN, date_of_book_issue FROM issue_books WHERE reference_number = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $ref_number);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $expected_return_date = $row['expected_date_of_return']; // Expected Return Date
            $mid = $row['MID'];
            $isbn = $row['ISBN'];
            $issueDate = $row['date_of_book_issue']; // Issue Date
        
            $actual_date_of_return = date('Y-m-d'); // Current date
            

            // Determine fine status
            $fine_imposed = "No";
            $fine_amount = 0;

            if ($actual_date_of_return > $expected_return_date) {
                $overdue_days = (strtotime($actual_date_of_return) - strtotime($expected_return_date)) / (60 * 60 * 24);
                $fine_amount = $overdue_days * 20; // 20 rupees per day
                $fine_imposed = "Yes";
            }

            // Update issue_books table
            $updateSql = "UPDATE issue_books SET 
                            actual_date_of_return = ?, 
                            fine_imposed = ?, 
                            fine_amount = ?, 
                            fine_paid = 0, 
                            date_of_fine_paid = NULL 
                          WHERE reference_number = ?";
            $updateStmt = $conn->prepare($updateSql);
            $updateStmt->bind_param("ssds", $actual_date_of_return, $fine_imposed, $fine_amount, $ref_number);
            $updateStmt->execute();

            // Fetch member details (email and first name)
            $memberSql = "SELECT email, first_name FROM membership_form WHERE membership_id = ?";
            $memberStmt = $conn->prepare($memberSql);
            $memberStmt->bind_param("s", $mid);
            $memberStmt->execute();
            $memberResult = $memberStmt->get_result();

            if ($memberResult->num_rows > 0) {
                $memberRow = $memberResult->fetch_assoc();
                $email = $memberRow['email'];
                $first_name = $memberRow['first_name'];

                // Fetch book name based on ISBN
                $bookNameSql = "SELECT name_of_book FROM books WHERE ISBN = ?";
                $bookNameStmt = $conn->prepare($bookNameSql);
                $bookNameStmt->bind_param("s", $isbn);
                $bookNameStmt->execute();
                $bookNameResult = $bookNameStmt->get_result();

                if ($bookNameResult->num_rows > 0) {
                    $bookRow = $bookNameResult->fetch_assoc();
                    $bookName = $bookRow['name_of_book'];

                    // Call email function
                    returnConfirmationEmail($mid, $bookName, $ref_number, $expected_return_date, $actual_date_of_return, $email, $first_name, $issueDate);
                } else {
                    echo json_encode(["error" => "Book details not found."]);
                }
                $bookNameStmt->close();
            } else {
                echo json_encode(["error" => "Member details not found."]);
            }

            // Update user statistics for books borrowed
            $updateUserStatsSql = "UPDATE userstatistics SET books_borrowed = books_borrowed - 1 WHERE MID = ?";
            $userStatsStmt = $conn->prepare($updateUserStatsSql);
            $userStatsStmt->bind_param("s", $mid);
            $userStatsStmt->execute();

            // Update books availability
            $updateBooksAvailabilitySql = "UPDATE books_availability SET 
                                              quantities = quantities + 1, 
                                              borrowed = borrowed - 1, 
                                              returned = returned + 1 
                                            WHERE ISBN = ?";
            $booksAvailabilityStmt = $conn->prepare($updateBooksAvailabilitySql);
            $booksAvailabilityStmt->bind_param("s", $isbn);
            $booksAvailabilityStmt->execute();

            // Commit transaction
            $conn->commit();

            echo json_encode(["success" => "Book returned successfully."]);
        } else {
            echo json_encode(["error" => "No record found for the given reference number."]);
        }
    } catch (Exception $e) {
        $conn->rollback(); // Rollback transaction on error
        echo json_encode(["error" => "Error: " . $e->getMessage()]);
    }
} else {
    echo json_encode(["error" => "No reference number provided."]);
}

// Close the connection
$conn->close();
?>
