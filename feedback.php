<?php
session_start();
include 'db.php'; // Database connection

// Initialize variables and error message
$error = '';
$success = '';

// Process feedback submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $feedback = $_POST['feedback'];

    // Prevent SQL Injection
    $feedback = $conn->real_escape_string($feedback);

    // Basic validation
    if (empty($feedback)) {
        $error = "Feedback cannot be empty.";
    } else {
        // Insert feedback into the database
        $sql = "INSERT INTO feedback (user_id, message, date) VALUES ('{$_SESSION['user_id']}', '$feedback', NOW())";
        if ($conn->query($sql) === TRUE) {
            $success = "Feedback submitted successfully!";
        } else {
            $error = "Error: " . $conn->error;
        }
    }
}

// Fetch all feedback for display
$sql = "SELECT * FROM feedback";
$feedback_result = $conn->query($sql);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="styles/style.css">
    <title>Feedback</title>
</head>
<body>
    <header>
        <h1>Feedback</h1>
    </header>
    <div class="container">
        <?php
        if (!empty($error)) {
            echo "<p class='error-message'>$error</p>";
        }
        if (!empty($success)) {
            echo "<p class='success-message'>$success</p>";
        }
        ?>
        <form method="POST" action="feedback.php">
            <textarea name="feedback" placeholder="Enter your feedback..." required></textarea>
            <button type="submit">Submit Feedback</button>
        </form>
        
        <h2>Feedback Received:</h2>
        <ul>
            <?php
            if ($feedback_result->num_rows > 0) {
                while ($row = $feedback_result->fetch_assoc()) {
                    echo "<li><strong>{$row['date']}:</strong> {$row['message']}</li>";
                }
            } else {
                echo "<li>No feedback received yet.</li>";
            }
            ?>
        </ul>
        <a href="admin.php">Back to Admin Dashboard</a>
    </div>
    <footer>
        <p>&copy; 2024 School Management System</p>
    </footer>
</body>
</html>
