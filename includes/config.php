<?php
// DreamNest - MySQL configuration
// XAMPP defaults: host=localhost, user=root, password='', database=primeestate
$host = 'localhost';
$user = 'root';
$pass = '';
$db   = 'primeestate';

$conn = new mysqli($host, $user, $pass, $db);
if ($conn->connect_error) {
    die("Database connection failed: " . $conn->connect_error);
}
$conn->set_charset("utf8mb4");
?>