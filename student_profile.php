<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

// Include the database connection
include 'db.php';

// Fetch the student details based on ID
if (isset($_GET['id'])) {
    $student_id = (int)$_GET['id'];
    $sql = "SELECT * FROM students WHERE id = $student_id";
    $result = $conn->query($sql);
    $student = $result->fetch_assoc();
} else {
    header("Location: view_students.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="styles/style.css">
    <title>Student Profile</title>
</head>
<body>
    <h1>Student Profile</h1>
    <nav>
        <a href="view_students.php">View Students</a>
        <a href="logout.php">Logout</a>
    </nav>

    <?php if ($student): ?>
        <h2><?php echo $student['name']; ?></h2>
        <p><strong>School ID:</strong> <?php echo $student['school_id']; ?></p>
        <p><strong>Class:</strong> <?php echo $student['class']; ?></p>
        <p><strong>Email:</strong> <?php echo $student['email']; ?></p>
        <p><strong>Phone:</strong> <?php echo $student['phone']; ?></p>
    <?php else: ?>
        <p>No student found.</p>
    <?php endif; ?>
</body>
</html>
