<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'C:/xampp/htdocs/PBL_SourceCode/vendor/autoload.php';

// Connect to the database
include("../../../database-connection/db_connection.php");

// Get form data
$announcement_type = $_POST['announcement-type'];
$subject = $_POST['subject'];
$body = $_POST['body'];
$send_to = $_POST['send-to'];

// Fetch the next sequence number from the database
$sql = "SELECT IFNULL(MAX(sequence_number), 0) + 1 AS next_sequence FROM announcements";
$result = $conn->query($sql);
$row = $result->fetch_assoc();
$sequence_number = $row['next_sequence'];

// Insert announcement into the database
$date = date("Y-m-d");
$sql = "INSERT INTO announcements (announcement_type, subject, body, sequence_number, status)
        VALUES ('$announcement_type', '$subject', '$body', '$sequence_number', 'Not Sent')";
if ($conn->query($sql) === TRUE) {
    $announcement_id = $conn->insert_id; // Get the ID of the newly inserted announcement
} else {
    echo "Error inserting announcement: " . $conn->error;
    exit;
}

// Function to send emails
function sendEmail($email, $subject, $body, $date, $announcement_type, $sequence_number) {
    $mail = new PHPMailer(true);

    try {
        // Server settings
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com'; // Your SMTP server
        $mail->SMTPAuth = true;
        $mail->Username = 'do.not.reply.test.2023@gmail.com'; // Your SMTP username
        $mail->Password = 'pnutrnitqdoslrqr'; // Your SMTP password
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS; // Use TLS if available
        $mail->Port = 587; // Use 587 for TLS

        // Recipients
        $mail->setFrom('do.not.reply.test.2023@gmail.com', 'Information Officer - Books & Co.');
        $mail->addAddress($email); // Add recipient

        // Content
        $mail->isHTML(true); // Set email format to HTML
        
        // Set the subject based on announcement type
        $mail->Subject = "$announcement_type : $subject";

        // Add logo image
        $mail->AddEmbeddedImage(__DIR__ . '/logo-no-background.png', 'logoimg', 'logo-no-background.png');

        // Customize email content
        $mail->Body = "
            <div style='font-family: Arial, sans-serif; padding: 20px;'>
                <div style='text-align: center;'>
                    <img src='cid:logoimg' alt='Books & Co.' style='width: 150px;'><br>
                    <p style='font-size: 12px; color: #888;'>$date</p>
                </div>
                <p><strong>Number: $announcement_type / " . date("Y") . " / $sequence_number</strong></p>
                <hr style='border: 0; border-top: 1px solid #ddd; margin: 20px 0;'>
                <p>Dear Members,</p>
                <p>$body</p>
                <p>Best regards,</p>
                <p><b>The Librarian & Team</b></p>
                
                <br>
                <div style='text-align: left;'>
                    <img src='cid:logoimg' alt='Books & Co.' style='width: 80px;'><br>
                    <p style='font-size: 12px; color: #888;'>Books & Co. | Information Officer</p>
                </div>
                <hr style='border: 0; border-top: 1px solid #ddd; margin: 20px 0;'>

                <p style='font-size: 12px; color: #888; text-align: center;'>Please do not reply to this email. If you have any questions, contact us at support@booksandco.com.</p>
            </div>
        ";

        // Send the email
        $mail->send();
        return true;
    } catch (Exception $e) {
        echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
        return false;
    }
}

// Send to all or individual
if ($send_to === "all") {
    // Query to get all member emails
    $sql = "SELECT m.email 
            FROM membership_form m
            JOIN transactions t ON m.membership_id = t.MID
            WHERE t.account_status = 'Active'";
    $result = $conn->query($sql);
    while ($row = $result->fetch_assoc()) {
        sendEmail($row['email'], $subject, $body, $date, $announcement_type, $sequence_number);
    }
} else {
    // Example for individual email
    $individual_email = 'individual@example.com'; // Replace with actual logic to fetch individual email
    sendEmail($individual_email, $subject, $body, $date, $announcement_type, $sequence_number);
}

// Update the status of the announcement
$sql = "UPDATE announcements SET status='Sent' WHERE id='$announcement_id'";
if ($conn->query($sql) === FALSE) {
    echo "Error updating record: " . $conn->error;
}

// Close connection
$conn->close();

echo "Email sent successfully!";
?>
