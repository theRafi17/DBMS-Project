<?php
// Database connection
$mysqli = new mysqli("localhost", "root", "", "farmer_dashboard");

if ($mysqli->connect_error) {
    echo json_encode(["error" => "Failed to connect to the database: " . $mysqli->connect_error]);
    exit;
}

// Fetch all crops from the database
$result = $mysqli->query("SELECT * FROM crops");

$crops = [];
while ($row = $result->fetch_assoc()) {
    $crops[] = $row;
}

echo json_encode($crops);

$mysqli->close();
?>
