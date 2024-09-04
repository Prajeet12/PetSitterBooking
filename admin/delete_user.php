<?php
include('../includes/csrf_token.php');    // Include your CSRF token script
include('../includes/config.php');  // Include your database connection

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['csrf_token']) && validate_csrf_token($_POST['csrf_token'])) {
        $user_id = intval($_POST['customer_id']); // Assuming the form sends the user ID as 'user_id'

        // Delete the user from the database
        $stmt = $conn->prepare("DELETE FROM customers WHERE id=?");
        $stmt->bind_param("i", $user_id);

        if ($stmt->execute()) {
            $_SESSION['message'] = "User deleted successfully!";
        } else {
            $_SESSION['error'] = "Failed to delete user.";
        }

        $stmt->close();
        header("Location: ../admin/manage-users.php"); // Redirect back to the manage users page
        exit();
    } else {
        die("CSRF token validation failed.");
    }
}
?>
