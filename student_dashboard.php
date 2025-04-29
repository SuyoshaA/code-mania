<?php
// student_dashboard.php
include('db.php');
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// ————————————————————————————————————————————————————————————————
// 1) Handle enroll / drop in‐page so we never redirect to a missing file
// ————————————————————————————————————————————————————————————————
if (isset($_GET['enroll_course'])) {
    $course_id = intval($_GET['enroll_course']);
    // only insert if not already enrolled
    $chk = $conn->prepare("SELECT 1 FROM student_progress WHERE student_id=? AND course_id=?");
    $chk->bind_param("ii", $user_id, $course_id);
    $chk->execute();
    if ($chk->get_result()->num_rows === 0) {
        $ins = $conn->prepare("INSERT INTO student_progress (student_id, course_id, completed) VALUES (?, ?, 0)");
        $ins->bind_param("ii", $user_id, $course_id);
        $ins->execute();
        $ins->close();
    }
    $chk->close();
    header("Location: student_dashboard.php#my-courses");
    exit();
}

if (isset($_GET['drop_course'])) {
    $course_id = intval($_GET['drop_course']);
    $del = $conn->prepare("DELETE FROM student_progress WHERE student_id=? AND course_id=?");
    $del->bind_param("ii", $user_id, $course_id);
    $del->execute();
    $del->close();
    header("Location: student_dashboard.php#new-courses");
    exit();
}

// ————————————————————————————————————————————————————————————————
// 2) Fetch data for display
// ————————————————————————————————————————————————————————————————

// 2.1 Enrolled courses with teacher info (Unassigned if none)
$progress_data = [];
$q1 = "
  SELECT c.id AS course_id,
         c.course_name,
         sp.completed,
         COALESCE(u.username, 'Unassigned') AS teacher_name
    FROM student_progress sp
    JOIN courses c      ON sp.course_id = c.id
    LEFT JOIN users u   ON c.teacher_id = u.id
   WHERE sp.student_id = ?
";
$stmt = $conn->prepare($q1);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$res = $stmt->get_result();
while ($row = $res->fetch_assoc()) {
    $progress_data[] = $row;
}
$stmt->close();

// 2.2 Available courses (not yet enrolled)
$available_courses = [];
$ids = array_column($progress_data, 'course_id');
if (count($ids)) {
    $placeholders = implode(",", array_fill(0, count($ids), "?"));
    $types        = str_repeat("i", count($ids));
    $q2 = "
      SELECT c.id, c.course_name, c.description,
             COALESCE(u.username,'Unassigned') AS teacher_name
        FROM courses c
        LEFT JOIN users u ON c.teacher_id = u.id
       WHERE c.id NOT IN ($placeholders)
    ";
    $stmt = $conn->prepare($q2);
    $stmt->bind_param($types, ...$ids);
} else {
    // if no enrolled courses, show every course
    $q2 = "
      SELECT c.id, c.course_name, c.description,
             COALESCE(u.username,'Unassigned') AS teacher_name
        FROM courses c
        LEFT JOIN users u ON c.teacher_id = u.id
    ";
    $stmt = $conn->prepare($q2);
}
$stmt->execute();
$res = $stmt->get_result();
while ($row = $res->fetch_assoc()) {
    $available_courses[] = $row;
}
$stmt->close();

// 2.3 Announcements for enrolled courses
$announcements = [];
if (count($ids)) {
    $placeholders = implode(",", array_fill(0, count($ids), "?"));
    $types        = str_repeat("i", count($ids));
    $q3 = "
      SELECT a.*, c.course_name, COALESCE(u.username,'Unassigned') AS teacher_name
        FROM announcements a
        JOIN courses c      ON a.course_id = c.id
        LEFT JOIN users u   ON a.teacher_id = u.id
       WHERE a.course_id IN ($placeholders)
       ORDER BY a.created_at DESC
       LIMIT 5
    ";
    $stmt = $conn->prepare($q3);
    $stmt->bind_param($types, ...$ids);
    $stmt->execute();
    $res = $stmt->get_result();
    while ($row = $res->fetch_assoc()) {
        $announcements[] = $row;
    }
    $stmt->close();
}

