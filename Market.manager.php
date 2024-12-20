<?php
// Database connection
$host = 'localhost';
$dbname = 'market_manager';
$username = 'root'; // Replace with your database username
$password = 'market_db';     // Replace with your database password

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Database connection failed: " . $e->getMessage());
}

// Fetch data for overview
$overviewQuery = "SELECT 
    COUNT(DISTINCT product_name) AS total_products,
    SUM(demand_quantity) AS total_demand,
    AVG(price) AS avg_price
FROM products";
$overviewStmt = $pdo->query($overviewQuery);
$overview = $overviewStmt->fetch(PDO::FETCH_ASSOC);

// Fetch demand and price data for charts
$chartQuery = "SELECT product_name, demand_quantity, price FROM products";
$chartStmt = $pdo->query($chartQuery);
$chartData = $chartStmt->fetchAll(PDO::FETCH_ASSOC);

// Process data for high and low demand products
$highDemandProduct = 'corn';
$lowDemandProduct = 'chili';
$maxDemand = 0;
$minDemand = PHP_INT_MAX;

foreach ($chartData as $row) {
    if ($row['demand_quantity'] > $maxDemand) {
        $maxDemand = $row['demand_quantity'];
        $highDemandProduct = $row['product_name'];
    }
    if ($row['demand_quantity'] < $minDemand) {
        $minDemand = $row['demand_quantity'];
        $lowDemandProduct = $row['product_name'];
    }
}

// Prepare JSON response
$response = [
    'overview' => $overview,
    'chartData' => $chartData,
    'highDemandProduct' => $highDemandProduct,
    'lowDemandProduct' => $lowDemandProduct
];

// Return JSON response
header('Content-Type: application/json');
echo json_encode($response);
?>
