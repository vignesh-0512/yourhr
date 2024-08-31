<?php
include 'db_connection.php';

$file = isset($_GET['file']) ? $_GET['file'] : '';

if ($file) {
    $db = new Database();
    $conn = $db->getConnection();
    
    $query = "SELECT resume_file FROM resumes WHERE resume_file = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param('s', $file);
    $stmt->execute();
    $stmt->store_result();
    $stmt->bind_result($resume_file);
    
    if ($stmt->num_rows > 0) {
        $stmt->fetch();
        
        header("Content-Type: application/pdf"); 
        header("Content-Disposition: inline; filename=\"$file\"");
        echo $resume_file; 
    } else {
        echo "Resume not found.";
    }
    
    $stmt->close();
    $conn->close();
} else {
    echo "No file specified.";
}
?>
