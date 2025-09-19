<?php
$host = "localhost";
$user = "root";   // your phpMyAdmin username
$pass = "";       // your phpMyAdmin password
$db   = "student";

$conn = new mysqli($host, $user, $pass, $db);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
