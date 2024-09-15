<?php 
include('../client/navbar_default.php');
// Include your database configuration file to establish a connection
include('../includes/config.php');

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get form data
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $subject = trim($_POST['subject']);
    $message = trim($_POST['message']);

    // Validate the inputs
    if (!empty($name) && !empty($email) && !empty($subject) && !empty($message)) {
        // Prepare the SQL query
        $sql = "INSERT INTO feedback (name, email, subject, message) VALUES (?, ?, ?, ?)";
        
        // Prepare the statement
        if ($stmt = $conn->prepare($sql)) {
            // Bind the parameters
            $stmt->bind_param("ssss", $name, $email, $subject, $message);
            
            // Execute the statement
            if ($stmt->execute()) {
                echo "Thank you for your feedback!";
            } else {
                echo "Error: Could not save your feedback. Please try again later.";
            }
            
            // Close the statement
            $stmt->close();
        }
    } else {
        echo "Please fill in all the fields.";
    }

    // Close the database connection
    $conn->close();
}
?>



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact Us</title>
    <link rel="stylesheet" href="../css/styles.css">
</head>
<style>

</style>
<body>
    <div class="contact-container">
        <div class="contact-form">
            <div class="contact-text-wrapper">
                <h2>Contact Us</h2>
                <p>Any question? Just write a message.</p>
            </div>
            <form action="contactus.php" method="POST">
                <input type="text" name="name" placeholder="Full Name" required>
                <input type="email" name="email" placeholder="Your Email" required>
                <input type="text" name="subject" placeholder="Subject" required>
                <textarea name="message" placeholder="Message" required></textarea>
                <button type="submit">Send Message</button>
            </form>
        </div>
        <div class="contact-image">
            <img src="../client/img/hero1.jpg" alt="Dogs">
        </div>
    </div>
    
    
</body>
</html>
