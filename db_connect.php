<?php
$host = 'localhost';
$username = 'root';
$password = ''; // Default WAMP password is empty
$database = 'trambakraj';

$conn = new mysqli($host, $username, $password, $database);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Set charset to UTF-8
$conn->set_charset("utf8");
?>



