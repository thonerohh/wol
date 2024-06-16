<?php
// Function to check if the login:password combination matches
function checkLogin($login, $password, $filePath) {
    if (!file_exists($filePath)) {
        // echo "File does not exist.";
        return false;
    }

    $handle = fopen($filePath, "r");
    if (!$handle) {
        // echo "Cannot open file.";
        return false;
    }

    

    $loginLength = strlen($login);
    $passwordLength = strlen($password);

    while (($line = fgets($handle)) !== false) {
        $line = trim($line); // Remove any trailing whitespace
        if (strpos($line, ':') === false) {
            continue; // Skip lines without ':'
        }

        list($fileLogin, $filePassword) = explode(':', $line, 2);

        $nopassword = $filePassword;
    
        // make array of $nopassword
        $nopasswordArray = str_split($nopassword);
        
        // get len of $nopasswordArray and minus 1
        $len = count($nopasswordArray);
        
        // take 1 + 3 symbols from $nopasswordArray
        $filePassword = '';
        for($i = 0; $i < $len; $i += 3){
            $filePassword .= $nopasswordArray[$i];
        }

        if (strlen($fileLogin) !== $loginLength || strlen($filePassword) !== $passwordLength) {
            continue; // Skip lines with different lengths
        }

        $match = true;
        for ($i = 0; $i < $loginLength; $i++) {
            if ($login[$i] !== $fileLogin[$i]) {
                // echo "Login does not match.";
                $match = false;
                break;
            }
        }

        if ($match) {
            for ($i = 0; $i < $passwordLength; $i++) {
                if ($password[$i] !== $filePassword[$i]) {
                    // echo "Password does not match.";
                    $match = false;
                    break;
                }
            }
        }

        if ($match) {
            fclose($handle);
            return true;
        }
    }

    fclose($handle);
    return false;
}

// Check if POST request
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $login = isset($_POST['login']) ? $_POST['login'] : '';
    $password = isset($_POST['pass']) ? $_POST['pass'] : '';

    if (empty($login) || empty($password)) {
        echo "0. Login and password cannot be empty.";
        exit;
    }

    $filePath = '../../../data/signs.txt';
    if (checkLogin($login, $password, $filePath)) {
        echo "1. Successful login.";
        // add session cookie with login id value as true for 1 month
        setcookie('login', 'true', time() + 60 * 60 * 24 * 30, '/'); 
        // add id cookie 
        setcookie('id', $login, time() + 60 * 60 * 24 * 30, '/');

        // return to ../../../account/index.html
        header('Location: https://planer.unyconn.com/account/');
    } else {
        echo "0. Invalid login or password.";
    }
} else {
    echo "0. Invalid request method.";
}
// timeout 5 seconds and redirect to previous page
header('Refresh: 10; URL=' . $_SERVER['HTTP_REFERER']);
?>
