<?php
$servername = "localhost";
$username = "root"; // Your MySQL username
$password = "";     // Your MySQL password
$dbname = "agriculture_product";

// Establish Database Connection
$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Handle POST Request (Form Submission)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['add_product'])) {
        // Insert Product Logic
        $product_id = $_POST['product_id'] ?? null; // Product ID to validate
        $product_name = $_POST['product_name'];
        $seasonality = $_POST['seasonality'];
        $production_cost = $_POST['production_cost'];
        $product_quantity = $_POST['product_quantity'];
        $old_price = $_POST['old_price'];
        $new_price = $_POST['new_price'];
        $production_date = $_POST['production_date'];
        $expiration_date = $_POST['expiration_date'];

        // Validate Product ID: Check for duplicates
        $checkStmt = $conn->prepare("SELECT product_id FROM products WHERE product_id = ?");
        $checkStmt->bind_param("s", $product_id);
        $checkStmt->execute();
        $checkStmt->store_result();

        if ($checkStmt->num_rows > 0) {
            echo "<script>alert('Error: Duplicate Product ID. This Product ID already exists.'); window.location.href = 'product_dashboard.php';</script>";
            $checkStmt->close();
            exit;
        }
        $checkStmt->close();

        // Insert the Product into Database
        $insertStmt = $conn->prepare("
            INSERT INTO products (product_id, product_name, seasonality, production_cost, product_quantity, old_price, new_price, production_date, expiration_date)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)
        ");
        $insertStmt->bind_param("sssdddsss", $product_id, $product_name, $seasonality, $production_cost, $product_quantity, $old_price, $new_price, $production_date, $expiration_date);

        if ($insertStmt->execute()) {
            echo "<script>alert('Product added successfully!'); window.location.href = 'product_dashboard.php';</script>";
        } else {
            echo "<script>alert('Error: Could not add the product.');</script>";
        }
        $insertStmt->close();
    } elseif (isset($_POST['update_product'])) {
        // Update Product Logic
        $product_id = $_POST['product_id'];
        $product_name = $_POST['product_name'];
        $seasonality = $_POST['seasonality'];
        $production_cost = $_POST['production_cost'];
        $product_quantity = $_POST['product_quantity'];
        $old_price = $_POST['old_price'];
        $new_price = $_POST['new_price'];
        $production_date = $_POST['production_date'];
        $expiration_date = $_POST['expiration_date'];

        $updateStmt = $conn->prepare("UPDATE products SET product_name = ?, seasonality = ?, production_cost = ?, product_quantity = ?, old_price = ?, new_price = ?, production_date = ?, expiration_date = ? WHERE product_id = ?");
        $updateStmt->bind_param("sssdddssss", $product_name, $seasonality, $production_cost, $product_quantity, $old_price, $new_price, $production_date, $expiration_date, $product_id);

        if ($updateStmt->execute()) {
            echo "<script>alert('Product updated successfully!'); window.location.href = 'product_dashboard.php';</script>";
        } else {
            echo "<script>alert('Error: Could not update the product.');</script>";
        }
        $updateStmt->close();
    } elseif (isset($_POST['delete_product'])) {
        // Delete Product Logic
        $product_id = $_POST['product_id'];

        $deleteStmt = $conn->prepare("DELETE FROM products WHERE product_id = ?");
        $deleteStmt->bind_param("s", $product_id);

        if ($deleteStmt->execute()) {
            echo "<script>alert('Product deleted successfully!'); window.location.href = 'product_dashboard.php';</script>";
        } else {
            echo "<script>alert('Error: Could not delete the product.');</script>";
        }
        $deleteStmt->close();
    }
}

