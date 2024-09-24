<?php
session_start();
include 'db.php';

// Check if user is logged in (in this case, a student)
if (!isset($_SESSION['student_id'])) {
    header("Location: login.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $student_id = $_SESSION['student_id'];
    $feedback_text = $_POST['feedback_text'];

    $stmt = $conn->prepare("INSERT INTO feedback (student_id, feedback_text) VALUES (?, ?)");
    $stmt->bind_param("is", $student_id, $feedback_text);
    
    if ($stmt->execute()) {
        $success = "Feedback submitted successfully!";
    } else {
        $error = "Failed to submit feedback.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="styles/style.css">
    <title>Submit Feedback</title>
</head>
<body>
    <h1>Submit Feedback</h1>
    <?php if (isset($success)) echo "<p style='color:green;'>$success</p>"; ?>
    <?php if (isset($error)) echo "<p style='color:red;'>$error</p>"; ?>
    <form method="POST" action="">
        <textarea name="feedback_text" rows="5" placeholder="Your feedback..." required></textarea>
        <button type="submit">Submit Feedback</button>
    </form>
    <nav>
        <a href="index.php">Back to Home</a>
    </nav>
</body>
</html>
