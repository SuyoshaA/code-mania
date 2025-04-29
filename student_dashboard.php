<?php
include('db.php');
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}
$user_id = $_SESSION['user_id'];

// 1) Enrolled courses
$progress_data = [];
if ($stmt = $conn->prepare("
    SELECT c.id AS course_id, c.course_name, sp.completed
    FROM student_progress sp
    JOIN courses c ON sp.course_id = c.id
    WHERE sp.student_id = ?
")) {
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $progress_data = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    $stmt->close();
}

// 2) Available courses (no teacher info at all)
$available_courses = [];
if ($stmt = $conn->prepare("
    SELECT id, course_name, description
    FROM courses
    WHERE id NOT IN (
      SELECT course_id FROM student_progress WHERE student_id = ?
    )
")) {
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $available_courses = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    $stmt->close();
}

// 3) Recent messages
$recent_messages = [];
if ($stmt = $conn->prepare("
    SELECT m.message, u.username AS teacher_name, c.course_name, m.created_at
    FROM messages m
    JOIN users u ON m.teacher_id = u.id
    LEFT JOIN courses c ON m.course_id = c.id
    WHERE m.student_id = ?
    ORDER BY m.created_at DESC
    LIMIT 5
")) {
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $recent_messages = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    $stmt->close();
}

// compute stats
$totalCourses    = count($progress_data);
$totalProgress   = array_sum(array_column($progress_data,'completed'));
$averageProgress = $totalCourses ? round($totalProgress/$totalCourses,2) : 0;

// badges
$badgeCounts = ['pro'=>0,'intermediate'=>0,'beginner'=>0];
foreach($progress_data as $c){
    $p = (int)$c['completed'];
    if ($p>=80)       $badgeCounts['pro']++;
    elseif($p>=60)    $badgeCounts['intermediate']++;
    elseif($p>=20)    $badgeCounts['beginner']++;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8"/>
<meta name="viewport" content="width=device-width,initial-scale=1.0"/>
<title>Student Dashboard</title>
<script src="https://cdn.tailwindcss.com"></script>
<style>
  .tab-content { display:none }
  .tab-content.active { display:block }
</style>
</head>
<body class="bg-gray-100">
  <header class="bg-gray-800 text-white p-4 flex justify-between">
    <h1 class="font-bold">Code Academy</h1>
    <a href="logout.php" class="hover:text-gray-300">Logout</a>
  </header>

  <main class="max-w-5xl mx-auto mt-8 p-6 bg-white rounded shadow">
    <h2 class="text-3xl font-bold mb-6 text-center">Student Dashboard</h2>

    <!-- Stats -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-8">
      <div class="bg-blue-50 p-4 rounded text-center">
        <div class="uppercase text-sm">Enrolled</div>
        <div class="text-2xl font-bold"><?= $totalCourses ?></div>
      </div>
      <div class="bg-green-50 p-4 rounded text-center">
        <div class="uppercase text-sm">Progress</div>
        <div class="text-2xl font-bold"><?= $averageProgress ?>%</div>
      </div>
      <div class="bg-purple-50 p-4 rounded text-center">
        <div class="uppercase text-sm">Badges</div>
        <div class="text-2xl font-bold"><?= array_sum($badgeCounts) ?></div>
      </div>
    </div>

    <!-- Tabs -->
    <nav class="mb-6 border-b">
      <ul class="flex space-x-6 justify-center">
        <li><a href="#" data-tab="my-courses" class="tab-link border-b-2 border-blue-600 pb-2">My Courses</a></li>
        <li><a href="#" data-tab="new-courses" class="tab-link pb-2">New Courses</a></li>
        <li><a href="#" data-tab="forum" class="tab-link pb-2">Discussion</a></li>
        <li><a href="#" data-tab="messages" class="tab-link pb-2">Messages</a></li>
      </ul>
    </nav>

    <!-- My Courses -->
    <section id="my-courses" class="tab-content active">
      <h3 class="text-xl font-semibold mb-4">My Enrolled Courses</h3>
      <table class="w-full table-auto border">
        <thead class="bg-blue-600 text-white">
          <tr><th class="p-2">Course</th><th class="p-2">Progress</th><th class="p-2">Actions</th></tr>
        </thead>
        <tbody>
          <?php if($progress_data): foreach($progress_data as $c): ?>
          <tr class="border-b hover:bg-gray-50">
            <td class="p-2"><?= htmlspecialchars($c['course_name']) ?></td>
            <td class="p-2">
              <div class="flex items-center">
                <span class="mr-2"><?= $c['completed'] ?>%</span>
                <div class="w-full bg-gray-200 h-2 rounded">
                  <div class="bg-green-600 h-full" style="width:<?= $c['completed']?>%"></div>
                </div>
              </div>
            </td>
            <td class="p-2 space-x-2">
              <a href="course.php?course_id=<?= $c['course_id'] ?>" class="bg-blue-600 text-white px-3 py-1 rounded">Continue</a>
              <a href="drop_course.php?course_id=<?= $c['course_id'] ?>" onclick="return confirm('Drop?')" class="bg-red-600 text-white px-3 py-1 rounded">Drop</a>
            </td>
          </tr>
          <?php endforeach; else: ?>
          <tr><td colspan="3" class="p-4 text-center text-gray-500">No enrollments yet.</td></tr>
          <?php endif; ?>
        </tbody>
      </table>
    </section>

    <!-- New Courses -->
    <section id="new-courses" class="tab-content">
      <h3 class="text-xl font-semibold mb-4">Available Courses</h3>
      <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-6">
        <?php if($available_courses): foreach($available_courses as $c): ?>
        <div class="bg-gray-50 p-4 rounded shadow">
          <h4 class="font-bold mb-2"><?= htmlspecialchars($c['course_name']) ?></h4>
          <p class="text-gray-600 mb-4"><?= htmlspecialchars($c['description']?:'No description') ?></p>
          <a href="enroll_course.php?course_id=<?= $c['id'] ?>" class="block bg-blue-600 text-white py-2 rounded text-center">Enroll Now</a>
        </div>
        <?php endforeach; else: ?>
        <p class="text-gray-500">You’re enrolled in all available courses.</p>
        <?php endif; ?>
      </div>
    </section>

    <!-- Discussion -->
    <section id="forum" class="tab-content">
      <h3 class="text-xl font-semibold mb-4">Discussion Forum</h3>
      <a href="peer_review.php" class="bg-purple-600 text-white px-4 py-2 rounded">Go to Peer Review</a>
    </section>

    <!-- Messages -->
    <section id="messages" class="tab-content">
      <h3 class="text-xl font-semibold mb-4">Recent Messages</h3>
      <?php if($recent_messages): foreach($recent_messages as $m): ?>
        <div class="border-b py-2">
          <div class="font-medium">From: <?= htmlspecialchars($m['teacher_name']) ?></div>
          <div class="text-sm text-gray-600"><?= date('M j, g:i a',strtotime($m['created_at'])) ?></div>
          <p class="mt-1"><?= htmlspecialchars($m['message']) ?></p>
        </div>
      <?php endforeach; else: ?>
        <p class="text-gray-500">No messages yet.</p>
      <?php endif; ?>
    </section>
  </main>

  <script>
    const tabs = document.querySelectorAll('.tab-link'),
          panes= document.querySelectorAll('.tab-content');
    tabs.forEach(tab=>{
      tab.addEventListener('click', e=>{
        e.preventDefault();
        tabs.forEach(t=>t.classList.remove('border-blue-600'));
        panes.forEach(p=>p.classList.remove('active'));
        tab.classList.add('border-blue-600');
        document.getElementById(tab.dataset.tab).classList.add('active');
      });
    });
  </script>
</body>
</html>
