<aside class="sidebar">
    <h2>Admin Panel</h2>
    <ul>
        <li><a href="dashboard.php" class="<?php echo basename($_SERVER['PHP_SELF']) == 'dashboard.php' ? 'active' : ''; ?>">Dashboard</a></li>
        <li><a href="manage-users.php" class="<?php echo basename($_SERVER['PHP_SELF']) == 'manage-users.php' ? 'active' : ''; ?>">Manage Users</a></li>
        <li><a href="manage-bookings.php" class="<?php echo basename($_SERVER['PHP_SELF']) == 'manage-bookings.php' ? 'active' : ''; ?>">Manage Bookings</a></li>
        <li><a href="feedback.php" class="<?php echo basename($_SERVER['PHP_SELF']) == 'feedback.php' ? 'active' : ''; ?>">Feedback & Suggestions</a></li>
        <li><a href="../admin/manage-services.php" class="<?php echo basename($_SERVER['PHP_SELF']) == 'manage-services.php' ? 'active' : ''; ?>">Manage Services</a></li>
        <li><a href="../auth/logout.php">Logout</a></li>
    </ul>
</aside>