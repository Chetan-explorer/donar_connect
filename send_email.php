<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

// Load Composer's autoloader
require 'C:/Users/prane/OneDrive/Desktop/Wave2.0/vendor/autoload.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $donorEmail = $_POST['donorEmail'];
    $patientName = $_POST['patientName'];
    $contactNumber = $_POST['contactNumber'];
    $bloodType = $_POST['bloodType'];
    $state = $_POST['state'];
    $district = $_POST['district'];
    $pincode = $_POST['pincode'];
    $hospital = $_POST['hospital'];

    $mail = new PHPMailer(true);

    try {
        // Server settings
        $mail->SMTPDebug = SMTP::DEBUG_SERVER;                      // Enable verbose debug output
        $mail->isSMTP();                                            // Send using SMTP
        $mail->Host       = 'smtp.gmail.com';                       // Set the SMTP server to send through
        $mail->SMTPAuth   = true;                                   // Enable SMTP authentication
        $mail->Username   = 'desaimansion456@gmail.com';            // SMTP username
        $mail->Password   = 'jcsb voqz ixdq lliz';                  // SMTP password
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;            // Enable implicit TLS encryption
        $mail->Port       = 465;                                    // TCP port to connect to; use 587 if you have set SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS

        // Recipients
        $mail->setFrom('desaimansion456@gmail.com', 'DonorConnect');
        $mail->addAddress($donorEmail);     // Add a recipient

        // Content
        $mail->isHTML(true);                                        // Set email format to HTML
        $mail->Subject = 'Urgent Blood Donation Request';
        $mail->Body    = "
            <h1>Blood Donation Request</h1>
            <p>A blood donation request has been made by:</p>
            <p><strong>Patient Name:</strong> $patientName</p>
            <p><strong>Contact Number:</strong> $contactNumber</p>
            <p><strong>Blood Type Needed:</strong> $bloodType</p>
            <p><strong>Location:</strong> $district, $state, $pincode</p>
            <p><strong>Hospital:</strong> $hospital</p>
        ";
        $mail->AltBody = "Blood Donation Request\n\nPatient Name: $patientName\nContact Number: $contactNumber\nBlood Type Needed: $bloodType\nLocation: $district, $state, $pincode\nHospital: $hospital";

        $mail->send();
        echo 'Message has been sent';
    } catch (Exception $e) {
        echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
    }
}
?>
