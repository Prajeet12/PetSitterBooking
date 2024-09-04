<?php
session_start();

// Check if the user is logged in
if (!isset($_SESSION['customer_id'])) {
    header('Location: ../auth/login.php');
    exit;
}

// Include your database configuration file to establish a connection
include('../includes/config.php');
include('../dashboard/navbar.php');

// Retrieve the logged-in user's ID
$user_id = $_SESSION['customer_id'];

// Fetch the user's details from the database
$query = "SELECT name, email, phone, address, profile_picture FROM customers WHERE id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();
$stmt->close();

// Handle form submission to update user details
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Retrieve form data
    $name = $_POST['name'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $address = $_POST['address'];
    $password = $_POST['password'];
    $profile_picture = $_FILES['profile_picture'];

    // Handle profile picture upload
    $profile_picture_name = $user['profile_picture']; // Default to current picture

    if (!empty($profile_picture['name'])) {
        $target_dir = "../admin/uploads";
        $profile_picture_name = $user_id . '_' . basename($profile_picture['name']);
        $target_file = $target_dir . $profile_picture_name;
        $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

        // Check file size (limit to 2MB)
        if ($profile_picture['size'] > 2000000) {
            echo "Sorry, your file is too large.";
            exit;
        }

        // Allow certain file formats
        if (!in_array($imageFileType, ['jpg', 'jpeg', 'png', 'gif'])) {
            echo "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
            exit;
        }

        // Move the uploaded file to the target directory
        if (!move_uploaded_file($profile_picture['tmp_name'], $target_file)) {
            echo "Sorry, there was an error uploading your file.";
            exit;
        }
    }
    

    // If the password field is not empty, update the password
    if (!empty($password)) {
        $password_hash = password_hash($password, PASSWORD_BCRYPT);
        $query = "UPDATE customers SET name = ?, email = ?, phone = ?, address = ?, password = ?, profile_picture = ? WHERE id = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("ssssssi", $name, $email, $phone, $address, $password_hash, $profile_picture_name, $user_id);
    } else {
        $query = "UPDATE customers SET name = ?, email = ?, phone = ?, address = ?, profile_picture = ? WHERE id = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("sssssi", $name, $email, $phone, $address, $profile_picture_name, $user_id);
    }

    if ($stmt->execute()) {
        // Update session data to reflect changes
        $_SESSION['customer_name'] = $name;
        $_SESSION['customer_email'] = $email;

        // Redirect to the profile page with a success message
        header("Location: ../dashboard/update_profile.php?success=1");
        exit;
    } else {
        echo "Failed to update profile.";
    }
   
    $stmt->close();
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Profile</title>
    <link rel="stylesheet" href="../dashboard/styles.css"> <!-- Link to the CSS file -->
</head>
<body>
    <h1>My Profile</h1>

    <?php if (isset($_GET['success']) && $_GET['success'] == 1): ?>
        <p style="color: green;">Profile updated successfully!</p>
    <?php endif; ?>

    <form action="update_profile.php" method="POST" enctype="multipart/form-data">
        <label for="name">Name:</label>
        <input type="text" id="name" name="name" value="<?php echo htmlspecialchars($user['name']); ?>" required>

        <label for="email">Email:</label>
        <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($user['email']); ?>" required>

        <label for="phone">Phone:</label>
        <input type="text" id="phone" name="phone" value="<?php echo htmlspecialchars($user['phone']); ?>" required>

        <label for="address">Address:</label>
        <input type="text" id="address" name="address" value="<?php echo htmlspecialchars($user['address']); ?>" required>

        <label for="password">Password:</label>
        <input type="password" id="password" name="password" placeholder="Leave blank if not changing">

        <label for="profile_picture">Profile Picture:</label>
        <?php if ($user['profile_picture']): ?>
            <img src="../admin/uploads<?php echo htmlspecialchars($user['profile_picture']); ?>" alt="Profile Picture" style="max-width: 150px;">
        <?php endif; ?>
        <input type="file" id="profile_picture" name="profile_picture" accept="image/*">

        <button type="submit">Update Profile</button>
    </form>
</body>
</html>
