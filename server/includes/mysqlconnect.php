<?php
// MySQL connection file
$host = 'localhost';
$user = 'frisenfeut_ig';
$pass = 'Frisenfruitig36';
$db = 'frisenfeut_feutenapp';
$sql = new PDO('mysql:host='.$host.';dbname='.$db.';charset=utf8', $user, $pass);
?>