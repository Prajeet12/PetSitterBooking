<?php
include('../includes/csrf_token.php');
include('../includes/config.php');

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['csrf_token']) && validate_csrf_token($_POST['csrf_token'])) {
        $user_id = intval($_POST['customer_id']);
        $name = trim(htmlspecialchars($_POST['name']));
        $email = trim(htmlspecialchars($_POST['email']));
        $role = trim(htmlspecialchars($_POST['role']));

        // Update the user's information in the database
        $stmt = $conn->prepare("UPDATE customers SET name=?, email=?, role=? WHERE id=?");
        $stmt->bind_param("sssi", $name, $email, $role, $customer_id);

        if ($stmt->execute()) {
            $_SESSION['message'] = "User updated successfully!";
        } else {
            $_SESSION['error'] = "Failed to update user.";
        }

        $stmt->close();
        header("Location: ../admin/manage-users.php"); // Redirect back to the manage users page
        exit();
    } else {
        die("CSRF token validation failed.");
    }
}
?>
