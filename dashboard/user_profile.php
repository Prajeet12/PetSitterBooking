<?php
session_start();

// // Check if the user is logged in
// if (!isset($_SESSION['user_id'])) {
//     header("Location: ../auth/login.php");
//     exit();
// }

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 50px;
        }
        .profile {
            margin-bottom: 20px;
        }
        .logout-button {
            padding: 10px 20px;
            background-color: #f44336;
            color: white;
            border: none;
            cursor: pointer;
        }
    </style>
</head>
<body>
    <h1>Welcome to your Dashboard</h1>

    <div class="profile">
        <h2>User Profile</h2>
        <p><strong>Name:</strong> <?php echo htmlspecialchars($_SESSION['username']); ?></p>
        <p><strong>Email:</strong> <?php echo htmlspecialchars($_SESSION['email']); ?></p>
    </div>

    <form action="../auth/logout.php" method="post">
        <button type="submit" class="logout-button">Logout</button>
    </form>
</body>
</html>
