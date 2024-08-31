<?php
// view_resume.php
if (isset($_GET['file'])) {
    $file = $_GET['file'];
    // Validate and sanitize the filename
    $file = basename($file);
    $filePath = 'uploads/' . $file; // Assuming resumes are stored in 'uploads' directory

    if (file_exists($filePath)) {
        // Set headers to display the file in the browser
        header('Content-Type: application/pdf'); // Change MIME type if necessary
        header('Content-Disposition: inline; filename="' . $file . '"');
        readfile($filePath);
        exit();
    } else {
        echo "File does not exist.";
    }
} else {
    echo "No file specified.";
}
