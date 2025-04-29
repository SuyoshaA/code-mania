<?php
include('db.php');
session_start();
if (!isset($_SESSION['user_id'])) {
  header("Location: login.php");
  exit();
}
if (!isset($_GET['chapter_id'])) {
  header("Location: modules.php");
  exit();
}
$chapter_id = $_GET['chapter_id'];
$stmt = $conn->prepare("SELECT * FROM quizzes WHERE chapter_id = ? ORDER BY sort_order ASC LIMIT 1");
$stmt->bind_param("i", $chapter_id);
$stmt->execute();
$quizResult = $stmt->get_result();
$quiz = $quizResult->fetch_assoc();
$stmt->close();
if (!$quiz) {
  echo "No quiz found for this module.";
  exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title><?= htmlspecialchars($quiz['quiz_title']); ?> - Quiz</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100">
  <div class="max-w-4xl mx-auto p-6 bg-white shadow-md mt-10">
    <h1 class="text-3xl font-bold mb-4"><?= htmlspecialchars($quiz['quiz_title']); ?></h1>
    <p class="mb-4"><?= nl2br(htmlspecialchars($quiz['quiz_content'])); ?></p>
    <a href="take_quiz.php?quiz_id=<?= $quiz['id']; ?>" class="text-blue-500 hover:underline">Take Quiz</a>
    <br>
    <a href="lessons.php?module_id=<?= (isset($_GET['module_id']) ? $_GET['module_id'] : 0); ?>" class="mt-2 inline-block text-blue-500 hover:underline">Back to Lesson</a>
  </div>
</body>
</html>
