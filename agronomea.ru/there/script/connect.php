<?php
// Set database connection credentials
$host = 'localhost';
$user = 'agronomea_admin';
$pass = 'y84u8m5G*';
$table = 'posts';
$base = 'agronomea_orders';

// Create connection
$conn = new mysqli($host, $user, $pass, $base);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

?>