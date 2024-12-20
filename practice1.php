<?php
// Database connection
$servername = "localhost";
$username = "root"; // Default XAMPP username
$password = ""; // Default XAMPP password
$dbname = "agriculture_product";

// Create connections
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $employeeId = $_POST['employeeId'];
    $specialization = $_POST['specialization'];
    $inspectionReport = $_POST['inspectionReport'];
    $inspectionProduct = $_POST['inspectionProduct'];
    $cropsQuality = $_POST['cropsQuality'];

    // Insert data into the database
    $sql = "INSERT INTO food_quality_data (employee_id, specialization, inspection_report, inspection_product, crops_quality)
            VALUES ('$employeeId', '$specialization', '$inspectionReport', '$inspectionProduct', '$cropsQuality')";
    
    if ($conn->query($sql) === TRUE) {
        echo "<script>alert('Data added successfully!');</script>";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}

// Fetch data from the database
$result = $conn->query("SELECT * FROM agriculture_product_data");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Food Quality Officer Data</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: linear-gradient(45deg, #f4d03f, #e74c3c, #8e44ad, #3498db);
            animation: gradientAnimation 10s ease infinite;
            color: #333;
            margin: 0;
            padding: 0;
            display: flex;
            flex-direction: column;
            align-items: center;
            min-height: 100vh;
        }
        @keyframes gradientAnimation {
            0%, 100% { background-color: #f4d03f; }
            25% { background-color: #e74c3c; }
            50% { background-color: #8e44ad; }
            75% { background-color: #3498db; }
        }
        .form-container, table {
            width: 90%;
            max-width: 600px;
            margin: 20px;
            padding: 15px;
            background-color: rgba(255, 255, 255, 0.9);
            border-radius: 8px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }
        label, input, select, button, table th, table td {
            font-size: 14px;
            margin: 10px 0;
        }
        input, select, button {
            width: 100%;
            padding: 10px;
            border-radius: 4px;
            border: 1px solid #ddd;
        }
        button {
            background-color: #3498db;
            color: white;
            cursor: pointer;
        }
        button:hover {
            background-color: #2980b9;
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        table th, table td {
            padding: 12px;
            border: 1px solid #ddd;
            text-align: center;
        }
        table th {
            background-color: #2c3e50;
            color: white;
        }
        table tr:nth-child(even) {
            background-color: #f9f9f9;
        }
        table tr:hover {
            background-color: #e6f7ff;
        }
    </style>
</head>
<body>
    <div class="form-container">
        <h1>Food Quality Officer Data</h1>
        <form method="POST" action="">
            <label for="employeeId">Employee ID:</label>
            <input type="text" id="employeeId" name="employeeId" required>

            <label for="specialization">Specialization:</label>
            <select id="specialization" name="specialization">
                <option value="Good">Good</option>
                <option value="Average">Average</option>
                <option value="Bad">Bad</option>
            </select>

            <label for="inspectionReport">Inspection Report:</label>
            <select id="inspectionReport" name="inspectionReport">
                <option value="Good">Good</option>
                <option value="Bad">Bad</option>
            </select>

            <label for="inspectionProduct">Inspection Product Info:</label>
            <select id="inspectionProduct" name="inspectionProduct">
                <option value="Rice">Rice</option>
                <option value="Bean">Bean</option>
                <option value="Potato">Potato</option>
                <option value="Tomato">Tomato</option>
                <option value="Onion">Onion</option>
                <option value="Chili">Chili</option>
                <option value="Corn">Corn</option>
            </select>

            <label for="cropsQuality">Crops Quality:</label>
            <select id="cropsQuality" name="cropsQuality">
                <option value="Good">Good</option>
                <option value="Average">Average</option>
                <option value="Bad">Bad</option>
            </select>

            <button type="submit">Add Data</button>
        </form>
    </div>

    <table>
        <thead>
            <tr>
                <th>Employee ID</th>
                <th>Specialization</th>
                <th>Inspection Report</th>
                <th>Inspection Product</th>
                <th>Crops Quality</th>
            </tr>
        </thead>
        <tbody>
            <?php if ($result->num_rows > 0): ?>
                <?php while ($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo $row['employee_id']; ?></td>
                        <td><?php echo $row['specialization']; ?></td>
                        <td><?php echo $row['inspection_report']; ?></td>
                        <td><?php echo $row['inspection_product']; ?></td>
                        <td><?php echo $row['crops_quality']; ?></td>
                    </tr>
                <?php endwhile; ?>
            <?php else: ?>
                <tr>
                    <td colspan="5">No data found.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</body>
</html>
