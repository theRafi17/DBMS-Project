<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $conn = new mysqli("localhost", "root", "", "agriculture_product");

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    $name = $_POST['name'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $phone = $_POST['phone'];
    $role = $_POST['role'];

    $stmt = $conn->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        echo "Email already exists.";
    } else {
        $stmt = $conn->prepare("INSERT INTO users (name, email, password, phone, role) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("sssss", $name, $email, $password, $phone, $role);
        if ($stmt->execute()) {
            echo "Registration successful. <a href='login.php'>Login here</a>";
        } else {
            echo "Error: " . $stmt->error;
        }
    }

    $conn->close();
}
?>
<!DOCTYPE html>
<html>

<head>
    <title>Sign Up</title>
    <link rel="stylesheet" type="text/css" href="login_php.css">
</head>

<body>
    <form method="POST">
        <label>Name:</label><input type="text" name="name" required><br>
        <label>Email:</label><input type="email" name="email" required><br>
        <label>Password:</label><input type="password" name="password" required><br>
        <label>Phone:</label><input type="text" name="phone" required><br>
        <label>Role:</label>
        <select name="role" required>
            <option value="Admin">Admin</option>
            <option value="User">User</option>
            <option value="Guest">Guest</option>
        </select><br>
        <button type="submit">Sign Up</button>
    </form>
</body>

</html>