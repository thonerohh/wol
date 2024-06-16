<?php
$salva = bin2hex(random_bytes(16));
$salva = password_hash($salva, PASSWORD_DEFAULT);
echo $salva;
?>