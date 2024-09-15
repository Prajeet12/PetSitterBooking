<?php
include('../includes/config.php');
include('../includes/csrf_token.php');

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$error_message = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (validate_csrf_token($_POST['csrf_token'])) {
        $username = trim(htmlspecialchars($_POST['username']));
        $email = filter_var(trim($_POST['email']), FILTER_SANITIZE_EMAIL);
        $phone = trim(htmlspecialchars($_POST['phone']));
        $address = trim(htmlspecialchars($_POST['address']));
        $password = password_hash($_POST['password'], PASSWORD_BCRYPT);

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $error_message = "Invalid email format.";
        } else {
            // Begin a transaction to ensure both inserts are successful
            $conn->begin_transaction();

            try {
                // Insert into 'customers' table
                $stmt2 = $conn->prepare("INSERT INTO customers (name, email, phone, address, password) VALUES (?, ?, ?, ?, ?)");
                $stmt2->bind_param("sssss", $username, $email, $phone, $address, $password);
                $stmt2->execute();
                $stmt2->close();

                // Commit the transaction
                $conn->commit();
                
                // Redirect or inform the user about the successful registration
                header("Location: login.php");
                exit();
            } catch (Exception $e) {
                // Rollback the transaction if something failed
                $conn->rollback();
                $error_message = "Error: " . $e->getMessage();
            }
        }
    } else {
        $error_message = "CSRF token validation failed.";
    }
}
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign Up</title>
    <link rel="stylesheet" href="../css/styles.css">
</head>
<style>
    form {
        display: flex;
        flex-direction: column;
    }

    body {
        font-family: Arial, sans-serif;
        background-color: #f4f4f4;
        margin: 0;
        padding: 0;
        display: flex;
        justify-content: center;
        align-items: center;
        height: 100vh;
    }

    .form-container {
        flex: 1;
        max-width: 500px;
        /* Adjust as per your design */
        padding: 30px;
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        background-color: #fff;
        display: flex;
        flex-direction: column;
        justify-content: center;
        margin-left: 500px;
        /* Adjust spacing from the left */
    }

    .error-message {
        color: red;
        margin-bottom: 10px;
    }

    label {
        display: block;
        margin-top: 10px;

    }

    button {
        margin-top: 15px;
        padding: 18px;
        width: 100%;
        background-color: #005569;
        color: white;
        border: none;
        border-radius: 4px;
        cursor: pointer;

    }

    button:hover {
        background-color: #007e9b;
    }

    p {
        margin-top: 25px;
        text-align: left;
    }

    a {
        color: #007bff;
        text-decoration: none;
    }

    a:hover {
        text-decoration: underline;
    }
</style>

<body>
    <div class="log-container">
        <div class="form-container">
            <h2>Sign Up</h2>
            <!-- Display error message if it exists -->
            <?php if (!empty($error_message)) : ?>
            <div class="error-message">
                <?php echo htmlspecialchars($error_message); ?>
            </div>
            <?php endif; ?>
            <form action="" method="post">
                <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars(generate_csrf_token()); ?>">
                <label for="username">Username:</label>
                <input type="text" name="username" required><br>

                <label for="email">Email:</label>
                <input type="email" name="email" required><br>

                <label for="password">Password:</label>
                <input type="password" name="password" required><br>

                <label for="phone">Phone number:</label>
                <input type="number" name="phone" required><br>

                <label for="address">Address:</label>
                <input type="text" name="address" required><br>

                <button type="submit">Sign Up</button>
            </form>
            <p>Already have an account? <a href="login.php">Log In</a></p>
        </div>

        <!-- Image on the Right -->
        <div class="image-container">

        </div>
    </div>

</body>

</html>