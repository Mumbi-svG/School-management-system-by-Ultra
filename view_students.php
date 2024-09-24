<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

// Include the database connection
include 'db.php';

// Fetch all students from the database
$sql = "SELECT * FROM students";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="styles/style.css">
    <title>View Students</title>
</head>
<body>
    <h1>View Students</h1>
    <nav>
        <a href="admin.php">Admin Dashboard</a>
        <a href="logout.php">Logout</a>
    </nav>

    <table>
        <tr>
            <th>ID</th>
            <th>School ID</th>
            <th>Name</th>
            <th>Class</th>
            <th>Actions</th>
        </tr>
        <?php
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                echo "<tr>
                        <td>{$row['id']}</td>
                        <td>{$row['school_id']}</td>
                        <td>{$row['name']}</td>
                        <td>{$row['class']}</td>
                        <td><a href='student_profile.php?id={$row['id']}'>View Profile</a></td>
                      </tr>";
            }
        } else {
            echo "<tr><td colspan='5'>No students found</td></tr>";
        }
        ?>
    </table>
</body>
</html>
