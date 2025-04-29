<?php
// enroll_course.php
include('db.php');
session_start();

// 1. Check if the user is logged in.
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// 2. Check if course_id is provided in the URL.
if (isset($_GET['course_id'])) {
    $user_id = $_SESSION['user_id'];  // This must be a valid user ID in the users table.
    $course_id = intval($_GET['course_id']);

    // Optional: Verify that the user exists in the users table.
    $checkUserQuery = "SELECT id FROM users WHERE id = ?";
    $stmtUser = $conn->prepare($checkUserQuery);
    $stmtUser->bind_param("i", $user_id);
    $stmtUser->execute();
    $resultUser = $stmtUser->get_result();
    if ($resultUser->num_rows == 0) {
        // User not found in the users table—this will prevent the foreign key error.
        die("Error: User not found. Please register and login.");
    }
    $stmtUser->close();

    // 3. Check if the student is already enrolled in the course.
    $checkQuery = "SELECT * FROM student_progress WHERE student_id = ? AND course_id = ?";
    $stmt = $conn->prepare($checkQuery);
    $stmt->bind_param("ii", $user_id, $course_id);
    $stmt->execute();
    $result = $stmt->get_result();

    // 4. If not already enrolled, insert a new record.
    if ($result->num_rows == 0) {
        $insertQuery = "INSERT INTO student_progress (student_id, course_id, completed) VALUES (?, ?, 0)";
        $stmtInsert = $conn->prepare($insertQuery);
        $stmtInsert->bind_param("ii", $user_id, $course_id);
        $stmtInsert->execute();
        $stmtInsert->close();
    }
    $stmt->close();
}

// 5. Redirect back to the dashboard with the "My Courses" tab active.
header("Location: dashboard.php#my-courses");
exit();
?>
