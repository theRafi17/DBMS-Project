<?php
session_start(); // Start session to store user data

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $conn = new mysqli("localhost", "root", "", "agriculture_product");

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    $email = $_POST['email'];
    $password = $_POST['password'];
    $role = $_POST['role'];

    $stmt = $conn->prepare("SELECT * FROM users WHERE email = ? AND role = ?");
    $stmt->bind_param("ss", $email, $role);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
        if (password_verify($password, $user['password'])) {
            // Set session variables
            $_SESSION['name'] = $user['name'];
            $_SESSION['role'] = $user['role'];

            // Redirect to dashboard based on role
            switch ($user['role']) {
                case 'Admin':
                    header("Location: /admin_db/index.php");
                    break;
                case 'Consumer':
                    header("Location: /admin_db/Consumer_dashboard.php");
                    break;
                case 'User':
                    header("Location: Consumer_dashboard.php");
                    break;
                case 'Guest':
                    header("Location: guest_dashboard.php");
                    break;
                default:
                    echo "Role not recognized.";
            }
            exit;
        } else {
            echo "Incorrect password.";
        }
    } else {
        echo "No user found with the given email and role.";
    }

    $conn->close();
}
?>
<!DOCTYPE html>


<html>

<head>
    <title>Login</title>
    <link rel="stylesheet" type="text/css" href="login_php.css">
</head>

<body>
    <form method="POST">
        <label>Email:</label><input type="email" name="email" required><br>
        <label>Password:</label><input type="password" name="password" required><br>
        <label>Role:</label>
        <select name="role" required>
            <option value="Admin">Admin</option>
            <option value="Consumer">Consumer</option>
            <option value="User">User</option>
            <option value="Guest">Guest</option>
        </select><br>
        <button type="submit">Login</button>
    </form>
</body>

</html>