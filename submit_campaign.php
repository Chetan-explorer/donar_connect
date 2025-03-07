<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "wave";

// Enable detailed error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if form data is received
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Get form data and sanitize input
    $name = $conn->real_escape_string($_POST['org_name']);
    $phone = $conn->real_escape_string($_POST['org_phone']);
    $email = $conn->real_escape_string($_POST['org_email']);
    $camName = $conn->real_escape_string($_POST['campaign_name']);
    $date = $conn->real_escape_string($_POST['campaign_date']);
    $time = $conn->real_escape_string($_POST['campaign_time']);
    $camLoc = $conn->real_escape_string($_POST['campaign_location']);
    $camCity = $conn->real_escape_string($_POST['campaign_city']);
    $camDesc = $conn->real_escape_string($_POST['campaign_description']);
    
    // Prepare and bind
    $stmt = $conn->prepare("INSERT INTO campaigns(`name`, `phone`, `email`, `camName`, `date`, `time`, `City`, `camLoc`, `camDesc`) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
    if ($stmt === false) {
        die("Prepare failed: " . $conn->error);
    }
    $stmt->bind_param("sssssssss", $name, $phone, $email, $camName, $date, $time, $camCity, $camLoc, $camDesc);

    // Execute the statement
    if ($stmt->execute()) {
        echo "New record created successfully";
    } else {
        echo "Error: " . $stmt->error;
    }

    // Close the statement and connection
    $stmt->close();
} else {
    echo "No POST data received";
}

$conn->close();
?>
