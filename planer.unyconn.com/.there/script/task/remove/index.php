<?php

// print errors
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$id = $_GET['id'] ?? $_GET['ids'] ?? null;
$user = $_GET['user'] ?? null;

if ($id == null) {
    http_response_code(400);
    echo "Missing 'id' or 'ids' parameter";
    exit;
}

function deleteObject($id, $jsonFile) {
  // Convert the JSON string to an associative array and select 'task' object as the root object
  $data = json_decode($jsonFile, true);

  // Loop through the 'task' array and remove the object with the specified id
  foreach ($data['task'] as $key => $object) {
      if ($object['id'] == $id) {
          unset($data['task'][$key]);
          break;
      }
  }

  // Re-index the array to maintain numeric keys
  $data['task'] = array_values($data['task']);

  // Convert the modified array back to JSON
  $updatedJson = json_encode($data, JSON_PRETTY_PRINT);

  // return the updated JSON string
  return $updatedJson;
}


try {
  if ($user && isset($_GET['id'])){
    $jsonFile = file_get_contents('../../../data/' . $user .'.json');
    $id = $_GET['id'];
    $jsonFile = deleteObject($id, $jsonFile);
    if (file_put_contents('../../../data/' . $user .'.json', $jsonFile)) {
      http_response_code(200);
      echo '1. User task deleted successfully. User and ID';
    } else {
      http_response_code(500);
      echo '0. Error deleting user task. User and ID';
    }

  } else if (isset($_GET['id'])) {
    // Call the function to delete the object with the specified id
    // Write the updated JSON string back to the file
  
    $id = $_GET['id'];
    $jsonFile = file_get_contents('../../../data/tasks.json');
    $jsonFile = deleteObject($id, $jsonFile);
    // file_put_contents('../../../data/tasks.json', $jsonFile);
    // if file_put_content success then return 200 and echo '1'
    if (file_put_contents('../../../data/tasks.json', $jsonFile)) {
      http_response_code(200);
      echo '1. Task deleted successfully. ID';
    } else {
      http_response_code(500);
      echo '0. Error deleting task. ID';
    }
  
  } else if (isset($_GET['ids'])) {
    // retrieve ids and split with comma, then delete all of them from .json
    // Loop through the array of ids and call the function to delete the object with each id
    // Write the updated JSON string back to the file
    
    $ids = explode(',', $_GET['ids']);
    $jsonFile = file_get_contents('../../../data/tasks.json');
    foreach ($ids as $id) {
      $jsonFile = deleteObject($id, $jsonFile);
    }
    // file_put_contents('../../../data/tasks.json', $jsonFile);

    if (file_put_contents('../../../data/tasks.json', $jsonFile)) {
      http_response_code(200);
      echo '1. Tasks deleted successfully. IDs';
    } else {
      http_response_code(500);
      echo '0. Error deleting tasks. IDs';
    }
  
  }
} catch (Exception $e) {
    http_response_code(500);
    echo "Server error: " . $e->getMessage();
}

// script to read .json file and remove object with certain id as get parameter ?id=n
// Check if the 'id' parameter is set in the query string
?>