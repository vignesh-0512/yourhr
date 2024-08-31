<?php
include 'db_connection.php';

class ResumeSubmission {
    private $conn;
    private $target_dir = "uploads/";
    private $allowed_file_types = ['pdf'];
    private $max_file_size = 2 * 1024 * 1024;    
     public function __construct($dbConnection) {
        $this->conn = $dbConnection;

    }

    public function submit($data, $file) {
        $name = htmlspecialchars($data['name']);
        $email = filter_var($data['email'], FILTER_SANITIZE_EMAIL);
        $phone = htmlspecialchars($data['phone']);
        $qualification = htmlspecialchars($data['qualification']);
        $preferences = htmlspecialchars($data['preferences']);
        
        $resume_file = $file['name'];
        $target_file = $this->target_dir . basename($resume_file);
        $file_type = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
        $file_size = $file['size'];

        if (!in_array($file_type, $this->allowed_file_types)) {
            echo "Error: Only PDF files are allowed.";
            return;
        }

        if ($file_size > $this->max_file_size) {
            echo "Error: File size exceeds the maximum limit of 2MB.";
            return;
        }

        if (move_uploaded_file($file['tmp_name'], $target_file)) {
            $sql = "INSERT INTO job_seekers (name, email, phone, qualification, preferences) 
                    VALUES (?, ?, ?, ?, ?)";

            $stmt = $this->conn->prepare($sql);
            $stmt->bind_param("sssss", $name, $email, $phone, $qualification, $preferences);

            if ($stmt->execute()) {
                $job_seeker_id = $this->conn->insert_id;

                $sql_resume = "INSERT INTO resumes (job_seeker_id, resume_file) 
                               VALUES (?, ?)";

                $stmt_resume = $this->conn->prepare($sql_resume);
                $stmt_resume->bind_param("is", $job_seeker_id, $resume_file);

                if ($stmt_resume->execute()) {
                    header("Location: index.php");
                    exit();
                } else {
                    echo "Error: " . $stmt_resume->error;
                }
            } else {
                echo "Error: " . $stmt->error;
            }
        } else {
            echo "Sorry, there was an error uploading your file.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Submit Resume</title>
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
    <div class="container mt-5">
        <form action="submit_resume.php" method="post" enctype="multipart/form-data">
            <div class="form-group">
                <label for="name">Name:</label>
                <input type="text" class="form-control" id="name" name="name" required placeholder="ram">
            </div>

            <div class="form-group">
                <label for="email">Email:</label>
                <input type="email" class="form-control" id="email" name="email" required placeholder="ram@gmail.com">
            </div>

            <div class="form-group">
                <label for="phone">Phone:</label>
                <input type="text" class="form-control" id="phone" name="phone" placeholder="9884887789">
            </div>

            <div class="form-group">
                <label for="qualification">Qualification:</label>
                <input type="text" class="form-control" id="qualification" name="qualification" placeholder="B.E">
            </div>

            <div class="form-group">
                <label for="preferences">Preferences:</label>
                <input type="text" class="form-control" id="preferences" name="preferences" placeholder="remote">
            </div>

            <div class="form-group">
                <label for="resume">Resume (PDF):</label>
                <input type="file" class="form-control-file" id="resume" name="resume" accept=".pdf" required>
            </div>

            <button type="submit" class="btn btn-primary">Submit</button>
        </form>
    </div>

    <!-- Bootstrap JS and dependencies -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>

<?php

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $database = new Database();
    $conn = $database->getConnection();

    $resumeSubmission = new ResumeSubmission($conn);
    $resumeSubmission->submit($_POST, $_FILES['resume']);

    $conn->close();
}
?>
