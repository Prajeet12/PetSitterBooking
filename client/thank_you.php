<?php
// Check if service_name is set in the URL and store it in a variable
$service_name = isset($_GET['service_name']) ? htmlspecialchars($_GET['service_name']) : 'Service name not provided';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Thank You</title>
    
</head>
<link rel="stylesheet" href="../client/styles.css">
<style>
   /* General page styles */
body {
    font-family: Arial, sans-serif;
    background-color: #f4f4f4;
    margin: 0;
    padding: 0;
    text-align: center;
}

/* Container for the thank you message */
.thank-you-container {
    max-width: 800px;
    margin: 50px auto;
    padding: 20px;
    background-color: #fff;
    border-radius: 8px;
    box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
}

/* Heading styles */
h1 {
    color: #007bff;
    margin-bottom: 20px;
}

h2 {
    color: #333;
    margin-bottom: 10px;
}

/* Paragraph styles */
p {
    font-size: 16px;
    line-height: 1.6;
    color: #555;
    margin: 10px 0;
}

/* Strong text styles */
strong {
    color: #333;
}

/* Link styles */
a {
    display: inline-block;
    margin-top: 20px;
    font-size: 16px;
    color: #007bff;
    text-decoration: none;
    border: 1px solid #007bff;
    padding: 10px 20px;
    border-radius: 5px;
    background-color: #f9f9f9;
}

a:hover {
    background-color: #007bff;
    color: #fff;
}

</style>
<body>
    <div class="thank-you-container">
        <h1>Thank You for Your Booking!</h1>
        
        <h2>Booking Details</h2>
        <p><strong>Service Name:</strong> <?php echo htmlspecialchars($_GET['service_name']); ?></p>
        <p><strong>Customer Name:</strong> <?php echo htmlspecialchars($_GET['customer_name']); ?></p>
        <p><strong>Email:</strong> <?php echo htmlspecialchars($_GET['customer_email']); ?></p>
        <p><strong>Booking Date:</strong> <?php echo htmlspecialchars($_GET['booking_date']); ?></p>
        <p><strong>Booking Time:</strong> <?php echo htmlspecialchars($_GET['booking_time']); ?></p>
        
        <a href="services.php">Back to Services</a> 
    </div>
</body>
</html>