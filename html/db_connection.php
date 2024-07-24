<?php
$servername = "localhost"; 
$username = "root"; 
$password = "nizar123"; 
$dbname = "1337_restaurant";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>