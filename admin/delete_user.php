<?php
include('../includes/csrf_token.php');    // Include your CSRF token script
include('../includes/config.php');  // Include your database connection

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['csrf_token']) && validate_csrf_token($_POST['csrf_token'])) {
        $user_id = intval($_POST['customer_id']); // Assuming the form sends the user ID as 'customer_id'

        // Fetch the user's role before attempting to delete
        $stmt = $conn->prepare("SELECT role FROM customers WHERE id=?");
        $stmt->bind_param("i", $user_id);
        $stmt->execute();
        $stmt->bind_result($role);
        $stmt->fetch();
        $stmt->close();

        // Check if the user is an admin
        if ($role === 'admin') {
            $_SESSION['error'] = "You cannot delete an admin user.";
            header("Location: ../admin/manage-users.php"); // Redirect back to the manage users page
            exit();
        }

        // If the user is not an admin, proceed to delete the user
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
// Check if there's an error message and display it
if (isset($_SESSION['error'])): ?>
    <div class="alert alert-danger">
        <?php echo htmlspecialchars($_SESSION['error']); ?>
    </div>
    <?php
    // Unset the error message after displaying it so it doesn't show again on reload
    unset($_SESSION['error']);
endif;

// Check if there's a success message and display it
if (isset($_SESSION['message'])): ?>
    <div class="alert alert-success">
        <?php echo htmlspecialchars($_SESSION['message']); ?>
    </div>
    <?php
    // Unset the success message after displaying it
    unset($_SESSION['message']);
endif;
?>

