<?php
session_start();
include 'db.php'; // Database connection

// Initialize an empty error message
$error = '';

// Check for remembered username
if (isset($_COOKIE['username'])) {
    $username = $_COOKIE['username'];
} else {
    $username = '';
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Prevent SQL Injection
    $username = $conn->real_escape_string($username);
    $password = $conn->real_escape_string($password);

    // Check if user exists
    $sql = "SELECT * FROM users WHERE username='$username'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        
        // Verify the password with password_hash
        if (password_verify($password, $row['password'])) {
            // Set session variables and redirect to admin page
            $_SESSION['user_id'] = $row['id'];
            $_SESSION['username'] = $row['username'];

            // Check if 'Remember Me' is checked
            if (isset($_POST['remember'])) {
                // Set cookie for 1 week
                setcookie("username", $username, time() + (86400 * 7), "/"); // 86400 = 1 day
            }

            header("Location: admin.php");
            exit();
        } else {
            $error = "Invalid username or password!";
        }
    } else {
        $error = "Invalid username or password!";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="styles/style.css">
    <title>Login</title>
    <style>
        body {
            background-image: url('background.jpg');
            background-size: cover;
            font-family: Arial, sans-serif;
            display: flex;
            justify-content: flex-end; /* Align to the right */
            align-items: center;
            height: 100vh;
            margin: 0;
        }

        .login-container {
            background: rgba(255, 255, 255, 0.9); /* White background with slight transparency */
            border-radius: 15px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.3);
            padding: 40px;
            width: 350px; /* Adjusted width */
            text-align: center;
            margin-right: 20px; /* Spacing from the edge */
        }

        h2 {
            margin-bottom: 20px;
            color: #333;
        }

        input[type="text"],
        input[type="password"] {
            width: 100%;
            padding: 10px;
            margin: 10px 0;
            border: 1px solid #ccc;
            border-radius: 5px;
            transition: border-color 0.3s;
        }

        input[type="text"]:focus,
        input[type="password"]:focus {
            border-color: #2575fc;
            outline: none;
        }

        button {
            background: #2575fc;
            color: white;
            padding: 10px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            width: 100%;
            font-size: 16px;
            transition: background-color 0.3s;
        }

        button:hover {
            background: #1d5bb5;
        }

        .error-message {
            color: red;
            margin-bottom: 20px;
        }

        a {
            color: #2575fc;
            text-decoration: none;
            font-size: 14px;
            margin-top: 10px;
            display: inline-block;
        }

        a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="login-container">
        <h2>Login</h2>

        <?php
        if (!empty($error)) {
            echo "<p class='error-message'>$error</p>";
        }
        ?>

        <form method="POST" action="login.php">
            <input type="text" name="username" placeholder="Username" value="<?php echo htmlspecialchars($username); ?>" required>
            <input type="password" name="password" placeholder="Password" required>
            <input type="checkbox" name="remember" id="remember" <?php if (!empty($username)) echo 'checked'; ?>>
            <label for="remember">Remember Me</label>
            <button type="submit">Login</button>
        </form>

        <a href="#">Forgot Password?</a>
    </div>
</body>
</html>
