<?php
include('db.php');
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'teacher') {
    header("Location: login.php");
    exit();
}
if (!isset($_GET['submission_id'])) {
    header("Location: teacher_dashboard.php");
    exit();
}
$submission_id = $_GET['submission_id'];

// Fetch submission details
$stmt = $conn->prepare("SELECT * FROM capstone_projects WHERE id = ?");
$stmt->bind_param("i", $submission_id);
$stmt->execute();
$result = $stmt->get_result();
$submission = $result->fetch_assoc();
$stmt->close();

if (!$submission) {
    echo "Submission not found.";
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $approval = $_POST['approval'];  // expected: 'approve' or 'resubmit'
    $comment = $_POST['comment'];
    
    // (Optional) If you have a teacher_comment column, update it; otherwise, you could log it separately.
    // $stmt = $conn->prepare("UPDATE capstone_projects SET teacher_comment = ? WHERE id = ?");
    // $stmt->bind_param("si", $comment, $submission_id);
    // $stmt->execute();
    // $stmt->close();
    
    if ($approval === 'approve') {
        // Update student progress for this student in the corresponding course to 100%
        $student_id = $submission['student_id'];
        $course_id = $submission['course_id'];
        $stmt = $conn->prepare("UPDATE student_progress SET completed = 100 WHERE student_id = ? AND course_id = ?");
        $stmt->bind_param("ii", $student_id, $course_id);
        $stmt->execute();
        $stmt->close();
        
        echo "<script>alert('Final project approved. Student progress updated.'); window.location.href='teacher_dashboard.php';</script>";
    } else {
        echo "<script>alert('Submission marked for resubmission. Ask the student to try again.'); window.location.href='teacher_dashboard.php';</script>";
    }
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Grade Final Project - Code Academy</title>
    <link href="https://cdn.tailwindcss.com" rel="stylesheet">
</head>
<body class="bg-gray-100">
  <div class="max-w-2xl mx-auto p-8 mt-10 bg-white rounded shadow">
    <h2 class="text-2xl font-bold mb-4">Grade Final Project</h2>
    <div class="mb-4">
      <p><strong>Student ID:</strong> <?= htmlspecialchars($submission['student_id']) ?></p>
      <p><strong>Project Title:</strong> <?= htmlspecialchars($submission['project_title']) ?></p>
      <p><strong>Description:</strong> <?= nl2br(htmlspecialchars($submission['project_description'])) ?></p>
      <p><strong>Submitted At:</strong> <?= htmlspecialchars($submission['submitted_at']) ?></p>
    </div>
    <form action="grade_capstone.php?submission_id=<?= $submission_id ?>" method="POST">
      <div class="mb-4">
        <label class="block mb-2 font-semibold">Your Comment (Optional)</label>
        <textarea name="comment" rows="3" class="w-full p-2 border rounded" placeholder="Enter comment (e.g., 'Do again' if not approved)"></textarea>
      </div>
      <div class="flex space-x-4">
        <button type="submit" name="approval" value="approve" class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700 transition duration-300">
          Approve
        </button>
        <button type="submit" name="approval" value="resubmit" class="bg-red-600 text-white px-4 py-2 rounded hover:bg-red-700 transition duration-300">
          Request Resubmission
        </button>
      </div>
    </form>
  </div>
</body>
</html>
