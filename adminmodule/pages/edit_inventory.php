<?php
include '../../database-connection/db_connection.php';

$id = $_GET['id'] ?? '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get updated values from form
    $location = $_POST['location'];
    $status = $_POST['status'];
    $condition = $_POST['condition'];
    $quantity = $_POST['quantity'];

    // Update query for the inventory
    $query = "UPDATE inventory SET Location = '$location', Status = '$status', Book_Condition = '$condition', Quantity = '$quantity' WHERE Inventory_ID = '$id'";
    if ($conn->query($query) === TRUE) {
        // Fetch ISBN and library_id related to this inventory item
        $fetch_query = "SELECT ISBN, library_id FROM inventory WHERE Inventory_ID = '$id'";
        $result = $conn->query($fetch_query);
        $inventory_row = $result->fetch_assoc();
        $isbn = $inventory_row['ISBN'];
        $library_id = $inventory_row['library_id'];

        // Update the corresponding quantities in books_availability
        $update_availability_query = "
            UPDATE books_availability 
            SET quantities = (SELECT SUM(Quantity) FROM inventory WHERE ISBN = '$isbn' AND library_id = '$library_id')
            WHERE ISBN = '$isbn' AND library_id = '$library_id'
        ";
        $conn->query($update_availability_query);

        // Redirect to inventory page after successful update
        header('Location: inventory.php');
        exit();
    } else {
        echo "Error updating record: " . $conn->error;
    }
}

// Fetch current record
$query = "SELECT * FROM inventory WHERE Inventory_ID = '$id'";
$result = $conn->query($query);
$row = $result->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Inventory</title>
    <link rel="stylesheet" href="../css/inventory.css">
</head>
<body>
    <h3>Edit Inventory</h3>
    <form action="edit_inventory.php?id=<?= urlencode($id) ?>" method="POST">
        <label for="quantity">Quantity:</label>
        <input type="number" id="quantity" name="quantity" value="<?= htmlspecialchars($row['Quantity']) ?>" required><br>

        <label for="location">Location:</label>
        <input type="text" id="location" name="location" value="<?= htmlspecialchars($row['Location']) ?>" required><br>

        <label for="status">Status:</label>
        <select id="status" name="status" required>
            <option value="Available" <?= $row['Status'] === 'Available' ? 'selected' : '' ?>>Available</option>
            <option value="Checked Out" <?= $row['Status'] === 'Checked Out' ? 'selected' : '' ?>>Checked Out</option>
            <option value="Reserved" <?= $row['Status'] === 'Reserved' ? 'selected' : '' ?>>Reserved</option>
        </select><br>

        <label for="condition">Condition:</label>
        <select id="condition" name="condition" required>
            <option value="New" <?= $row['Book_Condition'] === 'New' ? 'selected' : '' ?>>New</option>
            <option value="Good" <?= $row['Book_Condition'] === 'Good' ? 'selected' : '' ?>>Good</option>
            <option value="Fair" <?= $row['Book_Condition'] === 'Fair' ? 'selected' : '' ?>>Fair</option>
            <option value="Poor" <?= $row['Book_Condition'] === 'Poor' ? 'selected' : '' ?>>Poor</option>
        </select><br>

        <button type="submit">Update Inventory</button>
    </form>
</body>
</html>
