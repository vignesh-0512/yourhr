<?php
session_start();
include 'db_connection.php'; 
$db = new Database();
$conn = $db->getConnection();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $user_id = $_POST['user_id'];
    $password = $_POST['password'];

    $stmt = $conn->prepare("SELECT password_hash FROM admins WHERE user_id = ?");
    if ($stmt === false) {
        error_log("Failed to prepare SQL statement: " . $conn->error);
        die("An error occurred. Please try again later.");
    }

    $stmt->bind_param("s", $user_id);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows === 1) {
        $stmt->bind_result($stored_password);
        $stmt->fetch();

        error_log("Stored Password: " . $stored_password);
        error_log("Password Provided: " . $password);

        if ($password === $stored_password) {
            $_SESSION['admin_logged_in'] = true;
            $_SESSION['user_id'] = $user_id;
            header('Location: admin_panel.php');
            exit();
        } else {
            $error = "Invalid password.";
        }
    } else {
        $error = "Invalid user ID.";
    }

    $stmt->close();
}

$conn->close();
?>





<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container d-flex justify-content-center align-items-center min-vh-100">
        <div class="login-box bg-white p-4 border rounded shadow-sm">
            <form method="post" action="">
                <div class="form-group">
                    <label for="user_id">User ID:</label>
                    <input type="text" id="user_id" name="user_id" class="form-control" required>
                </div>
                <div class="form-group">
                    <label for="password">Password:</label>
                    <input type="password" id="password" name="password" class="form-control" required>
                </div>
                <div class="text-center">
                    <button type="submit" class="btn btn-primary">Login</button>
                </div>
            </form>
            <?php if (isset($error)) { echo "<p class='text-danger mt-3'>$error</p>"; } ?>
        </div>
    </div>

    <!-- Bootstrap JS and dependencies -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
