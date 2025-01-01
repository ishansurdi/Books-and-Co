<?php
include '../database-connection/db_connection.php'; // Correct path
// include("./adminlogin/admin_autoend.php"); // Include auto logout check

// Ensure the session is started and active
session_start();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Portal | Library</title>
    <link rel="icon" href="../images/logofile/favicon.ico" type="image/x-icon">
    <link rel="stylesheet" href="../css/styles.css">
    <link rel="stylesheet" href="adminportal.css">
</head>
<body>
    <header>
        <nav>
            <div class="logo">
                <img src="../images/logofile/png/logo-no-background.png" alt="Logo">
            </div>
            <ul class="navbar">
                <li><a href="./issue_book/issue_book.php">Issue Books</a></li>
                <li><a href="./return_book/return_book_index.php">Return Books</a></li>
                <li><a href="pages/inventory.php">Inventory</a></li>
                <li><a href="pages/member_details/member_details.php">Member Details</a></li>
                <!-- <li><a href="view_requests.php">View Approval Requests</a></li> -->
                <div class="misc-menu">
                <a href="#" id="misc-icon">Miscellaneous</a>
                <div class="misc-dropdown-content" id="misc-dropdown-content">
                    <a href="./pages/send_mail_all/send_email_all.php">Send Email to All</a>
                </div>
                
</div>
            </ul>
            <div class="profile-menu">
                <img src="../images/profilecircle.svg" alt="Profile Icon" class="profile-icon" id="profile-icon">
                <div class="dropdown-content" id="dropdown-content">
                    <a href="#">Update Profile</a>
                    <a href="#">View Profile</a>
                    <a href="./adminlogin/admin_logout_process.php">Log Out</a>
                </div>
            </div>
        </nav>
    </header>

    <div class="main-content">
        <!-- Library Stats Section -->
        <section class="library-stats">
            <h2>Library Stats</h2>
            <div class="stats-grid">
                <div class="stat-item">
                    <h3>Total Books in Library</h3>
                    <p>Placeholder</p>
                </div>
                <div class="stat-item">
                    <h3>Total Books Borrowed</h3>
                    <p>Placeholder</p>
                </div>
            </div>
        </section>

        <!-- Other Sections -->
        <!-- View Approval Requests Section -->
        <section class="view-approval-requests">
            <!-- Content for viewing approval requests -->
        </section>

        <!-- Return Books Section -->
        <section class="return-books">
            <!-- Content for returning books -->
        </section>

        <!-- Inventory Section -->
        <section class="inventory">
            <!-- Content for inventory management -->
        </section>
    </div>

    <hr class="footer-separator">
    
    <!-- Footer Section -->
    <footer>
        <div class="footer-container">
            <!-- Your footer content -->
        </div>
    </footer>

    <script>
        document.getElementById("profile-icon").addEventListener("click", function(event) {
    event.stopPropagation(); // Prevent conflict with window.onclick
    var profileDropdown = document.getElementById("dropdown-content");
    profileDropdown.classList.toggle("show");
});

document.getElementById("misc-icon").addEventListener("click", function(event) {
    event.preventDefault(); // Prevent default link behavior
    event.stopPropagation(); // Prevent conflict with window.onclick
    var miscDropdown = document.getElementById("misc-dropdown-content");
    miscDropdown.classList.toggle("show");
});

// Close both dropdowns if clicked outside
window.onclick = function(event) {
    if (!event.target.matches('.profile-icon') && !event.target.matches('#misc-icon')) {
        var profileDropdown = document.getElementById("dropdown-content");
        if (profileDropdown.classList.contains('show')) {
            profileDropdown.classList.remove('show');
        }
        var miscDropdown = document.getElementById("misc-dropdown-content");
        if (miscDropdown.classList.contains('show')) {
            miscDropdown.classList.remove('show');
        }
    }
};

    </script>
</body>
</html>
