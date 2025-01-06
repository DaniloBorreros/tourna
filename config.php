<?php
$host = "localhost";
$username = "root";
$password = "";
$database = "u545217854_sport";

// Create a new MySQLi connection
$conn = new mysqli($host, $username, $password, $database);

// Check if the connection was successful
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
