<?php
// manage-bookings.php
include('../includes/config.php');   // Database connection

// Check if a search term is provided
$searchTerm = '';
if (isset($_GET['search'])) {
    $searchTerm = trim($_GET['search']);
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Bookings</title>
    <link rel="stylesheet" href="../admin/styles.css">
</head>

<body>
    <div class="admin-container">
        <?php include('sidebar.php'); ?>

        <main class="main-content">
            <h1>Manage Bookings</h1>

            <!-- Search bar form -->
            <form method="GET" action="manage-bookings.php" style="margin-bottom: 20px;">
                <input type="text" name="search" placeholder="Search by customer name or phone" value="<?php echo htmlspecialchars($searchTerm); ?>">
                <button type="submit">Search</button>
            </form>

            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Customer</th>
                        <th>Service</th>
                        <th>Phone</th>
                        <th>Date</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    // Modify query based on search term
                    if (!empty($searchTerm)) {
                        $stmt = $conn->prepare("SELECT * FROM bookings WHERE customer_name LIKE ? OR customer_phone LIKE ?");
                        $searchWildcard = "%" . $searchTerm . "%";
                        $stmt->bind_param("ss", $searchWildcard, $searchWildcard);
                    } else {
                        $stmt = $conn->prepare("SELECT * FROM bookings");
                    }

                    $stmt->execute();
                    $result = $stmt->get_result();

                    if ($result->num_rows > 0) {
                        while ($row = $result->fetch_assoc()) {
                            echo "<tr>";
                            echo "<td>" . $row['id'] . "</td>";
                            echo "<td>" . $row['customer_name'] . "</td>";
                            echo "<td>" . $row['service_name'] . "</td>";
                            echo "<td>" . $row['customer_phone'] . "</td>";
                            
                            // Format the booking date
                            $bookingDate = new DateTime($row['booking_date']);
                            echo "<td>" . $bookingDate->format('F j, Y') . "</td>";
                            
                            // Display status with a dropdown for update
                            echo "<td>";
                            echo "<form action='update-booking-status.php' method='POST'>";
                            echo "<input type='hidden' name='booking_id' value='" . $row['id'] . "'>";
                            echo "<select name='status' onchange='this.form.submit()'>";
                            echo "<option value='Pending'" . ($row['status'] == 'Pending' ? ' selected' : '') . ">Pending</option>";
                            echo "<option value='Completed'" . ($row['status'] == 'Completed' ? ' selected' : '') . ">Completed</option>";
                            echo "</select>";
                            echo "</form>";
                            echo "</td>";
                            
                            echo "<td><a href='edit-booking.php?id=" . $row['id'] . "'>Edit</a> | <a href='delete-booking.php?id=" . $row['id'] . "'>Delete</a></td>";
                            echo "</tr>";
                        }
                    } else {
                        echo "<tr><td colspan='7'>No bookings found.</td></tr>";
                    }

                    $stmt->close();
                    ?>
                </tbody>
            </table>
        </main>
    </div>
</body>

</html>
