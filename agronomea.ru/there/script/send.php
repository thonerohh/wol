<?php

// display errors
// ini_set('display_errors', 1);
// ini_set('display_startup_errors', 1);
// error_reporting(E_ALL);

// // Print the content of $_FILES using var_dump
// echo "Printing content of \$_FILES using var_dump:\n";
// var_dump($_FILES);

// Function to sanitize and validate form data
function sanitize_input($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}

// Validate email
function validate_email($email) {
    return filter_var($email, FILTER_VALIDATE_EMAIL);
}

// Validate phone number with variations
function validate_phone($phone) {
    // Pattern to match optional '+' sign followed by 10 or 11 digits,
    // or a '7' or '8' followed by 9 digits
    return preg_match("/^(\+?[0-9]{10,11}|[7-8][0-9]{9})$/", $phone);
}

function upload_imgs($files) {
  $uploaded_files = array();
  $target_dir = "../image/uploads/";

  if (!file_exists($target_dir)) {
    mkdir($target_dir, 0764, true);
  }
  $uploadOk = 1;
  
  // check if $files is an array
  if (!is_array($files["tmp_name"])) {
    // Loop through each file else display an error
    foreach ($files["tmp_name"] as $key => $tmp_name) {
      $target_file = $target_dir . basename($files["name"][$key]);
      $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

      // Check if image file is an actual image or fake image
      $check = getimagesize($tmp_name);
      if ($check !== false) {
        echo "File is an image - " . $check["mime"] . ".";
      } else {
        echo "File is not an image.";
        $uploadOk = 0;
      }

      // create new name
      $filename = pathinfo($files["name"][$key], PATHINFO_FILENAME);
      $target_file = $target_dir . $filename . uniqid() . '.' . $imageFileType;
      // remove spaces 
      $target_file = str_replace(' ', '', $target_file);

      // Check file size
      if ($files["size"][$key] > 500000) {
        echo "Sorry, your file is too large.";
        $uploadOk = 1;
      }

      // Allow certain file formats
      if (!in_array($imageFileType, ['jpg', 'png', 'jpeg', 'gif'])) {
        echo "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
        $uploadOk = 0;
      }

      // Check if $uploadOk is set to 0 by an error
      if ($uploadOk == 1) {
        if (move_uploaded_file($tmp_name, $target_file)) {
          // remove first 2 symbols from path
          $target_file = substr($target_file, 2);
          echo "The file ". htmlspecialchars( basename($files["name"][$key])). " has been uploaded.";
          // Save the path to the uploaded file
          $uploaded_files[] = $target_file;
        } else {
          echo "Sorry, there was an error uploading your file.";
          return false;
        }
      } else {
        return false;
      }
    }
  } else {
    echo "Error: Files are not uploaded as an array.";
  }
  
  return $uploaded_files;
}

function addToDatabase($content, $table = 'orders') {
  $user_agent = $_SERVER['HTTP_USER_AGENT'] ?? '';
  $current_ip = $_SERVER['REMOTE_ADDR'] ?? '';
  $datetime = date('Y-m-d H:i:s');
  // Assuming $conn is your database connection variable and you have included 'connect.php'
  include 'connect.php';

  // Prepare an insert statement
  $sql = "INSERT INTO $table (data, date_created, user_agent, ip) VALUES (?, ?, ?, ?)";
  if ($stmt = $conn->prepare($sql)) {

      // Bind variables to the prepared statement as parameters
      $stmt->bind_param('ssss', $content, $datetime, $user_agent, $current_ip);  
  } else {
      echo "Error: " . $conn->error;
  }
  if ($stmt->execute()) {
      echo "New record created successfully";
  } else {
      echo "Error: " . $stmt->error;
  }
  $stmt->close();
}

// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $content = '';
  $uploaded_files = [];

  if (isset($_POST["name"])) {
    $name = sanitize_input($_POST["name"]);
    $content .= "Name: $name\n";
  }
  if (isset($_POST["email"])) {
    $email = sanitize_input($_POST["email"]);
    $content .= "Email: $email\n";
  }
  if (isset($_POST["phone"])) {
    $phone = sanitize_input($_POST["phone"]);
    $content .= "Phone: $phone\n";
  }
  if (isset($_POST["text"])) {
    $message = sanitize_input($_POST["text"]);
    $content .= "Message: $message\n";
  }

  // Validate email and phone number
  $errors = [];
  if (!validate_phone($phone)) {
    $errors[] = "Invalid phone number format (10 or 11 digits)";
  }

  // If there are no errors, proceed to save order as a text file
  if (empty($errors)) {
    
    // Handle image upload
    if (!empty($_FILES['images'])) {
      // Check if multiple files are uploaded
      if (is_array($_FILES['images']['tmp_name'])) {
        echo "Uploading images...";
        $uploaded_files = upload_imgs($_FILES['images']);
      } else {
        echo "Uploading images as array...";
        // If only one file is uploaded, create an array to mimic multiple files
        $uploaded_files = upload_imgs(array($_FILES['images']));
      }
    }

    // Proceed with other operations
    if (!empty($uploaded_files)) {
      $content .= "Images:\n";
      foreach ($uploaded_files as $image) {
        $content .= $image . "\n";
      }
    } else {
      echo "Error uploading images.";
    }

    // Directory to save orders
    $order_dir = "../order/";
    if (!file_exists($order_dir)) {
        mkdir($order_dir, 0764, true);
    }

    // Generate a unique filename
    $file_name = uniqid("order_") . ".txt";

    // Save order as a text file
    file_put_contents($order_dir . $file_name, $content);

    echo "Order saved successfully!" . $order_dir . $file_name;

    // make json object like $content and save it to database
    $json_content = [];
    if (isset($name)) {
      $json_content['name'] = $name;
    }
    if (isset($email)) {
      $json_content['email'] = $email;
    }
    if (isset($phone)) {
      $json_content['phone'] = $phone;
    }
    if (isset($message)) {
      $json_content['message'] = $message;
    }
    if (!empty($uploaded_files)) {
      $json_content['images'] = $uploaded_files;
    }
    $json_content = json_encode($json_content);

    addToDatabase($json_content);

    // // open previous page
    header("Location: {$_SERVER['HTTP_REFERER']}");
  } else {
    // Output errors
    foreach ($errors as $error) {
      echo "$error<br>";
    }
  }
}

?>