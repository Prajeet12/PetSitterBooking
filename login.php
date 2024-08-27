<?php
include('config.php');
include('csrf_token.php');
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (validate_csrf_token($_POST['csrf_token'])) {
        $username = trim(htmlspecialchars($_POST['username']));
        $password = $_POST['password'];

        $stmt = $conn->prepare("SELECT * FROM users WHERE username=?");
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $result = $stmt->get_result();
        $user = $result->fetch_assoc();

        if ($user && password_verify($password, $user['password'])) {
            $_SESSION['username'] = $user['username'];
            session_regenerate_id(); // Regenerate session ID for security
            echo "Login successful! Welcome, " . htmlspecialchars($user['username']);
        } else {
            echo "Invalid username or password.";
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
    <title>Log In</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div class="form-container">
        <h2>Log In</h2>
        <form action="" method="post">
            <input type="hidden" name="csrf_token" value="<?php echo generate_csrf_token(); ?>">
            <label for="username">Username:</label>
            <input type="text" name="username" required><br>
            
            <label for="password">Password:</label>
            <input type="password" name="password" required><br>
            
            <button type="submit">Log In</button>
        </form>
        <p>Don't have an account? <a href="signup.php">Sign Up</a></p>
    </div>
</body>
</html>
