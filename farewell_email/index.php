<?php
require 'C:/xampp/htdocs/PBL_SourceCode/vendor/autoload.php'; // Autoload PHPMailer and dependencies
require 'createpdf.php'; // Include the function definitions

$first_name = '<fname>';
$email = '<email>';
$membership_id = '';
$end_date = 'December 31, 2024';
$address = ' India';

if (sendFarewellEmail($first_name, $email, $membership_id, $end_date, $address)) {
    echo "Farewell email sent successfully to $email.";
} else {
    echo "Failed to send the farewell email.";
}
?>
