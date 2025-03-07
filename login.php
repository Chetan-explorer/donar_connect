<?php
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Database configuration
    // Database connection within your laptop
    $host = "localhost";
    $port = "3306"; // MySQL port
    $username = "root";
    $password = "";
    $dbname = "donarconnect"; // Add your actual database name here

    // Create connection
    $conn = new mysqli($host, $username, $password, $dbname,$port);

    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Retrieve form data
    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';
    $loginType = $_POST['loginType'] ?? '';

    // Determine the table based on the login type
    if ($loginType == "Donor") {
        $table = "donor";
        $stmt = $conn->prepare("SELECT password FROM $table WHERE name = ?");
    } elseif ($loginType == "Organization") {
        $table = "organization";
        $stmt = $conn->prepare("SELECT password FROM $table WHERE Name = ?");
    } elseif ($loginType == "bloodbank") {
        $table = "bloodbank"; // Assuming your blood bank table is named 'blood_bank'
        $stmt = $conn->prepare("SELECT password FROM $table WHERE name = ?");
    } else {
        die("Invalid login type specified.");
    }

    if ($stmt === false) {
        die("Prepare failed: " . $conn->error);
    }

    $stmt->bind_param("s", $username);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        $stmt->bind_result($dbStoredPassword);
        $stmt->fetch();

        // Verify password
        if ($password == $dbStoredPassword) { // Direct comparison
            // Set session variables based on login type
            if ($loginType == "Donor" || $loginType == "blood_bank") {
                $_SESSION['username'] = $username;
            } elseif ($loginType == "Hospital") {
                $_SESSION['companyname'] = $username; // Assuming companyname is the same as username
            }

            // Redirect to the appropriate page based on login type
            if ($loginType == "Donor") {
                header("Location: donorpage.html");
            } elseif ($loginType == "Organization") {
                header("Location: orgpage.html");
            } elseif ($loginType == "blood_bank") {
                // include 'blood_bank_page.php'; // Add your blood bank page here
            }
            exit();
        } else {
            echo "Invalid password.";
        }
    } else {
        echo "No user found with that username and login type.";
    }

    $stmt->close();
    $conn->close();
}
?>
