<?php
include('db.php');

// Handle Confirm or Cancel actions
if (isset($_GET['action']) && isset($_GET['id'])) {
    $id = intval($_GET['id']);
    $action = $_GET['action'];

    if ($action == 'confirm') {
        $sql = "UPDATE roombook SET stat = 'Confirmed' WHERE id = $id";
    } elseif ($action == 'cancel') {
        $sql = "UPDATE roombook SET stat = 'Cancelled' WHERE id = $id";
    }

    if (isset($sql) && $con->query($sql) !== TRUE) {
        echo "<p style='color:red;'>Error updating booking: " . $con->error . "</p>";
    }
}

// Fetch all bookings
$sql = "SELECT * FROM roombook ORDER BY id DESC";
$result = $con->query($sql);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Booking Management</title>
    <style>
        table { border-collapse: collapse; width: 100%; }
        th, td { padding: 10px; border: 1px solid #ccc; text-align: center; }
        th { background-color: #f2f2f2; }
        a.button { padding: 5px 10px; text-decoration: none; border-radius: 5px; }
        .confirm { background-color: green; color: white; }
        .cancel { background-color: red; color: white; }
    </style>
</head>
<body>

    <h2>All Bookings</h2>

    <table>
        <tr>
            <th>ID</th>
            <th>Name</th>
            <th>Room</th>
            <th>Check-In</th>
            <th>Check-Out</th>
            <th>Status</th>
            <th>Actions</th>
        </tr>

        <?php
        if ($result && $result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                echo "<tr>";
                echo "<td>{$row['id']}</td>";
                echo "<td>{$row['FName']} {$row['LName']}</td>";
                echo "<td>{$row['TRoom']}</td>";
                echo "<td>{$row['cin']}</td>";
                echo "<td>{$row['cout']}</td>";
                echo "<td>{$row['stat']}</td>";
                echo "<td>";

                if ($row['stat'] != 'Confirmed') {
                    echo "<a class='button confirm' href='bookings.php?action=confirm&id={$row['id']}' onclick='return confirm(\"Confirm this booking?\")'>Confirm</a> ";
                }

                if ($row['stat'] != 'Cancelled') {
                    echo "<a class='button cancel' href='bookings.php?action=cancel&id={$row['id']}' onclick='return confirm(\"Cancel this booking?\")'>Cancel</a>";
                }

                echo "</td></tr>";
            }
        } else {
            echo "<tr><td colspan='7'>No bookings found.</td></tr>";
        }
        ?>
    </table>

</body>
</html>