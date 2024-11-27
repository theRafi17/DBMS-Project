<?php
// Database connection
$servername = "localhost";
$username = "root"; // Update with your database username
$password = "";     // Update with your database password
$dbname = "agriculture_product";

$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Insert data into the database
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['add_data'])) {
    $product_name = $_POST['product_name'];
    $specialization = $_POST['specialization'];
    $inspection_report = $_POST['inspection_report'];
    $inspection_product_info = $_POST['inspection_product_info'];
    $crops_quality = $_POST['crops_quality'];

    $sql = "INSERT INTO food_data (product_name, specialization, inspection_report, inspection_product_info, crops_quality)
            VALUES ('$product_name', '$specialization', '$inspection_report', '$inspection_product_info', '$crops_quality')";

    if ($conn->query($sql) === TRUE) {
        echo "New record created successfully!";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}

// Update data in the database
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['update_data'])) {
    $id = $_POST['id'];
    $product_name = $_POST['product_name'];
    $specialization = $_POST['specialization'];
    $inspection_report = $_POST['inspection_report'];
    $inspection_product_info = $_POST['inspection_product_info'];
    $crops_quality = $_POST['crops_quality'];

    $sql = "UPDATE food_data SET product_name='$product_name', specialization='$specialization', inspection_report='$inspection_report', 
            inspection_product_info='$inspection_product_info', crops_quality='$crops_quality' WHERE id='$id'";

    if ($conn->query($sql) === TRUE) {
        echo "Record updated successfully!";
    } else {
        echo "Error: " . $conn->error;
    }
}

// Delete data from the database
if ($_SERVER['REQUEST_METHOD'] == 'GET' && isset($_GET['delete_id'])) {
    $id = $_GET['delete_id'];

    $sql = "DELETE FROM food_data WHERE id='$id'";

    if ($conn->query($sql) === TRUE) {
        echo "Record deleted successfully!";
    } else {
        echo "Error: " . $conn->error;
    }
}

// Fetch all data from the database
$sql = "SELECT * FROM food_data";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Product Management</title>
</head>

<body>
    <h2>Add Product</h2>
    <form method="POST">
        <label for="product_name">Product Name:</label>
        <input type="text" id="product_name" name="product_name" required><br><br>

        <label for="specialization">Specialization:</label>
        <input type="text" id="specialization" name="specialization" required><br><br>

        <label for="inspection_report">Inspection Report:</label>
        <textarea id="inspection_report" name="inspection_report" required></textarea><br><br>

        <label for="inspection_product_info">Inspection Product Info:</label>
        <textarea id="inspection_product_info" name="inspection_product_info" required></textarea><br><br>

        <label for="crops_quality">Crops Quality:</label>
        <input type="text" id="crops_quality" name="crops_quality" required><br><br>

        <button type="submit" name="add_data">Add Data</button>
    </form>

    <h2>Product List</h2>
    <table border="1">
        <tr>
            <th>Product Name</th>
            <th>Specialization</th>
            <th>Inspection Report</th>
            <th>Inspection Product Info</th>
            <th>Crops Quality</th>
            <th>Actions</th>
        </tr>

        <?php
        // Display the data in the table
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                echo "<tr>
                        <td>{$row['product_name']}</td>
                        <td>{$row['specialization']}</td>
                        <td>{$row['inspection_report']}</td>
                        <td>{$row['inspection_product_info']}</td>
                        <td>{$row['crops_quality']}</td>
                        <td>
                            <form style='display:inline' method='POST'>
                                <input type='hidden' name='id' value='{$row['id']}'>
                                <input type='text' name='product_name' value='{$row['product_name']}' required>
                                <input type='text' name='specialization' value='{$row['specialization']}' required>
                                <textarea name='inspection_report' required>{$row['inspection_report']}</textarea>
                                <textarea name='inspection_product_info' required>{$row['inspection_product_info']}</textarea>
                                <input type='text' name='crops_quality' value='{$row['crops_quality']}' required>
                                <button type='submit' name='update_data'>Update</button>
                            </form>
                            <a href='?delete_id={$row['id']}'>Delete</a>
                        </td>
                    </tr>";
            }
        } else {
            echo "<tr><td colspan='6'>No data available</td></tr>";
        }
        ?>
    </table>

</body>

</html>

<?php
$conn->close();
?>