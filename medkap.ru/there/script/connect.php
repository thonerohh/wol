<?php
// Set database connection credentials
$host = 'localhost';
$user = 'medkap_admin';
$pass = 'Rrhx2689!';
$base = 'medkap_orders';

// Create connection
$conn = new mysqli($host, $user, $pass, $base);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

?>