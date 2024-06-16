<?php

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