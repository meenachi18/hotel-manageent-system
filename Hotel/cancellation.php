<?php
// Connect to the database
$conn = new mysqli("localhost", "root", "", "hotel_ms");

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$result = null;
$sql = "SELECT booking_id, FName, LName, cancellation_status FROM roombook";

// Prepare the query with a condition for cancellation status, if specified
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $status = $_POST['status'];
    if (!empty($status)) {
        $sql .= " WHERE cancellation_status = ?";
    }
}

// Prepare and execute the query
$stmt = $conn->prepare($sql);

if (!empty($status)) {
    // Bind the status parameter for the prepared statement
    $stmt->bind_param("s", $status);  // "s" is for a string parameter
}

$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Search Booking Cancellation Status</title>
    <style>
        body {
            font-family: Arial, sans-serif;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        table, th, td {
            border: 1px solid black;
        }
        th, td {
            padding: 10px;
            text-align: left;
        }
        select, input[type="submit"] {
            padding: 10px;
            margin: 5px;
        }
    </style>
</head>
<body>
    <h2>Search Booking Cancellation Status</h2>

    <!-- Search form -->
    <form method="POST">
        <label>Select Status:</label>
        <select name="status">
            <option value="">--All--</option>
            <option value="Confirmed" <?php if (isset($status) && $status == "Confirmed") echo "selected"; ?>>Confirmed</option>
            <option value="Cancelled" <?php if (isset($status) && $status == "Cancelled") echo "selected"; ?>>Cancelled</option>
            <option value="Pending" <?php if (isset($status) && $status == "Pending") echo "selected"; ?>>Pending</option>
        </select>
        <input type="submit" value="Search">
    </form>

    <br>

    <!-- Display result table -->
    <table>
        <tr>
            <th>Booking ID</th>
            <th>First Name</th>
            <th>Last Name</th>
            <th>Cancellation Status</th>
        </tr>

        <?php
        // Display results
        if ($result && $result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                echo "<tr>
                        <td>{$row['booking_id']}</td>
                        <td>{$row['FName']}</td>
                        <td>{$row['LName']}</td>
                        <td>{$row['cancellation_status']}</td>
                      </tr>";
            }
        } else {
            echo "<tr><td colspan='4'>No records found.</td></tr>";
        }

        // Close the connection
        $conn->close();
        ?>
    </table>
</body>
</html>