<?php

// print errors
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// script to generate json file or add content to existing json file
function generateJson($data) {
  // create json file if it doesn't exist
  // Convert the JSON string to an associative array
  // Set object to the 'task' array
  // Convert the modified array back to JSON
  // return the updated JSON string
  
  $data = ['task' => []];
  $updatedJson = json_encode($data, JSON_PRETTY_PRINT);
  return $updatedJson;
}

function addObject($data, $file) {
  // Convert the JSON string to an associative array
  // add one more object to the 'task' array
  // Convert the modified array back to JSON
  // return the updated JSON string

  $file = json_decode($file, true);

  $file['task'][] = $data;
  $updatedJson = json_encode($file, JSON_PRETTY_PRINT);
  return $updatedJson;
}

// print request
echo '<pre>';
print_r($_POST);
echo '</pre>';


// script to read json file and write to the and of it new content
// Check if the 'data' parameter is set in the query string
if (isset($_POST)) {
  if(isset($_POST['script']) && isset($_GET['time']) && isset($_GET['user'])){
    echo 'script, time and user keys are set';
    $data = $_POST['script'];
    $time = $_GET['time'];
    $timeCurrent = date('Y-m-d H:i:s');

    echo $time . ' ' . $timeCurrent . '<br>';

    $user = $_GET['user'];
    $own = $timeCurrent . ', ' . $user . PHP_EOL;

    $file = '../../../data/' . $user . '.json';

    // check the last 'id' in the json file['task'] and increment it by 1 if json file exists
    if (!file_exists($file)) {
      $id = 0;

      $data = ['id' => $id, 'script' => $data, 'own' => $own];
      $file1 = ['task' => []];
      $file1['task'][] = $data;

      $file = json_encode($file1, JSON_PRETTY_PRINT);
    } else {
      $file = file_get_contents($file);
      $file = json_decode($file, true);
      $last = end($file['task']);
      $id = $last['id'] + 1;

      $data = ['id' => $id, 'script' => $data, 'own' => $own];
      $file['task'][] = $data;

      $file = json_encode($file, JSON_PRETTY_PRINT);
    }

    file_put_contents('../../../data/' . $user . '.json', $file);

    // return to previous page
    header('Location: ' . $_SERVER['HTTP_REFERER']);
    
  } else if(isset($_POST['script'])){
    echo 'script key is set';

    $data = $_POST['script'];
    $time = $_POST['time'] ?? date('Y-m-d H:i:s');
    $user = $_POST['user'] ?? $_SERVER['REMOTE_ADDR'] ?? 'unknown';
    $own = $time . ', ' . $user . PHP_EOL;
    
    // print_r($own);

    $file = '../../../data/tasks.json';
    // check the last 'id' in the json file['task']
    $file = file_get_contents($file);
    $file = json_decode($file, true);
    $last = end($file['task']);
    $id = $last['id'] + 1;

    // create object with 'id', 'script' and 'own' keys
    $data = ['id' => $id, 'script' => $data, 'own' => $own];

    // add object to the end of the json file
    $file['task'][] = $data;
    $file = json_encode($file, JSON_PRETTY_PRINT);
    file_put_contents('../../../data/tasks.json', $file);

    // return to previous page
    header('Location: ' . $_SERVER['HTTP_REFERER']);
  } else {
    // Call the function to add the object with the specified data
    // Write the updated JSON string back to the file

    // Read the raw POST data
    $json = file_get_contents("php://input");

    // Decode the JSON into an associative array
    $data = json_decode($json, true);

    if ($data !== null) {
        // Call the function to add the object with the specified data
        // check if $data contain 'script' key
        if (array_key_exists('script', $data)) {
          $file = '../../../data/tasks.json';
          // check if file exists
          if (!file_exists($file)) {
            $file = generateJson($data);
          } else {
            $file = file_get_contents($file);
            $file = addObject($data, $file);
          }
          file_put_contents('../../../data/tasks.json', $file);
          
        }else{
          echo ' no script key specified';
        }
    } else {
        // Handle the error, e.g., JSON is invalid or 'value' is not set
        http_response_code(400);
        echo json_encode(['error' => 'Invalid JSON or missing "value"']);
    }
  }

} else {
  // If the 'data' parameter is not set, return an error message
  echo 'Error: No data specified. Please provide data to add the object.';
}


?>