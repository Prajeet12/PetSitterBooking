<?php
session_start(); // Start the session

// Check if the user is logged in

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
    <title>Our Services</title>
    <link rel="stylesheet" href="../css/styles.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>

<body>

    <section class="services-section">
        <div class="container">
            <div class="services-header">
                <div class="about-text-wrapper">
                    <h2 class="services-subtitle">Services</h2>
                    <h1 class="services-title">Our Excellent Pet Care Services</h1>
                </div>
            </div>
            <div class="services-grid">
                <?php
                $row_count = 0;
                while ($service = $services_result->fetch_assoc()):
                    $row_count++;
                    $alignment_class = $row_count % 2 == 0 ? 'service-box-left' : 'service-box-right';
                ?>
                <div class="service-box <?php echo $alignment_class; ?>">
                    <div class="service-content">
                        <div class="service-icon">
                            <img src="../admin/uploads/<?php echo htmlspecialchars($service['image_url']); ?>"
                                alt="<?php echo htmlspecialchars($service['service_name']); ?> Icon">
                        </div>
                        <div class="service-text">
                            <h3 class="service-title"><?php echo htmlspecialchars($service['service_name']); ?></h3>
                            <p class="service-description"><?php echo htmlspecialchars($service['description']); ?></p>
                            <p>Price: $<?php echo htmlspecialchars(number_format($service['price'], 2)); ?></p>
                            <p>Duration: <?php echo htmlspecialchars($service['duration']); ?> minutes</p>
                            <a href="book_service.php?service_id=<?php echo $service['id']; ?>" class="book-now">Book
                                Now</a>
                        </div>
                    </div>
                </div>
                <?php endwhile; ?>
            </div>
        </div>
    </section>
    <!-- Services Section End -->
</body>

</html>