// 2.4 Recent messages (student ← teacher)
$recent_messages = [];
$q4 = "
  SELECT m.*, COALESCE(u.username,'Unknown') AS teacher_name, c.course_name
    FROM messages m
    LEFT JOIN users u   ON m.teacher_id = u.id
    LEFT JOIN courses c ON m.course_id = c.id
   WHERE m.student_id = ?
   ORDER BY m.created_at DESC
   LIMIT 5
";
$stmt = $conn->prepare($q4);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$res = $stmt->get_result();
while ($row = $res->fetch_assoc()) {
    $recent_messages[] = $row;
}
$stmt->close();

// 2.5 Stats
$totalCourses   = count($progress_data);
$totalProgress  = array_sum(array_column($progress_data,'completed'));
$averageProgress = $totalCourses
    ? round($totalProgress / $totalCourses, 2)
    : 0;

// 2.6 Badge counts
$badgeCounts = ['pro'=>0,'intermediate'=>0,'beginner'=>0];
foreach ($progress_data as $c) {
    if ($c['completed'] >= 80)       $badgeCounts['pro']++;
    elseif ($c['completed'] >= 60)   $badgeCounts['intermediate']++;
    elseif ($c['completed'] >= 20)   $badgeCounts['beginner']++;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8"/>
  <meta name="viewport" content="width=device-width,initial-scale=1"/>
  <title>Student Dashboard</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <style>
    @keyframes fadeIn {
      from { opacity:0; transform:translateY(20px); }
      to   { opacity:1; transform:translateY(0); }
    }
    .fade-in { animation:fadeIn 0.5s ease-out; }
    .tab-content { display:none; }
    .tab-content.active { display:block; }
  </style>
</head>
<body class="bg-gradient-to-r from-gray-200 to-gray-100">
  <header class="sticky top-0 bg-gray-800 text-white flex justify-between items-center px-6 py-4 shadow z-10">
    <h1 class="text-xl font-bold">Code Academy</h1>
    <a href="logout.php" class="hover:text-gray-300">Logout</a>
  </header>

  <div class="max-w-6xl mx-auto mt-16 p-8 bg-white rounded-lg shadow">
    <!-- Header -->
    <div class="text-center mb-10">
      <h2 class="text-4xl font-bold text-gray-800">Student Dashboard</h2>
      <p class="text-gray-600">Track your learning progress &amp; explore new courses.</p>
    </div>

    <!-- Quick Stats -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-10">
      <div class="bg-blue-100 p-6 rounded shadow">
        <h3 class="font-semibold text-blue-800">Enrolled</h3>
        <p class="text-3xl text-blue-600"><?= $totalCourses ?></p>
      </div>
      <div class="bg-green-100 p-6 rounded shadow">
        <h3 class="font-semibold text-green-800">Overall Progress</h3>
        <p class="text-3xl text-green-600"><?= $averageProgress ?>%</p>
      </div>
      <div class="bg-purple-100 p-6 rounded shadow">
        <h3 class="font-semibold text-purple-800">Badges</h3>
        <p class="text-3xl text-purple-600"><?= array_sum($badgeCounts) ?></p>
      </div>
    </div>

    <!-- Tabs -->
    <nav class="mb-8 border-b">
      <ul class="flex justify-center space-x-6">
        <li><a href="#" class="tab-link border-b-2 border-blue-600 pb-2" data-tab="my-courses">My Courses</a></li>
        <li><a href="#" class="tab-link pb-2" data-tab="new-courses">New Courses</a></li>
        <li><a href="#" class="tab-link pb-2" data-tab="progress-tracker">Progress</a></li>
        <li><a href="#" class="tab-link pb-2" data-tab="badges">Badges</a></li>
        <li><a href="#" class="tab-link pb-2" data-tab="discussion">Discussion Forum</a></li>
      </ul>
    </nav>

    <div class="space-y-10">
      <!-- My Courses -->
      <div id="my-courses" class="tab-content fade-in active">
        <h3 class="text-2xl font-semibold mb-4">My Enrolled Courses</h3>
        <div class="overflow-x-auto">
          <table class="min-w-full bg-white">
            <thead class="bg-blue-600 text-white">
              <tr>
                <th class="p-3 text-left">Course</th>
                <th class="p-3 text-left">Teacher</th>
                <th class="p-3 text-left">Progress</th>
                <th class="p-3 text-left">Actions</th>
              </tr>
            </thead>
            <tbody class="divide-y">
              <?php if ($progress_data): foreach($progress_data as $c): ?>
              <tr class="hover:bg-gray-50">
                <td class="p-3"><?= htmlspecialchars($c['course_name']) ?></td>
                <td class="p-3"><?= htmlspecialchars($c['teacher_name']) ?></td>
                <td class="p-3">
                  <?= $c['completed'] ?>%
                  <div class="bg-gray-200 h-2 rounded-full mt-1">
                    <div class="bg-green-600 h-2 rounded-full" style="width:<?= $c['completed'] ?>%"></div>
                  </div>
                </td>
                <td class="p-3 space-x-2">
                  <a href="student_dashboard.php?drop_course=<?= $c['course_id'] ?>#new-courses"
                     class="bg-red-600 text-white px-3 py-1 rounded">Drop</a>
                  <a href="course.php?course_id=<?= $c['course_id'] ?>"
                     class="bg-blue-600 text-white px-3 py-1 rounded">Continue</a>
                </td>
              </tr>
              <?php endforeach; else: ?>
              <tr>
                <td colspan="4" class="text-center p-4 text-gray-500">No enrolled courses yet.</td>
              </tr>
              <?php endif; ?>
            </tbody>
          </table>
        </div>
      </div>

      <!-- New Courses -->
      <div id="new-courses" class="tab-content">
        <h3 class="text-2xl font-semibold mb-4">Available Courses</h3>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
          <?php if ($available_courses): foreach($available_courses as $c): ?>
          <div class="border rounded p-4 shadow hover:border-blue-500 transition">
            <h4 class="font-bold"><?= htmlspecialchars($c['course_name']) ?></h4>
            <p class="text-sm text-gray-600 mb-2"><?= htmlspecialchars($c['teacher_name']) ?></p>
            <p class="text-sm mb-4"><?= htmlspecialchars($c['description'] ?: 'No description') ?></p>
            <a href="student_dashboard.php?enroll_course=<?= $c['id'] ?>#my-courses"
               class="block bg-blue-600 text-white text-center py-2 rounded">Enroll Now</a>
          </div>
          <?php endforeach; else: ?>
          <p class="text-gray-500">No new courses at this time.</p>
          <?php endif; ?>
        </div>
      </div>

      <!-- Progress Tracker -->
      <div id="progress-tracker" class="tab-content">
        <h3 class="text-2xl font-semibold mb-4">Overall Progress</h3>
        <?php if ($totalCourses): ?>
        <div class="mb-6">
          <div class="font-medium mb-2"><?= $averageProgress ?>% Complete</div>
          <div class="bg-gray-200 h-3 rounded-full">
            <div class="bg-green-600 h-3 rounded-full" style="width:<?= $averageProgress ?>%"></div>
          </div>
        </div>
        <h4 class="font-semibold mb-2">Course Breakdown</h4>
        <div class="overflow-x-auto">
          <table class="min-w-full bg-white">
            <thead class="bg-blue-600 text-white">
              <tr><th class="p-2 text-left">Course</th><th class="p-2 text-left">Progress</th></tr>
            </thead>
            <tbody class="divide-y">
              <?php foreach($progress_data as $c): ?>
              <tr>
                <td class="p-2"><?= htmlspecialchars($c['course_name']) ?></td>
                <td class="p-2">
                  <div class="bg-gray-200 h-2 rounded-full">
                    <div class="bg-green-600 h-2 rounded-full" style="width:<?= $c['completed'] ?>%"></div>
                  </div>
                  <span class="text-sm"><?= $c['completed'] ?>%</span>
                </td>
              </tr>
              <?php endforeach; ?>
            </tbody>
          </table>
        </div>
        <?php else: ?>
        <p class="text-gray-500">Enroll in a course to track progress.</p>
        <?php endif; ?>
      </div>

      <!-- Badges -->
      <div id="badges" class="tab-content">
        <h3 class="text-2xl font-semibold mb-4">Your Badges</h3>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
          <div class="p-4 bg-blue-50 rounded shadow text-center">
            <h4>Beginner</h4>
            <p class="text-3xl"><?= $badgeCounts['beginner'] ?></p>
          </div>
          <div class="p-4 bg-green-50 rounded shadow text-center">
            <h4>Intermediate</h4>
            <p class="text-3xl"><?= $badgeCounts['intermediate'] ?></p>
          </div>
          <div class="p-4 bg-purple-50 rounded shadow text-center">
            <h4>Pro</h4>
            <p class="text-3xl"><?= $badgeCounts['pro'] ?></p>
          </div>
        </div>
      </div>

      <!-- Discussion Forum -->
      <div id="discussion" class="tab-content">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
          <!-- Recent Announcements -->
          <div class="bg-gray-50 p-6 rounded shadow">
            <h3 class="text-2xl mb-4">Recent Announcements</h3>
            <?php if ($announcements): foreach($announcements as $a): ?>
              <div class="mb-4 border-b pb-2">
                <div class="font-medium"><?= htmlspecialchars($a['course_name']) ?></div>
                <div class="text-sm text-gray-600"><?= htmlspecialchars($a['teacher_name']) ?> &bull; <?= date('M j, g:ia',strtotime($a['created_at'])) ?></div>
                <p class="mt-1"><?= nl2br(htmlspecialchars($a['message'])) ?></p>
              </div>
            <?php endforeach; else: ?>
              <p class="text-gray-500">No announcements yet.</p>
            <?php endif; ?>
          </div>

          <!-- Recent Messages -->
          <div class="bg-gray-50 p-6 rounded shadow">
            <h3 class="text-2xl mb-4">Recent Messages</h3>
            <?php if ($recent_messages): foreach($recent_messages as $m): ?>
              <div class="mb-4 border-b pb-2">
                <div class="font-medium">From: <?= htmlspecialchars($m['teacher_name']) ?></div>
                <div class="text-sm text-gray-600"><?= date('M j, g:ia',strtotime($m['created_at'])) ?></div>
                <p class="truncate"><?= htmlspecialchars($m['message']) ?></p>
              </div>
            <?php endforeach; else: ?>
              <p class="text-gray-500">No messages yet.</p>
            <?php endif; ?>
          </div>
        </div>
      </div>
    </div>
  </div>

  <script>
    const tabs = document.querySelectorAll('.tab-link');
    const panes = document.querySelectorAll('.tab-content');
    tabs.forEach(t => t.addEventListener('click', e => {
      e.preventDefault();
      tabs.forEach(x=>x.classList.remove('border-b-2','border-blue-600'));
      panes.forEach(x=>x.classList.remove('active','fade-in'));
      t.classList.add('border-b-2','border-blue-600');
      document.getElementById(t.dataset.tab).classList.add('active','fade-in');
    }));
    document.addEventListener('DOMContentLoaded', ()=>{
      if(location.hash){
        let el = document.querySelector(`.tab-link[data-tab="${location.hash.substring(1)}"]`);
        if(el) return el.click();
      }
      tabs[0].click();
    });
  </script>
</body>
</html>
