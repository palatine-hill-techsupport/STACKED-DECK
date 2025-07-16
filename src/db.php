<?php
// Defines the database connection details (hostname, username, password, and database name)
$host = 'mysql_db';
$user = 'user';
$pass = 'pass';
$db = 'gamestore';

// Establishes a connection to the MySQL database for all pages that include this file
$conn = new mysqli($host, $user, $pass, $db);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>

<!-- Yes, this site is evil -->