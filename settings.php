<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

// Include the database connection
include 'db.php';

// Fetch current user details
$user_id = $_SESSION['user_id'];
$sql = "SELECT username, email FROM users WHERE id = $user_id";
$result = $conn->query($sql);
$user = $result->fetch_assoc();

// Handle profile update
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Update user profile
    if (isset($_POST['update_profile'])) {
        $new_username = $conn->real_escape_string($_POST['username']);
        $new_email = $conn->real_escape_string($_POST['email']);

        $update_sql = "UPDATE users SET username='$new_username', email='$new_email' WHERE id=$user_id";
        $conn->query($update_sql);
        $message = "Profile updated successfully!";
    }

    // Change password
    if (isset($_POST['change_password'])) {
        $current_password = $_POST['current_password'];
        $new_password = $_POST['new_password'];
        $confirm_password = $_POST['confirm_password'];

        $user_sql = "SELECT password FROM users WHERE id=$user_id";
        $user_result = $conn->query($user_sql);
        $user_data = $user_result->fetch_assoc();

        if (password_verify($current_password, $user_data['password'])) {
            if ($new_password === $confirm_password && strlen($new_password) >= 8) {
                $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
                $password_update_sql = "UPDATE users SET password='$hashed_password' WHERE id=$user_id";
                $conn->query($password_update_sql);
                $message = "Password changed successfully!";
            } else {
                $error_message = "New passwords do not match or are too short!";
            }
        } else {
            $error_message = "Current password is incorrect!";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="styles/style.css">
    <title>Settings</title>
    <style>
        body {
            background-image: url('background.jpg');
            background-size: cover;
            font-family: Arial, sans-serif;
            padding: 20px;
        }
        .settings-container {
            background: rgba(255, 255, 255, 0.9);
            border-radius: 15px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.3);
            padding: 30px;
            max-width: 600px;
            margin: auto;
        }
        h1, h2 {
            color: #333;
        }
        input[type="text"],
        input[type="email"],
        input[type="password"] {
            width: 100%;
            padding: 10px;
            margin: 10px 0;
            border: 1px solid #ccc;
            border-radius: 5px;
            transition: border-color 0.3s;
        }
        input[type="text"]:focus,
        input[type="email"]:focus,
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
        .message {
            color: green;
            margin: 10px 0;
        }
        .error-message {
            color: red;
            margin: 10px 0;
        }
    </style>
</head>
<body>
    <div class="settings-container">
        <h1>Settings</h1>
        <nav>
            <a href="admin.php">Admin Dashboard</a>
            <a href="logout.php">Logout</a>
        </nav>

        <!-- Profile Update Section -->
        <h2>Update Profile</h2>
        <?php if (isset($message)) echo "<p class='message'>$message</p>"; ?>
        <form method="POST" action="settings.php">
            <input type="text" name="username" placeholder="Username" value="<?php echo $user['username']; ?>" required>
            <input type="email" name="email" placeholder="Email" value="<?php echo $user['email']; ?>" required>
            <button type="submit" name="update_profile">Update Profile</button>
        </form>

        <!-- Change Password Section -->
        <h2>Change Password</h2>
        <?php if (isset($error_message)) echo "<p class='error-message'>$error_message</p>"; ?>
        <form method="POST" action="settings.php">
            <input type="password" name="current_password" placeholder="Current Password" required>
            <input type="password" name="new_password" placeholder="New Password" required>
            <input type="password" name="confirm_password" placeholder="Confirm New Password" required>
            <button type="submit" name="change_password">Change Password</button>
        </form>
    </div>
</body>
</html>
