<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    
</head>
<style>
    body {
    font-family: Arial, sans-serif;
    margin: 0;
    padding: 0;
    background-color:#f4f4f4;
}

nav {
    background-color: #7AB730;
    
    color: white;
    padding: 20px ;
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.nav-brand {
    font-size: 24px;
    font-weight: bold;
}

.nav-links {
    display: flex;
    gap: 20px;
}

.nav-link {
    color: white;
    text-decoration: none;
   
    transition: background-color 0.3s;
}



.profile-dropdown {
    position: relative;
    display: inline-block;
}

.profile-dropdown-content {
    display: none;
    position: absolute;
    right: 0;
    background-color: #f9f9f9;
    min-width: 160px;
    box-shadow: 0px 8px 16px 0px rgba(0,0,0,0.2);
    z-index: 1;
}

.profile-dropdown-content a {
    color: black;
    padding: 12px 16px;
    text-decoration: none;
    display: block;
}




.dashboard-container {
    margin: 50px;
}

.profile {
    margin-bottom: 20px;
    background-color: white;
    padding: 20px;
    border-radius: 8px;
    box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.1);
}

.logout-button {
    padding: 10px 20px;
    background-color: #f44336;
    color: white;
    border: none;
    cursor: pointer;
    border-radius: 5px;
    transition: background-color 0.3s;
}

.logout-button:hover {
    background-color: #d32f2f;
}

</style>
<body>
    <nav>
        <div class="nav-brand">Dashboard</div>
        <div class="nav-links">
            <a href="../client/index.php" class="nav-link">Home</a>
            <a href="../client/services.php" class="nav-link">Services</a>
            <div class="profile-dropdown">
                <a href="#" class="nav-link">Profile</a>
                <div class="profile-dropdown-content">
                    <a href="../dashboard/profile.php">View Profile</a>
                    <a href="../auth/logout.php">Logout</a>
                </div>
            </div>
        </div>
    </nav>
</body>
</html>