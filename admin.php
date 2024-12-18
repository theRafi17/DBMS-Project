<?php
// Database Connection
$host = "localhost";
$user = "root";
$password = "";
$database = "agriculture_product";

$conn = new mysqli($host, $user, $password, $database);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Determine which dashboard to show
$page = isset($_GET['page']) ? $_GET['page'] : 'admin';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f4;
            color: #333;
        }
        .container {
            margin: 20px auto;
            max-width: 800px;
            text-align: center;
        }
        h1 {
            color: #4CAF50;
        }
        .button-container button {
            margin: 10px;
            padding: 10px 20px;
            font-size: 16px;
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }
        .button-container button:hover {
            background-color: #45a049;
        }
        a {
            text-decoration: none;
            color: #4CAF50;
            display: block;
            margin: 20px 0;
        }
    </style>
</head>
<body>
    <div class="container">
        <?php if ($page === 'admin') { ?>
            <!-- Main Admin Dashboard -->
            <h1>Admin Dashboard</h1>
            <div class="button-container">
                <button onclick="window.location.href='?page=consumer'">Consumer_dashboard</button>
                <button onclick="window.location.href='?page=product'">Product_dashboard</button>
                <button onclick="window.location.href='?page=employee'">employee_dashboard</button>
                <button onclick="window.location.href='?page=warehouse'">warehouse_dashboard</button>
            </div>
        <?php } elseif ($page === 'consumer') { ?>
            <!-- Consumer Dashboard -->
            <h1>Consumer Dashboard</h1>
            <a href="?page=admin">Back to Admin Dashboard</a>
            <p>Manage consumers here.</p>
            <?php
                $result = $conn->query("SELECT * FROM consumers");
                if ($result->num_rows > 0) {
                    echo "<table border='1' width='100%'>";
                    echo "<tr><th>ID</th><th>Name</th><th>Email</th></tr>";
                    while ($row = $result->fetch_assoc()) {
                        echo "<tr><td>{$row['id']}</td><td>{$row['name']}</td><td>{$row['email']}</td></tr>";
                    }
                    echo "</table>";
                } else {
                    echo "No consumers found.";
                }
            ?>
        <?php } elseif ($page === 'product') { ?>
            <!-- Product Dashboard -->
            <h1>Product Dashboard</h1>
            <a href="?page=admin">Back to Admin Dashboard</a>
            <p>Manage products here.</p>
            <?php
                $result = $conn->query("SELECT * FROM products");
                if ($result->num_rows > 0) {
                    echo "<table border='1' width='100%'>";
                    echo "<tr><th>ID</th><th>Name</th><th>Type</th><th>Price</th></tr>";
                    while ($row = $result->fetch_assoc()) {
                        echo "<tr><td>{$row['id']}</td><td>{$row['name']}</td><td>{$row['type']}</td><td>{$row['price']}</td></tr>";
                    }
                    echo "</table>";
                } else {
                    echo "No products found.";
                }
            ?>
        <?php } elseif ($page === 'employee') { ?>
            <!-- Employee Dashboard -->
            <h1>Employee Dashboard</h1>
            <a href="?page=admin">Back to Admin Dashboard</a>
            <p>Manage employees here.</p>
            <?php
                $result = $conn->query("SELECT * FROM employees");
                if ($result->num_rows > 0) {
                    echo "<table border='1' width='100%'>";
                    echo "<tr><th>ID</th><th>Name</th><th>Role</th><th>Salary</th></tr>";
                    while ($row = $result->fetch_assoc()) {
                        echo "<tr><td>{$row['id']}</td><td>{$row['name']}</td><td>{$row['role']}</td><td>{$row['salary']}</td></tr>";
                    }
                    echo "</table>";
                } else {
                    echo "No employees found.";
                }
            ?>
        <?php } elseif ($page === 'warehouse') { ?>
            <!-- Warehouse Dashboard -->
            <h1>Warehouse Dashboard</h1>
            <a href="?page=admin">Back to Admin Dashboard</a>
            <p>Manage warehouses here.</p>
            <?php
                $result = $conn->query("SELECT * FROM warehouses");
                if ($result->num_rows > 0) {
                    echo "<table border='1' width='100%'>";
                    echo "<tr><th>ID</th><th>Location</th><th>Capacity</th></tr>";
                    while ($row = $result->fetch_assoc()) {
                        echo "<tr><td>{$row['id']}</td><td>{$row['location']}</td><td>{$row['capacity']}</td></tr>";
                    }
                    echo "</table>";
                } else {
                    echo "No warehouses found.";
                }
            ?>
        <?php } else { ?>
            <!-- Page Not Found -->
            <h1>404 - Page Not Found</h1>
            <a href="?page=admin">Back to Admin Dashboard</a>
        <?php } ?>
    </div>
</body>
</html>
<?php
$conn->close();
?>
