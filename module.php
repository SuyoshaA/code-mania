<?php
include('db.php');
session_start();
if (!isset($_SESSION['user_id'])) {
  header("Location: login.php");
  exit();
}
if (!isset($_GET['course_id'])) {
  header("Location: courses.php");
  exit();
}
$course_id = $_GET['course_id'];
$stmt = $conn->prepare("SELECT * FROM courses WHERE id = ?");
$stmt->bind_param("i", $course_id);
$stmt->execute();
$courseResult = $stmt->get_result();
$course = $courseResult->fetch_assoc();
$stmt->close();
if (!$course) {
  echo "Course not found.";
  exit();
}
$stmt = $conn->prepare("SELECT * FROM modules WHERE course_id = ? ORDER BY sort_order ASC");
$stmt->bind_param("i", $course_id);
$stmt->execute();
$modulesResult = $stmt->get_result();
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Modules - <?= htmlspecialchars($course['course_name']); ?></title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100">
  <div class="max-w-4xl mx-auto p-6 bg-white shadow-md mt-10">
    <h1 class="text-3xl font-bold mb-4">Modules for <?= htmlspecialchars($course['course_name']); ?></h1>
    <?php if($modulesResult->num_rows > 0): ?>
      <ul class="space-y-4">
      <?php while($module = $modulesResult->fetch_assoc()): ?>
        <li>
          <a href="lessons.php?module_id=<?= $module['id'] ?>" class="text-blue-500 hover:underline text-xl">
            <?= htmlspecialchars($module['module_name']); ?>
          </a>
          <p class="text-gray-700"><?= htmlspecialchars($module['module_description']); ?></p>
        </li>
      <?php endwhile; ?>
      </ul>
    <?php else: ?>
      <p>No modules available for this course.</p>
    <?php endif; ?>
    <a href="courses.php" class="text-blue-500 hover:underline">Back to Courses</a>
  </div>
</body>
</html>
