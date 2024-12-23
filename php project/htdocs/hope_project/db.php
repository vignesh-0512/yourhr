<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "hope_project";

$conn = new mysqli($servername, $username, $password, $dbname);
if($conn->connect_error){
    echo "Database connection failed";
}else{
    
}

?>