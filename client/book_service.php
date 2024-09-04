<?php
// Include your database connection file
include('../includes/config.php');
include('../dashboard/navbar.php');

session_start(); // Start the session

// Check if the user is logged in
if (!isset($_SESSION['customer_id'])) {
    // Store the requested page URL to redirect after login
    $_SESSION['redirect_after_login'] = '../client/book_service.php?service_id=' . $_GET['service_id'];
    // Redirect to login page if not logged in
    header('Location: ../auth/login.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Retrieve form data
    $service_id = $_POST['service_id'];
    $customer_name = $_POST['customer_name'];
    $customer_email = $_POST['customer_email'];
    $customer_phone = $_POST['customer_phone'];
    $customer_address = $_POST['customer_address'];
    $booking_date = $_POST['booking_date'];
    $booking_time = $_POST['booking_time'];

    // Check if the customer already exists in the `customers` table
    $query = "SELECT id FROM customers WHERE email = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $customer_email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // Customer exists, get the customer ID
        $customer = $result->fetch_assoc();
        $customer_id = $customer['id'];
    } else {
        // Customer does not exist, insert the customer into the `customers` table
        $query = "INSERT INTO customers (name, email, phone, address, created_at, updated_at) VALUES (?, ?, ?, ?, NOW(), NOW())";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("ssss", $customer_name, $customer_email, $customer_phone, $customer_address);

        if ($stmt->execute()) {
            $customer_id = $stmt->insert_id;
        } else {
            echo "Failed to create customer.";
            exit;
        }
    }

    // Get the service name for booking and insert booking data into the `bookings` table
    $query = "SELECT service_name FROM services WHERE id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $service_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $service = $result->fetch_assoc();
    $service_name = $service['service_name'];

    $query = "INSERT INTO bookings (service_id, service_name, customer_id, customer_name, customer_email, customer_phone, booking_date, booking_time, created_at, updated_at) VALUES (?, ?, ?, ?, ?, ?, ?, ?, NOW(), NOW())";
    $stmt = $conn->prepare($query);

    // Bind parameters: 
    // 'i' for integer (service_id), 
    // 's' for string (service_name, customer_name, customer_email, customer_phone, booking_date, booking_time)
    $stmt->bind_param("isisssss", $service_id, $service_name, $customer_id, $customer_name, $customer_email, $customer_phone, $booking_date, $booking_time);

    if ($stmt->execute()) {
        // Redirect to thank you page with booking details
        header("Location: thank_you.php?service_name=" . urlencode($service_name) . "&customer_name=" . urlencode($customer_name) . "&customer_email=" . urlencode($customer_email) . "&booking_date=" . urlencode($booking_date) . "&booking_time=" . urlencode($booking_time));
        exit;
    } else {
        echo "Failed to book the service.";
    }

    $stmt->close();
    $conn->close();

} else {
    // Retrieve the service details from the database using the service ID from the URL
    if (isset($_GET['service_id'])) {
        $service_id = intval($_GET['service_id']);
        $query = "SELECT * FROM services WHERE id = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("i", $service_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $service = $result->fetch_assoc();
        $stmt->close();
    } else {
        echo "No service ID provided.";
        exit;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Book Service</title>
    <link rel="stylesheet" href="../client/styles.css"> <!-- Link to your external CSS file if any -->
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f4;
        }

        h1 {
            text-align: center;
            margin-top: 20px;
        }

        .container {
            max-width: 800px;
            margin: 0 auto;
            padding: 20px;
            background: #fff;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        form {
            display: flex;
            flex-direction: column;
        }

        label {
            margin-bottom: 5px;
            font-weight: bold;
        }

        input[type="text"],
        input[type="email"],
        input[type="date"],
        input[type="time"] {
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 4px;
            margin-bottom: 15px;
        }

        button {
            padding: 10px 20px;
            background-color: #76b852;
            color: white;
            font-weight: bold;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        button:hover {
            background-color: #669940;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Book Service: <?php echo htmlspecialchars($service['service_name']); ?></h1>
        <form action="book_service.php" method="POST" onsubmit="disableSubmitButton()">
            <input type="hidden" name="service_id" value="<?php echo $service_id; ?>">
            <label for="customer_name">Name:</label>
            <input type="text" name="customer_name" id="customer_name" value="<?php echo htmlspecialchars($_SESSION['customer_name']); ?>" required>
            
            <label for="customer_email">Email:</label>
            <input type="email" name="customer_email" id="customer_email" value="<?php echo htmlspecialchars($_SESSION['customer_email']); ?>" required>
            
            <label for="customer_phone">Phone:</label>
            <input type="text" name="customer_phone" id="customer_phone" required>
            
            <label for="customer_address">Address:</label>
            <input type="text" name="customer_address" id="customer_address" required>
            
            <label for="booking_date">Date:</label>
            <input type="date" name="booking_date" id="booking_date" required>
            
            <label for="booking_time">Time:</label>
            <input type="time" name="booking_time" id="booking_time" required>
            
            <button type="submit" id="submitBtn">Book Now</button>
        </form>
    </div>

    <script>
        function disableSubmitButton() {
            document.getElementById('submitBtn').disabled = true;
        }
        
        // Prevent users from modifying the email field
        document.getElementById('customer_email').addEventListener('focus', function() {
            alert("You cannot change the email address.");
            this.blur();
        });

        // Disable the submit button after form submission to prevent duplicate submissions
        function disableSubmitButton() {
            document.getElementById('submitBtn').disabled = true;
        }
    
    </script>
</body>
</html>
