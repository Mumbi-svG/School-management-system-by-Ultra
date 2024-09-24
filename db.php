<?php
session_start();

// Database credentials
$host = 'localhost';
$db = 'school_management';
$user = 'your_username'; // Replace with your database username
$pass = 'your_password'; // Replace with your database password

// Create a connection
$conn = new mysqli($host, $user, $pass, $db);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

/**
 * Check login credentials for admin
 *
 * @param string $username
 * @param string $password
 * @return bool
 */
function checkAdminLogin($username, $password) {
    global $conn;

    $stmt = $conn->prepare("SELECT * FROM admins WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();
    $admin = $result->fetch_assoc();

    // Verify the password if admin exists
    if ($admin && password_verify($password, $admin['password'])) {
        $_SESSION['user_id'] = $admin['id'];
        return true;
    }
    return false;
}

/**
 * Check login credentials for students
 *
 * @param string $username
 * @param string $password
 * @return bool
 */
function checkStudentLogin($username, $password) {
    global $conn;

    $stmt = $conn->prepare("SELECT * FROM students WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();
    $student = $result->fetch_assoc();

    // Verify the password if student exists
    if ($student && password_verify($password, $student['password'])) {
        $_SESSION['student_id'] = $student['id'];
        return true;
    }
    return false;
}

/**
 * Add a new student to the database
 *
 * @param string $school_id
 * @param string $name
 * @param string $email
 * @param string $class
 * @param string $dob
 * @return bool
 */
function addStudent($school_id, $name, $email, $class, $dob) {
    global $conn;

    $stmt = $conn->prepare("INSERT INTO students (school_id, name, email, class, date_of_birth) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("sssss", $school_id, $name, $email, $class, $dob);
    
    return $stmt->execute();
}

/**
 * Fetch all students
 *
 * @return array
 */
function getAllStudents() {
    global $conn;

    $sql = "SELECT * FROM students";
    $result = $conn->query($sql);
    return $result->fetch_all(MYSQLI_ASSOC);
}

/**
 * Fetch student by ID
 *
 * @param int $id
 * @return array|null
 */
function getStudentById($id) {
    global $conn;

    $stmt = $conn->prepare("SELECT * FROM students WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();

    return $result->fetch_assoc();
}

/**
 * Fetch all feedback
 *
 * @return array
 */
function getAllFeedback() {
    global $conn;

    $sql = "SELECT feedback.*, students.name FROM feedback INNER JOIN students ON feedback.student_id = students.id";
    $result = $conn->query($sql);
    return $result->fetch_all(MYSQLI_ASSOC);
}

/**
 * Add feedback from students
 *
 * @param int $student_id
 * @param string $feedback_text
 * @return bool
 */
function addFeedback($student_id, $feedback_text) {
    global $conn;

    $stmt = $conn->prepare("INSERT INTO feedback (student_id, feedback_text) VALUES (?, ?)");
    $stmt->bind_param("is", $student_id, $feedback_text);
    
    return $stmt->execute();
}

/**
 * Mark attendance for students
 *
 * @param int $student_id
 * @param string $date
 * @param string $status
 * @return bool
 */
function markAttendance($student_id, $date, $status) {
    global $conn;

    $stmt = $conn->prepare("INSERT INTO attendance (student_id, date, status) VALUES (?, ?, ?)");
    $stmt->bind_param("iss", $student_id, $date, $status);
    
    return $stmt->execute();
}

/**
 * Close the database connection
 */
function closeConnection() {
    global $conn;
    $conn->close();
}

// Ensure to close the connection when done
// closeConnection();
?>
