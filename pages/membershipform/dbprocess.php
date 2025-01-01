<?php
// Include database connection
include '../../database-connection/db_connection.php'; 

// Include the mail function
include '../../mailsystem/index.php'; 

// Function to generate a random 15-character alphanumeric string
function generate_temp_password($length = 15) {
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $characters_length = strlen($characters);
    $random_password = '';
    for ($i = 0; $i < $length; $i++) {
        $random_password .= $characters[mt_rand(0, $characters_length - 1)];
    }
    return $random_password;
}

// Function to generate unique membership ID
function generate_membership_id() {
    return 'MID' . str_pad(mt_rand(1, 99999999), 8, '0', STR_PAD_LEFT);
}

// Function to generate unique numeric transaction ID
function generate_tid($length = 12) {
    $characters = '0123456789';
    $characters_length = strlen($characters);
    $random_tid = '';
    for ($i = 0; $length > $i; $i++) {
        $random_tid .= $characters[mt_rand(0, $characters_length - 1)];
    }
    return $random_tid;
}

// Check if the form was submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Step 1: Generate membership_id, temp_password, and tid
    $membership_id = generate_membership_id(); // Generate membership ID
    $temp_password = generate_temp_password(); // Generate 15-character temporary password
    $tid = generate_tid(); // Generate 12-digit Transaction ID

    // Step 2: Get form data and IP address
    $first_name = $_POST['first_name'] ?? '';
    $middle_name = $_POST['middle_name'] ?? '';
    $last_name = $_POST['last_name'] ?? '';
    $gender = $_POST['gender'] ?? '';
    $mobile_number = $_POST['mobile_number'] ?? '';
    $email = $_POST['email'] ?? '';
    $dob = $_POST['dob'] ?? '';
    $occupation = $_POST['occupation'] ?? '';
    $residential_address = $_POST['residential_address'] ?? '';
    $membership_plan = $_POST['membership_plan'] ?? '';
    $membership_plan_id = $_POST['membership_plan_id'] ?? '';
    $membership_amount = $_POST['membership_amount'] ?? '';
    $ip_address = $_SERVER['REMOTE_ADDR'];

    // Sanitize and format the membership amount
    $membership_amount = floatval(preg_replace('/[^\d.]/', '', $membership_amount));

    // Calculate age from DOB
    $dob_object = new DateTime($dob);
    $current_date = new DateTime();  // Current date

    // Calculate submission date, time, and day
    $submission_date = $current_date->format('Y-m-d');
    $submission_time = $current_date->format('H:i:s');
    $submission_day = $current_date->format('l'); // 'l' gives full textual representation of the day

    // Create a DateTime object for next payment date
    $next_payment_date = clone $current_date; 

    switch ($membership_plan) {
        case '1':
            $next_payment_date->modify('+1 month'); // Adds one month
            break;
        case '2':
            $next_payment_date->modify('+6 months'); // Adds six months
            break;
        case '3':
            $next_payment_date->modify('+1 year'); // Adds one year
            break;
        default:
            $next_payment_date = new DateTime(); // Reset to current date if plan is invalid
            break;
    }

    // Format the next payment date
    $next_date_of_transaction = $next_payment_date->format('Y-m-d');

    // Check if the database connection is successful
    if ($conn) {
        // Start a transaction
        $conn->begin_transaction();

        try {
            // Attempt to send mail
            $mail_sent = 'no';
            $mail_sent_status = 'error';

            if (sendMembershipWelcomeEmail($first_name, $email, $membership_id, $temp_password) == true) {
                $mail_sent = 'yes';
                $mail_sent_status = 'sent';
            } else {
                // If mail sending fails, rollback transaction
                $conn->rollback();
                throw new Exception("Failed to send email to: " . $email);
            }

            // Step 3: Insert data into membership_form table
            $stmt = $conn->prepare("INSERT INTO membership_form 
            (first_name, middle_name, last_name, gender, mobile_number, email, dob, age, occupation, residential_address, 
            membership_plan, membership_plan_id, membership_amount, submission_date, submission_day, submission_time, ip_address, 
            membership_id, temp_password, mail_sent, mail_sent_date, mail_sent_status) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");

            if (!$stmt) {
                throw new Exception("Error preparing statement for membership_form: " . $conn->error);
            }

            // Calculate age
            $age = $dob_object->diff($current_date)->y;

            // Bind the parameters (22 placeholders for 22 variables)
            $stmt->bind_param("ssssssssssssssssssssss", 
                $first_name, $middle_name, $last_name, $gender, $mobile_number, 
                $email, $dob, $age, $occupation, $residential_address, 
                $membership_plan, $membership_plan_id, $membership_amount, 
                $submission_date, $submission_day, $submission_time, $ip_address, 
                $membership_id, $temp_password, $mail_sent, $submission_date, $mail_sent_status);

            // Execute the statement
            if (!$stmt->execute()) {
                throw new Exception("Error inserting into membership_form: " . $stmt->error);
            }

            // Step 4: Insert data into Transactions table
            $stmt = $conn->prepare("INSERT INTO Transactions 
            (TID, MID, Amount, Plan, Method, Status, Date_of_Transaction, Time_of_Transaction, Next_Date_of_Transaction, Account_Status) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");

            if (!$stmt) {
                throw new Exception("Error preparing statement for Transactions: " . $conn->error);
            }

            // Prepare the data for the Transactions table
            $status = "Completed";  // Default to completed unless an error occurs
            $date_of_transaction = $submission_date; // Same date as submission
            $time_of_transaction = $submission_time;
            $account_status = "Active"; // Default to Active

            // Bind the parameters for the Transactions table
            $stmt->bind_param("ssssssssss", $tid, $membership_id, $membership_amount, $membership_plan, $payment_method, $status, 
                $date_of_transaction, $time_of_transaction, $next_date_of_transaction, $account_status);

            // Execute the statement for the Transactions table
            if (!$stmt->execute()) {
                throw new Exception("Error inserting into Transactions: " . $stmt->error);
            }

            // Commit the transaction
            $conn->commit();

            echo "Membership form and transaction submitted successfully!";
        } catch (Exception $e) {
            // Rollback transaction in case of error
            $conn->rollback();
            echo "Error: " . $e->getMessage();
        } finally {
            // Close the statement and connection
            $stmt->close();
            $conn->close();
        }
    } else {
        echo "Error: Database connection failed.";
    }
}
?>
