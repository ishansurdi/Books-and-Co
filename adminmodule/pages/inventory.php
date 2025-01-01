<?php
include '../../database-connection/db_connection.php';

// Initialize variables for search criteria
$isbn = $_GET['isbn'] ?? '';
$book_name = $_GET['book_name'] ?? '';

// Initialize query with join to Books table
$query = "SELECT inv.Inventory_ID, inv.ISBN, inv.Library_ID, inv.Quantity, inv.Location, inv.Status, inv.Book_Condition, bk.Name_of_Book
          FROM inventory inv
          JOIN books bk ON inv.ISBN = bk.ISBN
          WHERE 1=1";

// Modify query based on search criteria
if ($isbn) {
    $query .= " AND inv.ISBN = '$isbn'";
}
if ($book_name) {
    $query .= " AND bk.Name_of_Book LIKE '%$book_name%'";
}

// Execute the query
$result = $conn->query($query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Portal Inventory</title>
    <link rel="stylesheet" href="../css/inventory.css">
</head>
<body>
    <!-- Header and Navbar -->
    <header>
        <a href = "../adminmodule.php"
        <div class="logo">
            <img src="../../images/logofile/png/logo-no-background.png" alt="Library Logo" width="150px" height="50px">
        </div>
        </a>
        <ul class="navbar">
            <li><a href="../adminmodule.php">Dashboard</a></li>
            <li><a href="inventory.php">Inventory</a></li>
            <!-- <li><a href="approval_requests.php">Approval Requests</a></li> -->
            <li><a href="../return_book/return_book_index.php">Return Books</a></li>
            <!-- <li><a href="library_stats.php">Miscellaneous</a></li> -->
        </ul>
        <div class="profile">
            <img src="../../images/profilecircle.svg" alt="Profile Icon" width="40px" height="40px">
            <div class="profile-dropdown">
                <a href="view_profile.php">View Profile</a>
                <a href="update_profile.php">Update Profile</a>
                <a href="logout.php">Log Out</a>
            </div>
        </div>
    </header>

    <!-- Search Bar Section -->
    <div class="search-bar">
        <form action="inventory.php" method="GET">
            <label for="isbn">Enter ISBN:</label>
            <input type="text" id="isbn" name="isbn" value="<?= htmlspecialchars($isbn) ?>">
            <button type="submit">Search</button>
        </form>
        <form action="inventory.php" method="GET">
            <label for="book_name">Book Name:</label>
            <input type="text" id="book_name" name="book_name" value="<?= htmlspecialchars($book_name) ?>">
            <button type="submit">Search</button>
        </form>
    </div>

    <!-- Inventory Table -->
    <div class="inventory-table">
        <table>
            <thead>
                <tr>
                    <th>Inventory ID</th>
                    <th>ISBN</th>
                    <th>Book Name</th>
                    <th>Library ID</th>
                    <th>Quantity</th>
                    <th>Location</th>
                    <th>Status</th>
                    <th>Condition</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($isbn || $book_name): // Show table only if search parameters are set ?>
                    <?php if ($result->num_rows > 0): ?>
                        <?php while ($row = $result->fetch_assoc()): ?>
                            <tr>
                                <td><?= htmlspecialchars($row['Inventory_ID']); ?></td>
                                <td><?= htmlspecialchars($row['ISBN']); ?></td>
                                <td><?= htmlspecialchars($row['Name_of_Book']); ?></td>
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
                            <td colspan="9">No inventory records found</td>
                        </tr>
                    <?php endif; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <!-- Add New Inventory Form -->
    <div class="add-inventory-form">
        <h3>Add New Inventory</h3>
        <form action="add_inventory.php" method="POST">
            <label for="isbn">ISBN:</label>
            <input type="text" id="isbn" name="isbn" required><br>

            <label for="bookname">Name of Book:</label>
            <input type="text" id="bookname" name="bookname" required><br>

            <label for="author">Name of Author:</label>
            <input type="text" id="author" name="author" required><br>

            <label for="jmo">Category:</label>
            <select id="jmo" name="jmo">
                <option >Select</option>
                <option value="Magazine">Magazine</option>
                <option value="Journal">Journal</option>
                <option value="Book">Book</option>
            </select>

            <label for="genre">Genre:</label>
            <select id="genre" name="genre">
                <option >Select</option>
                <option value="Adventure">Adventure</option>
                <option value="Classic">Classic</option>
                <option value="Fiction">Fiction</option>
                <option value="Non_Fiction">Non-Fiction</option>
                <option value="Fantasy">Fantasy</option>
            </select>

            <!-- <label for="Publication_Year">Library ID:</label>
            <input type="text" id="Publication_Year" name="Publication_Year" required><br> -->

            <label for="edition">Edition</label>
            <input type="text" id="edition" name="edition" required><br>

            <label for="language">Language</label>
            <input type="text" id="language" name="language" required><br>

            <label for="library_id">Library ID:</label>
            <input type="text" id="library_id" name="library_id" placeholder = "LIBXYZ" required><br>

            <label for="quantity">Quantity:</label>
            <input type="number" name="quantity" id="quantity" required>

            <label for="location">Location:</label>
            <input type="text" id="location" name="location"><br>

            <label for="status">Status:</label>
            <select id="status" name="status">
                <option >Select</option>
                <option value="Available">Available</option>
                <option value="Checked Out">Checked Out</option>
                <option value="Reserved">Reserved</option>
            </select><br>

            <label for="condition">Condition:</label>
            <select id="condition" name="condition">
                <option >Select</option>
                <option value="New">New</option>
                <option value="Good">Good</option>
                <option value="Fair">Fair</option>
                <option value="Poor">Poor</option>
            </select><br>

            <button type="submit">Add Inventory</button>
        </form>
    </div>

    <!-- Footer Section -->
    <footer>
        <div class="footer-container">
            <!-- Your footer content -->
        </div>
    </footer>

    <script>
        // Toggle dropdown menu
        document.querySelector('.profile img').addEventListener('click', function() {
            var dropdown = document.querySelector('.profile-dropdown');
            dropdown.classList.toggle('show');
        });

        // Close the dropdown if clicked outside
        window.onclick = function(event) {
            if (!event.target.matches('.profile img')) {
                var dropdowns = document.getElementsByClassName("profile-dropdown");
                for (var i = 0; i < dropdowns.length; i++) {
                    var openDropdown = dropdowns[i];
                    if (openDropdown.classList.contains('show')) {
                        openDropdown.classList.remove('show');
                    }
                }
            }
        }
    </script>
</body>
</html>