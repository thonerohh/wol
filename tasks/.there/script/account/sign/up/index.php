<?php
// Define file paths
$usersFilePath = './../../../../data/users.json';
$signsFilePath = './../../../../data/signs.txt';

// Function to get the next user ID
function getNextUserId($users) {
    if (empty($users['users'])) {
        return 1;
    }
    $lastUser = end($users['users']);
    return $lastUser['id'] + 1;
}

// Function to save a new user to the JSON file
function saveUserToJson($user, $filePath) {
    $users = json_decode(file_get_contents($filePath), true);
    if ($users === null) {
        $users = ['users' => []];
    }
    $users['users'][] = $user;
    file_put_contents($filePath, json_encode($users, JSON_PRETTY_PRINT));
}

// Function to save login and password to signs.txt
function saveSignToFile($login, $password, $filePath) {
    $entry = "{$login}:{$password}\r\n";
    file_put_contents($filePath, $entry, FILE_APPEND);
    return true;
}

// Check if POST request
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Retrieve data from POST request
    $name = isset($_POST['name']) ? $_POST['name'] : '';
    $login = isset($_POST['login']) ? $_POST['login'] : '';
    $password = isset($_POST['password']) ? $_POST['password'] : '';
    $descript = isset($_POST['descript']) ? $_POST['descript'] : '';
    $birthdate = isset($_POST['birthdate']) ? $_POST['birthdate'] : '';
    $signdate = date('Y-m-d H:i:s');
    $ip = $_SERVER['REMOTE_ADDR'];
    $useragent = $_SERVER['HTTP_USER_AGENT'];

    // Validate required fields
    if (empty($name) || empty($login) || empty($password) || empty($descript) || empty($birthdate)) {
        echo "0. All fields are required.";
        exit;
    }

    // Read the current users data
    $users = json_decode(file_get_contents($usersFilePath), true);
    if ($users === null) {
        $users = ['users' => []];
    }

    // Check if login already exists
    foreach ($users['users'] as $user) {
        if ($user['login'] === $login) {
            echo "0. Login already exists.";
            exit;
        }
    }

    // Get next user ID
    $userId = getNextUserId($users);

    // Create new user array
    $newUser = [
        'id' => $userId,
        'name' => $name,
        'login' => $login,
        'birthdate' => $birthdate,
        'script' => $descript,
        'signdate' => $signdate,
        'ip' => $ip,
        'useragent' => $useragent
    ];

    // Save user to JSON file
    saveUserToJson($newUser, $usersFilePath);

    // Save login and password to signs.txt
    if(saveSignToFile($login, $password, $signsFilePath)){
        echo "1. User signed up successfully.";
        http_response_code(201);
        // add session cookie login true for month
        setcookie('login', 'true', time() + 60 * 60 * 24 * 30, '/'); 
        setcookie('id', $login, time() + 60 * 60 * 24 * 30, '/');
        // redirect to https://test.unyconn.com/account/
        header('Location: https://test.unyconn.com/account/');
    } else {
        echo "0. Error saving user data.";
    }

} else {
    echo "0. Invalid request method.";
}
// timeout 5 seconds and redirect to previous page
header('Refresh: 10; URL=' . $_SERVER['HTTP_REFERER']);
?>
