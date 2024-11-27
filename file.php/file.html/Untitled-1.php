<?php
// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$database = "agriculture_product";

// Create connection
$conn = new mysqli($servername, $username, $password, $database);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Handle form submissions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'];

    // Insert product into the cart
    if ($action === 'insert') {
        $consumer_id = $_POST['consumer_id'];
        $first_name = $_POST['first_name'];
        $last_name = $_POST['last_name'];
        $phone_no = $_POST['phone_no'];
        $product = $_POST['product'];
        $quantity = $_POST['quantity'];
        $price_per_kg = $_POST['price_per_kg'];
        $total_price = $quantity * $price_per_kg;

        $query = "INSERT INTO cart (consumer_id, first_name, last_name, phone_no, product, quantity, price_per_kg, total_price) 
                  VALUES ($consumer_id, '$first_name', '$last_name', '$phone_no', '$product', $quantity, $price_per_kg, $total_price)";
        $conn->query($query);
        header("Location: consumer_dashboard.php");
        exit;
    }
    // Update product details
    elseif ($action === 'update') {
        $id = $_POST['id'];
        $first_name = $_POST['first_name'];
        $last_name = $_POST['last_name'];
        $phone_no = $_POST['phone_no'];
        $product = $_POST['product'];
        $quantity = $_POST['quantity'];
        $price_per_kg = $_POST['price_per_kg'];
        $total_price = $quantity * $price_per_kg;

        $query = "UPDATE cart SET 
                  first_name='$first_name', 
                  last_name='$last_name', 
                  phone_no='$phone_no', 
                  product='$product', 
                  quantity=$quantity, 
                  price_per_kg=$price_per_kg, 
                  total_price=$total_price 
                  WHERE id=$id";
        $conn->query($query);
        header("Location: consumer_dashboard.php");
        exit;
    }
    // Delete product from cart
    elseif ($action === 'delete') {
        $id = $_POST['id'];
        $query = "DELETE FROM cart WHERE id=$id";
        $conn->query($query);
        header("Location: consumer_dashboard.php");
        exit;
    }
    // Simulate payment via bKash
    elseif ($action === 'payment') {
        echo "<script>alert('Payment successfully processed via bKash!');</script>";
        header("Location: consumer_dashboard.php");
        exit;
    }
}

// Fetch all cart records
$query = "SELECT * FROM cart";
$result = $conn->query($query);
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
            background-color: lightblue;
            margin: 0;
            padding: 20px;
        }
        h1 {
            text-align: center;
            color: darkblue;
        }
        .container {
            margin: 20px auto;
            max-width: 800px;
            background: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }
        form input, form select, form button {
            padding: 10px;
            margin: 5px 0;
            border: 1px solid #ddd;
            border-radius: 4px;
            width: 100%;
        }
        form button {
            background-color: #4caf50;
            color: white;
            cursor: pointer;
        }
        form button:hover {
            background-color: #45a049;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        table, th, td {
            border: 1px solid #ddd;
            text-align: center;
            padding: 10px;
        }
        .update-btn, .delete-btn {
            padding: 5px 10px;
            border: none;
            border-radius: 4px;
            color: white;
            cursor: pointer;
        }
        .update-btn {
            background-color: #4caf50;
        }
        .delete-btn {
            background-color: #f44336;
        }
        .payment-btn {
            background-color: orange;
            color: white;
            padding: 10px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            margin-top: 20px;
        }
    </style>
</head>
<body>
    <h1>Consumer Dashboard</h1>
    <div class="container">
        <h2>Add to Cart</h2>
        <form method="POST" action="">
            <input type="hidden" name="action" value="insert">
            <input type="number" name="consumer_id" placeholder="Consumer ID" required>
            <input type="text" name="first_name" placeholder="First Name" required>
            <input type="text" name="last_name" placeholder="Last Name" required>
            <input type="text" name="phone_no" placeholder="Phone Number" required>
            <label>Product:</label>
            <select name="product" required>
                <option value="Rice">Rice</option>
                <option value="Wheat">Wheat</option>
                <option value="Corn">Corn</option>
                <option value="Soybean">Soybean</option>
                <option value="Barley">Barley</option>
                <option value="Millet">Millet</option>
                <option value="Oats">Oats</option>
                <option value="Sorghum">Sorghum</option>
                <option value="Sunflower">Sunflower</option>
                <option value="Canola">Canola</option>
                <option value="Peas">Peas</option>
                <option value="Lentils">Lentils</option>
                <option value="Alfalfa">Alfalfa</option>
                <option value="Clover">Clover</option>
                <option value="Chia">Chia</option>
                <option value="Potato">Potato</option>
                <option value="Tomato">Tomato</option>
            </select>
            <input type="number" name="quantity" placeholder="Quantity (kg)" min="1" required>
            <input type="number" name="price_per_kg" placeholder="Price per kg" min="1" required>
            <button type="submit">Add to Cart</button>
        </form>

        <h2>Cart</h2>
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Consumer ID</th>
                    <th>First Name</th>
                    <th>Last Name</th>
                    <th>Phone</th>
                    <th>Product</th>
                    <th>Quantity</th>
                    <th>Price per kg</th>
                    <th>Total Price</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td><?= $row['id'] ?></td>
                        <td><?= $row['consumer_id'] ?></td>
                        <td><?= $row['first_name'] ?></td>
                        <td><?= $row['last_name'] ?></td>
                        <td><?= $row['phone_no'] ?></td>
                        <td><?= $row['product'] ?></td>
                        <td><?= $row['quantity'] ?></td>
                        <td><?= $row['price_per_kg'] ?></td>
                        <td><?= $row['total_price'] ?></td>
                        <td>
                            <form method="POST" action="" style="display:inline;">
                                <input type="hidden" name="action" value="update">
                                <input type="hidden" name="id" value="<?= $row['id'] ?>">
                                <button type="submit" class="update-btn">Update</button>
                            </form>
                            <form method="POST" action="" style="display:inline;">
                                <input type="hidden" name="action" value="delete">
                                <input type="hidden" name="id" value="<?= $row['id'] ?>">
                                <button type="submit" class="delete-btn">Delete</button>
                            </form>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>

        <form method="POST" action="">
            <input type="hidden" name="action" value="payment">
            <button type="submit" class="payment-btn">Proceed to Payment via bKash</button>
        </form>
    </div>
</body>
</html>

<?php $conn->close(); ?>
