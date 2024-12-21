<?php

// Database connection
$host = "localhost";
$username = "root";
$password = "";
$dbname = "farmer_dashboard";

$conn = new mysqli($host, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    echo json_encode(["error" => "Connection failed: " . $conn->connect_error]);
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Retrieve form data
    $farmerId = $_POST['farmerId'];
    $cropType = $_POST['cropType'];
    $plantingDate = $_POST['plantingDate'];
    $harvestDate = $_POST['harvestDate'];
    $quantity = $_POST['quantity'];
    $pricePerKg = $_POST['pricePerKg'];
    $totalPrice = $_POST['totalPrice'];

    // Insert data into the database
    $sql = "INSERT INTO crops (farmer_id, crop, planting_date, harvest_date, quantity, price_per_kg, total_price) 
            VALUES ('$farmerId', '$cropType', '$plantingDate', '$harvestDate', $quantity, $pricePerKg, $totalPrice)";

    if ($conn->query($sql) === TRUE) {
        echo json_encode(["success" => "Data inserted successfully."]);
    } else {
        echo json_encode(["error" => "Error 2: " . $conn->error]);
    }
}

$conn->close();
?>
