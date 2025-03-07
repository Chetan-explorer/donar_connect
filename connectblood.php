<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "donarconnect"; 

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
    $name_a = $conn->real_escape_string($_POST['OrganisationName']);
    $password_a = $conn->real_escape_string($_POST['password']);
    $email_a = $conn->real_escape_string($_POST['email']);
    $state_a = $conn->real_escape_string($_POST['state']);
    $district_a = $conn->real_escape_string($_POST['district']);
    $location_link_a = $conn->real_escape_string($_POST['locationLink']);
    $phone_a = $conn->real_escape_string($_POST['contactNumber']);
    
    // Prepare and bind
    $stmt = $conn->prepare("INSERT INTO bloodbank(`Name`, `State`, `district`, `LocationLink`, `password`, `email`, `phone`) VALUES (?, ?, ?, ?, ?, ?, ?)");
    if ($stmt === false) {
        die("Prepare failed: " . $conn->error);
    }
    $stmt->bind_param("sssssss", $name_a, $state_a, $district_a, $location_link_a, $password_a, $email_a, $phone_a);

    // Execute the statement
    if ($stmt->execute()) {
        header("Location: bloodpage.html");
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
