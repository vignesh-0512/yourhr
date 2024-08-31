<?php
include 'db_connection.php';

$file = isset($_GET['file']) ? $_GET['file'] : '';

if ($file) {
    $file = basename($file);

    $filePath = __DIR__ . '/uploads/' . $file;

    echo "File path: " . htmlspecialchars($filePath) . "<br>";

    if (file_exists($filePath)) {
        header("Content-Type: application/pdf"); 
        header("Content-Disposition: attachment; filename=\"$file\"");
        header("Content-Length: " . filesize($filePath));
        readfile($filePath);
        exit();
    } else {
        echo "File not found.";
    }
} else {
    echo "No file specified.";
}
?>
