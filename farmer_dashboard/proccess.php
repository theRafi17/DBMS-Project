<?php
include 'db.php';

$action = $_POST['action'];

if ($action == "add") {
    $farmerId = $_POST['farmerId'];
    $crop = $_POST['crop'];
    $plantingDate = $_POST['plantingDate'];
    $harvestDate = $_POST['harvestDate'];
    $quantity = $_POST['quantity'];
    $pricePerKg = $_POST['pricePerKg'];
    $totalPrice = $_POST['totalPrice'];

    $sql = "INSERT INTO crops (farmer_id, crop, planting_date, harvest_date, quantity, price_per_kg, total_price) 
            VALUES ('$farmerId', '$crop', '$plantingDate', '$harvestDate', $quantity, $pricePerKg, $totalPrice)";
    echo $conn->query($sql) ? "success" : "error";
}

if ($action == "read") {
    $result = $conn->query("SELECT * FROM crops");
    $rows = [];
    while ($row = $result->fetch_assoc()) {
        $rows[] = $row;
    }
    echo json_encode($rows);
}

if ($action == "delete") {
    $id = $_POST['id'];
    $sql = "DELETE FROM crops WHERE id = $id";
    echo $conn->query($sql) ? "success" : "error";
}

if ($action == "update") {
    $id = $_POST['id'];
    $farmerId = $_POST['farmerId'];
    $crop = $_POST['crop'];
    $plantingDate = $_POST['plantingDate'];
    $harvestDate = $_POST['harvestDate'];
    $quantity = $_POST['quantity'];
    $pricePerKg = $_POST['pricePerKg'];
    $totalPrice = $_POST['totalPrice'];

    $sql = "UPDATE crops 
            SET farmer_id='$farmerId', crop='$crop', planting_date='$plantingDate', harvest_date='$harvestDate', 
                quantity=$quantity, price_per_kg=$pricePerKg, total_price=$totalPrice 
            WHERE id=$id";
    echo $conn->query($sql) ? "success" : "error";
}
?>
