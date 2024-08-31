<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Job Search</title>
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        .form-group {
            margin-bottom: 20px; /* Adjust spacing as needed */
        }
        .btn-primary {
            margin-top: 20px; /* Adjust spacing as needed */
        }
    </style>
</head>
<body>
    <div class="container mt-5">
        <!-- Form -->
        <form action="" method="post">
            <div class="form-group">
                <label for="qualification">Qualification:</label>
                <input type="text" class="form-control" id="qualification" name="qualification" required placeholder="B.E">
            </div>

            <div class="form-group">
                <label for="preferences">Preferences:</label>
                <input type="text" class="form-control" id="preferences" name="preferences" placeholder="part-time">
            </div>

            <button type="submit" class="btn btn-primary mb-4">Search</button>
        </form>
        
        <?php
        include 'db_connection.php';
        $conn = (new Database())->getConnection(); // Get DB connection

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $qualification = htmlspecialchars($_POST['qualification']);
            $preferences = htmlspecialchars($_POST['preferences']);

            $sql_jobs = "SELECT * FROM jobs WHERE 
                        (Qualification LIKE ? OR preference LIKE ?)";

            $stmt = $conn->prepare($sql_jobs);
            $search_qualification = "%$qualification%";
            $search_preferences = "%$preferences%";
            $stmt->bind_param("ss", $search_qualification, $search_preferences);

            if ($stmt->execute()) {
                $result_jobs = $stmt->get_result();

                if ($result_jobs->num_rows > 0) {
                    while($job = $result_jobs->fetch_assoc()) {
                        echo "<div class='card mb-3'>";
                        echo "<div class='card-body'>";
                        echo "<p class='card-text'><strong>Description:</strong> " . htmlspecialchars($job['description']) . "</p>";
                        echo "<p class='card-text'><strong>Requirements:</strong> " . htmlspecialchars($job['requirements']) . "</p>";
                        echo "<p class='card-text'><strong>Location:</strong> " . htmlspecialchars($job['location']) . "</p>";
                        echo "</div></div>";
                    }
                } else {
                    echo "<div class='alert alert-warning' role='alert'>No job recommendations available.</div>";
                }
            } else {
                echo "Error: " . $stmt->error;
            }

            $stmt->close();
        }

        $conn->close();
        ?>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
