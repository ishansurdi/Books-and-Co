<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Return Book</title>
    <link rel="stylesheet" href="../css/issue_book.css">
</head>
<body>
    <!-- Header Section -->
    <header>
        <a href="../adminlogin/index.php">
            <div class="logo">
                <img src="../../images/logofile/png/logo-no-background.png" alt="Library Logo" width="150px" height="50px">
            </div>
        </a>
        <ul class="navbar">
            <li><a href = "../adminmodule.php"> Dashboard </a><li>
            <li><a href="../pages/inventory.php">Inventory</a></li>
            <!-- <li><a href="approval_requests.php">Approval Requests</a></li> -->
            <!-- <li><a href="return_books.php">Return Books</a></li> -->
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

    <!-- Return Book Section -->
    <div class="issue-book-container">
        <h2>Return Book</h2>
        
        <!-- Main Form for Returning Book -->
        <form id="return-book-form">
            <!-- Reference Number Form -->
            <div class="form-section">
                <h3>Reference Number</h3>
                <div class="input-group">
                    <label for="ref-number">Enter Reference Number:</label>
                    <input type="text" id="ref-number" name="ref_number" required>
                    <button type="button" id="get-return-details">Get Details</button>
                </div>

                <!-- Return Details Table -->
                <div class="details-section" id="return-details" style="display: none;">
                    <table>
                        <thead>
                            <tr>
                                <th>Member ID</th>
                                <th>First Name</th>
                                <th>Last Name</th>
                                <th>ISBN</th>
                                <th>Name of Book</th>
                                <th>Expected Date of Return</th>
                                <th>Return</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td id="mid"></td>
                                <td id="first-name"></td>
                                <td id="last-name"></td>
                                <td id="isbn"></td>
                                <td id="book-name"></td>
                                <td id="expected-return-date"></td>
                                <td><button type="button" id="submit-return">Return Book</button></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </form>
    </div>

    <!-- Footer Section -->
    <footer>
        <div class="footer-container">
            <!-- Footer content -->
        </div>
    </footer>

    <script>
        // Fetch Return Details
        document.getElementById('get-return-details').addEventListener('click', function() {
            var refNumber = document.getElementById('ref-number').value;

            if (refNumber) {
                fetch('return_book_process.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded'
                    },
                    body: `ref_number=${encodeURIComponent(refNumber)}`
                })
                .then(response => response.json())
                .then(data => {
                    if (data.error) {
                        alert(data.error);
                        return;
                    }

                    // Update the return details section with fetched data
                    document.getElementById('mid').innerText = data.MID || 'N/A';
                    document.getElementById('first-name').innerText = data.first_name || 'N/A';
                    document.getElementById('last-name').innerText = data.last_name || 'N/A';
                    document.getElementById('isbn').innerText = data.ISBN || 'N/A';
                    document.getElementById('book-name').innerText = data.name_of_book || 'N/A';
                    document.getElementById('expected-return-date').innerText = data.expected_date_of_return || 'N/A';
                    document.getElementById('return-details').style.display = 'block';
                })
                .catch(error => {
                    console.error('Error fetching return details:', error);
                });
            } else {
                alert("Please enter a reference number.");
            }
        });

        // Handle Return Book submission
        document.getElementById('submit-return').addEventListener('click', function() {
            var refNumber = document.getElementById('ref-number').value;

            if (refNumber) {
                // Redirect to return_book_update.php with the reference number
                window.location.href = `return_book_update.php?ref_number=${encodeURIComponent(refNumber)}`;
            } else {
                alert("No reference number found.");
            }
        });
    </script>
</body>
</html>
