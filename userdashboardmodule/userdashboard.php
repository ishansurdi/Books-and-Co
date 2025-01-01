<?php
include '../database-connection/db_connection.php';
session_start();

// Redirect to login page if not authenticated
if (!isset($_SESSION['membership_id'])) {
    header("Location: /PBL_SourceCode/pages/login/login.php");
    exit();
}

// Retrieve session variables
$membership_id = $_SESSION['membership_id'];
$first_name = $_SESSION['first_name'];
$plan_name = $_SESSION['plan_name'];
$plan_amount = $_SESSION['plan_amount'];
$next_payment_date = $_SESSION['next_payment_date'];
$account_status = $_SESSION['account_status'];

// Initialize statistics variables
$books_borrowed = '0';
$pending_requests = '0';
$penalties = '0';

// Fetch user statistics from the database
$stats_sql = "SELECT Books_Borrowed, Pending_Requests, Penalties 
              FROM UserStatistics 
              WHERE MID = ?";
$stmt = $conn->prepare($stats_sql);
$stmt->bind_param("s", $membership_id);
$stmt->execute();
$stats_result = $stmt->get_result();

if ($stats_result->num_rows > 0) {
    $stats = $stats_result->fetch_assoc();
    $books_borrowed = $stats['Books_Borrowed'];
    $pending_requests = $stats['Pending_Requests'];
    $penalties = $stats['Penalties'];
}

// Fetch account status from the Transactions table
$account_status_sql = "SELECT account_status FROM Transactions WHERE MID = ?";
$stmt = $conn->prepare($account_status_sql);
$stmt->bind_param("s", $membership_id);
$stmt->execute();
$account_status_result = $stmt->get_result();

if ($account_status_result->num_rows > 0) {
    $account_status_row = $account_status_result->fetch_assoc();
    $account_status = $account_status_row['account_status'];
} else {
    // Default value if no status is found
    $account_status = 'Inactive';
}

// Fetch next transaction date from the Transactions table
$next_transaction_date_sql = "SELECT next_date_of_transaction FROM Transactions WHERE MID = ?";
$stmt = $conn->prepare($next_transaction_date_sql);
$stmt->bind_param("s", $membership_id);
$stmt->execute();
$next_transaction_result = $stmt->get_result();

if ($next_transaction_result->num_rows > 0) {
    $next_transaction_row = $next_transaction_result->fetch_assoc();
    $next_transaction_date = $next_transaction_row['next_date_of_transaction'];
} else {
    // Default value if no transaction date is found
    $next_transaction_date = 'N/A';  // You can set a default value or handle it as needed
}




?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Dashboard | Books & Co</title>
    <link rel="icon" href="../images/logofile/favicon.ico" type="image/x-icon">
    <link rel="stylesheet" href="../css/styles.css">
    <link rel="stylesheet" href="../css/dashboard.css">
    <!-- <link rel="stylesheet" href="../ai/aicss.css"> -->
</head>
<body>
    <header>
        <nav>
            <a href = "userdashboard.php">
            <div class="logo">
                <img src="../images/logofile/png/logo-no-background.png" alt="Logo">
            </div>
</a>
            <ul class="navbar">
                <!-- <li><a href="#">Search Books</a></li> -->
                <!-- <li><a href="#">Request Books</a></li>
                <li><a href="#">Digital Library</a></li> -->
                <li><a href="#">Order History</a></li>
            </ul>
            <div class="profile-menu">
                <img src="../images/profilecircle.svg" alt="Profile Icon" class="profile-icon" id="profile-icon">
                <div class="dropdown-content" id="dropdown-content">
                    <a href="#">Update Profile</a>
                    <a href="#">View Profile</a>
                    <a href="/PBL_SourceCode/pages/login/login.php">Log Out</a>
                </div>
            </div>
        </nav>
    </header>
    Chatbox Button
<div id="chat-button" class="chat-button">
    💬
  </div>
  
  <!-- Chatbox Container -->
  <!-- <div id="chatbox-container" class="chatbox-container">
    <div class="chatbox-header">
      <span>Books & Co. AI Assistant</span>
      <button id="close-button" class="close-button">×</button>
    </div>
    <div id="chatbox-content" class="chatbox-content"></div>
    <div id="suggested-questions" class="suggested-questions">
      <span>Suggested Questions:</span>
      <ul id="suggestions-list"></ul>
    </div>
    <div class="input-area">
      <input type="text" id="chat-input" placeholder="Ask a question..." />
      <button id="send-button">Send</button>
    </div>
