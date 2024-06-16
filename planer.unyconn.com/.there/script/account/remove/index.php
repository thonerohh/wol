<?php


// print errors
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// script to read .json file and remove object with certain id through get parameter ?id=n

function deleteUser($id, $filePath) {
    $data = json_decode(file_get_contents($filePath), true);
    if ($data === null) {
        return false;
        // echo "Error reading JSON file.";
    }

    $index = -1;
    foreach ($data['users'] as $key => $users) {
        if ($users['id'] == $id) {
          // echo "User found.";
          $index = $key;
          break;
        }
    }

    if ($index === -1) {
        return false;
        // echo "User not found.";
    }

    array_splice($data['users'], $index, 1);
    $updatedJson = json_encode($data, JSON_PRETTY_PRINT);
    return $updatedJson;
}

$jsonFilePath = '../../../data/users.json';

try {
  if (isset($_GET['id'])) {
        $id = $_GET['id'];
        $jsonFile = deleteUser($id, $jsonFilePath);
        if (file_put_contents($jsonFilePath, $jsonFile)) {
            http_response_code(200);
            echo "1. User deleted successfully.";
        } else {
            http_response_code(504);
            echo "0. Error deleting user.";
            
        }
    } else if (isset($_GET['ids'])) {
        $ids = explode(',', $_GET['ids']);
        foreach ($ids as $id) {
            $jsonFile = deleteUser($id, $jsonFilePath);
        }
        if (file_put_contents($jsonFilePath, $jsonFile)) {
            http_response_code(200);
            echo "1. Users deleted successfully.";
        } else {
            http_response_code(500);
            echo "0. Error deleting users.";
        }
    }
} catch (Exception $e) {
    http_response_code(501);
    echo "Server error: " . $e->getMessage();
}
?>