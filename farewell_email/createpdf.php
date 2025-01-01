<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Autoload PHPMailer via Composer
require __DIR__ . '/../vendor/autoload.php';
require "../fpdf186/fpdf.php";


/**
 * CHANGE $password variable, if password is changed.
 */
$password = '<Enter your email password>';

function sendFarewellEmail($first_name, $email, $membership_id, $end_date, $address) {
    global $password;
    $mail = new PHPMailer(true); 

    try {
        // Generate PDF Letter
        $pdf_file = __DIR__ . "/farewell_letter_$membership_id.pdf";
        generateGratitudePDF($first_name, $pdf_file, $address); // Changed to use the correct function name

        // Email Setup
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com'; 
        $mail->SMTPAuth = true;
        $mail->Username = '<Enter your email>';
        $mail->Password = $password;
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = 587;

        // $mail->SMTPDebug = 2;  // Enable debug output
        $mail->setFrom('<Enter your email>', 'Office of Lead');
        $mail->addAddress($email, $first_name); 

        if (file_exists($pdf_file)) {
            $mail->addAttachment($pdf_file);
        }

        // // Attach PDF
        // $mail->addAttachment($pdf_file);

        // Content
        $mail->isHTML(true); 
        $mail->Subject = 'A Fond Farewell from Books & Co.';

        // Current date
        $current_date = date('F j, Y');
        $mail->AddEmbeddedImage(__DIR__ . '/logo-no-background.png', 'logoimg', 'logo-no-background.png');

        // Email Body

        $mail->Body = "
        <html>
            <head>
                <meta charset='UTF-8'>
            </head>
            <body>
            <div style='font-family: Arial, sans-serif; padding: 20px;'>
                <div style='text-align: center;'>
                    <img src='cid:logoimg' alt='Books & Co.' style='width: 150px;'><br>
                    <p style='font-size: 12px; color: #888;'>$current_date</p>
                </div>
                <hr style='border: 0; border-top: 1px solid #ddd; margin: 20px 0;'>

                <h2>Goodbye, $first_name!</h2>

                <p>It is with a blend of gratitude and sadness that we bid you farewell as a valued member of Books & Co.. The time we’ve spent together has been nothing short of remarkable, and we are deeply thankful for the joy, learning, and memories we’ve shared through the world of books.</p>


                <p>While our formal association may have come to a close, the spirit of what we’ve experienced together will remain in our hearts. As a token of our appreciation, <b> we have attached a heartfelt farewell letter </b>, which we hope serves as a cherished reminder of the moments we’ve enjoyed together.</p>

                <p>As you move forward on your journey, please know that the doors of <b>Books & Co.<b> will always be open to you. You are forever part of our story, and we will always welcome you back with open arms.</p>
                <p>Best wishes for all that lies ahead, and may your future endeavors be as bright and inspiring as your time with us. </p>

                <p>Warm regards,</p>
                <p>On Behalf Of Books & Co.,</p>
                <p><b>Ishan R. Surdi</b></p>
                <p>Team Lead</p>
                <hr style='border: 0; border-top: 1px solid #ddd; margin: 20px 0;'>

                <p style='font-size: 12px; color: #888; text-align: center;'>Please do not reply to this email. If you have any questions, contact us at support@booksandco.com.</p>
            </div>
            </body>
        </html>
        ";

        // Send Email
        $mail->send();

        // Clean up the PDF file
        unlink($pdf_file);

        return true;
    } catch (Exception $e) {
        echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
    }
}

function generateGratitudePDF($name, $file_path, $address) {
    // Create a new FPDF instance
    $pdf = new FPDF();
    
    // Set standard margins
    $pdf->SetMargins(20, 20, 20); 
    $pdf->AddPage();

    // Add the logo at the top
    $logo_path = __DIR__ . '/logo-no-background.png';
    if (file_exists($logo_path)) {
        $pdf->Image($logo_path, 80, 10, 50); // Center the logo at the top
    }

    // Add header details
    $pdf->SetFont('Arial', 'B', 12);
    $pdf->SetTextColor(0, 0, 0);
    $pdf->Ln(40); // Space after logo
    $pdf->SetFont('Arial', '', 12);
    $pdf->Cell(0, 6, $address, 0, 1); // Address dynamically passed
    $pdf->Ln(10); // Add space after address

    // Add the current date
    $pdf->Cell(0, 6, date('F j, Y'), 0, 1);
    $pdf->Ln(10); // Add space after the date

    // Add greeting
    $pdf->SetFont('Arial', 'B', 12);
    $pdf->Cell(0, 6, "Dear $name,", 0, 1);
    $pdf->Ln(10);

    // Add the body text
    $pdf->SetFont('Arial', '', 12);
    $body = "To Our Cherished Patron and Supporter, $name,\n\n";
    $body .= "With unwavering respect and heartfelt sincerity, we extend our deepest gratitude for the trust, encouragement, and wisdom you have shared throughout the remarkable journey of Books & Co. Your unwavering belief in our mission has been more than just support; it has been a beacon guiding us through every chapter of our story.\n\n";
    $body .= "The insights and constructive feedback you have so graciously offered were never merely suggestions; they were gifts, expressions of care and belief in what we could become. This bond, forged through shared passion and purpose, has been nothing short of extraordinary.\n\n";
    $body .= "Today, it is with a mix of solemn reflection and hopeful anticipation that we announce the temporary suspension of operations at Books & Co. This is not an ending but a purposeful pause, a moment to step back, reflect, and prepare for a more meaningful and invigorated future.\n\n";
    $body .= "This chapter may close, but the essence of Books & Co. endures. Our shared love for knowledge, growth, and connection remains as vibrant as ever. We hold steadfast to the promise of returning to this shared journey with renewed energy and deeper purpose, ready to create something even greater.\n\n";
    $body .= "\"Keep Reading, Keep Discovering, Keep Growing.\"\n\n";
    $body .= "To you,$name, our loyal reader and companion on this journey, we offer our profound thanks. You have been our greatest inspiration and our truest strength. Until the day we meet again, we hold close the memories we have created together and look forward with hope to the chapters yet to come.\n\n";
    $body .= "Warm regards,\n\n";
    $body .= "On behalf Books & Co.\n";
    $body .= "Ishan Surdi \n";
    $body .= "Project Lead";
    
    $pdf->MultiCell(0, 8, $body);

    // Add closing details
    $pdf->Ln(10);
    $pdf->SetFont('Arial', 'B', 12);
    $pdf->Cell(0, 6, "From,", 0, 1);
    $pdf->SetFont('Arial', '', 12);
    $pdf->Cell(0, 6, "Books & Co.", 0, 1);
    $pdf->Ln(10);
    // $pdf->Cell(0, 6, "To,", 0, 1);
    // $pdf->MultiCell(0, 6, $address); // Include the dynamically provided address
    // $pdf->Ln(10);

    // Add footer
    // $pdf->SetTextColor(0, 0, 255); // Set text color to blue for website link
    // $pdf->SetFont('Arial', 'I', 10);
    // $pdf->Cell(0, 6, 'www.booksandco.com', 0, 1, 'C');

    // Output the PDF to a file
    $pdf->Output('F', $file_path);
}

?>
