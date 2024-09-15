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
    background-color: #005569 ;
    
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


.profile-dropdown-content a:hover {
    background-color: #f1f1f1;
}

.profile-dropdown:hover .profile-dropdown-content {
    display: block;
}

.profile-dropdown:hover .nav-link {
    background-color: #f1f1f1;
    border-radius: 5px;
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
    background-color: 
    /* Contact Us */

    .contact-container {
    
        display: flex;
        background-color: #ffffff00;
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
        background-color: #005569;
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
    };
   
}

.logout-button:hover {
    background-color: #d32f2f;
}



</style>
<body>
    <nav>
        <div class="nav-brand"> <a href="../dashboard/profile.php" style= "font-size: 34px">Dashboard</a></div>
        <div class="nav-links">
            <a href="../client/index.php" class="nav-link">Home</a>
            <a href="../client/services.php" class="nav-link">Services</a>
            <div class="profile-dropdown">
                <a href="#" class="nav-link">Profile</a>
                <div class="profile-dropdown-content">
                    <a href="../dashboard/update_profile.php">View Profile</a>
                    <a href="../auth/logout.php">Logout</a>
                </div>
            </div>
        </div>
    </nav>
</body>
</html>