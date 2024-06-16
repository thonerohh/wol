<?php

$password = 'password';


// ENCRIPTION
// make array of $password
$passwordArray = str_split($password);
echo $passwordArray;
echo "\r\n";

// get len of $passwordArray and minus 1
$len = count($passwordArray) - 1;
echo $len;
echo "\r\n";

// make repeat action for $passwordArray to make $nopassword add 2 symbols after symbol $len times
$nopassword = '';
for($i = 0; $i < $len; $i++){
    $nopassword .= $passwordArray[$i] . bin2hex(random_bytes(1));
}
$nopassword .= $passwordArray[$len];
echo $nopassword;
echo "\r\n";
echo $password;

// DECRIPTION

// make array of $nopassword
$nopasswordArray = str_split($nopassword);
echo $nopasswordArray;
echo "\r\n";

// get len of $nopasswordArray and minus 1
$len = count($nopasswordArray);
echo $len;
echo "\r\n";

// take 1 + 3 symbols from $nopasswordArray
$password = '';
for($i = 0; $i < $len; $i += 3){
    $password .= $nopasswordArray[$i];
}

echo $password;
echo "\r\n";


?>