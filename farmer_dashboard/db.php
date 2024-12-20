<?php
$servername = "localhost";   // MySQL server (usually 'localhost')
$username = "root";          // Your MySQL username
$password = "";              // Your MySQL password (default is usually empty)
$dbname = "farmer_dashboard"; // Your database name

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
