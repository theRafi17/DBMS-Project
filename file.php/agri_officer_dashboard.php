<?php
// Database connection
$conn = new mysqli("localhost", "root", "", "agriculture_product");

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Add Record
if (isset($_POST['add_record'])) {
    $officer_id = $_POST['officer_id'];
    $warehouse_address = $_POST['warehouse_address'];
    $product_scanned = $_POST['product_scanned'];
    $product_type = $_POST['product_type'];
    $check_storage = $_POST['check_storage'];
    $data_received = $_POST['data_received'];
    $selling_price = $_POST['selling_price'];

    $query = "INSERT INTO officer_records (officer_id, warehouse_address, product_scanned, product_type, check_storage, data_received, selling_price) 
              VALUES ('$officer_id', '$warehouse_address', '$product_scanned', '$product_type', '$check_storage', '$data_received', '$selling_price')";
    $conn->query($query);
    echo "<script>alert('Record added successfully!');</script>";
}

// Delete Record
if (isset($_POST['delete_record'])) {
    $id = $_POST['id'];
    $query = "DELETE FROM officer_records WHERE id = $id";
    $conn->query($query);
    echo "<script>alert('Record deleted successfully!');</script>";
}

// Update Record
if (isset($_POST['update_record'])) {
    $id = $_POST['id'];
    $officer_id = $_POST['officer_id'];
    $warehouse_address = $_POST['warehouse_address'];
    $product_scanned = $_POST['product_scanned'];
    $product_type = $_POST['product_type'];
    $check_storage = $_POST['check_storage'];
    $data_received = $_POST['data_received'];
    $selling_price = $_POST['selling_price'];

    $query = "UPDATE officer_records SET 
              officer_id = '$officer_id',
              warehouse_address = '$warehouse_address',
              product_scanned = '$product_scanned',
              product_type = '$product_type',
              check_storage = '$check_storage',
              data_received = '$data_received',
              selling_price = '$selling_price'
              WHERE id = $id";
    $conn->query($query);
    echo "<script>alert('Record updated successfully!');</script>";
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Agriculture Officer Dashboard</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #87CEEB; /* Light sky background */
            color: #333;
            margin: 0;
            padding: 0;
        }
        .container {
            width: 80%;
            margin: 20px auto;
            padding: 20px;
            background-color: white;
            border-radius: 10px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.2);
        }
        h1 {
            text-align: center;
            margin-bottom: 20px;
        }
        form {
            margin-bottom: 20px;
        }
        form input, form select {
            width: calc(50% - 10px);
            padding: 10px;
            margin: 5px;
            border: 1px solid #ddd;
            border-radius: 5px;
        }
        form button {
            padding: 10px 20px;
            margin: 10px 0;
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }
        form button:hover {
            background-color: #45a049;
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        table, th, td {
            border: 1px solid #ddd;
        }
        th, td {
            padding: 15px;
            text-align: left;
        }
        .action-buttons {
            display: flex;
            gap: 10px;
        }
        .action-buttons button {
            padding: 5px 10px;
            border: none;
            cursor: pointer;
            border-radius: 3px;
        }
        .edit-button {
            background-color: #2196F3;
            color: white;
        }
        .delete-button {
            background-color: #f44336;
            color: white;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Agriculture Officer Dashboard</h1>

        <!-- Add/Update Form -->
        <form method="POST">
            <input type="hidden" name="id" id="id">
            <input type="text" name="officer_id" id="officer_id" placeholder="Officer ID" required>
            <input type="text" name="warehouse_address" id="warehouse_address" placeholder="Warehouse Address" required>
            <select name="product_scanned" id="product_scanned" required>
                <option value="">Product Scanned</option>
                <option value="Yes">Yes</option>
                <option value="No">No</option>
            </select>
            <select name="product_type" id="product_type" required>
                <option value="">Product Type</option>
                <option value="Rice">Rice</option>
                <option value="Wheat">Wheat</option>
                <option value="Onion">Onion</option>
                <option value="Potato">Potato</option>
                <option value="Tomato">Tomato</option>
                <option value="Chili">Chili</option>
                <option value="Corn">Corn</option>
            </select>
            <select name="check_storage" id="check_storage" required>
                <option value="">Check Storage</option>
                <option value="Yes">Yes</option>
                <option value="No">No</option>
            </select>
            <select name="data_received" id="data_received" required>
                <option value="">Data Received</option>
                <option value="Yes">Yes</option>
                <option value="No">No</option>
            </select>
            <input type="number" step="0.01" name="selling_price" id="selling_price" placeholder="Selling Price per KG" required>
            <button type="submit" name="add_record">Submit</button>
        </form>

        <!-- Records Table -->
        <table>
            <thead>
                <tr>
                    <th>Officer ID</th>
                    <th>Warehouse Address</th>
                    <th>Product Scanned</th>
                    <th>Product Type</th>
                    <th>Check Storage</th>
                    <th>Data Received</th>
                    <th>Selling Price (per KG)</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php
                // Fetch and Display Records
                $result = $conn->query("SELECT * FROM officer_records");
                while ($row = $result->fetch_assoc()) {
                    echo "
                    <tr>
                        <td>{$row['officer_id']}</td>
                        <td>{$row['warehouse_address']}</td>
                        <td>{$row['product_scanned']}</td>
                        <td>{$row['product_type']}</td>
                        <td>{$row['check_storage']}</td>
                        <td>{$row['data_received']}</td>
                        <td>{$row['selling_price']}</td>
                        <td class='action-buttons'>
                            <button class='edit-button' onclick='editRecord(".json_encode($row).")'>Edit</button>
                            <form method='POST' style='display:inline;'>
                                <input type='hidden' name='id' value='{$row['id']}'>
                                <button type='submit' name='delete_record' class='delete-button'>Delete</button>
                            </form>
                        </td>
                    </tr>";
                }
                ?>
            </tbody>
        </table>
    </div>

    <script>
        function editRecord(record) {
            document.getElementById('id').value = record.id;
            document.getElementById('officer_id').value = record.officer_id;
            document.getElementById('warehouse_address').value = record.warehouse_address;
            document.getElementById('product_scanned').value = record.product_scanned;
            document.getElementById('product_type').value = record.product_type;
            document.getElementById('check_storage').value = record.check_storage;
            document.getElementById('data_received').value = record.data_received;
            document.getElementById('selling_price').value = record.selling_price;
        }
    </script>
</body>
</html>

