<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'C:/xampp/htdocs/PBL_SourceCode/vendor/autoload.php';

/**
 * CHANGE $password variable, if password is changed.
 */
$password = 'Enter your email password';


function sendMembershipWelcomeEmail($first_name, $email, $membership_id, $temp_password) {
    global $password;
    $mail = new PHPMailer(true); 

    try {
        // Server settings
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com'; // Your SMTP server
        $mail->SMTPAuth = true;
        $mail->Username = '<Enter your email that will send notifications>'; // Your SMTP username
        $mail->Password = $password; // Your SMTP password
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS; // Use TLS if available
        $mail->Port = 587; 

        // Recipients
        $mail->setFrom('<Enter your email that will send notifications>', 'Books & Co.');
        $mail->addAddress($email, $first_name); // Add recipient

        // Content
        $mail->isHTML(true); // Set email format to HTML
        $mail->Subject = 'Welcome to Books & Co.';

        // Get current date
        $current_date = date('F j, Y');
        $mail->AddEmbeddedImage(__DIR__ . '/logo-no-background.png', 'logoimg', 'logo-no-background.png');

        // Customize email content with letter format
        $mail->Body = "
            <div style='font-family: Arial, sans-serif; padding: 20px;'>
                <div style='text-align: center;'>
                    <img  src='cid:logoimg' alt='Books & Co.' style='width: 150px;'><br>
                    <p style='font-size: 12px; color: #888;'>$current_date</p>
                </div>
                <hr style='border: 0; border-top: 1px solid #ddd; margin: 20px 0;'>

                <h2>Welcome, $first_name!</h2>

                <p>We are pleased to welcome you as our valued member of <b>Books & Co.</b> Thank you for choosing us for your reading adventures.</p>

                <p>Your Membership ID: <b>$membership_id</b></p>
                <p>Your temporary password: <b>$temp_password</b></p>

                <p>Please log in and update your password at your earliest convenience.</p>

                <p>Thank you once again for joining our community. We look forward to providing you with the best reading experiences!</p>

                <p>Best regards,</p>
                <p><b>The Librarian & Team</b></p>

                <div style='text-align: center;'>
                    <img src='cid:logoimg' alt='Books & Co.' style='width: 80px;'><br>
                    <p style='font-size: 12px; color: #888;'>Books & Co. | Admin Team</p>
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
        
    }
}

