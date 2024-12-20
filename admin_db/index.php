<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="style.css">
</head>

<body>
    <!-- Sidebar -->
    <aside id="sidebar">
        <div class="logo">
            <h2><a href="#">AgriDashboard</a></h2>
        </div>
        <nav>
            <ul>
                <li><a href="#overview">Dashboard Overview</a></li>
                <li><a href="../Product_dashboard.php">Products</a></li>
                <li><a href="../warhouse_dashboard.php">Warhouse Dashboard</a></li>
                <li><a href="../Consumer_dashboard.php">Consumer Dashboard</a></li>
                <li><a href="../employee_dashboard.php">Employee Dashboard</a></li>
                <li><a href="#inventory">Inventory Levels</a></li>
            </ul>
        </nav>
    </aside>

    <!-- Main Content -->
    <main id="main-content">
        <!-- Header -->
        <header>
            <div class="header-left">
                <h1>Admin Dashboard</h1>
            </div>
            <div class="header-right">
                <a href="../login.php"><button >Logout</button></a>
            </div>
        </header>

        <!-- Dashboard Overview Section -->
        <section id="overview">
            <h2>Dashboard Overview</h2>
            <div class="overview-stats">
                <div class="stat-card">
                    <h3>Products Overview</h3>
                    <p>Total Products: 120</p>
                    <p>Active Products: 95</p>
                </div>
                <div class="stat-card">
                    <h3>Market Price Trends</h3>
                    <p>Average Price Change: +3.2%</p>
                </div>
                <div class="stat-card">
                    <h3>Real-time Inventory</h3>
                    <p>Current Stock: 25,000 units</p>
                    <p>In-Transit: 5,000 units</p>
                </div>
            </div>
        </section>

        <!-- Demand & Supply Section -->
        <section id="demand-supply">
            <h2>Demand vs Supply</h2>
            <div class="chart-container">
                <p>Demand and Supply Chart will go here.</p>
            </div>
        </section>

        <!-- Market Price Trends Section -->
        <section id="market-price">
            <h2>Market Price Trends</h2>
            <div class="chart-container">
                <p>Market Price Trend Chart will go here.</p>
            </div>
        </section>
        <!-- Warhouse  Section -->
        

        <!-- Inventory Levels Section -->
        <section id="inventory">
            <h2>Real-time Inventory Levels</h2>
            <table>
                <thead>
                    <tr>
                        <th>Product</th>
                        <th>Available Stock</th>
                        <th>In-Transit</th>
                        <th>Location</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>Tomato</td>
                        <td>12,000 units</td>
                        <td>3,000 units</td>
                        <td>Warehouse A</td>
                    </tr>
                    <tr>
                        <td>Rice</td>
                        <td>8,000 units</td>
                        <td>2,500 units</td>
                        <td>Warehouse B</td>
                    </tr>
                </tbody>
            </table>
        </section>

        <!-- Analytics Section -->
        <section id="analytics">
            <h2>Analytics</h2>
            <div class="chart-container">
                <p>Interactive Charts and Forecasting Tools will go here.</p>
            </div>
        </section>

        <!-- Reports Section -->
        <section id="reports">
            <h2>Reports</h2>
            <button>Export Data</button>
            <button>Download Report</button>
        </section>

        <!-- User Settings Section -->
        <section id="settings">
            <h2>User Settings</h2>
            <p>Manage your profile, preferences, and settings here.</p>
        </section>
    </main>

    <!-- <script>
        function logout() {
            alert("Logging out...");
        }
    </script> -->
</body>

</html>