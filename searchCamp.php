<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Campaign Information</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 20px;
            background-color: #f8f9fa;
        }
        h2 {
            color: #343a40;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
            background-color: #fff;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        th, td {
            border: 1px solid #ddd;
            padding: 12px;
            text-align: left;
        }
        th {
            background-color: #007bff;
            color: white;
        }
        td {
            vertical-align: top;
        }
        .btn {
            display: inline-block;
            padding: 8px 16px;
            margin: 4px 2px;
            background-color: #28a745;
            color: white;
            text-decoration: none;
            border-radius: 4px;
            text-align: center;
            transition: background-color 0.3s;
        }
        .btn:hover {
            background-color: #218838;
        }
    </style>
</head>
<body>

<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $host = "localhost";
    $username = "root";
    $password = "";
    $dbname = "donarconnect"; 

    // Create connection
    $conn = new mysqli($host, $username, $password, $dbname);

    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Sanitize inputs
    $city = $conn->real_escape_string($_POST['city']);

    // Retrieve data from the 'campaigns' table based on city
    $sql = "SELECT `name`, `phone`, `email`, `camName`, `date`, `time`, `City`, `camLoc`, `camDesc` FROM campaigns WHERE `City` = '$city'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        echo "<h2>Nearby Campaigns</h2>";
        echo "<table>";
        echo "<tr><th>Organization Name</th><th>Contact Phone</th><th>Contact Email</th><th>Campaign Name</th><th>Date</th><th>Time</th><th>Location</th><th>Description</th><th>Map</th></tr>";
        
        // Output data of each row
        while($row = $result->fetch_assoc()) {
            $location = urlencode($row['camLoc']);
            $googleMapsUrl = "https://www.google.com/maps/search/?api=1&query={$location}";
            echo "<tr>
                    <td>{$row['name']}</td>
                    <td>{$row['phone']}</td>
                    <td>{$row['email']}</td>
                    <td>{$row['camName']}</td>
                    <td>{$row['date']}</td>
                    <td>{$row['time']}</td>
                    <td>{$row['camLoc']}</td>
                    <td>{$row['camDesc']}</td>
                    <td><a href='$googleMapsUrl' target='_blank' class='btn'>View on Map</a></td>
                  </tr>";
        }
        echo "</table>";
    } else {
        echo "<p>No campaigns found for the selected city.</p>";
    }

    // Close connection
    $conn->close();
}
?>

</body>
</html>
