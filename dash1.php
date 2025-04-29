<?php
// dashboard.php
include('db.php');
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// Query to get enrolled courses (My Courses)
$query = "SELECT c.id AS course_id, c.course_name, sp.completed 
          FROM student_progress sp 
          JOIN courses c ON sp.course_id = c.id 
          WHERE sp.student_id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$progress_data = [];
while ($row = $result->fetch_assoc()) {
    $progress_data[] = $row;
}
$stmt->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Student Dashboard</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 min-h-screen">
  <div class="max-w-6xl mx-auto mt-10 p-8 bg-white rounded-lg shadow-xl">
    <header class="text-center mb-8">
      <h1 class="text-4xl font-bold text-gray-800">Student Dashboard</h1>
    </header>

    <!-- Enrolled Courses -->
    <h2 class="text-2xl font-semibold text-gray-800 mb-4">Enrolled Courses</h2>
    <div class="overflow-x-auto">
      <table class="min-w-full divide-y divide-gray-200 shadow-md rounded-lg">
        <thead class="bg-blue-500 text-white">
          <tr>
            <th class="px-4 py-2 text-left">Course Name</th>
            <th class="px-4 py-2 text-left">Progress (%)</th>
            <th class="px-4 py-2 text-left">Action</th>
          </tr>
        </thead>
        <tbody class="bg-white divide-y divide-gray-200">
          <?php if (!empty($progress_data)): ?>
            <?php foreach ($progress_data as $course): ?>
              <tr>
                <td class="px-4 py-2"><?= htmlspecialchars($course['course_name']) ?></td>
                <td class="px-4 py-2"><?= htmlspecialchars($course['completed']) ?>%</td>
                <td class="px-4 py-2">
                  <!-- Redirect to course.php with course name as a parameter -->
                  <a href="course.php?course=<?= urlencode($course['course_name']) ?>" 
                     class="bg-green-500 hover:bg-green-600 text-white px-3 py-1 rounded transition duration-150">
                    Continue Course
                  </a>
                </td>
              </tr>
            <?php endforeach; ?>
          <?php else: ?>
            <tr>
              <td colspan="3" class="px-4 py-2 text-center text-gray-500">No enrolled courses found.</td>
            </tr>
          <?php endif; ?>
        </tbody>
      </table>
    </div>
  </div>
</body>
</html>
