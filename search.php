<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Donor Information</title>
    <style>
        /* Your existing CSS styles */

        .hidden-details {
            display: none;
        }

        .hidden-details.show {
            display: table-row;
        }

        .read-more {
            cursor: pointer;
            color: blue;
            text-decoration: underline;
        }

        .btn {
            padding: 5px 10px;
            margin: 5px;
            cursor: pointer;
        }

        .btn-accept {
            background-color: green;
            color: white;
        }

        .btn-reject {
            background-color: red;
            color: white;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th, td {
            border: 1px solid black;
            padding: 10px;
            text-align: left;
        }

        th {
            background-color: #f2f2f2;
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
    $state = $conn->real_escape_string($_POST['state']);
    $district = $conn->real_escape_string($_POST['district']);
    $bloodGroup = $conn->real_escape_string($_POST['bloodType']);
    $patientName = $conn->real_escape_string($_POST['patientName']);
    $contactNumber = $conn->real_escape_string($_POST['contactNumber']);
    $hospital = $conn->real_escape_string($_POST['hospital']);
    $pincode = $conn->real_escape_string($_POST['pincode']);

    // Retrieve data from the 'donor' table based on filters
    $sql = "SELECT `name`, `phone_number`, `State`, `District`, `Pincode`, `bloodgroup`, `email`, `age`, `Health` FROM donor WHERE `State` = '$state' AND `District` = '$district' AND `bloodgroup` = '$bloodGroup' order by age desc";
    $sq = "SELECT COUNT(*) AS NO_OF_AVAILABLE_DONORS FROM donor WHERE `State` = '$state' AND `District` = '$district' AND `bloodgroup` = '$bloodGroup'";
    $sql1="SELECT MAX(age) as Maximum_age from donor";
    
    $result = $conn->query($sql);
    $res = $conn->query($sq);
   $result1=$conn->query($sql1);
    if ($result === false) {
        echo "Error in query: " . $conn->error;
    } else if ($result->num_rows > 0) {
        echo "<h1>Donors Details</h1>";
        if ($row_count = $res->fetch_assoc()) {
            echo "<h2>Number of Available Donors: {$row_count['NO_OF_AVAILABLE_DONORS']}</h2>";
        }
        if($max_age=$result1->fetch_assoc()){
            echo "<h2>Maximum age of Donors: {$max_age['Maximum_age']}</h2>";    
        }
        echo "<table>";
        echo "<tr><th>Name</th><th>Phone Number</th><th>State</th><th>District</th><th>Blood Group</th><th>Read More</th></tr>";
        
        // Output data of each row
        $rowIndex = 0;
        while ($row = $result->fetch_assoc()) {
            echo "<tr>
                    <td>{$row['name']}</td>
                    <td>{$row['phone_number']}</td>
                    <td>{$row['State']}</td>
                    <td>{$row['District']}</td>
                    <td>{$row['bloodgroup']}</td>
                    <td class='read-more' data-index='$rowIndex'>Read More</td>
                  </tr>";
            echo "<tr class='hidden-details' id='details-$rowIndex'>
                    <td colspan='6'>
                        <strong>Pincode:</strong> {$row['Pincode']}<br>
                        <strong>Email:</strong> {$row['email']}<br>
                        <strong>Age:</strong> {$row['age']}<br>
                        <strong>Health:</strong> {$row['Health']}<br>
                        <button class='btn btn-accept' onclick='acceptDonor(\"{$row['email']}\", \"$patientName\", \"$contactNumber\", \"$bloodGroup\", \"$state\", \"$district\", \"$pincode\", \"$hospital\")'>Accept</button>
                        <button class='btn btn-reject' onclick='rejectDonor()'>Reject</button>
                    </td>
                  </tr>";
            $rowIndex++;
        }
        echo "</table>";
    } else {
        echo "<p>No donor records found matching the criteria.</p>";
    }

    // Close connection
    $conn->close();
}
?>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        document.querySelectorAll('.read-more').forEach(function(element) {
            element.addEventListener('click', function() {
                const index = this.getAttribute('data-index');
                const detailsRow = document.getElementById('details-' + index);
                detailsRow.classList.toggle('show');
            });
        });
    });

    function acceptDonor(email, patientName, contactNumber, bloodType, state, district, pincode, hospital) {
        if (confirm("Do you want to accept this donor?")) {
            const xhr = new XMLHttpRequest();
            xhr.open("POST", "send_email.php", true);
            xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
            xhr.onreadystatechange = function () {
                if (xhr.readyState === 4 && xhr.status === 200) {
                    alert("Email sent to the donor.");
                }
            };
            xhr.send("donorEmail=" + encodeURIComponent(email) +
                     "&patientName=" + encodeURIComponent(patientName) +
                     "&contactNumber=" + encodeURIComponent(contactNumber) +
                     "&bloodType=" + encodeURIComponent(bloodType) +
                     "&state=" + encodeURIComponent(state) +
                     "&district=" + encodeURIComponent(district) +
                     "&pincode=" + encodeURIComponent(pincode) +
                     "&hospital=" + encodeURIComponent(hospital));
        }
    }

    function rejectDonor() {
        alert("Donor rejected.");
    }
</script>

</body>
</html>
