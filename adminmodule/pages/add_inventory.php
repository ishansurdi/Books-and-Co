<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

include '../../database-connection/db_connection.php';

// Fetch and validate form values
$isbn = isset($_POST['isbn']) ? $_POST['isbn'] : '';
$bookname = isset($_POST['bookname']) ? $_POST['bookname'] : '';
$author = isset($_POST['author']) ? $_POST['author'] : '';
$jmo = isset($_POST['jmo']) ? $_POST['jmo'] : '';
$genre = isset($_POST['genre']) ? $_POST['genre'] : '';
$edition = isset($_POST['edition']) ? $_POST['edition'] : '';
$language = isset($_POST['language']) ? $_POST['language'] : '';
$library_id = isset($_POST['library_id']) ? $_POST['library_id'] : '';
$quantity = isset($_POST['quantity']) && is_numeric(trim($_POST['quantity'])) ? intval(trim($_POST['quantity'])) : null;
$location = isset($_POST['location']) ? $_POST['location'] : '';
$status = isset($_POST['status']) ? $_POST['status'] : '';
$condition = isset($_POST['condition']) ? $_POST['condition'] : '';

// Debugging output to verify values
echo "Debug: ISBN = $isbn, Quantity = " . var_export($quantity, true);
if ($quantity === null || $quantity <= 0) {
    echo "Error: Quantity cannot be zero, null, or negative!";
    exit();
}

// Validate required fields
if (empty($isbn) || empty($bookname) || empty($author) || empty($library_id) || empty($status) || empty($condition) || $quantity === null || $quantity <= 0) {
    echo "Some required fields are missing or invalid!";
    exit();
}

// Start the transaction
$conn->begin_transaction();
echo "Transaction started.";

// Insert into books table
echo "Preparing books table insert statement...\n";
$insertBooksQuery = "INSERT INTO books (ISBN, Library_id, Name_of_Book, Author, Journal_Magazine_Other, Genre, Edition, Language) 
                     VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
$stmt = $conn->prepare($insertBooksQuery);
$stmt->bind_param('ssssssss', $isbn, $library_id, $bookname, $author, $jmo, $genre, $edition, $language);

if (!$stmt->execute()) {
    echo "Error inserting into books table: " . $stmt->error;
    $conn->rollback();
    exit();
} else {
    echo "Successfully inserted into books table.\n";
}

// Insert into inventory table
echo "Preparing inventory insert statement...\n";
$insertInventoryQuery = "INSERT INTO inventory (ISBN, Library_ID, Quantity, Location, Status, Book_Condition) 
                         VALUES (?, ?, ?, ?, ?, ?)";
$stmt = $conn->prepare($insertInventoryQuery);
$stmt->bind_param('ssi\]\+sss', $isbn, $library_id, $quantity, $location, $status, $condition);

if (!$stmt->execute()) {
    echo "Error inserting into inventory table: " . $stmt->error;
    $conn->rollback();
    exit();
} else {
    echo "Successfully inserted into inventory table.\n";
}

// Check if the record already exists in books_availability
echo "Checking if entry already exists in books_availability...\n";
$checkAvailabilityQuery = "SELECT * FROM books_availability WHERE ISBN = ? AND library_id = ?";
$stmt = $conn->prepare($checkAvailabilityQuery);
$stmt->bind_param('ss', $isbn, $library_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    // Record exists, update the quantities
    echo "Record found. Updating quantities...\n";
    $updateQuery = "UPDATE books_availability SET quantities = quantities + ? WHERE ISBN = ? AND library_id = ?";
    $stmt = $conn->prepare($updateQuery);
    $stmt->bind_param('iss', $quantity, $isbn, $library_id);

    if (!$stmt->execute()) {
        echo "Error updating books_availability table: " . $stmt->error;
        $conn->rollback();
        exit();
    } else {
        echo "Successfully updated quantities in books_availability.\n";
    }
} else {
    // Record does not exist, insert a new record
    echo "Record not found. Inserting new entry...\n";
    $insertAvailabilityQuery = "INSERT INTO books_availability (ISBN, library_id, name_of_book, quantities, borrowed, returned, status) 
                                VALUES (?, ?, ?, ?, 0, 0, 'Available')";
    $stmt = $conn->prepare($insertAvailabilityQuery);
    $stmt->bind_param('ssss', $isbn, $library_id, $bookname, $quantity);

    if (!$stmt->execute()) {
        echo "Error inserting into books_availability: " . $stmt->error;
        $conn->rollback();
        exit();
    } else {
        echo "Successfully inserted into books_availability table.\n";
    }
}

// Commit transaction
$conn->commit();
echo "Inventory successfully added!";
?>
