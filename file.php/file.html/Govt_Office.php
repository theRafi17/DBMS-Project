<?php
// Database connection
$conn = new mysqli('localhost', 'root', '', 'agriculture_product');
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['add'])) {
        $office_id = $_POST['office_id'];
        $address = $_POST['address'];
        $road_no = $_POST['road_no'];
        $house_no = $_POST['house_no'];
        $storage_capacity = $_POST['storage_capacity'];
        $current_stock_level = $_POST['current_stock_level'];

        $stmt = $conn->prepare("INSERT INTO offices (office_id, address, road_no, house_no, storage_capacity, current_stock_level) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("ssssss", $office_id, $address, $road_no, $house_no, $storage_capacity, $current_stock_level);
        $stmt->execute();
        $stmt->close();
    } elseif (isset($_POST['delete'])) {
        $id = $_POST['id'];
        $conn->query("DELETE FROM offices WHERE id = $id");
    } elseif (isset($_POST['update'])) {
        $id = $_POST['id'];
        $office_id = $_POST['office_id'];
        $address = $_POST['address'];
        $road_no = $_POST['road_no'];
        $house_no = $_POST['house_no'];
        $storage_capacity = $_POST['storage_capacity'];
        $current_stock_level = $_POST['current_stock_level'];

        $stmt = $conn->prepare("UPDATE offices SET office_id=?, address=?, road_no=?, house_no=?, storage_capacity=?, current_stock_level=? WHERE id=?");
        $stmt->bind_param("ssssssi", $office_id, $address, $road_no, $house_no, $storage_capacity, $current_stock_level, $id);
        $stmt->execute();
        $stmt->close();
    }
}

// Fetch data for table
$result = $conn->query("SELECT * FROM offices");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Government Office Dashboard</title>
    <style>
        body {
            background-color: lightblue;
            font-family: Arial, sans-serif;
        }
        .container {
            margin: 50px auto;
            max-width: 800px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        table, th, td {
            border: 1px solid #000;
        }
        th, td {
            padding: 10px;
            text-align: left;
        }
        form {
            margin-top: 20px;
        }
        .form-control {
            margin-bottom: 10px;
        }
        .form-control label {
            display: block;
        }
        .form-control input, select {
            width: 100%;
            padding: 5px;
        }
        .form-actions button {
            padding: 10px 20px;
            margin-right: 10px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Government Office Dashboard</h1>
        <form method="POST">
            <div class="form-control">
                <label for="office_id">Office ID:</label>
                <input type="text" id="office_id" name="office_id" required>
            </div>
            <div class="form-control">
                <label for="address">Address:</label>
                <input type="text" id="address" name="address" required>
            </div>
            <div class="form-control">
                <label for="road_no">Road No:</label>
                <input type="text" id="road_no" name="road_no" required>
            </div>
            <div class="form-control">
                <label for="house_no">House No:</label>
                <input type="text" id="house_no" name="house_no" required>
            </div>
            <div class="form-control">
                <label for="storage_capacity">Storage Capacity:</label>
                <select id="storage_capacity" name="storage_capacity">
                    <option value="Full">Full</option>
                    <option value="Empty">Empty</option>
                </select>
            </div>
            <div class="form-control">
                <label for="current_stock_level">Current Stock Level:</label>
                <select id="current_stock_level" name="current_stock_level">
                    <option value="High">High</option>
                    <option value="Low">Low</option>
                </select>
            </div>
            <div class="form-actions">
                <button type="submit" name="add">Submit</button>
            </div>
        </form>

        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Office ID</th>
                    <th>Address</th>
                    <th>Road No</th>
                    <th>House No</th>
                    <th>Storage Capacity</th>
                    <th>Stock Level</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td><?= $row['id'] ?></td>
                        <td><?= $row['office_id'] ?></td>
                        <td><?= $row['address'] ?></td>
                        <td><?= $row['road_no'] ?></td>
                        <td><?= $row['house_no'] ?></td>
                        <td><?= $row['storage_capacity'] ?></td>
                        <td><?= $row['current_stock_level'] ?></td>
                        <td>
                            <form method="POST" style="display:inline-block;">
                                <input type="hidden" name="id" value="<?= $row['id'] ?>">
                                <button type="submit" name="delete">Delete</button>
                            </form>
                            <form method="POST" style="display:inline-block;">
                                <input type="hidden" name="id" value="<?= $row['id'] ?>">
                                <input type="text" name="office_id" value="<?= $row['office_id'] ?>" required>
                                <input type="text" name="address" value="<?= $row['address'] ?>" required>
                                <input type="text" name="road_no" value="<?= $row['road_no'] ?>" required>
                                <input type="text" name="house_no" value="<?= $row['house_no'] ?>" required>
                                <select name="storage_capacity">
                                    <option value="Full" <?= $row['storage_capacity'] == 'Full' ? 'selected' : '' ?>>Full</option>
                                    <option value="Empty" <?= $row['storage_capacity'] == 'Empty' ? 'selected' : '' ?>>Empty</option>
                                </select>
                                <select name="current_stock_level">
                                    <option value="High" <?= $row['current_stock_level'] == 'High' ? 'selected' : '' ?>>High</option>
                                    <option value="Low" <?= $row['current_stock_level'] == 'Low' ? 'selected' : '' ?>>Low</option>
                                </select>
                                <button type="submit" name="update">Update</button>
                            </form>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</body>
</html>
