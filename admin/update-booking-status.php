<?php
include('../includes/config.php'); // Include database connection

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Fetch the booking ID and new status from the POST request
    $booking_id = intval($_POST['booking_id']);
    $status = $_POST['status'];

    // Update the booking status in the database
    $stmt = $conn->prepare("UPDATE bookings SET status = ? WHERE id = ?");
    $stmt->bind_param('si', $status, $booking_id);

    if ($stmt->execute()) {
        $_SESSION['message'] = "Booking status updated successfully!";
    } else {
        $_SESSION['error'] = "Failed to update booking status.";
    }

    // Redirect back to the manage bookings page
    header("Location: manage-bookings.php");
    exit();
}
?>
