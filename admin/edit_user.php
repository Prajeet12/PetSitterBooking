<?php
include('../includes/csrf_token.php');
include('../includes/config.php');

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$user_id = isset($_GET['customer_id']) ? intval($_GET['customer_id']) : 0;

// Fetch user details from the database
$stmt = $conn->prepare("SELECT * FROM customers WHERE id=?");
$stmt->bind_param("i", $customer_id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

if (!$user) {
    $_SESSION['error'] = "User not found.";
    header("Location: ../admin/manage-users.php");
    exit();
}

$stmt->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit User</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div class="admin-container">
        <?php include('sidebar.php'); ?>

        <main class="main-content">
            <h1>Edit User</h1>
            <?php if (isset($_SESSION['error'])): ?>
                <div class="error-message"><?php echo htmlspecialchars($_SESSION['error']); ?></div>
                <?php unset($_SESSION['error']); ?>
            <?php endif; ?>
            <form action="../admin/process_edit_user.php" method="post">
                <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars(generate_csrf_token()); ?>">
                <input type="hidden" name="customer_id" value="<?php echo htmlspecialchars($user['id']); ?>">

                <label for="name">Name:</label>
                <input type="text" id="name" name="name" value="<?php echo htmlspecialchars($user['name']); ?>" required><br>

                <label for="email">Email:</label>
                <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($user['email']); ?>" required><br>

                <label for="role">Role:</label>
                <input type="text" id="role" name="role" value="<?php echo htmlspecialchars($user['role']); ?>" required><br>

                <button type="submit">Save Changes</button>
            </form>
        </main>
    </div>
</body>
</html>
