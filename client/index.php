
<?php

include('../client/navbar_default.php');
// Include your database configuration file to establish a connection
include('../includes/config.php');

// Fetch services from the database
$query = "SELECT * FROM services";
$services_result = $conn->query($query);

if (!$services_result) {
    die("Error fetching services: " . $conn->error);
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pet Shop</title>
    <link rel="stylesheet" href="../css/styles.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>

<body>
    
    <!-- Hero Start -->
    <div class="hero-header">
        <div class="hero-content">
            <h1>PET SITTER BOOKING </h1>
            <h2>MAKE YOUR PETS HAPPY</h2>
            <p>Dolore tempor clita lorem rebum kasd eirmod dolore diam eos kasd. Kasd clita ea justo est sed kasd erat
                clita sea</p>
            <div class="hero-btn-container">
                <a href="../auth/login.php" class="hero-btn">BOOK NOW</a>
            </div>
        </div>
    </div>
    <!-- Hero End -->

</body>

</html>