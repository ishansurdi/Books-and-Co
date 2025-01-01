<!-- DONE -->

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Member Details</title>
    <link rel="stylesheet" href="../../css/member_details.css">
    <link rel="stylesheet" href="../../css/issue_book.css">
</head>
<body>
    <!-- Header Section -->
    
    <header>
        <a href="../adminlogin/index.php">
            <div class="logo">
                <img src="../../../images/logofile/png/logo-no-background.png" alt="Library Logo" width="150px" height="50px">
            </div>
        </a>
        <ul class="navbar">
            <li><a href="../../adminmodule.php">Dashboard</a></li>
            <!-- <li><a href="inventory.php">Inventory</a></li>
            <li><a href="approval_requests.php">Approval Requests</a></li>
            <li><a href="return_books.php">Return Books</a></li>
            <li><a href="library_stats.php">Library Stats</a></li> -->
        </ul>
        <div class="profile">
            <img src="../../../images/profilecircle.svg" alt="Profile Icon" width="40px" height="40px">
            <div class="profile-dropdown">
                <a href="view_profile.php">View Profile</a>
                <a href="update_profile.php">Update Profile</a>
                <a href="logout.php">Log Out</a>
            </div>
        </div>
    </header>

    <!-- Member Details Section -->
    <div class="member-details-container">
        <h2>Member Details</h2>
        
        <!-- Member Details Input -->
        <input type="text" id="mid" placeholder="Enter Member ID" />
        <button id="get-member-details">Get Member Details</button>
        
        <!-- Member Details Table -->
        <table>
            <thead>
                <tr>
                    <th>Membership ID</th>
                    <th>First Name</th>
                    <th>Last Name</th>
                    <th>Email</th>
                    <th>Action</th>  <!-- Added Action column header -->
                </tr>
            </thead>
            <tbody>
                <!-- Fetched member details will be inserted here -->
            </tbody>
        </table>
    </div>

    <!-- Footer Section -->
    <footer>
        <div class="footer-container">
            <!-- Footer content -->
        </div>
    </footer>

    <script>
    document.addEventListener('DOMContentLoaded', function () {
    console.log("DOM fully loaded");

    document.getElementById('get-member-details').addEventListener('click', function () {
        console.log("Button clicked");

        const mid = document.getElementById('mid').value;

        if (mid) {
            fetch(`member_details_process.php?mid=${mid}`)
                .then(response => {
                    if (!response.ok) {
                        throw new Error("Network response was not ok");
                    }
                    return response.text();
                })
                .then(text => {
                    console.log('Raw Response:', text);

                    try {
                        const data = JSON.parse(text);

                        if (data.error) {
                            alert(data.error);

                            if (data.show_buttons) {
                                document.querySelector('.member-details-container table tbody').innerHTML = `
                                    <tr>
                                        <td colspan="5">${data.alert_message}</td>
                                    </tr>
                                    <tr>
                                        <td colspan="5">
                                            <button onclick="revokeAccount('${mid}')">Revoke Account</button>
                                            <button onclick="deleteAccount('${mid}')">Delete Account</button>
                                        </td>
                                    </tr>
                                `;
                            }
                            return;
                        }

                        document.querySelector('.member-details-container table tbody').innerHTML = `
                            <tr>
                                <td>${data.membership_id || 'N/A'}</td>
                                <td>${data.first_name || 'N/A'}</td>
                                <td>${data.last_name || 'N/A'}</td>
                                <td>${data.email || 'N/A'}</td>
                                <td>
                                    <button onclick="suspendAccount('${data.membership_id}')">Suspend Account</button>
                                    <button onclick="revokeAccount('${data.membership_id}')">Revoke Account Status</button>
                                    <button onclick="closeAccount('${data.membership_id}')">Close Account</button>
                                </td>
                            </tr>
                        `;
                    } catch (error) {
                        console.error('Error parsing JSON:', error);
                        alert('Error parsing the response. Check the console for details.');
                    }
                })
                .catch(error => {
                    console.error('Fetch Error:', error);
                    alert('An error occurred while fetching member details.');
                });
        } else {
            alert("Please enter a Member ID.");
        }
    });
});



function suspendAccount(membership_id) {
    if (membership_id) {
        fetch(`sap.php?mid=${membership_id}&action=suspend_account`)  // Correct URL with backticks
            .then(response => response.text())
            .then(text => {
                try {
                    const data = JSON.parse(text);

                    if (data.success) {
                        alert(data.message);  // Display success message from PHP
                        // Optionally, update the table or the member's status
                        document.querySelector('.member-details-container table tbody').innerHTML = `
                            <tr>
                                <td colspan="5">Account is suspended for MID ${membership_id}</td>
                            </tr>
                            <tr>
                                <td colspan="5">
                                    <button onclick="revokeAccount('${membership_id}')">Revoke Account</button>
                                    <button onclick="deleteAccount('${membership_id}')">Delete Account</button>
                                </td>
                            </tr>
                        `;
                    } else if (data.error) {
                        alert(data.error);  // Display error message from PHP (e.g., account already suspended)
                        // Handle showing of "Revoke" and "Delete" buttons
                        document.querySelector('.member-details-container table tbody').innerHTML = `
                            <tr>
                                <td colspan="5">Account is suspended for MID ${membership_id}</td>
                            </tr>
                            <tr>
                                <td colspan="5">
                                    <button onclick="revokeAccount('${membership_id}')">Revoke Account</button>
                                    <button onclick="deleteAccount('${membership_id}')">Delete Account</button>
                                </td>
                            </tr>
                        `;
                    } else {
                        alert('Failed to suspend account. Please try again.');
                    }
                } catch (error) {
                    console.error('Error parsing response:', error);
                    alert('Error parsing response: ' + text);  // Alert with raw response
                }
            })
            .catch(error => {
                console.error('Error suspending account:', error);
                alert('Error suspending account. Please try again.');
            });
    } else {
        alert("Member ID is missing.");
    }
}
</script>
</body>
</html>