// Fetch Existing Products for Display
$result = $conn->query("SELECT * FROM products");
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
<a href="/admin_db/index.php" class="back-button">Back</a>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Product Dashboard</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #87ceeb;
            margin: 0;
            padding: 0;
            color: #333;
        }
        h1, h2 {
            text-align: center;
            color: white;
        }
        .form-container {
            width: 80%;
            margin: 20px auto;
            background: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }
        label, select, input, button {
            display: block;
            width: 100%;
            margin: 10px 0;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 4px;
        }
        button {
            background-color: #2980b9;
            color: white;
            cursor: pointer;
        }
        button:hover {
            background-color: #1c5985;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        th, td {
            padding: 12px;
            border: 1px solid #ddd;
            text-align: center;
        }
        th {
            background-color: #2980b9;
            color: white;
        }
        .edit-btn, .delete-btn {
            padding: 5px 10px;
            color: white;
            border: none;
            border-radius: 3px;
            cursor: pointer;
        }
        .edit-btn { background-color: #f39c12; }
        .delete-btn { background-color: #e74c3c; }
    </style>
</head>
<body>

    <h1>Product Dashboard</h1>

    <div class="form-container">
        <form method="POST" action="">
            <label>Product ID:</label>
            <input type="text" name="product_id" id="product_id" required>

            <label>Product Name:</label>
            <select name="product_name" id="product_name" required>
                <option value="">Select Product</option>
                <option value="Rice">Rice</option>
                <option value="Wheat">Wheat</option>
                <option value="Corn">Corn</option>
                <option value="Soybean">Soybean</option>
                <option value="Barley">Barley</option>
                <option value="Millet">Millet</option>
                <option value="Oats">Oats</option>
                <option value="Sorghum">Sorghum</option>
                <option value="Tomato">Tomato</option>
                <option value="Apple">Apple</option>
                <option value="Banana">Banana</option>
                <option value="Peas">Peas</option>
                <option value="Orange">Orange</option>
            </select>

            <label>Seasonality:</label>
            <select name="seasonality" id="seasonality" required>
                <option value="">Select Season</option>
                <option value="Summer">Summer</option>
                <option value="Rainy">Rainy</option>
                <option value="Autumn">Autumn</option>
                <option value="Late Autumn">Late Autumn</option>
                <option value="Winter">Winter</option>
                <option value="Spring">Spring</option>
            </select>

            <label>Production Cost:</label>
            <input type="number" name="production_cost" required>

            <label>Product Quantity:</label>
            <input type="number" name="product_quantity" required>

            <label>Old Price:</label>
            <input type="number" name="old_price" required>

            <label>New Price:</label>
            <input type="number" name="new_price" required>

            <label>Production Date:</label>
            <input type="date" name="production_date" required>

            <label>Expiration Date:</label>
            <input type="date" name="expiration_date" required>

            <button type="submit" name="add_product">Submit</button>
        </form>
    </div>

    <div class="form-container">
        <h2>Product List</h2>
        <table>
            <thead>
                <tr>
                    <th>Product ID</th>
                    <th>Product Name</th>
                    <th>Seasonality</th>
                    <th>Production Cost</th>
                    <th>Quantity</th>
                    <th>Old Price</th>
                    <th>New Price</th>
                    <th>Production Date</th>
                    <th>Expiration Date</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td><?= htmlspecialchars($row['product_id']) ?></td>
                        <td><?= htmlspecialchars($row['product_name']) ?></td>
                        <td><?= htmlspecialchars($row['seasonality']) ?></td>
                        <td><?= htmlspecialchars($row['production_cost']) ?></td>
                        <td><?= htmlspecialchars($row['product_quantity']) ?></td>
                        <td><?= htmlspecialchars($row['old_price']) ?></td>
                        <td><?= htmlspecialchars($row['new_price']) ?></td>
                        <td><?= htmlspecialchars($row['production_date']) ?></td>
                        <td><?= htmlspecialchars($row['expiration_date']) ?></td>
                        <td>
                            <!-- Edit Button -->
                            <form method="POST" action="product_dashboard.php" style="display:inline;">
                                <input type="hidden" name="product_id" value="<?= $row['product_id'] ?>">
                                <button type="submit" name="update_product" class="edit-btn">Update</button>
                            </form>
                            <!-- Delete Button -->
                            <form method="POST" action="product_dashboard.php" style="display:inline;">
                                <input type="hidden" name="product_id" value="<?= $row['product_id'] ?>">
                                <button type="submit" name="delete_product" class="delete-btn" onclick="return confirm('Are you sure you want to delete this product?')">Delete</button>
                            </form>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>

</body>
</html>