function sendTransactionDetailsEmail($email, $MID) {
    global $password;
    $mail = new PHPMailer(true); // Use true for exceptions

    try {
        // Fetch transaction details from the database
        $conn = new mysqli('localhost', 'root', '123456789', 'library');
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        // Fetching transaction details based on MID
        $sql = "SELECT Status, Date_of_Transaction, TID, Next_Date_of_Transaction, Amount, Plan, first_name, last_name
                FROM Transactions 
                JOIN membership_form ON Transactions.MID = membership_form.membership_id
                WHERE Transactions.MID = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $MID);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $transaction_status = $row['Status'];
            $date_of_payment = $row['Date_of_Transaction'];
            $transaction_id = $row['TID'];
            $next_payment_date = $row['Next_Date_of_Transaction'];
            $amount = $row['Amount'];
            $plan = $row['Plan'];
            $first_name = $row['first_name'];
            $last_name = $row['last_name'];
        } else {
            throw new Exception('No transaction found for the provided MID.');
        }

        // Close the connection
        $stmt->close();
        $conn->close();

        // Server settings
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com'; // Your SMTP server
        $mail->SMTPAuth = true;
        $mail->Username = '<Enter your email that will send notifications>'; // Your SMTP username
        $mail->Password = $password; // Your SMTP password
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS; // Use TLS if available
        $mail->Port = 587; // Use 587 for TLS

        // Recipients
        $mail->setFrom('<Enter your email that will send notifications>', 'Department of Finance - Books & Co.');
        $mail->addAddress($email); // Add recipient (hardcoded)

        // Get current date
        $current_date = date('F j, Y');
        $mail->AddEmbeddedImage(__DIR__ . '/logo-no-background.png', 'logoimg', 'logo-no-background.png');

        // Content
        $mail->isHTML(true); // Set email format to HTML
        $mail->Subject = 'Transaction Details for Your Membership';

        // Customize email content to match the format of sendMembershipWelcomeEmail
        $mail->Body = "
            <div style='font-family: Arial, sans-serif; padding: 20px;'>
                <div style='text-align: center;'>
                    <img  src='cid:logoimg' alt='Books & Co.' style='width: 150px;'><br>
                    <p style='font-size: 12px; color: #888;'>$current_date</p>
                </div>
                <hr style='border: 0; border-top: 1px solid #ddd; margin: 20px 0;'>

                <h2>Dear $first_name $last_name,</h2>

                <p>Kindly find your transaction details below:</p>

                <ul>
                    <li><b>Transaction Status:</b> $transaction_status</li>
                    <li><b>Date of Payment:</b> $date_of_payment</li>
                    <li><b>Transaction ID (TID):</b> $transaction_id</li>
                    <li><b>Next Payment Date:</b> $next_payment_date</li>
                    <li><b>Amount:</b> ₹$amount</li>
                    <li><b>Plan:</b> $plan</li>
                </ul>

                <p><b>Note:</b> It may take 7-14 days for this transaction to reflect on your portal. In case of any discrepancies or wrong transactions, kindly contact the finance department.</p>
                <p>In the event that you do not receive your MID, kindly reach out to the administrative department promptly to facilitate the mailing process.</p>
                <p>Please keep your Transaction ID (TID) for future reference.</p>

                <p>Best regards,</p>
                <p><b>The Librarian & Team</b></p>

                <div style='text-align: center;'>
                    <img src='cid:logoimg' alt='Books & Co.' style='width: 80px;'><br>
                    <p style='font-size: 12px; color: #888;'>Books & Co. | Department of Finance</p>
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

function sendIssueConfirmationEmail($MID, $bookName, $referenceNumber, $issueDate, $returnDate, $email, $first_name) {
    global $password;
    $mail = new PHPMailer(true);

    try {
        // Server settings
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->Username = '<Enter your email that will send notifications>';
        $mail->Password =  $password;
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = 587;

        // Recipients
        $mail->setFrom('<Enter your email that will send notifications>', 'Admin - Books & Co.');
        $mail->addAddress($email, $first_name);

        // Email content
        $mail->isHTML(true);
        $mail->Subject = 'Book Issue Confirmation';
        $mail->AddEmbeddedImage(__DIR__ . '/logo-no-background.png', 'logoimg', 'logo-no-background.png');

        // Email body
        $mail->Body = "
            <div style='font-family: Arial, sans-serif; padding: 20px;'>
                <div style='text-align: center;'>
                    <img src='cid:logoimg' alt='Books & Co.' style='width: 150px;'><br>
                    <p style='font-size: 16px;'>Hello, $first_name</p>
                </div>
                <p>We are pleased to inform you that the book '<b>$bookName</b>' has been successfully issued to your account.</p>
                <p>Details:</p>
                <ul>
                    <li><b>Reference Number:</b> $referenceNumber</li>
                    <li><b>Issue Date:</b> $issueDate</li>
                    <li><b>Expected Return Date:</b> $returnDate</li>
                </ul>
                <p>Please ensure you return the book by the due date.</p>
                

                <p>Best regards,</p>
                <p><b>The Librarian & Team</b></p>

                <div style='text-align: center;'>
                    <img src='cid:logoimg' alt='Books & Co.' style='width: 80px;'><br>
                    <p style='font-size: 12px; color: #888;'>Books & Co. | Admin</p>
                </div>
                <hr style='border: 0; border-top: 1px solid #ddd; margin: 20px 0;'>

                <p style='font-size: 12px; color: #888; text-align: center;'>Please do not reply to this email. If you have any questions, contact us at support@booksandco.com.</p>
            </div>
        ";

        // Send email
        $mail->send();
        echo 'Return confirmation email sent successfully.';
    } catch (Exception $e) {
        echo "Error sending email: {$mail->ErrorInfo}";
    }
}



