<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login and Signup Page</title>
    <link rel="stylesheet" href="style.css">
   
</head>
<body>
    <div class="container">
        <!-- Signup Form -->
        <div class="form-container">
            <h2>Signup</h2>
            <form id="signup-form">
                <label for="name">Name</label>

                <label for="email">Email</label>
                <input type="email" id="email" name="email" required>

                <label for="phone">Phone Number</label>
                <input type="tel" id="phone" name="phone" required>

                <label for="role">Employee Role</label>
                <select id="role" name="role" required>
                    <option value="Agricultural Officer">Agricultural Officer</option>
                    <option value="Customer">Customer</option>
                </select>

                <button type="submit">Signup</button>
            </form>
        </div>

        <!-- Login Form -->
        <div class="form-container">
            <h2>Login</h2>
            <form id="login-form">
                <label for="email-login">Email</label>
                <input type="email" id="email-login" name="email" required>

                <label for="password-login">Password</label>
                <input type="password" id="password-login" name="password" required>

                <button type="submit">Login</button>
            </form>
        </div>
    </div>
</body>
</html>
