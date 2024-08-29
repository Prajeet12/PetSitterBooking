<?php
// Database connection and other necessary includes
require '../includes/config.php';

// Include PHPMailer classes
require '../vendor/phpmailer/phpmailer/src/PHPMailer.php';
require '../vendor/phpmailer/phpmailer/src/Exception.php';
require '../vendor/phpmailer/phpmailer/src/SMTP.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

$message = ''; // Variable to store feedback messages

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    
    // Generate reset token
    $token = bin2hex(random_bytes(50));

    // Insert the token and email into the password_resets table
    $stmt = $conn->prepare("INSERT INTO password_resets (email, token, expiry) VALUES (?, ?, DATE_ADD(NOW(), INTERVAL 1 HOUR))");
    $stmt->bind_param("ss", $email, $token);
    $stmt->execute();

    // Store the token in the users table
    $stmt = $conn->prepare("UPDATE users SET reset_token = ?, reset_expiry = DATE_ADD(NOW(), INTERVAL 1 HOUR) WHERE email = ?");
    $stmt->bind_param("ss", $token, $email);
    $stmt->execute();

    // Send the password reset email
    $mail = new PHPMailer(true);

    try {
        //Server settings
        $mail->isSMTP();
        $mail->Host       = 'smtp.gmail.com';
        $mail->SMTPAuth   = true;
        $mail->Username   = 'prajeetsth3@gmail.com';  // Replace with your email
        $mail->Password   = 'fptf jkfu ewki ojvs';   // Replace with your email password
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port       = 587;

        //Recipients
        $mail->setFrom('your-email@gmail.com', 'Your Name');
        $mail->addAddress($email);

        // Configure the email
        $mail->isHTML(true);
        $mail->Subject = 'Password Reset';
        $mail->Body    = 'Here is the password reset link: 
        <a href="http://localhost/petsitter/auth/reset_password.php?token=' . $token . '">Reset Password</a>';

        $mail->send();
        $message = 'Password reset link has been sent.';
    } catch (Exception $e) {
        $message = "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Forgot Password</title>
    <link rel="stylesheet" href="styles.css">
    <style>
    .login-container {
        width: 300px;
        margin: auto;
        padding: 20px;
        border: 1px solid #ddd;
        border-radius: 5px;
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
    }

    .message {
        margin-bottom: 20px;
        padding: 10px;
        border-radius: 5px;
    }

    .success {
        background-color: #4caf50;
        color: #fff;
    }

    .error {
        background-color: #f44336;
        color: #fff;
    }
    </style>
</head>

<body>
    <div class="login-container">
        <h2>Password Recovery</h2>
        <?php if ($message): ?>
        <div class="message <?php echo strpos($message, 'could not be sent') !== false ? 'error' : 'success'; ?>">
            <?php echo $message; ?>
        </div>
        <?php endif; ?>
        <form action="forgot_password.php" method="post">
            <label for="email">Enter your email address:</label>
            <input type="email" id="email" name="email" required><br><br>
            <button type="submit">Send Recovery Link</button>
        </form>
        <p><a href="login.php">Back to Login</a></p>
    </div>
</body>

</html>