<?php
// dashboard.php

include('../includes/config.php');   // Database connection

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="../admin/styles.css">
</head>
<body>
    <div class="admin-container">
        <?php include('../admin/sidebar.php'); ?> <!-- Sidebar -->

        <main class="main-content">
            <h1>Dashboard</h1>
            <p>Welcome to the admin dashboard!</p>
            <div class="stats">
                <!-- Display some statistics like number of users, bookings, feedbacks -->
                <div class="stat">
                    <h3>Total Users</h3>
                    <p>
                        <?php
                        // Example query to count users
                        $result = $conn->query("SELECT COUNT(*) as total_users FROM customers");
                        $row = $result->fetch_assoc();
                        echo $row['total_users'];
                        ?>
                    </p>
                </div>
                <div class="stat">
                    <h3>Total Bookings</h3>
                    <p>
                        <?php
                        // Example query to count bookings
                        $result = $conn->query("SELECT COUNT(*) as total_bookings FROM bookings");
                        $row = $result->fetch_assoc();
                        echo $row['total_bookings'];
                        ?>
                    </p>
                </div>
                <div class="stat">
                    <h3>Total Feedback</h3>
                    <p>
                        <?php
                        // Example query to count feedback
                        $result = $conn->query("SELECT COUNT(*) as total_feedback FROM feedback");
                        $row = $result->fetch_assoc();
                        echo $row['total_feedback'];
                        ?>
                    </p>
                </div>
            </div>
        </main>
    </div>
</body>
</html>
