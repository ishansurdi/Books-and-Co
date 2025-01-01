<?php
include "../../../mailsystem/index.php";
header('Content-Type: application/json');
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Database connection
$conn = new mysqli('localhost', 'root', '123456789', 'library');

// Check if the connection is successful
if ($conn->connect_error) {
    echo json_encode(['error' => 'Connection failed: ' . $conn->connect_error]);
    exit();
}

// Retrieve MID and action from query string
$mid = $_GET['mid'] ?? '';
$action = $_GET['action'] ?? '';

// Check if MID is provided
if (empty($mid)) {
    echo json_encode(['error' => 'Member ID (MID) is missing']);
    exit();
}

try {
    // Begin transaction
    $conn->begin_transaction();

    if ($action == 'get_details') {
        // Fetch member details
        $sql = "SELECT membership_id, first_name, last_name, email FROM membership_form WHERE membership_id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('s', $mid);
        $stmt->execute();
        $result = $stmt->get_result();
        $member = $result->fetch_assoc();

        if (!$member) {
            echo json_encode(['error' => 'Member not found with MID: ' . $mid]);
            exit();
        }

        // Check the account status in member_details table
        $sql_check_status = "SELECT account_status, last_date_of_appeal FROM member_details WHERE mid = ?";
        $stmt_check = $conn->prepare($sql_check_status);
        $stmt_check->bind_param('s', $mid);
        $stmt_check->execute();
        $status_result = $stmt_check->get_result();
        $status = $status_result->fetch_assoc();

        if ($status && $status['account_status'] === 'Suspended') {
            echo json_encode([
                'error' => 'Account is Suspended for MID: ' . $mid,
                'show_buttons' => true,
                'alert_message' => 'Account is Suspended for MID: ' . $mid
            ]);
        } else {
            echo json_encode([
                'success' => true,
                'member' => $member,
                'status' => $status ? $status['account_status'] : 'Active'
            ]);
        }
    } elseif ($action == 'suspend_account') {
        // Check if account is already suspended
        $sql_check_status = "SELECT account_status FROM member_details WHERE mid = ?";
        $stmt_check = $conn->prepare($sql_check_status);
        $stmt_check->bind_param('s', $mid);
        $stmt_check->execute();
        $status_result = $stmt_check->get_result();
        $status = $status_result->fetch_assoc();

        if ($status && $status['account_status'] === 'Suspended') {
            echo json_encode(['error' => 'Account is already suspended for MID: ' . $mid]);
            exit();
        }

        // Fetch member details for suspension
        $sql = "SELECT first_name, last_name, email FROM membership_form WHERE membership_id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('s', $mid);
        $stmt->execute();
        $result = $stmt->get_result();
        $member = $result->fetch_assoc();

        if (!$member) {
            echo json_encode(['error' => 'Member not found with MID: ' . $mid]);
            exit();
        }

        $first_name = $member['first_name'];
        $last_name = $member['last_name'];
        $email = $member['email'];
        $current_date = date('Y-m-d');
        $current_time = date('H:i:s');
        $last_date_of_appeal = date('Y-m-d', strtotime('+90 days'));
        $appealed = 'No';
        $appealed_on = null;

        // Insert suspension details
        $sql_insert = "INSERT INTO member_details (mid, first_name, last_name, email, account_status, Date_of_update, Time_of_update, last_date_of_appeal, appealed, appealed_on) 
                       VALUES (?, ?, ?, ?, 'Suspended', ?, ?, ?, ?, ?)";
        $stmt_insert = $conn->prepare($sql_insert);
        $stmt_insert->bind_param('sssssssss', $mid, $first_name, $last_name, $email, $current_date, $current_time, $last_date_of_appeal, $appealed, $appealed_on);

        if (!$stmt_insert->execute()) {
            $conn->rollback();
            echo json_encode(['error' => 'Failed to insert member details']);
            exit();
        }

        // Update account status in member_details table
        $sql_update_member = "UPDATE member_details SET account_status = 'Suspended', Date_of_update = ?, Time_of_update = ? WHERE mid = ?";
        $stmt_update_member = $conn->prepare($sql_update_member);
        $stmt_update_member->bind_param('sss', $current_date, $current_time, $mid);

        if (!$stmt_update_member->execute()) {
            $conn->rollback();
            echo json_encode(['error' => 'Failed to update account status']);
            exit();
        }

        // Update account status in transactions table
        $sql_update_transaction = "UPDATE transactions SET account_status = 'Suspended' WHERE MID = ?";
        $stmt_update_transaction = $conn->prepare($sql_update_transaction);
        $stmt_update_transaction->bind_param('s', $mid);

        if (!$stmt_update_transaction->execute()) {
            $conn->rollback();
            echo json_encode(['error' => 'Failed to update account status in Transactions table']);
            exit();
        }

        // Commit transaction
        $conn->commit();

        // Send the suspension email
        $email_sent = accountsuspensionmail($first_name, $email, $current_date, $last_date_of_appeal);

        // Return final response with email status
        echo json_encode([
            'success' => true,
            'message' => 'Account successfully suspended for MID: ' . $mid,
            'email_status' => $email_sent ? 'Suspension email sent.' : 'Error sending suspension email.'
        ]);

    } else {
        echo json_encode(['error' => 'Invalid action specified']);
    }
} catch (Exception $e) {
    $conn->rollback();
    echo json_encode(['error' => 'Transaction failed: ' . $e->getMessage()]);
} finally {
    $conn->close();
}
?>
