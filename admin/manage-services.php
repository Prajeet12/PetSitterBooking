<?php
include('../includes/config.php');
include('../includes/csrf_token.php');

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Check if user is logged in and is an admin
if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../login.php"); // Redirect to login page if not an admin
    exit();
}

// Handle form submissions for adding or updating services
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['csrf_token']) && validate_csrf_token($_POST['csrf_token'])) {
        $service_name = trim(htmlspecialchars($_POST['service_name']));
        $service_description = trim(htmlspecialchars($_POST['service_description']));
        $service_id = isset($_POST['service_id']) ? intval($_POST['service_id']) : null;
        $image_url = '';

        // Handle image upload
        if (isset($_FILES['service_image']) && $_FILES['service_image']['error'] === UPLOAD_ERR_OK) {
            $upload_dir = '../uploads/';
            $upload_file = $upload_dir . basename($_FILES['service_image']['name']);
            if (move_uploaded_file($_FILES['service_image']['tmp_name'], $upload_file)) {
                $image_url = htmlspecialchars(basename($_FILES['service_image']['name']));
            } else {
                $message = "Error uploading image.";
            }
        }

        if ($service_id) {
            // Update existing service
            $stmt = $conn->prepare("UPDATE services SET name=?, description=?, image_url=? WHERE id=?");
            $stmt->bind_param("sssi", $service_name, $service_description, $image_url, $service_id);
        } else {
            // Add new service
            $stmt = $conn->prepare("INSERT INTO services (name, description, image_url) VALUES (?, ?, ?)");
            $stmt->bind_param("sss", $service_name, $service_description, $image_url);
        }

        if ($stmt->execute()) {
            $message = "Service updated successfully!";
        } else {
            $message = "Error updating service.";
        }
        $stmt->close();
    } else {
        die("CSRF token validation failed.");
    }
}

// Handle service deletion
if (isset($_GET['delete_id'])) {
    $delete_id = intval($_GET['delete_id']);
    $stmt = $conn->prepare("DELETE FROM services WHERE id=?");
    $stmt->bind_param("i", $delete_id);
    $stmt->execute();
    $stmt->close();
    header("Location: manage-services.php"); // Redirect to refresh the page
    exit();
}

