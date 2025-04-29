<?php
// drop_course.php
include('db.php');
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

if (!isset($_GET['course_id'])) {
    header("Location: dashboard.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$course_id = $_GET['course_id'];

// Delete the student's progress record for this course.
$query = "DELETE FROM student_progress WHERE student_id = ? AND course_id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("ii", $user_id, $course_id);
$stmt->execute();
$stmt->close();

// Redirect back to dashboard (optionally you could add a URL hash to show the "New Courses" tab)
header("Location: dashboard.php");
exit();
?>
