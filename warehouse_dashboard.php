<?php
// Database connection
$conn = new mysqli('localhost', 'root', '', 'agriculture_product'); // Adjust credentials as needed

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Handle CRUD operations
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'];
    $id = $_POST['id'] ?? null;
    $govt_office_id = $_POST['govt_office_id'] ?? null;
    $address = $_POST['address'] ?? null;
    $storage_capacity = $_POST['storage_capacity'] ?? null;
    $current_stock_level = $_POST['current_stock_level'] ?? null;

    if ($action === 'add') {
        $stmt = $conn->prepare("INSERT INTO warehouses (govt_office_id, address, storage_capacity, current_stock_level) VALUES (?, ?, ?, ?)");
        $stmt->bind_param('ssdd', $govt_office_id, $address, $storage_capacity, $current_stock_level);
        $stmt->execute();
        echo json_encode(['success' => true]);
    } elseif ($action === 'edit') {
        $stmt = $conn->prepare("UPDATE warehouses SET govt_office_id = ?, address = ?, storage_capacity = ?, current_stock_level = ? WHERE id = ?");
        $stmt->bind_param('ssddi', $govt_office_id, $address, $storage_capacity, $current_stock_level, $id);
        $stmt->execute();
        echo json_encode(['success' => true]);
    } elseif ($action === 'delete') {
        $stmt = $conn->prepare("DELETE FROM warehouses WHERE id = ?");
        $stmt->bind_param('i', $id);
        $stmt->execute();
        echo json_encode(['success' => true]);
    }
    exit;
}

// Fetch all data for display
$result = $conn->query("SELECT * FROM warehouses");
$warehouses = $result->fetch_all(MYSQLI_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <a href="/admin_db/index.php" class="back-button">Back</a>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Warehouse Dashboard</title>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <link rel="stylesheet" href="war_styles.css">

    <style>
        /* Add your styles here */
    </style>
</head>
<body>
    <h1>Government Warehouse Dashboard</h1>
    <div class="form-container">
        <h2>Add or Update Warehouse Data</h2>
        <form id="warehouseForm">
            <input type="hidden" id="id" name="id">
            <label for="govtOfficeId">Government Office ID:</label>
            <input type="text" id="govtOfficeId" name="govt_office_id" required>
            <label for="address">Address:</label>
            <input type="text" id="address" name="address" required>
            <label for="storageCapacity">Storage Capacity (m³):</label>
            <input type="number" id="storageCapacity" name="storage_capacity" min="0" required>
            <label for="currentStockLevel">Current Stock Level (Kg):</label>
            <input type="number" id="currentStockLevel" name="current_stock_level" min="0" required>
            <input type="hidden" id="action" name="action" value="add">
            <button type="submit">Submit</button>
        </form>
    </div>
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Government Office ID</th>
                <th>Address</th>
                <th>Storage Capacity (m³)</th>
                <th>Current Stock Level (Kg)</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody id="warehouseTableBody">
            <?php foreach ($warehouses as $warehouse): ?>
            <tr>
                <td><?= $warehouse['id'] ?></td>
                <td><?= $warehouse['govt_office_id'] ?></td>
                <td><?= $warehouse['address'] ?></td>
                <td><?= $warehouse['storage_capacity'] ?></td>
                <td><?= $warehouse['current_stock_level'] ?></td>
                <td>
                    <button onclick="editWarehouse(<?= htmlspecialchars(json_encode($warehouse)) ?>)">Edit</button>
                    <button onclick="deleteWarehouse(<?= $warehouse['id'] ?>)">Delete</button>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    <div class="chart-container">
        <canvas id="stockLevelChart"></canvas>
    </div>
    <script>
        function editWarehouse(data) {
            document.getElementById('id').value = data.id;
            document.getElementById('govtOfficeId').value = data.govt_office_id;
            document.getElementById('address').value = data.address;
            document.getElementById('storageCapacity').value = data.storage_capacity;
            document.getElementById('currentStockLevel').value = data.current_stock_level;
            document.getElementById('action').value = 'edit';
        }

        function deleteWarehouse(id) {
            if (confirm('Are you sure you want to delete this entry?')) {
                const formData = new FormData();
                formData.append('action', 'delete');
                formData.append('id', id);
                fetch('', { method: 'POST', body: formData })
                    .then(response => response.json())
                    .then(() => location.reload());
            }
        }

        document.getElementById('warehouseForm').addEventListener('submit', function (e) {
            e.preventDefault();
            const formData = new FormData(this);
            fetch('', { method: 'POST', body: formData })
                .then(response => response.json())
                .then(() => location.reload());
        });

        // Render Chart
        const ctx = document.getElementById('stockLevelChart').getContext('2d');
        const data = <?= json_encode(array_column($warehouses, 'current_stock_level')) ?>;
        const labels = <?= json_encode(array_column($warehouses, 'govt_office_id')) ?>;
        new Chart(ctx, {
            type: 'bar',
            data: {
                labels: labels,
                datasets: [{
                    label: 'Current Stock Level (Kg)',
                    data: data,
                    backgroundColor: '#3498db'
                }]
            },
            options: {
                responsive: true,
                scales: {
                    y: { beginAtZero: true }
                }
            }
        });
    </script>
</body>
</html>