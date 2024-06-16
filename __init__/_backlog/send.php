<?php
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

// Validate phone number (simple check for numeric characters)
function validate_phone($phone) {
    return preg_match("/^[0-9]{10}$/", $phone);
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
            move_uploaded_file($file_tmp, $upload_dir . $file_name_new);

            // Add uploaded image path to array
            $uploaded_images[] = $upload_dir . $file_name_new;
        }
    }
    return $uploaded_images;
}

// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $content = '';
    // Sanitize and validate form data
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
    if (isset($_POST["message"])) {
      $message = sanitize_input($_POST["message"]);
      $content .= "Message: $message\n";
    }

    // Validate email and phone number
    $errors = [];
    if (!validate_email($email)) {
        $errors[] = "Invalid email format";
    }
    if (!validate_phone($phone)) {
        $errors[] = "Invalid phone number format (10 digits)";
    }

    // If there are no errors, proceed to send email
    if (empty($errors)) {
      // Prepare email message
      $to = "rohhthone@gmail.com"; // Change this to your email address
      $subject = "New Form Submission";
      $message_body = $content;
      $headers = "From: $email";

      // Boundary for multipart email
      $boundary = md5(time());

      // Construct email headers for attachments
      $headers .= "\r\nMIME-Version: 1.0\r\n";
      $headers .= "Content-Type: multipart/mixed; boundary=\"" . $boundary . "\"\r\n\r\n";

      // Construct email message
      $message = "--" . $boundary . "\r\n";
      $message .= "Content-Type: text/plain; charset=\"utf-8\"\r\n";
      $message .= "Content-Transfer-Encoding: 7bit\r\n\r\n";
      $message .= $message_body . "\r\n";

      if (!empty($_FILES['images']['name'][0])){
        $uploaded_images = handle_image_upload();
        
        // Attach images to email if any uploaded
        foreach ($uploaded_images as $image) {
          $file = fopen($image, "rb");
          $data = fread($file, filesize($image));
          fclose($file);

          $message .= "--" . $boundary . "\r\n";
          $message .= "Content-Type: application/octet-stream; name=\"" . basename($image) . "\"\r\n";
          $message .= "Content-Transfer-Encoding: base64\r\n";
          $message .= "Content-Disposition: attachment; filename=\"" . basename($image) . "\"\r\n\r\n";
          $message .= chunk_split(base64_encode($data)) . "\r\n";
        }
        $message .= "--" . $boundary . "--";
      }

        // Send email
        if (mail($to, $subject, $message, $headers)) {
            echo "Form submitted successfully!";
        } else {
            echo "Failed to submit form. Please try again later.";
        }
    } else {
        // Output errors
        foreach ($errors as $error) {
            echo "$error<br>";
        }
    }
}
?>