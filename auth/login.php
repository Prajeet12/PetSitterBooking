<?php
include('../includes/config.php');
include('../includes/csrf_token.php');

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['csrf_token']) && validate_csrf_token($_POST['csrf_token'])) {
        $email = trim(htmlspecialchars($_POST['email'])); // Updated to 'email'
        $password = $_POST['password'];

        $stmt = $conn->prepare("SELECT * FROM users WHERE email=?"); // Updated to query by 'email'
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();
        $user = $result->fetch_assoc();

        if ($user && password_verify($password, $user['password'])) {
            $_SESSION['username'] = $user['username'];
            $_SESSION['email'] = $user['email']; // Store email in session
            $_SESSION['role'] = $user['role'];   // Store role in session
            session_regenerate_id(); // Regenerate session ID for security

            // Redirect based on role
            if ($user['role'] === 'admin') {
                header("Location: ../admin/dashboard.php");
            }
             else {
                header("Location: ../dashboard/user_profile.php");
            }
            exit(); // Ensure no further code is executed
        } else {
            echo "Invalid email or password."; // Updated error message
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
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div class="login-container">
        <h2>Log In</h2>
        <form action="login.php" method="post">
    <label for="email">Email:</label>
    <input type="email" id="email" name="email" required><br><br>
    <label for="password">Password:</label>
    <input type="password" id="password" name="password" required><br><br>
    <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars(generate_csrf_token()); ?>">
    <button type="submit">Log In</button>
</form>
        <p><a href="forgot_password.php">Forgot Password?</a></p>
        <p>Don't have an account? <a href="signup.php">Sign Up</a></p>
    </div>
</body>
</html>
