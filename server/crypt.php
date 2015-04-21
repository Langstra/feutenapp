<?php
$salt = 'frisenfruitig36';
$password = 'f3';
echo crypt($password,$salt);
echo '<br />';
echo bin2hex(openssl_random_pseudo_bytes(16));
?>