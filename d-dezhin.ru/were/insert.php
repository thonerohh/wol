<?php

// with post method add data to $table in such format:
  // id INT PRIMARY KEY,
  // img $POST('img'),
  // alt $POST('alt'),
  // text $POST('text'),
  // date_created now(),
  // date_edited now(),
  // user_agent $user_agent,
  // ip $cuurentip,
  // other INT

  function upload_img($file) {
    $target_dir = "../there/uploads/";
    $target_file = $target_dir . basename($file["name"]);
    $uploadOk = 1;
    $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

    // Check if image file is an actual image or fake image
    $check = getimagesize($file["tmp_name"]);
    if ($check !== false) {
        echo "File is an image - " . $check["mime"] . ".";
    } else {
        echo "File is not an image.";
        $uploadOk = 0;
    }

    // Check if file already exists
    if (file_exists($target_file)) {
        // create new name
        $target_file = $target_dir . uniqid() . '.' . $imageFileType;
    }

    // Check file size
    if ($file["size"] > 500000) {
        echo "Sorry, your file is too large.";
        $uploadOk = 0;
    }

    // Allow certain file formats
    if (!in_array($imageFileType, ['jpg', 'png', 'jpeg', 'gif'])) {
        echo "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
        $uploadOk = 0;
    }

    // Check if $uploadOk is set to 0 by an error
    if ($uploadOk == 1) {
      if (move_uploaded_file($file["tmp_name"], $target_file)) {
          // remove first 2 symbols from path
          $target_file = substr($target_file, 2);

          echo "The file ". htmlspecialchars( basename($file["name"])). " has been uploaded.";
          // Return the path to the uploaded file
          return $target_file;
      } else {
          echo "Sorry, there was an error uploading your file.";
          return false;
      }
    } else {
        return false;
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_FILES['img'])) {
        $img = upload_img($_FILES['img']);
        if ($img !== false) {
            $alt = $_POST['alt'] ?? '';
            $text = $_POST['text'] ?? '';
            $user_agent = $_SERVER['HTTP_USER_AGENT'] ?? '';
            $current_ip = $_SERVER['REMOTE_ADDR'] ?? '';

            $datetime = date('Y-m-d H:i:s');


            // Assuming $conn is your database connection variable and you have included 'connect.php'
            include 'connect.php';
            $table = 'posts'; // Replace with your actual table name
            // Prepare an insert statement
            $sql = "INSERT INTO $table (img, alt, text, date_created, user_agent, ip) VALUES (?, ?, ?, ?, ?, ?)";
            if ($stmt = $conn->prepare($sql)) {
                // Bind variables to the prepared statement as parameters
                $imgPath = $img !== false ? $img : null; // If $img is false, use null or handle the error as required
                $stmt->bind_param('ssssss', $imgPath, $alt, $text, $datetime, $user_agent, $current_ip);  
            } else {
                echo "Error: " . $conn->error;
            }

            if ($stmt->execute()) {
                echo "New record created successfully";
            } else {
                echo "Error: " . $stmt->error;
            }
            $stmt->close();
        } else {
            echo "Sorry, there was an error uploading your file.";
        }
    } else {
        echo "No file to upload.";
    }
}
?>