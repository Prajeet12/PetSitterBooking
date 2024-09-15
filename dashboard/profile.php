<?php
session_start();

// Check if the user is logged in
if (!isset($_SESSION['customer_id'])) {
    header('Location: ../auth/login.php');
    exit;
}

// Include your database configuration file to establish a connection
include('../includes/config.php');
include('../dashboard/navbar.php');

// Retrieve the logged-in user's ID
$user_id = $_SESSION['customer_id'];

// Fetch bookings for the logged-in user
$query = "SELECT b.service_id, s.service_name, b.booking_date, b.booking_time, b.created_at 
          FROM bookings b
          JOIN services s ON b.service_id = s.id
          WHERE b.customer_id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$bookings = $result->fetch_all(MYSQLI_ASSOC);
$stmt->close();
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Profile</title>
    <link rel="stylesheet" href="../css/styles.css"> <!-- Link to the CSS file -->
   
</head>
<style>
      /* Add some styling to the booking table */
  .booking-table {
    width: 100%;
    border-collapse: collapse;
}

.booking-table th, .booking-table td {
    border: 1px solid #ddd;
    padding: 20px;
  
}

.booking-table th {
    background-color: #f4f4f4;
    text-align: left;
}

.booking-table tr:nth-child(even) {
    background-color: #f9f9f9;
}

.booking-table tr:hover {
    background-color: #eaeaea;
}
</style>
<body>
    <h1>My Profile</h1>
    
    <h2>My Bookings</h2>
    
    <table class="booking-table">
        <thead>
            <tr>
            <th>Service ID</th>
                <th>Service Name</th>
                <th>Booking Date</th>
                <th>Booking Time</th>
                
            </tr>
        </thead>
        <tbody>
            <?php if (count($bookings) > 0): ?>
                <?php foreach ($bookings as $booking): ?>
                    <tr>
                    <td><?php echo htmlspecialchars($booking['service_id']); ?></td>
                        <td><?php echo htmlspecialchars($booking['service_name']); ?></td>
                        <td>
                    <?php 
                    $bookingDate = new DateTime($booking['booking_date']); 
                    echo htmlspecialchars($bookingDate->format('F j, Y')); // Output: September 9, 2023
                    ?>
                    </td>
                        <td><?php echo htmlspecialchars($booking['booking_time']); ?></td>
                      
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="4">No bookings found.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</body>
</html>
