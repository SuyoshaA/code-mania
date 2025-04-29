<?php
// enroll.php
include('db.php');
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

if (isset($_GET['course_id'])) {
    $user_id = $_SESSION['user_id'];
    $course_id = intval($_GET['course_id']);
    
    // Check if the course is already enrolled
    $checkQuery = "SELECT * FROM student_progress WHERE student_id = ? AND course_id = ?";
    $stmt = $conn->prepare($checkQuery);
    $stmt->bind_param("ii", $user_id, $course_id);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows == 0) {
        // Enroll the student in the course (initial progress set to 0)
        $insertQuery = "INSERT INTO student_progress (student_id, course_id, completed) VALUES (?, ?, 0)";
        $insertStmt = $conn->prepare($insertQuery);
        $insertStmt->bind_param("ii", $user_id, $course_id);
        $insertStmt->execute();
        $insertStmt->close();
    }
    $stmt->close();
}

// Redirect to dashboard and scroll to the My Courses tab
header("Location: dashboard.php#my-courses");
exit();
?>
