<?php
include('../includes/config.php');
include('../includes/csrf_token.php');

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// // Check if user is logged in and is an admin
// if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'admin') {
//     header("Location: ../admin/manage-services.php"); // Redirect to login page if not an admin
//     exit();
// }

$service_name = '';
$service_description = '';
$service_price = '';
$service_duration = '';
$service_id = '';
$image_url = '';

// Fetch the service data if we are editing
if (isset($_GET['edit_id'])) {
    $edit_id = intval($_GET['edit_id']);
    $stmt = $conn->prepare("SELECT * FROM services WHERE id = ?");
    $stmt->bind_param("i", $edit_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $service = $result->fetch_assoc();

    if ($service) {
        $service_name = $service['service_name'];
        $service_description = $service['description'];
        $service_price = $service['price'];
        $service_duration = $service['duration'];
        $service_id = $service['id'];
        $image_url = $service['image_url'];
    } else {
        $message = "Service not found.";
    }

    $stmt->close();
}

// Handle form submissions for adding or updating services
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['csrf_token']) && validate_csrf_token($_POST['csrf_token'])) {
        $service_name = trim(htmlspecialchars($_POST['service_name']));
        $service_description = trim(htmlspecialchars($_POST['service_description']));
        $service_price = trim(htmlspecialchars($_POST['service_price']));
        $service_duration = trim(htmlspecialchars($_POST['service_duration']));
        $service_id = isset($_POST['service_id']) ? intval($_POST['service_id']) : null;

        // Handle image upload
        if (isset($_FILES['image_url']) && $_FILES['image_url']['error'] === UPLOAD_ERR_OK) {
            $upload_dir = '../admin/uploads/';
            $upload_file = $upload_dir . basename($_FILES['image_url']['name']);
            if (move_uploaded_file($_FILES['image_url']['tmp_name'], $upload_file)) {
                $image_url = htmlspecialchars(basename($_FILES['image_url']['name']));
            } else {
                $message = "Error uploading image.";
            }
        }

        if ($service_id) {
            // Update existing service
            $stmt = $conn->prepare("UPDATE services SET service_name=?, description=?, price=?, duration=?, image_url=? WHERE id=?");
            $stmt->bind_param("sssssi", $service_name, $service_description, $service_price, $service_duration, $image_url, $service_id);
        } else {
            // Add new service
            $stmt = $conn->prepare("INSERT INTO services (service_name, description, price, duration, image_url) VALUES (?, ?, ?, ?, ?)");
            $stmt->bind_param("sssss", $service_name, $service_description, $service_price, $service_duration, $image_url);
        }
        
        if ($stmt->execute()) {
            $message = "Service updated successfully!";
            $success = true;
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
</head>

<body>
<div class="admin-container">
<?php include('sidebar.php'); ?>
<main class="main-content">
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
        <input type="hidden" name="service_id" id="service_id" value="<?php echo htmlspecialchars($service_id); ?>">

        <label for="service_name">Service Name:</label>
        <input type="text" id="service_name" name="service_name" value="<?php echo htmlspecialchars($service_name); ?>" required>
        <br><br>

        <label for="service_description">Service Description:</label>
        <textarea id="service_description" name="service_description" rows="4" required><?php echo htmlspecialchars($service_description); ?></textarea>
        <br><br>

        <label for="service_price">Service Price:</label>
        <input type="text" id="service_price" name="service_price" value="<?php echo htmlspecialchars($service_price); ?>" required>
        <br><br>

        <label for="service_duration">Service Duration (in minutes):</label>
        <input type="text" id="service_duration" name="service_duration" value="<?php echo htmlspecialchars($service_duration); ?>" required>
        <br><br>

        <label for="image_url">Service Image:</label>
        <input type="file" id="image_url" name="image_url">
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
                <th>Price</th>
                <th>Duration</th>
                <th>Image</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($service = $services_result->fetch_assoc()): ?>
                <tr>
                    <td><?php echo htmlspecialchars($service['id']); ?></td>
                    <td><?php echo htmlspecialchars($service['service_name']); ?></td>
                    <td><?php echo htmlspecialchars($service['description']); ?></td>
                    <td><?php echo htmlspecialchars($service['price']); ?></td>
                    <td><?php echo htmlspecialchars($service['duration']); ?></td>
                    <td>
                        <?php if ($service['image_url']): ?>
                            <img style="height:50px" src="../admin/uploads/<?php echo htmlspecialchars($service['image_url']); ?>" alt="Service Image" class="service-image">
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
    </main>
    <div>
   

</body>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Populate the form with service data for editing
        <?php if (isset($_GET['edit_id'])): ?>
            var service = <?php echo json_encode($service); ?>;
            document.querySelector('#service_id').value = service.id;
            document.querySelector('#service_name').value = service.name;
            document.querySelector('#service_description').value = service.description;
            document.querySelector('#service_price').value = service.price;
            document.querySelector('#service_duration').value = service.duration;
        <?php endif; ?>

        // Clear form fields after a successful operation
        <?php if ($success): ?>
            document.querySelector('#service_id').value = '';
            document.querySelector('#service_name').value = '';
            document.querySelector('#service_description').value = '';
            document.querySelector('#service_price').value = '';
            document.querySelector('#service_duration').value = '';
            document.querySelector('#image_url').value = '';
        <?php endif; ?>
    });
</script>
</html>