function returnConfirmationEmail($MID, $bookName, $referenceNumber, $returnDate, $actualdateofreturn, $email, $first_name, $issueDate) {
    global $password;
    $mail = new PHPMailer(true);

    try {
        // Server settings
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->Username = '<Enter your email that will send notifications>';
        $mail->Password =  'Enter your email password';
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = 587;

        // Recipients
        $mail->setFrom('<Enter your email that will send notifications>', 'Admin - Books & Co.');
        $mail->addAddress($email, $first_name);

        // Email content
        $mail->isHTML(true);
        $mail->Subject = 'Book Return Confirmation';
        $mail->AddEmbeddedImage(__DIR__ . '/logo-no-background.png', 'logoimg', 'logo-no-background.png');

        // Email body
        $mail->Body = "
            <div style='font-family: Arial, sans-serif; padding: 20px;'>
                <div style='text-align: center;'>
                    <img src='cid:logoimg' alt='Books & Co.' style='width: 150px;'><br>
                    <p style='font-size: 16px;'>Hello, $first_name</p>
                </div>
                <p>We are pleased to inform you that the book '<b>$bookName</b>' has been successfully returned  from your account.</p>
                <p>Details:</p>
                <ul>
                    <li><b>Reference Number:</b> $referenceNumber</li>
                    <li><b>Issue Date:</b> $issueDate</li>
                    <li><b>Expected Return Date:</b> $returnDate</li>
                    <li><b> Return Date:</b> $actualdateofreturn</li>
                </ul>
                <p>Note: If the book is returned after the due date, any applicable fines will be reflected in your account.</p>
                <p>We kindly remind you to settle any outstanding fines to avoid restrictions on your account.</p>

                <p>Best regards,</p>
                <p><b>The Librarian & Team</b></p>

                <div style='text-align: center;'>
                    <img src='cid:logoimg' alt='Books & Co.' style='width: 80px;'><br>
                    <p style='font-size: 12px; color: #888;'>Books & Co. | Admin</p>
                </div>
                <hr style='border: 0; border-top: 1px solid #ddd; margin: 20px 0;'>

                <p style='font-size: 12px; color: #888; text-align: center;'>Please do not reply to this email. If you have any questions, contact us at support@booksandco.com.</p>
            </div>
        ";

        // Send email
        $mail->send();
        echo 'Issue confirmation email sent successfully.';
    } catch (Exception $e) {
        echo "Error sending email: {$mail->ErrorInfo}";
    }
}



function accountsuspensionmail($first_name, $email, $current_date, $suspension_date) {
    global $password;
    $mail = new PHPMailer(true);

    try {
        // Server settings
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->Username = '<Enter your email that will send notifications>';
        $mail->Password =  $password;
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = 587;

        // Recipients
        $mail->setFrom('<Enter your email that will send notifications>', 'Information Officer - Books & Co.');
        $mail->addAddress($email, $first_name);

        // Email content
        $mail->isHTML(true);
        $mail->Subject = 'Account Suspension Notification';
        $mail->AddEmbeddedImage(__DIR__ . '/logo-no-background.png', 'logoimg', 'logo-no-background.png');

        // Email body
        $mail->Body = "
            <div style='font-family: Arial, sans-serif; padding: 20px;'>
                <div style='text-align: center;'>
                    <img src='cid:logoimg' alt='Books & Co.' style='width: 150px;'><br>
                    <p style='font-size: 16px;'>Dear, $first_name</p>
                </div>
                <p>I am writing to inform you that your library account has been suspended effective immediately. 
                This action has been taken due to repeated violations of library policies. 
                As a result, your payment amount has been frozen and is not eligible for a refund in accordance with our policies.</p>
                
                <p>You have a period of 90 days to appeal this decision. After this time, your account will be permanently deleted from our systems, 
                and the payment amount will not be refunded. 
                Please be advised that if there are any outstanding penalties, 
                such as non-returned books, we reserve the right to pursue legal action. </p>

                <p>Thank you for your attention to this matter.</p>

                <p>Details: </p>
                <ul>
                    <li><b>Date of Account Suspension: $current_date </b></li>
                    <li><b>Last Date of Appeal: $suspension_date </b></li>
                </ul>
                <p>Best regards,</p>
                <p><b>The Books & Co. Team</b></p>

                <div style='text-align: center;'>
                    <img src='cid:logoimg' alt='Books & Co.' style='width: 80px;'><br>
                    <p style='font-size: 12px; color: #888;'>Books & Co. | Information Officer</p>
                </div>
                <hr style='border: 0; border-top: 1px solid #ddd; margin: 20px 0;'>

                <p style='font-size: 12px; color: #888; text-align: center;'>Please do not reply to this email. If you have any questions, contact us at support@booksandco.com.</p>
            </div>
        ";

        // Send email
        $mail->send();
        return true;
    } catch (Exception $e) {
        echo "Error sending suspension email: {$mail->ErrorInfo}";
        return false;
    }
}



