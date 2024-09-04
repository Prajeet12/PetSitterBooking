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
    <link rel="stylesheet" href="../client/styles.css">
</head>
<style>
    /* Contact Us */

.contact-container {
    
    display: flex;
    background-color: #fff;
    width: 100%;
    box-shadow: 0 0 15px rgba(0, 0, 0, 0.1);
    border-radius: 8px;
    overflow: hidden;
    margin-top:50px;
}

.contact-form {
    flex: 1;
    padding: 40px;
    
}

.contact-form h2 {
    margin: 0 0 10px;
    font-size: 44px;
    font-weight: bold;
    color: #333;
    text-align: left;
}

.contact-form p {
    margin: 0 0 20px;
    color: #666;
    text-align:left;
}

.contact-form input,
.contact-form textarea {
    width: 100%;
    padding: 15px;
    margin-bottom: 20px;
    border: 1px solid #ccc;
    border-radius: 5px;
    font-size: 14px;
}

.contact-form button {
    width: 100%;
    padding: 15px;
    background-color: #4CAF50;
    color: #fff;
    border: none;
    border-radius: 5px;
    font-size: 16px;
    cursor: pointer;
}

.contact-form button:hover {
    background-color: #45a049;
}

.contact-image {
    flex: 1;
}

.contact-image img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}
.contact-text-wrapper{
    border-left: 5px solid #7AB730;
    padding-left: 20px;
    margin-bottom: 30px;
}
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