// Fetch services for display
$services_result = $conn->query("SELECT * FROM services");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Services</title>
    <link rel="stylesheet" href="../admin/styles.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin-left: 30px;
            padding: 0;
            display: flex;
        }

        .admin-container {
            display: flex;
            height: 100vh;
            width: 100%;
        }

        .sidebar {
            width: 250px;
            background-color: #333;
            color: #fff;
            padding: 20px;
            position: fixed;
            top: 0;
            left: 0;
            height: 100%;
            box-shadow: 2px 0 5px rgba(0, 0, 0, 0.1);
            overflow-y: auto;
        }

        .sidebar h2 {
            text-align: center;
        }

        .sidebar ul {
            list-style: none;
            padding: 0;
        }

        .sidebar ul li {
            margin: 20px 0;
        }

        .sidebar ul li a {
            color: #fff;
            text-decoration: none;
            display: block;
            padding: 10px;
            background-color: #444;
            border-radius: 4px;
        }

        .sidebar ul li a:hover {
            background-color: #555;
        }

        .main-content {
            margin-left: 250px;
            padding: 20px;
            width: calc(100% - 250px);
            background-color: #fff;
        }

        form {
            margin-bottom: 20px;
            background: #fff;
            padding: 20px;
            border-radius: 5px;
            box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        table, th, td {
            border: 1px solid #ddd;
        }

        th, td {
            padding: 10px;
            text-align: left;
        }

        th {
            background-color: #f2f2f2;
        }

        .message-box {
            border: 1px solid #ddd;
            padding: 10px;
            margin: 10px 0;
            border-radius: 5px;
            background-color: #f9f9f9;
            color: #333;
        }

        .error {
            color: red;
        }

        .success {
            color: green;
        }

        .btn {
            padding: 5px 10px;
            border: none;
            color: #fff;
            cursor: pointer;
            border-radius: 4px;
        }

        .btn-edit {
            background-color: #007bff;
        }

        .btn-delete {
            background-color: #dc3545;
        }

        .btn-edit:hover {
            background-color: #0056b3;
        }

        .btn-delete:hover {
            background-color: #c82333;
        }

        .service-image {
            max-width: 150px;
            height: auto;
        }
    </style>
</head>
<body>
    <div class="admin-container">
        <!-- Sidebar -->
        <div class="sidebar">
            <h2>Admin Menu</h2>
            <ul>
                <li><a href="dashboard.php">Dashboard</a></li>
                <li><a href="manage-services.php">Manage Services</a></li>
                <li><a href="manage-customers.php">Manage Customers</a></li>
                <li><a href="feedback.php">Feedback</a></li>
                <li><a href="../logout.php">Logout</a></li>
            </ul>
        </div>

        <!-- Main Content -->
        <div class="main-content">
            <h2>Manage Services</h2>

            <!-- Display success or error message -->
            <?php if (isset($message)): ?>
                <div class="message-box <?= strpos($message, 'Error') !== false ? 'error' : 'success'; ?>">
                    <?= htmlspecialchars($message); ?>
                </div>
            <?php endif; ?>

            <!-- Form to add or update a service -->
            <form action="manage-services.php" method="post" enctype="multipart/form-data">
                <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars(generate_csrf_token()); ?>">
                <input type="hidden" name="service_id" id="service_id" value="">
                <label for="service_name">Service Name:</label>
                <input type="text" id="service_name" name="service_name" required>
                <br><br>
                <label for="service_description">Service Description:</label>
                <textarea id="service_description" name="service_description" rows="4" required></textarea>
                <br><br>
                <label for="service_image">Service Image:</label>
                <input type="file" id="service_image" name="service_image">
                <br><br>
                <button type="submit" class="btn btn-edit">Save Service</button>
            </form>

            <!-- Table displaying services -->
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Description</th>
                        <th>Image</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($service = $services_result->fetch_assoc()): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($service['id']); ?></td>
                            <td><?php echo htmlspecialchars($service['name']); ?></td>
                            <td><?php echo htmlspecialchars($service['description']); ?></td>
                            <td>
                                <?php if ($service['image_url']): ?>
                                    <img src="../uploads/<?php echo htmlspecialchars($service['image_url']); ?>" alt="Service Image" class="service-image">
                                <?php endif; ?>
                            </td>
                            <td>
                                <a href="manage-services.php?edit_id=<?php echo htmlspecialchars($service['id']); ?>" class="btn btn-edit">Edit</a>
                                <a href="manage-services.php?delete_id=<?php echo htmlspecialchars($service['id']); ?>" class="btn btn-delete">Delete</a>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>

            <script>
                // Script to populate the form with data for editing
                <?php if (isset($_GET['edit_id'])): ?>
                    document.addEventListener('DOMContentLoaded', function() {
                        var serviceId = <?php echo intval($_GET['edit_id']); ?>;
                        var form = document.querySelector('form');
                        form.querySelector('#service_id').value = serviceId;
                        
                        var xhr = new XMLHttpRequest();
                        xhr.open('GET', 'get_service.php?id=' + serviceId, true);
                        xhr.onload = function() {
                            if (xhr.status === 200) {
                                var service = JSON.parse(xhr.responseText);
                                form.querySelector('#service_name').value = service.name;
                                form.querySelector('#service_description').value = service.description;
                                // Handling image display
                                if (service.image_url) {
                                    document.querySelector('#service_image').value = ''; // File input can't be set directly; handle this differently if needed
                                }
                            }
                        };
                        xhr.send();
                    });
                <?php endif; ?>
            </script>
        </div>
    </div>
</body>
</html>
