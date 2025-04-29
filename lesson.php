<?php
// lesson.php
include('db.php');
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}
if (!isset($_GET['lesson_id'])) {
    header("Location: dashboard.php");
    exit();
}

$lesson_id = $_GET['lesson_id'];

// Retrieve lesson details
$query = "SELECT * FROM lessons WHERE id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $lesson_id);
$stmt->execute();
$result = $stmt->get_result();
$lesson = $result->fetch_assoc();
$stmt->close();

if (!$lesson) {
    echo "Lesson not found.";
    exit();
}

// Override video URL for "Hello, World!" lesson
if (stripos($lesson['lesson_title'], 'Hello, World') !== false) {
    $lesson['video_url'] = 'https://www.youtube.com/watch?v=KOdfpbnWLVo';
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title><?php echo htmlspecialchars($lesson['lesson_title']); ?> - Lesson</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <!-- Tailwind CSS CDN -->
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100">
  <div class="max-w-4xl mx-auto p-6 bg-white shadow-md mt-10">
    <h1 class="text-3xl font-bold mb-4"><?php echo htmlspecialchars($lesson['lesson_title']); ?></h1>
    <div class="mb-4">
      <?php echo nl2br(htmlspecialchars($lesson['lesson_content'])); ?>
    </div>
    <?php if (!empty($lesson['video_url'])): ?>
      <div class="mb-4">
        <a href="<?php echo htmlspecialchars($lesson['video_url']); ?>" target="_blank" class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded">
          Watch Video
        </a>
      </div>
    <?php endif; ?>
    <a href="course.php?course_id=<?php echo $lesson['course_id'] ?? 0; ?>" class="text-blue-500 hover:underline">Back to Course</a>
  </div>
</body>
</html>
