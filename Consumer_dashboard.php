<?php
session_start();

// Connect to the database
$servername = "localhost";
$username = "root"; // Your MySQL username
$password = "";     // Your MySQL password
$dbname = "agriculture_product"; // Your database name

// Connect to the database
$conn = new mysqli($servername, $username, $password, $dbname);

// Check the connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch products from the database
$productQuery = "SELECT product_id, product_name, seasonality, product_quantity, new_price FROM products";
$productResult = $conn->query($productQuery);

// Handle "Add to Cart" action
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['add_to_cart'])) {
    $product_id = $_POST['product_id'];
    $quantity_to_purchase = (int)$_POST['quantity_to_purchase'];

    // Fetch the selected product details
    $stmt = $conn->prepare("SELECT product_name, seasonality, product_quantity, new_price FROM products WHERE product_id = ?");
    $stmt->bind_param("i", $product_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $product = $result->fetch_assoc();

    // Validate product and quantity
    if ($product && $quantity_to_purchase > 0 && $quantity_to_purchase <= $product['product_quantity']) {
        $product_name = $product['product_name'];
        $seasonality = $product['seasonality'];
        $price = $product['new_price'];
        $total_price = $price * $quantity_to_purchase;

        // Add to the session cart
        if (!isset($_SESSION['cart'])) {
            $_SESSION['cart'] = [];
        }

        // Check if product is already in the cart
        $exists = false;
        foreach ($_SESSION['cart'] as $key => $item) {
            if ($item['product_id'] == $product_id) {
                // Update quantity if product already exists
                $_SESSION['cart'][$key]['quantity'] += $quantity_to_purchase;
                $_SESSION['cart'][$key]['total_price'] += $total_price;
                $exists = true;
                break;
            }
        }

        if (!$exists) {
            // Add new product to the cart
            $_SESSION['cart'][] = [
                'product_id' => $product_id,
                'product_name' => $product_name,
                'seasonality' => $seasonality,
                'price' => $price,
                'quantity' => $quantity_to_purchase,
                'total_price' => $total_price
            ];
        }

        echo "<script>alert('Product added to cart successfully!');</script>";
    } else {
        echo "<script>alert('Invalid quantity or product unavailable.');</script>";
    }
    $stmt->close();
}

// Handle "Purchase" action
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['purchase'])) {
    // Ensure there are items in the cart
    if (isset($_SESSION['cart']) && count($_SESSION['cart']) > 0) {
        foreach ($_SESSION['cart'] as $item) {
            $product_id = $item['product_id'];
            $quantity = $item['quantity'];
            $total_price = $item['total_price'];

            // Insert the purchase record into the database
            $insertStmt = $conn->prepare("INSERT INTO purchases (product_id, quantity, total_price) VALUES (?, ?, ?)");
            $insertStmt->bind_param("iid", $product_id, $quantity, $total_price);
            if (!$insertStmt->execute()) {
                echo "<script>alert('Error inserting purchase record: " . $insertStmt->error . "');</script>";
            }

            // Update the product quantity in the database
            $updateStmt = $conn->prepare("UPDATE products SET product_quantity = product_quantity - ? WHERE product_id = ?");
            $updateStmt->bind_param("ii", $quantity, $product_id);
            if (!$updateStmt->execute()) {
                echo "<script>alert('Error updating product quantity: " . $updateStmt->error . "');</script>";
            }

            // Close statements
            $insertStmt->close();
            $updateStmt->close();
        }

        // Clear the cart after purchase
        unset($_SESSION['cart']);

        echo "<script>alert('Purchase successful!');</script>";
    } else {
        echo "<script>alert('Your cart is empty.');</script>";
    }
}

// Fetch products to display in the available products table
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Consumer Dashboard</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f8f9fa;
        }
        h1, h2 {
            text-align: center;
            margin-top: 20px;
            color: #2980b9;
        }
        table {
            width: 80%;
            margin: 20px auto;
            border-collapse: collapse;
            background: #fff;
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
        }
        th, td {
            border: 1px solid #ddd;
            padding: 12px;
            text-align: center;
        }
        th {
            background-color: #2980b9;
            color: white;
        }
        input[type="number"] {
            width: 60px;
            text-align: center;
        }
        .action-btn {
            padding: 6px 12px;
            color: white;
            background-color: #27ae60;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }
        .action-btn:hover {
            background-color: #219150;
        }
        .purchase-btn {
            background-color: #2980b9;
        }
        .purchase-btn:hover {
            background-color: #1c5985;
        }
    </style>
</head>
<body>

<h1>Consumer Dashboard</h1>

<!-- Product Table -->
<h2>Available Products</h2>
<table>
    <thead>
        <tr>
            <th>Product Name</th>
            <th>Seasonality</th>
            <th>Available Quantity</th>
            <th>Price (per unit)</th>
            <th>Quantity to Purchase</th>
            <th>Action</th>
        </tr>
    </thead>
    <tbody>
        <?php if ($productResult && $productResult->num_rows > 0): ?>
            <?php while ($row = $productResult->fetch_assoc()): ?>
                <tr>
                    <form method="POST">
                        <input type="hidden" name="product_id" value="<?= $row['product_id'] ?>">
                        <td><?= htmlspecialchars($row['product_name']) ?></td>
                        <td><?= htmlspecialchars($row['seasonality']) ?></td>
                        <td><?= htmlspecialchars($row['product_quantity']) ?></td>
                        <td>$<?= htmlspecialchars($row['new_price']) ?></td>
                        <td><input type="number" name="quantity_to_purchase" min="1" max="<?= $row['product_quantity'] ?>" required></td>
                        <td><button type="submit" name="add_to_cart" class="action-btn">Add to Cart</button></td>
                    </form>
                </tr>
            <?php endwhile; ?>
        <?php else: ?>
            <tr><td colspan="6">No products available.</td></tr>
        <?php endif; ?>
    </tbody>
</table>

<!-- Cart Table -->
<h2>Your Cart</h2>
<table>
    <thead>
        <tr>
            <th>Product Name</th>
            <th>Seasonality</th>
            <th>Price (per unit)</th>
            <th>Quantity</th>
            <th>Total Price</th>
            <th>Action</th>
        </tr>
    </thead>
    <tbody>
        <?php if (isset($_SESSION['cart']) && count($_SESSION['cart']) > 0): ?>
            <?php foreach ($_SESSION['cart'] as $item): ?>
                <tr>
                    <td><?= htmlspecialchars($item['product_name']) ?></td>
                    <td><?= htmlspecialchars($item['seasonality']) ?></td>
                    <td>$<?= htmlspecialchars($item['price']) ?></td>
                    <td><?= htmlspecialchars($item['quantity']) ?></td>
                    <td>$<?= htmlspecialchars($item['total_price']) ?></td>
                    <td></td>
                </tr>
            <?php endforeach; ?>
            <tr>
                <td colspan="6" style="text-align: center;">
                    <form method="POST">
                        <button type="submit" name="purchase" class="purchase-btn">Purchase</button>
                    </form>
                </td>
            </tr>
        <?php else: ?>
            <tr><td colspan="6">Your cart is empty.</td></tr>
        <?php endif; ?>
    </tbody>
</table>

</body>
</html>
