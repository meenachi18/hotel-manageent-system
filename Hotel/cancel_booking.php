<?php
include('db.php');

// Handle Confirm
if (isset($_GET['confirm_id'])) {
    $id = $_GET['confirm_id'];
    $stmt = $con->prepare("UPDATE roombook SET stat = 'Confirmed' WHERE id = ? AND stat = 'Pending'");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $stmt->close();
}

// Handle Cancel
if (isset($_GET['cancel_id'])) {
    $id = $_GET['cancel_id'];
    $stmt = $con->prepare("UPDATE roombook SET stat = 'Cancelled' WHERE id = ? AND stat != 'Cancelled'");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $stmt->close();
}

// Fetch all bookings
$result = $con->query("SELECT * FROM roombook ORDER BY id DESC");
?>

<h2>Booking Management</h2>
<table border="1" cellpadding="10">
    <tr>
        <th>ID</th><th>Name</th><th>Room</th><th>Check-In</th><th>Check-Out</th><th>Status</th><th>Actions</th>
    </tr>

<?php
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        echo "<tr>
            <td>{$row['id']}</td>
            <td>{$row['FName']} {$row['LName']}</td>
            <td>{$row['TRoom']}</td>
            <td>{$row['cin']}</td>
            <td>{$row['cout']}</td>
            <td>{$row['stat']}</td>
            <td>";

        // Show appropriate actions
        if ($row['stat'] === 'Pending') {
            echo "<a href='?confirm_id={$row['id']}' onclick='return confirm(\"Confirm booking?\")'>Confirm</a> | ";
            echo "<a href='?cancel_id={$row['id']}' onclick='return confirm(\"Cancel booking?\")'>Cancel</a>";
        } elseif ($row['stat'] === 'Confirmed') {
            echo "<a href='?cancel_id={$row['id']}' onclick='return confirm(\"Cancel confirmed booking?\")'>Cancel</a>";
        } else {
            echo "No Action";
        }

        echo "</td></tr>";
    }
} else {
    echo "<tr><td colspan='7'>No bookings found.</td></tr>";
}
?>
</table>

<?php $con->close(); ?>