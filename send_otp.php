<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'C:/Users/prane/OneDrive/Desktop/Wave2.0/vendor/autoload.php';

if (isset($_POST['email'])) {
    $email = $_POST['email'];
    $otp = rand(100000, 999999);

    // Store OTP in session
    session_start();
    $_SESSION['otp'] = $otp;

    $mail = new PHPMailer(true);

    try {
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->Username = 'desaimansion456@gmail.com';
        $mail->Password = 'jcsb voqz ixdq lliz';
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
        $mail->Port = 465;

        $mail->setFrom('desaimansion456@gmail.com', 'DonorConnect');
        $mail->addAddress($email);

        $mail->isHTML(true);
        $mail->Subject = 'Your OTP Code';
        $mail->Body    = 'Thank for the registration in our website</b>,Your OTP code is <b>' . $otp . '</b>';
        $mail->AltBody = 'Your OTP code is ' . $otp;

        $mail->send();
        echo 'OTP sent';
    } catch (Exception $e) {
        echo "OTP could not be sent. Mailer Error: {$mail->ErrorInfo}";
    }
}
?>
