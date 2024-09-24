<?php
session_start();
include 'db.php';

// Check if the user is an admin
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$student_id = $_GET['id'];

// Fetch the student record by ID
$student = getStudentById($student_id);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $class = $_POST['class'];
    $dob = $_POST['dob'];

    $stmt = $conn->prepare("UPDATE students SET name=?, email=?, class=?, date_of_birth=? WHERE id=?");
    $stmt->bind_param("ssssi", $name, $email, $class, $dob, $student_id);

    if ($stmt->execute()) {
        $success = "Student details updated successfully!";
        $student = getStudentById($student_id); // Refresh student data
    } else {
        $error = "Failed to update student details.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="styles/style.css">
    <title>Edit Student</title>
</head>
<body>
    <h1>Edit Student: <?php echo $student['name']; ?></h1>

    <?php if (isset($success)) echo "<p style='color:green;'>$success</p>"; ?>
    <?php if (isset($error)) echo "<p style='color:red;'>$error</p>"; ?>

    <form method="POST">
        <label>Name:</label>
        <input type="text" name="name" value="<?php echo $student['name']; ?>" required><br>

        <label>Email:</label>
        <input type="email" name="email" value="<?php echo $student['email']; ?>" required><br>

        <label>Class:</label>
        <input type="text" name="class" value="<?php echo $student['class']; ?>" required><br>

        <label>Date of Birth:</label>
        <input type="date" name="dob" value="<?php echo $student['date_of_birth']; ?>" required><br>

        <button type="submit">Update</button>
    </form>
    <nav>
        <a href="view_students.php">Back to Students</a>
    </nav>
</body>
</html>
