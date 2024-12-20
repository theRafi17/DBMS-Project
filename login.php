<?php
require 'config.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $role = $_POST['role'];
    $password = $_POST['password'];

    try {
        $stmt = $conn->prepare("SELECT * FROM users WHERE email = :email AND phone = :phone AND role = :role");
        $stmt->execute([
            ':email' => $email,
            ':phone' => $phone,
            ':role' => $role
        ]);

        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user && password_verify($password, $user['password'])) {
            echo "Login successful! Welcome, " . $user['name'];
        } else {
            echo "Invalid credentials!";
        }
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
}
?>
<form id="login-form" action="login.php" method="POST">
    <label for="email-login">Email</label>
    


    <input type="email" id="email-login" name="email" required>

    <label for="phone">Phone Number</label>
    <input type="tel" id="phone" name="phone" required>

    <label for="password-login">Password</label>
    <input type="password" id="password-login" name="password" required>

    <label for="role">Employee Role</label>
    <select id="role" name="role" required>
        <option value="Agricultural Officer">Agricultural Officer</option>
        <option value="Farmer">Farmer</option>
        <option value="Customer">Customer</option>
        <option value="Food Quality Officer">Food Quality Officer</option>
        <option value="Market Manager">Market Manager</option>
        <option value="Warehouse Manager">Warehouse Manager</option>
    </select>

    <button type="submit">Login</button>

    <link rel="stylesheet" href="login_php.css">
</form>
