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
$user_id = $_SESSION['user_id'];

// Get course details
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

// Get modules
$stmt = $conn->prepare("SELECT * FROM modules WHERE course_id = ? ORDER BY sort_order ASC");
$stmt->bind_param("i", $course_id);
$stmt->execute();
$modulesResult = $stmt->get_result();

// Get progress
$progressQuery = "SELECT completed FROM student_progress WHERE student_id = ? AND course_id = ?";
$stmt = $conn->prepare($progressQuery);
$stmt->bind_param("ii", $user_id, $course_id);
$stmt->execute();
$progressResult = $stmt->get_result();
$progress = $progressResult->fetch_assoc();
$currentProgress = $progress ? $progress['completed'] : 0;
$stmt->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title><?= htmlspecialchars($course['course_name']) ?> Modules</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <style>
    @keyframes fadeIn {
      from { opacity: 0; transform: translateY(20px); }
      to { opacity: 1; transform: translateY(0); }
    }
    .fade-in { animation: fadeIn 0.5s ease-out; }
  </style>
</head>
<body class="bg-gradient-to-r from-gray-200 to-gray-100">
  <!-- Sticky Top Navigation Bar -->
  <header class="sticky top-0 bg-gray-800 text-white flex justify-between items-center px-6 py-4 shadow-lg z-10">
    <h1 class="text-xl font-bold">Code Academy</h1>
    <nav>
      <a href="courses.php" class="text-sm font-semibold hover:text-gray-300 transition-colors duration-300 mr-4">All Courses</a>
      <a href="dashboard.php" class="text-sm font-semibold hover:text-gray-300 transition-colors duration-300 mr-4">Dashboard</a>
      <a href="logout.php" class="text-sm font-semibold hover:text-gray-300 transition-colors duration-300">Logout</a>
    </nav>
  </header>
  
  <div class="max-w-6xl mx-auto mt-16 p-8 bg-white rounded-lg shadow-xl fade-in">
    <!-- Page Header -->
    <div class="text-center mb-10">
      <h2 class="text-4xl font-bold text-gray-800"><?= htmlspecialchars($course['course_name']) ?></h2>
      <?php if (!empty($course['description'])): ?>
        <p class="text-gray-600 mt-2 max-w-2xl mx-auto"><?= nl2br(htmlspecialchars($course['description'])) ?></p>
      <?php endif; ?>
    </div>
    
    <!-- Progress Bar -->
    <div class="mb-10">
      <div class="flex justify-between items-center mb-2">
        <span class="font-medium text-gray-700">Course Progress</span>
        <span class="text-sm font-semibold text-blue-600"><?= $currentProgress ?>% Complete</span>
      </div>
      <div class="w-full bg-gray-200 rounded-full h-3">
        <div class="bg-blue-600 h-3 rounded-full" style="width: <?= $currentProgress ?>%;"></div>
      </div>
    </div>
    
    <!-- Modules List -->
    <div class="space-y-6">
      <h3 class="text-2xl font-semibold text-gray-800 mb-4">Course Modules</h3>
      
      <?php if($modulesResult->num_rows > 0): ?>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
          <?php while($module = $modulesResult->fetch_assoc()): ?>
            <div class="bg-white p-6 rounded-lg shadow border border-gray-200 hover:border-blue-500 transition-colors duration-300">
              <div class="flex items-start">
                <div class="bg-blue-100 p-3 rounded-full mr-4">
                  <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                  </svg>
                </div>
                <div>
                  <h4 class="text-xl font-semibold mb-2">
                    <a href="lessons.php?module_id=<?= $module['id'] ?>" class="text-gray-800 hover:text-blue-600 transition-colors duration-300">
                      <?= htmlspecialchars($module['module_name']) ?>
                    </a>
                  </h4>
                  <?php if (!empty($module['module_description'])): ?>
                    <p class="text-gray-600 mb-4"><?= htmlspecialchars($module['module_description']) ?></p>
                  <?php endif; ?>
                  <a href="lessons.php?module_id=<?= $module['id'] ?>" class="inline-flex items-center bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded transition duration-300">
                    View Lessons
                    <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3" />
                    </svg>
                  </a>
                </div>
              </div>
            </div>
          <?php endwhile; ?>
        </div>
      <?php else: ?>
        <div class="bg-white p-6 rounded-lg shadow border border-gray-200 text-center">
          <svg class="w-12 h-12 mx-auto text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
          </svg>
          <h4 class="text-lg font-medium text-gray-700 mt-4">No Modules Available</h4>
          <p class="text-gray-500 mt-1">This course doesn't have any modules yet.</p>
        </div>
      <?php endif; ?>
    </div>
    
    <div class="mt-10 text-center">
      <a href="courses.php" class="inline-flex items-center text-blue-600 hover:text-blue-800 transition-colors duration-300">
        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
        </svg>
        Back to All Courses
      </a>
    </div>
  </div>

  <script>
    // Simple fade-in animation on page load
    document.addEventListener('DOMContentLoaded', () => {
      const elements = document.querySelectorAll('.fade-in');
      elements.forEach(el => {
        el.style.opacity = '0';
        setTimeout(() => {
          el.style.opacity = '1';
        }, 100);
      });
    });
  </script>
</body>
</html>