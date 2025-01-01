<?php
include '../db_connection.php';

$id = $_GET['id'] ?? '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Start transaction
    $conn->begin_transaction();
    
    try {
        // First, fetch the ISBN from the inventory
        $isbn_query = "SELECT ISBN FROM inventory WHERE Inventory_ID = '$id'";
        $isbn_result = $conn->query($isbn_query);
        
        if ($isbn_result->num_rows > 0) {
            $isbn_row = $isbn_result->fetch_assoc();
            $isbn = $isbn_row['ISBN'];
        } else {
            throw new Exception("No record found with Inventory_ID: " . $id);
        }

        // Delete from inventory table
        $query1 = "DELETE FROM inventory WHERE Inventory_ID = '$id'";
        if (!$conn->query($query1)) {
            throw new Exception("Error deleting from inventory: " . $conn->error);
        }

        // Now delete from books table using the fetched ISBN
        $query2 = "DELETE FROM books WHERE ISBN = '$isbn'";
        if (!$conn->query($query2)) {
            throw new Exception("Error deleting from books: " . $conn->error);
        }

        // Commit transaction
        $conn->commit();

        header('Location: inventory.php');
        exit();
    } catch (Exception $e) {
        // Rollback transaction on error
        $conn->rollback();
        echo $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Delete Inventory</title>
    <link rel="stylesheet" href="../css/inventory.css">
    <script>
        function confirmDeletion() {
            return confirm('Are you sure you want to delete this record?');
        }
    </script>
</head>
<body>
    <h3>Confirm Deletion</h3>
    <form action="delete_inventory.php?id=<?= urlencode($id) ?>" method="POST" onsubmit="return confirmDeletion()">
        <p>Are you sure you want to delete this record?</p>
        <button type="submit">Yes, Delete</button>
        <a href="inventory.php">Cancel</a>
    </form>
</body>
</html>
