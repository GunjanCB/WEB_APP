<?php
session_start();
include('db.php');

$loginError = "";

// Handle Login
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['login'])) {
    $email = $_POST['login_email'];
    $password = $_POST['login_password'];

    // Fetch user data from the database
    $query = "SELECT * FROM users WHERE email = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        $user = $result->fetch_assoc();

        // Verify password
        if (password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];
            header("Location: profile.php"); // Redirect to profile page
            exit();
        } else {
            $loginError = "Invalid email or password!";
        }
    } else {
        $loginError = "User not found!";
    }

    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <header>
        <nav>
            <ul>
                <li><a href="index.php">Home</a></li>
                <li><a href="login.php">Login</a></li>
                <li><a href="register.php">Register</a></li>
            </ul>
        </nav>
    </header>
    <main>
        <h2>Login</h2>
        <?php if (!empty($loginError)): ?>
            <p class="error"><?php echo $loginError; ?></p>
        <?php endif; ?>
        <form action="login.php" method="post">
            <label>Email:</label>
            <input type="email" name="login_email" required>
            <label>Password:</label>
            <input type="password" name="login_password" required>
            <button type="submit" name="login">Login</button>
        </form>
    </main>
    <footer>
        <p>&copy; 2025 Web App</p>
    </footer>
</body>
</html>
