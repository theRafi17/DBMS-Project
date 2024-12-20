<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Warehouse Dashboard</title>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <link rel="stylesheet" href="../admin_db/ware_style.css">

    <style>
        /* Add your styles here */
        .back-button {
            margin-bottom: 20px;
            padding: 10px 20px;
            background-color: #f39c12;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 16px;
        }

        .back-button:hover {
            background-color: #e67e22;
        }
    </style>
</head>
<body>
    <h1>Government Warehouse Dashboard</h1>
    
    <!-- Back Button -->
    <button class="back-button" onclick="goBack()">Back to Dashboard</button>

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
        // Function for Back Button
        function goBack() {
            window.location.href = 'dashboard.php'; // Replace 'dashboard.php' with your actual dashboard page URL
        }

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
