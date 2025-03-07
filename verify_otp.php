<?php
session_start();

if (isset($_POST['otp']) && isset($_SESSION['otp'])) {
    if ($_POST['otp'] == $_SESSION['otp']) {
        echo "Verified";
        // Redirect to donorpage.html after successful verification
        
        exit(); // Make sure to exit after the redirect
    }else{
        echo "Invalid OTP!";
    }
} else {
    echo "OTP not found or session expired!";
}
?>
