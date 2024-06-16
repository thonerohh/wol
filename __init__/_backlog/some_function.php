<?php

function upload_img($file) {
  $target_dir = "../image/uploads/";
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
      $uploadOk = 1;
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
// Function to handle file uploads
function handle_image_upload() {
    $uploaded_images = [];
    $upload_dir = "uploads/"; // Directory where images will be uploaded

    // Check if files were uploaded
    if (!empty($_FILES['images']['name'][0])) {
        $files = $_FILES['images'];

        // Loop through all uploaded files
        foreach ($files['tmp_name'] as $key => $tmp_name) {
            $file_name = $files['name'][$key];
            $file_tmp = $files['tmp_name'][$key];
            $file_type = $files['type'][$key];

            // Check file type (optional)
            // You can add more file type validation as needed
            $allowed_types = array('image/jpeg', 'image/png', 'image/gif');
            if (!in_array($file_type, $allowed_types)) {
                continue; // Skip invalid file types
            }

            // Generate unique file name to avoid conflicts
            $file_name_new = uniqid() . "_" . $file_name;

            // Move uploaded file to destination directory
            if (!move_uploaded_file($file_tmp, $upload_dir . $file_name_new)) {
              echo "Error uploading file: " . $file_name;
            }

            // Add uploaded image path to array
            $uploaded_images[] = $upload_dir . $file_name_new;
        }
    }
    return $uploaded_images;
}