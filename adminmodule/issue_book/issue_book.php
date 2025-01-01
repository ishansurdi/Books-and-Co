<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Issue Book</title>
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
            <li><a href="../adminmodule.php">Dashboard</a></li>
            <li><a href="inventory.php">Inventory</a></li>
            <li><a href="approval_requests.php">Approval Requests</a></li>
            <li><a href="../return_book/return_book_index.php">Return Books</a></li>
            <li><a href="library_stats.php">Library Stats</a></li>
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

    <!-- Issue Book Section -->
    <div class="issue-book-container">
        <h2>Issue Book</h2>
        
        <!-- Main Form for Issuing Book -->
        <form id="issue-book-form" action="./issue_book_process.php" method="POST">
            
            <!-- Member Details Form -->
            <div class="form-section">
                <h3>Member Details</h3>
                <div class="input-group">
                    <label for="mid">Member ID (MID):</label>
                    <input type="text" id="mid" name="mid" required>
                    <button type="button" id="get-member-details">Get Details</button>
                </div>

                <!-- Member Details Table -->
                <div class="details-section" id="member-details" style="display: none;">
                    <table>
                        <thead>
                            <tr>
                                <th>Member ID</th>
                                <th>First Name</th>
                                <th>Last Name</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>[MID]</td>
                                <td>[First Name]</td>
                                <td>[Last Name]</td>
                                <td>[Status]</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Spacing Between Forms -->
            <div class="spacing"></div>

            <!-- Book Details Form -->
            <div class="form-section">
                <h3>Book Details</h3>
                <div class="input-group">
                    <label for="isbn">Book ISBN:</label>
                    <input type="text" id="isbn" name="isbn" required>
                    <button type="button" id="get-book-details">Get Details</button>
                </div>

                <!-- Book Details Table -->
                <div class="details-section" id="book-details" style="display: none;">
                    <table>
                        <thead>
                            <tr>
                                <th>ISBN</th>
                                <th>Library ID</th>
                                <th>Name</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>[ISBN]</td>
                                <td>[Library ID]</td>
                                <td>[Name]</td>
                                <td>[Status]</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Submit Button for Issuing the Book -->
            <button type="submit" id="issue-button">Issue Book</button>

        </form>
    </div>

    <!-- Footer Section -->
    <footer>
        <div class="footer-container">
            <!-- Footer content -->
        </div>
    </footer>

    <script>
        // Fetch Member Details
        document.getElementById('get-member-details').addEventListener('click', function() {
            var mid = document.getElementById('mid').value;
            if (mid) {
                fetch(`fetch_member_details.php?mid=${mid}`)
                    .then(response => response.json())
                    .then(data => {
                        if (data.error) {
                            alert(data.error);
                            return;
                        }
                        // Update the member details section with fetched data
                        document.querySelector('#member-details table tbody').innerHTML = `
                            <tr>
                                <td>${data.membership_id || 'N/A'}</td>
                                <td>${data.first_name || 'N/A'}</td>
                                <td>${data.last_name || 'N/A'}</td>
                                <td>${data.account_status || 'N/A'}</td>
                            </tr>
                        `;
                        document.getElementById('member-details').style.display = 'block';
                    })
                    .catch(error => {
                        console.error('Error fetching member details:', error);
                    });
                    
            }
        });

        // Fetch Book Details
        document.getElementById('get-book-details').addEventListener('click', function() {
    var isbn = document.getElementById('isbn').value;
    if (isbn) {
        fetch(`fetch_book_details.php?isbn=${isbn}`)
            .then(response => {
                if (!response.ok) {
                    throw new Error('Network response was not ok');
                }
                return response.json();
            })
            .then(data => {
                if (data.error) {
                    alert(data.error);
                    return;
                }
                document.querySelector('#book-details table tbody').innerHTML = `
                    <tr>
                        <td>${data.ISBN || 'N/A'}</td>
                        <td>${data.library_id || 'N/A'}</td>
                        <td>${data.name_of_book || 'N/A'}</td>
                        <td>${data.status || 'N/A'}</td>
                    </tr>
                `;
                document.getElementById('book-details').style.display = 'block';
                document.getElementById('issue-button').disabled = (data.status !== 'Available');
            })
            .catch(error => {
                console.error('Error fetching book details:', error);
                document.getElementById('issue-button').disabled = false; // Enable the button on error
            });
    }
});

    </script>
</body>
</html>
