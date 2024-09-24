<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

// Include the database connection
include 'db.php';

// Fetch students to display
$sql = "SELECT * FROM students";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="styles/style.css">
    <title>Admin Dashboard</title>
</head>
<body>
    <h1>Admin Dashboard</h1>

    <!-- Navigation Menu for Admin Actions -->
    <nav>
        <a href="add_student.php">Add Student</a> <!-- Add Student -->
        <a href="view_students.php">View All Students</a> <!-- View All Students -->
        <a href="mark_attendance.php">Mark Attendance</a> <!-- Mark Attendance -->
        <a href="view_attendance.php">View Attendance</a> <!-- Link for Attendance (to be implemented later) -->
        <a href="feedback.php">View Feedback</a> <!-- View Feedback -->
        <a href="logout.php">Logout</a> <!-- Logout -->
    </nav>

    <!-- Students List Section -->
    <h2>Students List</h2>
    <table border="1">
        <thead>
            <tr>
                <th>ID</th>
                <th>School ID</th>
                <th>Name</th>
                <th>Class</th>
                <th>Actions</th> <!-- New Column for Actions -->
            </tr>
        </thead>
        <tbody>
            <?php
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    echo "<tr>";
                    echo "<td>{$row['id']}</td>";
                    echo "<td>{$row['school_id']}</td>";
                    echo "<td>{$row['name']}</td>";
                    echo "<td>{$row['class']}</td>";
                    echo "<td>";
                    echo "<a href='edit_student.php?id={$row['id']}'>Edit</a> | ";
                    echo "<a href='delete_student.php?id={$row['id']}' onclick='return confirm(\"Are you sure?\");'>Delete</a>";
                    echo "</td>";
                    echo "</tr>";
                }
            } else {
                echo "<tr><td colspan='5'>No students found</td></tr>";
            }
            ?>
        </tbody>
    </table>
</body>
</html>
