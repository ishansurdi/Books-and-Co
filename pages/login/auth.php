<?php
// File to validate login

session_start();

// Database configuration
include '../../database-connection/db_connection.php'; 

// Initialize error messages
$_SESSION['mid_error'] = '';
$_SESSION['password_error'] = '';
$_SESSION['account_error'] = ''; // To handle suspended account error

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $membership_id = $_POST['MID']; 
    $password = $_POST['password'];

    
    $stmt = $conn->prepare("SELECT m.membership_id, m.temp_password, m.first_name, t.account_status, t.plan AS plan_name, t.amount AS amount, t.next_date_of_transaction AS next_payment_date 
                            FROM membership_form m 
                            JOIN Transactions t ON m.membership_id = t.MID 
                            WHERE m.membership_id = ?");
    $stmt->bind_param("s", $membership_id);

    // Execute and get result
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // Account exists, now fetch user details
        $user_details = $result->fetch_assoc();
        
        // Check if the account is suspended
        echo $user_details['account_status'];
        if ($user_details['account_status'] === 'Suspended') {
            $_SESSION['account_error'] = "Your account is suspended. Please contact support.";
            // Redirect to login page with error message
            header("Location: /PBL_SourceCode/pages/login/login.php");
            exit();
        } else {
            // Proceed to check password if account is not suspended
            if ($password === $user_details['temp_password']) {
                // Password is correct and account is not suspended
                $_SESSION['membership_id'] = $membership_id;
                $_SESSION['first_name'] = $user_details['first_name'];
                $_SESSION['plan_name'] = $user_details['plan_name'];
                $_SESSION['plan_amount'] = $user_details['amount'];
                $_SESSION['next_payment_date'] = $user_details['next_payment_date'];

                // Redirect to dashboard
                header("Location: /PBL_SourceCode/userdashboardmodule/userdashboard.php");
                exit();
            } else {
                $_SESSION['password_error'] = "Invalid password.";
            }
        }
    } else {
        $_SESSION['mid_error'] = "No account found with that MID.";
    }

    // Close the statement
    $stmt->close();
}

// Close the connection
$conn->close();

// Redirect to login page if there was an error
if (!empty($_SESSION['mid_error']) || !empty($_SESSION['password_error']) || !empty($_SESSION['account_error'])) {
    header("Location: /PBL_SourceCode/pages/login/login.php");
    exit();
}
?>
