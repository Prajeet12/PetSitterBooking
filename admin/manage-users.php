<?php
// manage-users.php

include('../includes/config.php');   // Database connection

// if (session_status() === PHP_SESSION_NONE) {
//     session_start();
// }

include('../includes/csrf_token.php');  // CSRF token generation and validation

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Users</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div class="admin-container">
        <?php include('sidebar.php'); ?>

        <main class="main-content">
            <h1>Manage Users</h1>
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $result = $conn->query("SELECT * FROM customers");
                    while($row = $result->fetch_assoc()) {
                        echo "<tr>";
                        echo "<td>" . htmlspecialchars($row['id']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['name']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['email']) . "</td>";
                        echo "<td>";
                        // Edit form
                        echo "<form action='../admin/edit_user.php' method='post' style='display:inline;'>";
                        echo "<input type='hidden' name='csrf_token' value='" . htmlspecialchars(generate_csrf_token()) . "'>";
                        echo "<input type='hidden' name='customer_id' value='" . htmlspecialchars($row['id']) . "'>";
                        echo "<input type='hidden' name='name' value='" . htmlspecialchars($row['name']) . "'>";
                        echo "<input type='hidden' name='email' value='" . htmlspecialchars($row['email']) . "'>";
                        echo "<button type='submit'>Edit</button>";
                        echo "</form> ";

                        // Delete form
                        echo "<form action='../admin/delete_user.php' method='post' style='display:inline;'>";
                        echo "<input type='hidden' name='csrf_token' value='" . htmlspecialchars(generate_csrf_token()) . "'>";
                        echo "<input type='hidden' name='customer_id' value='" . htmlspecialchars($row['id']) . "'>";
                        echo "<button type='submit' onclick='return confirm(\"Are you sure you want to delete this user?\");'>Delete</button>";
                        echo "</form>";

                        echo "</td>";
                        echo "</tr>";
                    }
                    ?>
                </tbody>
            </table>
        </main>
    </div>
</body>
</html>
