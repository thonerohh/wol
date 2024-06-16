<?php
// Set database connection credentials
$host = 'localhost';
$user = 'dezhin001';
$pass = 'Dz102938475600!';
$table = 'posts';

// Create connection
$conn = new mysqli($host, $user, $pass, 'dezhin_posts');

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

?>