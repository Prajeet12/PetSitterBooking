<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
} // Start the session to access session variables
?>


<!-- Navbar Start -->
<nav class="navbar">
    <div class="navbar-brand">
        <h1 class="logo"><i class="icon"><img src="../client/img/logo.png" alt="" style="width:250px"></i> </h1>
    </div>
    <div class="navbar-links">
        <a href="index.php"
            class="nav-link <?php echo basename($_SERVER['PHP_SELF']) == 'index.php' ? 'active' : ''; ?>">Home</a>
        <a href="aboutus.php"
            class="nav-link <?php echo basename($_SERVER['PHP_SELF']) == 'aboutus.php' ? 'active' : ''; ?>">About Us</a>
        <a href="services.php"
            class="nav-link <?php echo basename($_SERVER['PHP_SELF']) == 'services.php' ? 'active' : ''; ?>">Our
            Services</a>
        <a href="contactus.php" class="nav-link contact-btn">Contact Us</a>

        <?php if (isset($_SESSION['customer_id'])): ?>

         

      <!-- Profile Icon and Dropdown Start -->
<div class="profile-dropdown">
    <a href="#" class="nav-link profile-icon">
    <?php
// Check if the customer is logged in
if (isset($_SESSION['customer_id'])) {
    include('../includes/config.php');

    // Get the customer_id from the session
    $customer_id = $_SESSION['customer_id'];

    // Fetch the profile picture from the database
    $stmt = $conn->prepare("SELECT profile_picture FROM customers WHERE id = ?");
    if ($stmt) {
        $stmt->bind_param("i", $customer_id);
        $stmt->execute();
        $stmt->bind_result($profile_picture);
        $stmt->fetch();
        $stmt->close();
    }

    // If profile picture is fetched successfully, display it
    if (isset($profile_picture) && !empty($profile_picture)) {
        echo '<img src="../admin/uploads/' . htmlspecialchars($profile_picture) . '" alt="Profile Picture" style="width:60px;height:60px;border-radius:50%;">';
    } else {
        echo "Profile picture not found.";
    }
} else {
    echo "User not logged in.";
}
?>
    </a>
    <div class="profile-dropdown-content">
        <a href="../dashboard/profile.php">View Profile</a>
        <a href="../auth/logout.php">Logout</a>
    </div>
</div>
<!-- Profile Icon and Dropdown End -->
        <?php else: ?>
        <!-- Login Button -->
        <a href="../auth/login.php" class="nav-link contact-btn">Login</a>
        <?php endif; ?>
    </div>
</nav>
<!-- Navbar End -->

<style>
    /* Navbar styles */

    .profile-dropdown {
        position: relative;
    }

    .profile-icon {
        font-size: 24px;
    }

    .profile-dropdown-content {
        display: none;
        position: absolute;
        top: 100%;
        right: 0;
        background-color: #fff;
        color: #000;
        border: 1px solid #ccc;
        border-radius: 4px;
        min-width: 160px;
        z-index: 1000;
    }

    .profile-dropdown-content a {
        color: #000;
        padding: 12px 16px;
        text-decoration: none;
        display: block;
    }

    .profile-dropdown-content a:hover {
        background-color: #f1f1f1;
    }

    .profile-dropdown:hover .profile-dropdown-content {
        display: block;
    }

    .profile-pic {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        object-fit: cover;
        border: 2px solid #FFAD50;
    }

    .icon {
        font-size: 24px;
        color: #fff;
    }
</style>