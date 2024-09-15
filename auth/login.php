<?php
include('../includes/config.php');
include('../includes/csrf_token.php');

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$login_error = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['csrf_token']) && validate_csrf_token($_POST['csrf_token'])) {
        $email = trim(htmlspecialchars($_POST['email']));
        $password = $_POST['password'];

        // Query the customers table to include role
        $stmt = $conn->prepare("SELECT * FROM customers WHERE email=?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();
        $customer = $result->fetch_assoc();

        if ($customer && password_verify($password, $customer['password'])) {
            // Store relevant customer data in session
            $_SESSION['customer_id'] = $customer['id'];
            $_SESSION['customer_name'] = $customer['name'];
            $_SESSION['customer_email'] = $customer['email'];
            $_SESSION['customer_role'] = $customer['role']; // Store role in session
            session_regenerate_id();

            // Redirect based on user role
            if ($customer['role'] === 'admin') {
                header("Location: ../admin/dashboard.php");
            } else {
                header("Location: ../dashboard/profile.php");
            }
            exit();
        } else {
            $login_error = "Invalid email or password.";
        }
        $stmt->close();
    } else {
        die("CSRF token validation failed.");
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="../css/styles.css">
</head>
<style>
    body,
    html {
        margin: 0;
        padding: 0;
        height: 100%;
        font-family: 'Poppins', sans-serif;
    }

    form {
        display: flex;
        flex-direction: column;
    }

    /* Login css */
</style>

<body>
    <div class="log-container">
        <!-- Login Container on the Left -->
        <div class="login-container">
            <h2>Log In</h2>
            <?php if (!empty($login_error)) : ?>
            <div class="error-message">
                <?php echo htmlspecialchars($login_error); ?>
            </div>
            <?php endif; ?>
            <form action="login.php" method="post">
                <label for="email">Email:</label>
                <input type="email" id="email" name="email" required>
                <label for="password">Password:</label>
                <input type="password" id="password" name="password" required>
                <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars(generate_csrf_token()); ?>">
                <button type="submit">Log In</button>
            </form>
            <p><a href="forgot_password.php">Forgot Password?</a></p>
            <p>Don't have an account? <a href="signup.php">Sign Up</a></p>
        </div>

        <!-- Image on the Right -->
        <div class="image-container">

        </div>
    </div>
</body>

</html>