<?php
session_start();
include 'db.php';

// Check if the user is an admin
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$student_id = $_GET['id'];

// Delete student by ID
$stmt = $conn->prepare("DELETE FROM students WHERE id = ?");
$stmt->bind_param("i", $student_id);

if ($stmt->execute()) {
    header("Location: view_students.php?msg=Student deleted successfully");
} else {
    echo "Failed to delete student.";
}
?>
