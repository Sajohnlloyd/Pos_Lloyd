<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "database_1";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch data
$sql = "SELECT id, username, password FROM profile";
$result = $conn->query($sql);
?>
