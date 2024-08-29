
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
    <link rel="stylesheet" href="styles.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">

    <style>


    </style>

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

    <!-- About Start -->
    <div class="about-section">
        <div class="about-container">
            <div class="about-row">
                <div class="about-col-img">
                    <div class="about-img-wrapper">
                        <img src="img/about.jpg" class="about-img">
                    </div>
                </div>
                <div class="about-col-text">
                    <div class="about-text-wrapper">
                        <h2 class="about-subtitle">About Us</h2>
                        <h1 class="about-title">We Keep Your Pets Happy All Time</h1>
                    </div>
                    <h4 class="about-description">Diam dolor diam ipsum tempor sit. Clita erat ipsum et lorem stet no
                        labore
                        lorem sit clita duo justo magna dolore</h4>
                    <div class="about-content">
                        <div class="tab-list">
                            <button class="tablinks" onclick="openCity(event, 'Mission')"
                                id="defaultOpen">Mission</button>
                            <button class="tablinks" onclick="openCity(event, 'Vision')">Vision</button>
                        </div>

                        <div id="Mission" class="tabcontent">
                            
                            <p>Tempor erat elitr at rebum at at clita aliquyam consetetur. Diam dolor diam ipsum et,
                                tempor voluptua sit consetetur sit. Aliquyam diam amet diam et eos sadipscing labore.
                                Clita erat ipsum et lorem et sit, sed stet no labore lorem sit. Sanctus clita duo justo
                                et tempor consetetur takimata eirmod, dolores takimata consetetur invidunt magna dolores
                                aliquyam dolores dolore. Amet erat amet et magna</p>
                        </div>

                        <div id="Vision" class="tabcontent">
                            
                            <p>Tempor erat elitr at rebum at at clita aliquyam consetetur. Diam dolor diam ipsum et,
                                tempor voluptua sit consetetur sit. Aliquyam diam amet diam et eos sadipscing labore.
                                Clita erat ipsum et lorem et sit, sed </p>
                        </div>

                        <div id="Tokyo" class="tabcontent">
                            <h3>Tokyo</h3>
                            <p>Tokyo is the capital of Japan.</p>
                        </div>

                        <script>
                            function openCity(evt, cityName) {
                                var i, tabcontent, tablinks;
                                tabcontent = document.getElementsByClassName("tabcontent");
                                for (i = 0; i < tabcontent.length; i++) {
                                    tabcontent[i].style.display = "none";
                                }
                                tablinks = document.getElementsByClassName("tablinks");
                                for (i = 0; i < tablinks.length; i++) {
                                    tablinks[i].className = tablinks[i].className.replace(" active", "");
                                }
                                document.getElementById(cityName).style.display = "block";
                                evt.currentTarget.className += " active";
                            }

                            // Get the element with id="defaultOpen" and click on it
                            document.getElementById("defaultOpen").click();
                        </script>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- About End -->

     <!-- Services Section Start -->
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

     <!-- Footer Section Start -->
     <footer class="footer-section">
        <div class="container">
            <div class="footer-columns">
                <!-- Get in Touch -->
                <div class="footer-column">
                    <h3 class="footer-title"><span class="line"></span>Get In Touch</h3>
                    <p>No dolore ipsum accusam no lorem. Invidunt sed clita kasd clita et et dolor sed dolor</p>
                    <ul class="contact-info">
                        <li><i class="fas fa-map-marker-alt"></i> 123 Street, New York, USA</li>
                        <li><i class="fas fa-envelope"></i> info@example.com</li>
                        <li><i class="fas fa-phone"></i> +012 345 67890</li>
                    </ul>
                </div>

                <!-- Quick Links -->
                <div class="footer-column">
                    <h3 class="footer-title"><span class="line"></span>Quick Links</h3>
                    <ul class="footer-links">
                        <li><a href="#">Home</a></li>
                        <li><a href="#">About Us</a></li>
                        <li><a href="#">Our Services</a></li>
                        <li><a href="#">Contact Us</a></li>
                    </ul>
                </div>

                <!-- Popular Links -->
                <div class="footer-column">
                    <h3 class="footer-title"><span class="line"></span>Popular Links</h3>
                    <ul class="footer-links">
                        <li><a href="#">Home</a></li>
                        <li><a href="#">About Us</a></li>
                        <li><a href="#">Our Services</a></li>
                        <li><a href="#">Contact Us</a></li>
                    </ul>
                </div>

                <!-- Newsletter -->
                <div class="footer-column">
                    <h3 class="footer-title"><span class="line"></span>Newsletter</h3>
                    <form action="#" class="newsletter-form">
                        <input type="email" placeholder="Your Email">
                        <button type="submit">Sign Up</button>
                    </form>
                    <h5>Follow Us</h5>
                    <div class="social-links">
                        <a href="#"><i class="fab fa-twitter"></i></a>
                        <a href="#"><i class="fab fa-facebook-f"></i></a>
                        <a href="#"><i class="fab fa-linkedin-in"></i></a>
                        <a href="#"><i class="fab fa-instagram"></i></a>
                    </div>
                </div>
            </div>
        </div>
        <div class="footer-bottom">
            <p>Terms & Conditions | Privacy Policy | Customer Support | Payments | Help | FAQs</p>
        </div>
    </footer>
    <!-- Footer Section End -->

</body>

</html>