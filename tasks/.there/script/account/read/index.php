<?php

function readUserInfo($username, $jsonFilePath) {
    if (!file_exists($jsonFilePath)) {
        return false;
    }

    $users = json_decode(file_get_contents($jsonFilePath), true);
    if ($users === null) {
        return false;
    }

    foreach ($users['users'] as $user) {
        if ($user['login'] === $username) {
            return $user;
        }
    }

    return false;
}

// handle GET request with 'id' as username parameter
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $username = isset($_GET['id']) ? $_GET['id'] : '';
    $jsonFilePath = '../../../data/users.json';

    if (empty($username)) {
        http_response_code(400);
        echo "Username cannot be empty.";
        exit;
    }

    $user = readUserInfo($username, $jsonFilePath);
    if ($user) {
        http_response_code(200);
        echo json_encode($user);
    } else {
        http_response_code(404);
        echo "User not found.";
    }
} else {
    http_response_code(405);
    echo "Invalid request method.";
}
?>