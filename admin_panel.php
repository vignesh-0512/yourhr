<?php
session_start();
include 'db_connection.php'; 
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header('Location: login.php');
    exit();
}

$db = new Database();
$conn = $db->getConnection();

$query = "
    SELECT u.id, u.name, u.email, u.phone, u.qualification, u.preferences, r.resume_file, r.upload_date
    FROM job_seekers u
    LEFT JOIN resumes r ON u.id = r.job_seeker_id
";
$result = mysqli_query($conn, $query);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Admin Panel</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
    <div class="container mt-5">
        <h1 class="mb-4">Admin Panel</h1>
        <p>Welcome, <?php echo htmlspecialchars($_SESSION['user_id']); ?>!</p>
        <a href="logout.php" class="btn btn-danger mb-3">Logout</a>
        
        <div class="row">
            <?php
            if (mysqli_num_rows($result) > 0) {
                while ($row = mysqli_fetch_assoc($result)) {
                    echo '<div class="col-md-4 mb-4">';
                    echo '<div class="card">';
                    echo '<div class="card-header">';
                    echo '<h5 class="card-title">' . htmlspecialchars($row['name']) . '</h5>';
                    echo '</div>';
                    echo '<div class="card-body">';
                    echo '<p class="card-text"><strong>Email:</strong> ' . htmlspecialchars($row['email']) . '</p>';
                    echo '<p class="card-text"><strong>Phone:</strong> ' . htmlspecialchars($row['phone']) . '</p>';
                    echo '<p class="card-text"><strong>Qualification:</strong> ' . htmlspecialchars($row['qualification']) . '</p>';
                    echo '<p class="card-text"><strong>Preferences:</strong> ' . htmlspecialchars($row['preferences']) . '</p>';
                    echo '<p class="card-text"><strong>Resume File:</strong> ' . htmlspecialchars($row['resume_file']) . '</p>';
                    echo '<p class="card-text"><strong>Upload Date:</strong> ' . htmlspecialchars($row['upload_date']) . '</p>';
                    echo '</div>';
                    echo '<div class="card-footer">';
                    echo '<a href="download_resume.php?file=' . urlencode($row['resume_file']) . '" class="btn btn-primary">Download Resume</a>';
                    echo '</div>';
                    echo '</div>';
                    echo '</div>';
                }
            } else {
                echo '<div class="col-12">';
                echo '<div class="alert alert-info">No records found</div>';
                echo '</div>';
            }
            ?>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.3/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
