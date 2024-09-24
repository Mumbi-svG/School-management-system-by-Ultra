<?php
session_start();
include 'db.php'; // Database connection

// Initialize variables and error message
$error = '';
$success = '';

// Process the form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $school_id = $_POST['school_id'];
    $name = $_POST['name'];
    $class = $_POST['class'];

    // Prevent SQL Injection
    $school_id = $conn->real_escape_string($school_id);
    $name = $conn->real_escape_string($name);
    $class = $conn->real_escape_string($class);

    // Basic validation
    if (empty($school_id) || empty($name) || empty($class)) {
        $error = "All fields are required.";
    } else {
        // Insert student into the database
        $sql = "INSERT INTO students (school_id, name, class) VALUES ('$school_id', '$name', '$class')";
        if ($conn->query($sql) === TRUE) {
            $success = "Student added successfully!";
        } else {
            $error = "Error: " . $conn->error;
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
    <title>Add Student</title>
</head>
<body>
    <header>
        <h1>Add Student</h1>
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
        <form method="POST" action="add_student.php">
            <input type="text" name="school_id" placeholder="School ID" required>
            <input type="text" name="name" placeholder="Student Name" required>
            <input type="text" name="class" placeholder="Class" required>
            <button type="submit">Add Student</button>
        </form>
        <a href="admin.php">Back to Admin Dashboard</a>
    </div>
    <footer>
        <p>&copy; 2024 School Management System</p>
    </footer>
</body>
</html>
