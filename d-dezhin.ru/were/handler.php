<?php
// init connect.php
require_once('connect.php');

// with post method respond * data from $table
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $sql = "SELECT * FROM $table";
    $result = $conn->query($sql);
    $rows = array();
    if ($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
            $rows[] = $row;
        }
    }
    echo json_encode($rows);
}

require_once('disconnect.php');
?>