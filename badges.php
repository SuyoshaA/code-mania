<?php
// badges.php
include('db.php');
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// Query to get enrolled courses and progress data
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
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Earned Badges</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100">
  <div class="max-w-4xl mx-auto p-8">
    <h1 class="text-3xl font-bold mb-6 text-center">Your Earned Badges</h1>
    <div class="overflow-x-auto">
      <table class="min-w-full divide-y divide-gray-200 shadow-md rounded-lg">
        <thead class="bg-blue-500 text-white">
          <tr>
            <th class="px-4 py-2 text-left">Course Name</th>
            <th class="px-4 py-2 text-left">Progress (%)</th>
            <th class="px-4 py-2 text-left">Badge Earned</th>
          </tr>
        </thead>
        <tbody class="bg-white divide-y divide-gray-200">
          <?php if (!empty($progress_data)): ?>
            <?php foreach ($progress_data as $course): ?>
              <?php 
                $badge = '';
                $badgeImage = '';
                if ($course['completed'] >= 80) {
                    $badge = 'Pro Badge';
                    $badgeImage = 'pro_badge.png';
                } elseif ($course['completed'] >= 60) {
                    $badge = 'Intermediate Badge';
                    $badgeImage = 'intermediate_badge.png';
                } elseif ($course['completed'] >= 20) {
                    $badge = 'Beginner Badge';
                    $badgeImage = 'beginner_badge.png';
                } else {
                    $badge = 'No badge earned';
                }
              ?>
              <tr>
                <td class="px-4 py-2"><?= htmlspecialchars($course['course_name']) ?></td>
                <td class="px-4 py-2"><?= htmlspecialchars($course['completed']) ?>%</td>
                <td class="px-4 py-2">
                  <?php if ($badge != 'No badge earned'): ?>
                    <div class="flex items-center space-x-2">
                      <img src="<?= $badgeImage ?>" alt="<?= $badge ?>" class="h-10">
                      <span><?= $badge ?></span>
                    </div>
                  <?php else: ?>
                    <?= $badge ?>
                  <?php endif; ?>
                </td>
              </tr>
            <?php endforeach; ?>
          <?php else: ?>
            <tr>
              <td colspan="3" class="px-4 py-2 text-center text-gray-500">No badge data available.</td>
            </tr>
          <?php endif; ?>
        </tbody>
      </table>
    </div>
    <div class="text-center mt-6">
      <a href="dashboard.php" class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded transition duration-150">Back to Dashboard</a>
    </div>
  </div>
</body>
</html>
