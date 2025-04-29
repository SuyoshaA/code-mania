<?php
// complete_module.php
include('db.php');
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

if (!isset($_GET['course_id']) || !isset($_GET['module_id'])) {
    header("Location: dashboard.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$course_id = $_GET['course_id'];
$module_id = $_GET['module_id'];

// Get the total number of modules for this course
$totalModulesQuery = "SELECT COUNT(*) as total FROM modules WHERE course_id = ?";
$stmt = $conn->prepare($totalModulesQuery);
$stmt->bind_param("i", $course_id);
$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_assoc();
$totalModules = $row['total'];
$stmt->close();

if ($totalModules == 0) {
    // Should not happen
    header("Location: course.php?course_id=" . $course_id);
    exit();
}

// Get the module's sort_order value
$moduleQuery = "SELECT sort_order FROM modules WHERE id = ? AND course_id = ?";
$stmt = $conn->prepare($moduleQuery);
$stmt->bind_param("ii", $module_id, $course_id);
$stmt->execute();
$result = $stmt->get_result();
if ($row = $result->fetch_assoc()) {
    $moduleOrder = $row['sort_order'];
} else {
    // Module not found for this course
    header("Location: course.php?course_id=" . $course_id);
    exit();
}
$stmt->close();

// Calculate the progress percentage that should be reached when this module is completed
$progressIncrement = 100 / $totalModules;
$newProgress = ceil($moduleOrder * $progressIncrement);

// Get the current progress of the student for this course from student_progress
$currentProgressQuery = "SELECT completed FROM student_progress WHERE student_id = ? AND course_id = ?";
$stmt = $conn->prepare($currentProgressQuery);
$stmt->bind_param("ii", $user_id, $course_id);
$stmt->execute();
$result = $stmt->get_result();
$currentRow = $result->fetch_assoc();
$currentProgress = $currentRow ? $currentRow['completed'] : 0;
$stmt->close();

// Only update if the new progress is higher than the current progress
if ($newProgress > $currentProgress) {
    $updateQuery = "UPDATE student_progress SET completed = ? WHERE student_id = ? AND course_id = ?";
    $stmt = $conn->prepare($updateQuery);
    $stmt->bind_param("iii", $newProgress, $user_id, $course_id);
    $stmt->execute();
    $stmt->close();
}

// Redirect back to the course page
header("Location: course.php?course_id=" . $course_id);
exit();
?>
