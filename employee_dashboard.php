<?php
session_start();

// Database connection
$servername = "localhost";
$username = "root"; // Your MySQL username
$password = "";     // Your MySQL password
$dbname = "agriculture_product"; // Your database name

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

// Add employee to the database
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['submit'])) {
  $employee_name = $_POST['employee_name'];
  $phone_no = $_POST['phone_no'];
  $email = $_POST['email'];
  $address = $_POST['address'];
  $role = $_POST['role'];
  $hire_date = $_POST['hire_date'];

  $stmt = $conn->prepare("INSERT INTO employees (employee_name, phone_no, email, address, role, hire_date) VALUES (?, ?, ?, ?, ?, ?)");
  $stmt->bind_param("ssssss", $employee_name, $phone_no, $email, $address, $role, $hire_date);
  if ($stmt->execute()) {
    echo "<script>alert('Employee added successfully!');</script>";
  } else {
    echo "<script>alert('Error adding employee.');</script>";
  }
  $stmt->close();
}

// Update employee data
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['update'])) {
  $employee_id = $_POST['employee_id'];
  $employee_name = $_POST['employee_name'];
  $phone_no = $_POST['phone_no'];
  $email = $_POST['email'];
  $address = $_POST['address'];
  $role = $_POST['role'];
  $hire_date = $_POST['hire_date'];

  $stmt = $conn->prepare("UPDATE employees SET employee_name=?, phone_no=?, email=?, address=?, role=?, hire_date=? WHERE employee_id=?");
  $stmt->bind_param("ssssssi", $employee_name, $phone_no, $email, $address, $role, $hire_date, $employee_id);
  if ($stmt->execute()) {
    echo "<script>alert('Employee updated successfully!');</script>";
  } else {
    echo "<script>alert('Error updating employee.');</script>";
  }
  $stmt->close();
}

// Delete employee
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['delete'])) {
  $employee_id = $_POST['employee_id'];

  $stmt = $conn->prepare("DELETE FROM employees WHERE employee_id=?");
  $stmt->bind_param("i", $employee_id);
  if ($stmt->execute()) {
    echo "<script>alert('Employee deleted successfully!');</script>";
  } else {
    echo "<script>alert('Error deleting employee.');</script>";
  }
  $stmt->close();
}

// Fetch employees from the database
$employeeQuery = "SELECT * FROM employees";
$employeeResult = $conn->query($employeeQuery);

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">

<head>
<a href="/admin_db/index.php" class="back-button">Back</a>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Employee Dashboard</title>
  <style>
    body {
      font-family: Arial, sans-serif;
      background-color: lightskyblue;
      margin: 0;
      padding: 0;
    }

    h1 {
      text-align: center;
      margin-top: 20px;
      color: #2980b9;
    }

    h2 {
      text-align: center;
      color: #2980b9;
    }

    .container {
      width: 80%;
      margin: 0 auto;
    }

    .form-container,
    .table-container {
      background: #fff;
      box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
      padding: 20px;
      margin-bottom: 20px;
    }

    input[type="text"],
    input[type="email"],
    input[type="tel"],
    input[type="date"],
    select {
      width: 100%;
      padding: 8px;
      margin: 10px 0;
      border-radius: 4px;
      border: 1px solid #ddd;
    }

    button {
      padding: 10px 15px;
      background-color: #2980b9;
      color: white;
      border: none;
      border-radius: 4px;
      cursor: pointer;
    }

    button:hover {
      background-color: #1c5985;
    }

    table {
      width: 100%;
      margin-top: 20px;
      border-collapse: collapse;
      background-color: #fff;
    }

    th,
    td {
      padding: 12px;
      text-align: center;
      border: 1px solid #ddd;
    }

    th {
      background-color: #2980b9;
      color: white;
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
  </style>
</head>

<body>

  <div class="container">
    <h1>Employee Dashboard</h1>

    <!-- Employee Form -->
    <div class="form-container">
      <h2>Add New Employee</h2>
      <form method="POST">
        <input type="text" name="employee_name" placeholder="Employee Name" required>
        <input type="tel" name="phone_no" placeholder="Phone Number" required>
        <input type="email" name="email" placeholder="Email" required>

        <!-- Address Dropdown Menu -->
        <input type="text" name="address" placeholder="Address" required>
        

        <select name="role" required>
          <option value="" disabled selected>Role</option>
          <option value="Manager">Manager</option>
          <option value="Developer">Developer</option>
          <option value="HR">HR</option>
          <option value="Sales">Sales</option>
        </select>
        <input type="date" name="hire_date" required>
        <button type="submit" name="submit">Submit</button>
      </form>
    </div>

    <!-- Employee Table -->
    <div class="table-container">
      <h2>Employee List</h2>
      <table>
        <thead>
          <tr>
            <th>Employee ID</th>
            <th>Employee Name</th>
            <th>Phone Number</th>
            <th>Email</th>
            <th>Address</th>
            <th>Role</th>
            <th>Hire Date</th>
            <th>Actions</th>
          </tr>
        </thead>
        <tbody>
          <?php if ($employeeResult && $employeeResult->num_rows > 0): ?>
            <?php while ($row = $employeeResult->fetch_assoc()): ?>
              <tr>
                <td><?= htmlspecialchars($row['employee_id']) ?></td>
                <td><?= htmlspecialchars($row['employee_name']) ?></td>
                <td><?= htmlspecialchars($row['phone_no']) ?></td>
                <td><?= htmlspecialchars($row['email']) ?></td>
                <td><?= htmlspecialchars($row['address']) ?></td>
                <td><?= htmlspecialchars($row['role']) ?></td>
                <td><?= htmlspecialchars($row['hire_date']) ?></td>
                <td>
                  <form method="POST" style="display:inline;">
                    <input type="hidden" name="employee_id" value="<?= $row['employee_id'] ?>">
                    <input type="text" name="employee_name" value="<?= $row['employee_name'] ?>" required>
                    <input type="tel" name="phone_no" value="<?= $row['phone_no'] ?>" required>
                    <input type="email" name="email" value="<?= $row['email'] ?>" required>

                    <!-- Address Dropdown Menu for Update -->
                    <input type="text" name="address" value="<?= $row['address'] ?>" required>
                    
                    
                    

                    <select name="role" required>
                      <option value="Manager" <?= $row['role'] == 'Manager' ? 'selected' : '' ?>>Manager</option>
                      <option value="Developer" <?= $row['role'] == 'Developer' ? 'selected' : '' ?>>Developer</option>
                      <option value="HR" <?= $row['role'] == 'HR' ? 'selected' : '' ?>>HR</option>
                      <option value="Sales" <?= $row['role'] == 'Sales' ? 'selected' : '' ?>>Sales</option>
                    </select>
                    <input type="date" name="hire_date" value="<?= $row['hire_date'] ?>" required>
                    <button type="submit" name="update">Update</button>
                  </form>

                  <form method="POST" style="display:inline;">
                    <input type="hidden" name="employee_id" value="<?= $row['employee_id'] ?>">
                    <button type="submit" name="delete" class="action-btn">Delete</button>
                  </form>
                </td>
              </tr>
            <?php endwhile; ?>
          <?php else: ?>
            <tr>
              <td colspan="8">No employees found.</td>
            </tr>
          <?php endif; ?>
        </tbody>
      </table>
    </div>
  </div>

</body>

</html>