// function testmail($first_name, $email) {
//     global $password;
//     $mail = new PHPMailer(true);

//     try {
//         // Server settings
//         $mail->isSMTP();
//         $mail->Host = 'smtp.gmail.com';
//         $mail->SMTPAuth = true;
//         $mail->Username = '<Enter your email that will send notifications>';
//         $mail->Password = $password;
//         $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
//         $mail->Port = 587;

//         // Recipients
//         $mail->setFrom('<Enter your email that will send notifications>', 'Information Officer - Books & Co.');
//         $mail->addAddress($email, $first_name);

//         // Email content
//         $mail->isHTML(true);
//         $mail->Subject = 'Account Suspension Notification -- Test';
//         $mail->AddEmbeddedImage(__DIR__ . '/logo-no-background.png', 'logoimg', 'logo-no-background.png');

//         // Email body
//         $mail->Body = "
//             <div style='font-family: Arial, sans-serif; padding: 20px;'>
//                 <div style='text-align: center;'>
//                     <img src='cid:logoimg' alt='Books & Co.' style='width: 150px;'><br>
//                     <p style='font-size: 16px;'>Dear, $first_name</p>
//                 </div>
//                 <p>I am writing to inform you that your library account has been suspended effective immediately. 
//                 This action has been taken due to repeated violations of library policies. 
//                 As a result, your payment amount has been frozen and is not eligible for a refund in accordance with our policies.</p>
                
//                 <p>You have a period of 90 days to appeal this decision. After this time, your account will be permanently deleted from our systems, 
//                 and the payment amount will not be refunded. 
//                 Please be advised that if there are any outstanding penalties, 
//                 such as non-returned books, we reserve the right to pursue legal action. </p>

//                 <p>Thank you for your attention to this matter.</p>

//                 <p>Details: </p>
//                 <ul>
//                     <li><b>Date of Account Suspension: 08-11-2024 </b></li>
//                     <li><b>Last Date of Appeal: 08-01-2025 </b></li>
//                 </ul>
//                 <p>Best regards,</p>
//                 <p><b>The Books & Co. Team</b></p>

//                 <div style='text-align: center;'>
//                     <img src='cid:logoimg' alt='Books & Co.' style='width: 80px;'><br>
//                     <p style='font-size: 12px; color: #888;'>Books & Co. | Information Officer</p>
//                 </div>
//                 <hr style='border: 0; border-top: 1px solid #ddd; margin: 20px 0;'>

//                 <p style='font-size: 12px; color: #888; text-align: center;'>Please do not reply to this email. If you have any questions, contact us at support@booksandco.com.</p>
//             </div>
//         ";

//         // Send email
//         $mail->send();
//         return true;
//     } catch (Exception $e) {
//         echo "Error sending suspension email: {$mail->ErrorInfo}";
//         return false;
//     }
// }






