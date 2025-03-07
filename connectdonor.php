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
    // Check each form input before using it
    $username_a = isset($_POST['name']) ? $conn->real_escape_string($_POST['name']) : '';
    $password_a = isset($_POST['password']) ? $conn->real_escape_string($_POST['password']) : '';
    $phone_number_a = isset($_POST['phone']) ? $conn->real_escape_string($_POST['phone']) : '';
    $state_a = isset($_POST['state']) ? $conn->real_escape_string($_POST['state']) : '';
    $district_a = isset($_POST['district']) ? $conn->real_escape_string($_POST['district']) : '';
    $pincode_a = isset($_POST['pincode']) ? $conn->real_escape_string($_POST['pincode']) : '';
    $bloodgroup_a = isset($_POST['bloodType']) ? $conn->real_escape_string($_POST['bloodType']) : '';
    $email_a = isset($_POST['email']) ? $conn->real_escape_string($_POST['email']) : '';
    $age_a = isset($_POST['age']) ? intval($_POST['age']) : 0; // Ensure age is an integer
    $health_a = isset($_POST['health_issues']) ? $conn->real_escape_string($_POST['health_issues']) : '';
    $weight_a = isset($_POST['weight']) ? $conn->real_escape_string($_POST['weight']) : '';

    // Prepare and bind parameters
    $stmt = $conn->prepare("INSERT INTO donor (`name`, `password`, `phone_number`, `State`, `District`, `Pincode`, `bloodgroup`, `email`, `age`, `Health`, `weight`) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
    if ($stmt === false) {
        die("Prepare failed: " . $conn->error);
    }

    // Bind parameters
    $stmt->bind_param("ssssssssdss", $username_a, $password_a, $phone_number_a, $state_a, $district_a, $pincode_a, $bloodgroup_a, $email_a, $age_a, $health_a, $weight_a);

    // Execute the statement
    if ($stmt->execute()) { 
        header("Location: donorpage.html"); // Redirect after successful insertion
        exit();
    } else {
        echo "Error: " . $stmt->error;
    }

    // Close the statement
    $stmt->close();
} else {
    echo "No POST data received";
}

// Close the database connection
$conn->close();
?>
