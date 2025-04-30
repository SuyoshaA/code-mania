<?php
include('db.php');
session_start();
if (!isset($_SESSION['user_id'])) {
  header("Location: login.php");
  exit();
}
$user_id = $_SESSION['user_id'];

if (isset($_GET['course_id'])) {
  $course_id = (int)$_GET['course_id'];
  // only insert if not already enrolled
  if ($stmt = $conn->prepare("SELECT 1 FROM student_progress WHERE student_id=? AND course_id=?")) {
    $stmt->bind_param("ii",$user_id,$course_id);
    $stmt->execute();
    $exists = $stmt->get_result()->num_rows>0;
    $stmt->close();
    if (!$exists && $insert = $conn->prepare("INSERT INTO student_progress (student_id,course_id,completed) VALUES (?,?,0)")) {
      $insert->bind_param("ii",$user_id,$course_id);
      $insert->execute();
      $insert->close();
    }
  }
}

// go back here
header("Location: student_dashboard.php#my-courses");
exit();
