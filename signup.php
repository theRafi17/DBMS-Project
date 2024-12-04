<?php
require 'config.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $role = $_POST['role'];
    $password = password_hash($_POST['password'], PASSWORD_BCRYPT); // Encrypt password

    try {
        $stmt = $conn->prepare("INSERT INTO users (name, email, phone, role, password) VALUES (:name, :email, :phone, :role, :password)");
        $stmt->execute([
            ':name' => $name,
            ':email' => $email,
            ':phone' => $phone,
            ':role' => $role,
            ':password' => $password
        ]);
        echo "Signup successful!";
    } catch (PDOException $e) {
        if ($e->getCode() == 23000) {
            echo "Email already exists!";
        } else {
            echo "Error: " . $e->getMessage();
        }
    }
}
?>
<form id="signup-form" action="signup.php" method="POST">
    <label for="name">Name</label>

    

    <input type="text" id="name" name="name" required>

    <label for="email">Email</label>
    <input type="email" id="email" name="email" required>

    <label for="phone">Phone Number</label>
    <input type="tel" id="phone" name="phone" required>

    <label for="role">Employee Role</label>
    <select id="role" name="role" required>
        <option value="Agricultural Officer">Agricultural Officer</option>
        <option value="Farmer">Farmer</option>
        <option value="Customer">Customer</option>
        <option value="Food Quality Officer">Food Quality Officer</option>
        <option value="Market Manager">Market Manager</option>
        <option value="Warehouse Manager">Warehouse Manager</option>
    </select>

    <label for="password">Password</label>
    <input type="password" id="password" name="password" required>

    <button type="submit">Signup</button>

    <link rel="stylesheet" href="signup_php.css">

</form>
