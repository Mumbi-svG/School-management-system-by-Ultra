<?php
session_start();
include 'db.php'; // Database connection

// Initialize variables and error message
$error = '';
$success = '';

// Fetch students for the attendance form
$sql = "SELECT * FROM students";
$result = $conn->query($sql);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get attendance data from the form
    $attendance_records = $_POST['attendance'];

    foreach ($attendance_records as $student_id => $status) {
        // Prevent SQL Injection
        $student_id = $conn->real_escape_string($student_id);
        $status = $conn->real_escape_string($status);
        
        // Insert attendance into the database
        $sql = "INSERT INTO attendance (student_id, status, date) VALUES ('$student_id', '$status', NOW())";
        $conn->query($sql);
    }
    $success = "Attendance marked successfully!";
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="styles/style.css">
    <title>Mark Attendance</title>
</head>
<body>
    <header>
        <h1>Mark Attendance</h1>
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
        <form method="POST" action="mark_attendance.php">
            <table>
                <tr>
                    <th>Student ID</th>
                    <th>Name</th>
                    <th>Status</th>
                </tr>
                <?php
                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        echo "<tr>
                                <td>{$row['id']}</td>
                                <td>{$row['name']}</td>
                                <td>
                                    <select name='attendance[{$row['id']}]'>
                                        <option value='present'>Present</option>
                                        <option value='absent'>Absent</option>
                                    </select>
                                </td>
                              </tr>";
                    }
                }
                ?>
            </table>
            <button type="submit">Submit Attendance</button>
        </form>
        <a href="admin.php">Back to Admin Dashboard</a>
    </div>
    <footer>
        <p>&copy; 2024 School Management System</p>
    </footer>
</body>
</html>
