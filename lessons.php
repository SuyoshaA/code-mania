<?php
include('db.php');
session_start();
if (!isset($_SESSION['user_id'])) {
  header("Location: login.php");
  exit();
}
if (!isset($_GET['module_id'])) {
  header("Location: modules.php");
  exit();
}
$module_id = $_GET['module_id'];
$stmt = $conn->prepare("SELECT * FROM modules WHERE id = ?");
$stmt->bind_param("i", $module_id);
$stmt->execute();
$moduleResult = $stmt->get_result();
$module = $moduleResult->fetch_assoc();
$stmt->close();
if (!$module) {
  echo "Module not found.";
  exit();
}
$stmt = $conn->prepare("SELECT * FROM chapters WHERE module_id = ? ORDER BY sort_order ASC LIMIT 1");
$stmt->bind_param("i", $module_id);
$stmt->execute();
$chapterResult = $stmt->get_result();
$chapter = $chapterResult->fetch_assoc();
$stmt->close();
if (!$chapter) {
  echo "No lesson found for this module.";
  exit();
}
$stmt = $conn->prepare("SELECT * FROM lessons WHERE chapter_id = ? ORDER BY sort_order ASC LIMIT 1");
$stmt->bind_param("i", $chapter['id']);
$stmt->execute();
$lessonResult = $stmt->get_result();
$lesson = $lessonResult->fetch_assoc();
$stmt->close();
if (!$lesson) {
  echo "Lesson not found.";
  exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title><?= htmlspecialchars($lesson['lesson_title']); ?></title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100">
  <div class="max-w-4xl mx-auto p-6 bg-white shadow-md mt-10">
    <h1 class="text-3xl font-bold mb-4"><?= htmlspecialchars($lesson['lesson_title']); ?></h1>
    <div class="mb-4">
      <p><?= nl2br(htmlspecialchars($lesson['lecture_content'])); ?></p>
    </div>
    <?php if(!empty($lesson['video_url'])): ?>
      <div class="mb-4">
        <?php 
          // If the video URL is from YouTube, embed it; otherwise, just show a link.
          if (strpos($lesson['video_url'], 'youtube.com') !== false || strpos($lesson['video_url'], 'youtu.be') !== false) {
            // Extract video ID (for simplicity, assume URL is standard)
            preg_match('/(?:v=|\/)([0-9A-Za-z_-]{11})/', $lesson['video_url'], $matches);
            $videoID = $matches[1] ?? '';
            if($videoID) {
              echo '<iframe width="560" height="315" src="https://www.youtube.com/embed/'.$videoID.'" frameborder="0" allowfullscreen></iframe>';
            } else {
              echo '<a href="'.htmlspecialchars($lesson['video_url']).'" target="_blank" class="text-blue-500 hover:underline">Watch Video</a>';
            }
          } else {
            echo '<a href="'.htmlspecialchars($lesson['video_url']).'" target="_blank" class="text-blue-500 hover:underline">Watch Video</a>';
          }
        ?>
      </div>
    <?php endif; ?>
    <a href="modules.php?course_id=<?= $module['course_id'] ?>" class="text-blue-500 hover:underline">Back to Modules</a>
    <br>
    <a href="quizzes.php?chapter_id=<?= $chapter['id'] ?>" class="mt-2 inline-block text-blue-500 hover:underline">Take Quiz for this Module</a>
  </div>
</body>
</html>