</div> -->

    <div class="main-content">
        <!-- Welcome message with user's first name -->
        <h1 class="typing" id="welcome-message"></h1>
        <p id="current-date-time"></p>

        <!-- User Statistics Section -->
        <section class="user-statistics">
            <h2>User Statistics</h2>
            <div class="statistics-grid">
                <div class="stat-item">
                    <h3>Books Borrowed</h3>
                    <p><?php echo htmlspecialchars($stats['Books_Borrowed']); ?></p>
                    
                    <button class="pay-now-button">View Details</button>
                </div>
                <div class="stat-item">
                    <h3>Pending Requests</h3>
                    <p><?php echo htmlspecialchars($stats['Pending_Requests']); ?></p>
                    <button class="pay-now-button">View Details</button>
                </div>
                <div class="stat-item">
                    <h3>Penalties</h3>
                    <p>₹ <?php echo htmlspecialchars($stats['Penalties']); ?></p>
                    <button class="pay-now-button">View Details</button>
                </div>
                <div class="stat-item">
                    <h3>Membership Status</h3>
                    <p><?php echo htmlspecialchars($account_status); ?></p>
                </div>
            </div>
        </section>

        <!-- Next Payment Details Section -->
        <section class="next-payment-details">
    <h2>Next Payment Details</h2>
    <div class="payment-details">
        <div><strong>Plan:</strong> <?php echo htmlspecialchars($plan_name); ?></div>
        <div><strong>Amount:</strong> ₹<?php echo htmlspecialchars($plan_amount); ?></div>
        <!-- <div><strong>Next Payment Date:</strong>  <p><?php echo htmlspecialchars($next_transaction_date); ?></p></div> -->
        <!-- <button class="pay-now-button">Pay Now</button> -->
    </div>
</section>

        <!-- Recommended/Trending Books Section -->
        <section class="recommended-books">
            <h2>Recommended Books</h2>
            <div class="books-grid">
                <div class="book-item">
                    <img src="../images/books/book1.jpg" alt="Book 1">
                    <p>Book Title 1</p>
                </div>
                <div class="book-item">
                    <img src="../images/books/book2.jpg" alt="Book 2">
                    <p>Book Title 2</p>
                </div>
                <div class="book-item">
                    <img src="../images/books/book3.jpg" alt="Book 3">
                    <p>Book Title 3</p>
                </div>
            </div>
        </section>

        <!-- User Announcements/Notices Section -->
        <section class="announcements">
            <h2>Announcements</h2>
            <div class="announcement-item">
                <p>On Account of Independence Day (15/08/2024) library will remain closed. [14/08/24]</p>
            </div>
            <div class="announcement-item">
                <p>Library will be closed on August 20th for maintenance.</p>
            </div>
            <div class="announcement-item">
                <p>Don't forget to renew your membership for uninterrupted access.</p>
            </div>
        </section>
    </div>

    <hr class="footer-separator">
    
    <!-- Footer Section -->
    <footer>
        <div class="footer-container">
            <div class="footer-logo">
                <img src="../images/logofile/png/logo-no-background.png" alt="Logo"> <!-- Replace with your logo image -->
            </div>
            <div class="footer-maps">
                <h4>Maps Location</h4>
                <div class="map-container">
                    <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3783.24647107663!2d73.81253627470771!3d18.517760969261065!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x3bc2bfb9e53a05f9%3A0x2be5e8da02be693e!2sMIT%20World%20Peace%20University%20(MIT-WPU)!5e0!3m2!1sen!2sin!4v1723285471888!5m2!1sen!2sin" 
                        width="300" height="300" style="border:0;" allowfullscreen="" loading="lazy"></iframe>
                </div>
                <p>Address Line 1<br>Address Line 2</p> <!-- Replace with actual address -->
                <p>Contact: +123 456 7890</p> <!-- Replace with actual contact number -->
            </div>
            <div class="footer-links">
                <a href="#">Privacy Policy</a>
                <a href="#">Terms of Service</a>
                <a href="#">Disclaimer</a>
                <a href="pages/credits.html" target="_blank">Credits</a>
            </div>
        </div>
        <div class="social-media">
            <a href="#"><img src="../images/facebook.svg" alt="Facebook"></a> <!-- Replace with actual social media icons -->
            <a href="#"><img src="../images/x.svg" alt="Twitter"></a>
            <a href="#"><img src="../images/instagram.svg" alt="Instagram"></a>
        </div>
        <div class="footer-bottom">
            <p>&copy; Books & Co. All rights reserved.</p>
            <p>Developed by: PBL Group - 13, MITWPU, Pune</p>
        </div>
    </footer>

    <script>
        // Toggle dropdown menu
        document.getElementById("profile-icon").addEventListener("click", function() {
            var dropdown = document.getElementById("dropdown-content");
            dropdown.classList.toggle("show");
        });

        // Close the dropdown if clicked outside
        window.onclick = function(event) {
            if (!event.target.matches('.profile-icon')) {
                var dropdowns = document.getElementsByClassName("dropdown-content");
                for (var i = 0; i < dropdowns.length; i++) {
                    var openDropdown = dropdowns[i];
                    if (openDropdown.classList.contains('show')) {
                        openDropdown.classList.remove('show');
                    }
                }
            }
        }

        // Display current date and time
        function updateDateTime() {
            var now = new Date();
            var options = { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric', hour: '2-digit', minute: '2-digit' };
            document.getElementById("current-date-time").textContent = now.toLocaleDateString('en-US', options);
        }
        setInterval(updateDateTime, 1000);

        const firstName = "<?php echo $first_name; ?>";
        console.log("First Name: " + firstName);
        
        
    </script>
    <!-- <script src="../ai/chatbot.js"></script> -->
    <script src="../js/userdashboardtyping.js"></script>
</body>
</html>
