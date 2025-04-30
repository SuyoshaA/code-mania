<?php
include('db.php');
session_start();
if (!isset($_SESSION['user_id'])) {
  header("Location: login.php");
  exit();
}
if (!isset($_GET['course_id'])) {
  header("Location: student_dashboard.php");
  exit();
}
$user_id   = $_SESSION['user_id'];
$course_id = (int)$_GET['course_id'];

if ($stmt = $conn->prepare("DELETE FROM student_progress WHERE student_id=? AND course_id=?")) {
  $stmt->bind_param("ii", $user_id, $course_id);
  $stmt->execute();
  $stmt->close();
}

// return to “New Courses”
header("Location: student_dashboard.php#new-courses");
exit();
