<?php
include '../db_connection.php';

$isbn = $_GET['isbn'] ?? '';
$book_name = $_GET['book_name'] ?? '';

$query = "SELECT * FROM inventory WHERE 1=1";
if ($isbn) {
    $query .= " AND ISBN = '$isbn'";
}
if ($book_name) {
    $query .= " AND Book_Name LIKE '%$book_name%'";
}

$result = $conn->query($query);
?>

<tbody>
    <?php if ($result->num_rows > 0): ?>
        <?php while ($row = $result->fetch_assoc()): ?>
            <tr>
                <td><?= htmlspecialchars($row['Inventory_ID']); ?></td>
                <td><?= htmlspecialchars($row['ISBN']); ?></td>
                <td><?= htmlspecialchars($row['Library_ID']); ?></td>
                <td><?= htmlspecialchars($row['Quantity']); ?></td>
                <td><?= htmlspecialchars($row['Location']); ?></td>
                <td><?= htmlspecialchars($row['Status']); ?></td>
                <td><?= htmlspecialchars($row['Book_Condition']); ?></td>
                <td>
                    <a href="edit_inventory.php?id=<?= urlencode($row['Inventory_ID']); ?>" class="edit-link">Edit</a> |
                    <a href="delete_inventory.php?id=<?= urlencode($row['Inventory_ID']); ?>" class="delete-link" onclick="return confirm('Are you sure you want to delete this record?');">Delete</a>
                </td>
            </tr>
        <?php endwhile; ?>
    <?php else: ?>
        <tr>
            <td colspan="8">No inventory records found</td>
        </tr>
    <?php endif; ?>
</tbody>
