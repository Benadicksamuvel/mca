<?php
$host = "localhost";
$dbname = "bookinv";
$username = "root";
$password = ""; // ← Replace with your actual MySQL root password

$conn = new mysqli($host, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
