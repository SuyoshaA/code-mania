<?php
include('db.php');
session_start();

// 1) Must be logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}
$user_id = $_SESSION['user_id'];

// 2) Must be a teacher
$stmt = $conn->prepare("SELECT role FROM users WHERE id = ?");
if (!$stmt) die("Prepare failed: ".$conn->error);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$user = $stmt->get_result()->fetch_assoc();
$stmt->close();

if (!$user || $user['role'] !== 'teacher') {
    header("Location: student_dashboard.php");
    exit();
}

// We’ll use the teacher’s own user_id as the courses.teacher_id
$teacher_id = $user_id;

// 3) Fetch all courses this teacher teaches
$taught_courses = [];
$stmt = $conn->prepare("SELECT id, course_name, description FROM courses WHERE teacher_id = ?");
$stmt->bind_param("i", $teacher_id);
$stmt->execute();
$res = $stmt->get_result();
while ($row = $res->fetch_assoc()) {
    $taught_courses[] = $row;
}
$stmt->close();

// 4) For each course, compute total students, avg progress, completed count
$enrollment_stats = [];
foreach ($taught_courses as $course) {
    $stmt = $conn->prepare("
      SELECT 
        COUNT(*) AS total_students,
        IFNULL(AVG(completed),0) AS avg_progress,
        SUM(completed = 100) AS completed_count
      FROM student_progress
      WHERE course_id = ?
    ");
    $stmt->bind_param("i", $course['id']);
    $stmt->execute();
    $stat = $stmt->get_result()->fetch_assoc();
    $stmt->close();
    $stat['course_id']   = $course['id'];
    $stat['course_name'] = $course['course_name'];
    $enrollment_stats[]  = $stat;
}

// 5) Top 5 students across all your courses
$top_students = [];
$stmt = $conn->prepare("
  SELECT 
    u.username,
    c.course_name,
    sp.completed,
    sp.last_accessed
  FROM student_progress sp
  JOIN users u   ON sp.student_id = u.id
  JOIN courses c ON sp.course_id  = c.id
  WHERE c.teacher_id = ?
  ORDER BY sp.completed DESC, sp.last_accessed DESC
  LIMIT 5
");
$stmt->bind_param("i", $teacher_id);
$stmt->execute();
$res = $stmt->get_result();
while ($row = $res->fetch_assoc()) {
    $top_students[] = $row;
}
$stmt->close();

// 6) Peer‐forum: determine which course is selected (via GET or default to first)
$selected_course_id = isset($_GET['course_id'])
    ? (int)$_GET['course_id']
    : (isset($taught_courses[0]) ? $taught_courses[0]['id'] : null);

// 7) Load posts for that course
$posts = [];
if ($selected_course_id) {
    $stmt = $conn->prepare("
      SELECT 
        pp.id, pp.content, pp.created_at, pp.file_path, u.email
      FROM peer_posts pp
      JOIN users u ON pp.user_id = u.id
      WHERE pp.course_id = ?
      ORDER BY pp.created_at DESC
    ");
    $stmt->bind_param("i", $selected_course_id);
    $stmt->execute();
    $res = $stmt->get_result();
    while ($row = $res->fetch_assoc()) {
        $posts[] = $row;
    }
    $stmt->close();
}

// 8) Handle new post + optional file upload
if ($_SERVER['REQUEST_METHOD']==='POST' && isset($_POST['content'],$_POST['course_id'])) {
    $content = trim($_POST['content']);
    $cid     = (int)$_POST['course_id'];
    $fname   = null;

    // file handling
    if (!empty($_FILES['file']['name'])) {
        $ext = pathinfo($_FILES['file']['name'], PATHINFO_EXTENSION);
        $allowed = ['cpp','java','py','php','txt'];
        if (in_array(strtolower($ext), $allowed)) {
            $upload_dir = 'uploads/';
            if (!is_dir($upload_dir)) mkdir($upload_dir,0755,true);
            $fname = uniqid().'_'.basename($_FILES['file']['name']);
            move_uploaded_file($_FILES['file']['tmp_name'], $upload_dir.$fname);
        }
    }

    if ($cid>0 && $content!=='') {
        $stmt = $conn->prepare("
          INSERT INTO peer_posts (user_id, content, course_id, file_path)
          VALUES (?, ?, ?, ?)
        ");
        $stmt->bind_param("isis", $user_id, $content, $cid, $fname);
        $stmt->execute();
        $stmt->close();
        header("Location: teacher_dashboard.php?course_id={$cid}#peer-forum");
        exit();
    }
}

// helper: find the selected course name
$selected_course_name = '—';
foreach ($taught_courses as $c) {
    if ($c['id']==$selected_course_id) {
        $selected_course_name = $c['course_name'];
        break;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8"/>
  <meta name="viewport" content="width=device-width,initial-scale=1"/>
  <title>Teacher Dashboard</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <style>
    @keyframes fadeIn { from {opacity:0;transform:translateY(20px);} to{opacity:1;transform:translateY(0);} }
    .fade-in { animation: fadeIn .5s ease-out; }
    .tab-content { display:none; }
    .tab-content.active { display:block; }
  </style>
</head>
<body class="bg-gradient-to-r from-gray-200 to-gray-100">
  <header class="sticky top-0 bg-gray-800 text-white flex justify-between px-6 py-4 shadow">
    <h1 class="text-xl font-bold">Code Academy — Teacher</h1>
    <a href="logout.php" class="hover:text-gray-300">Logout</a>
  </header>

  <main class="max-w-6xl mx-auto mt-16 p-8 bg-white rounded shadow">
    <!-- Header -->
    <div class="text-center mb-10">
      <h2 class="text-4xl font-bold">Teacher Dashboard</h2>
      <p class="text-gray-600">Manage your courses and track student progress.</p>
      <?php if ($taught_courses): ?>
        <div class="mt-4">
          <?php foreach ($taught_courses as $c): ?>
            <span class="inline-block bg-blue-100 text-blue-800 px-3 py-1 rounded-full text-sm mr-2">
              <?= htmlspecialchars($c['course_name']) ?>
            </span>
          <?php endforeach; ?>
        </div>
      <?php endif; ?>
    </div>

    <!-- Quick Stats -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-10">
      <div class="bg-blue-100 p-6 rounded shadow">
        <h3 class="font-semibold text-blue-800">Courses Taught</h3>
        <p class="text-3xl text-blue-600"><?= count($taught_courses) ?></p>
      </div>
      <div class="bg-green-100 p-6 rounded shadow">
        <h3 class="font-semibold text-green-800">Total Students</h3>
        <p class="text-3xl text-green-600">
          <?= array_sum(array_column($enrollment_stats,'total_students')) ?>
        </p>
      </div>
    </div>

    <!-- Tabs -->
    <nav class="mb-8 border-b">
      <ul class="flex justify-center space-x-6">
        <li><a href="#" class="tab-link pb-2 border-b-2 border-blue-600 text-blue-600" data-tab="my-courses">My Courses</a></li>
        <li><a href="#" class="tab-link pb-2 text-gray-700 hover:text-blue-600" data-tab="student-progress">Student Progress</a></li>
        <li><a href="#" class="tab-link pb-2 text-gray-700 hover:text-blue-600" data-tab="peer-forum">Peer Forum</a></li>
      </ul>
    </nav>

    <div class="space-y-10">
      <!-- My Courses -->
      <section id="my-courses" class="tab-content active fade-in">
        <h3 class="text-2xl font-semibold mb-4">My Courses</h3>
        <div class="overflow-x-auto">
          <table class="min-w-full bg-white">
            <thead class="bg-blue-600 text-white">
              <tr>
                <th class="px-4 py-2">Name</th>
                <th class="px-4 py-2">Description</th>
                <th class="px-4 py-2">Students</th>
              </tr>
            </thead>
            <tbody class="divide-y">
              <?php if ($taught_courses): ?>
                <?php foreach ($taught_courses as $c): 
                  $count=0;
                  foreach($enrollment_stats as $s){
                    if($s['course_id']==$c['id']){ $count=$s['total_students']; break;}
                  }
                ?>
                  <tr class="hover:bg-gray-50">
                    <td class="px-4 py-2"><?= htmlspecialchars($c['course_name']) ?></td>
                    <td class="px-4 py-2"><?= htmlspecialchars($c['description']?:'–') ?></td>
                    <td class="px-4 py-2"><?= $count ?></td>
                  </tr>
                <?php endforeach; ?>
              <?php else: ?>
                <tr>
                  <td colspan="3" class="py-6 text-center text-gray-500">You’re not teaching any courses yet.</td>
                </tr>
              <?php endif; ?>
            </tbody>
          </table>
        </div>
      </section>

      <!-- Student Progress -->
      <section id="student-progress" class="tab-content fade-in">
        <h3 class="text-2xl font-semibold mb-4">Student Progress Overview</h3>

        <?php if ($enrollment_stats): ?>
          <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-6">
            <?php foreach ($enrollment_stats as $s): ?>
              <div class="p-4 bg-white rounded shadow border">
                <h4 class="font-semibold mb-2"><?= htmlspecialchars($s['course_name']) ?></h4>
                <p>Total Students: <?= $s['total_students'] ?></p>
                <p>Avg Progress: <?= round($s['avg_progress'],2) ?>%</p>
                <div class="w-full bg-gray-200 h-2 rounded mt-1">
                  <div class="h-2 bg-blue-600 rounded" style="width:<?= round($s['avg_progress'],2) ?>%"></div>
                </div>
                <p class="mt-2">Completed: <?= $s['completed_count'] ?></p>
                <a href="course_progress.php?course_id=<?= $s['course_id'] ?>" class="mt-2 inline-block bg-blue-600 text-white px-3 py-1 rounded hover:bg-blue-700">
                  Details
                </a>
              </div>
            <?php endforeach; ?>
          </div>

          <h4 class="text-xl font-semibold mb-2">Top 5 Students</h4>
          <div class="overflow-x-auto">
            <table class="min-w-full bg-white">
              <thead class="bg-green-600 text-white">
                <tr>
                  <th class="px-4 py-2">Student</th>
                  <th class="px-4 py-2">Course</th>
                  <th class="px-4 py-2">Progress</th>
                  <th class="px-4 py-2">Last Access</th>
                </tr>
              </thead>
              <tbody class="divide-y">
                <?php if ($top_students): ?>
                  <?php foreach ($top_students as $st): ?>
                    <tr class="hover:bg-gray-50">
                      <td class="px-4 py-2"><?= htmlspecialchars($st['username']) ?></td>
                      <td class="px-4 py-2"><?= htmlspecialchars($st['course_name']) ?></td>
                      <td class="px-4 py-2"><?= $st['completed'] ?>%</td>
                      <td class="px-4 py-2"><?= date('M j, Y',strtotime($st['last_accessed'])) ?></td>
                    </tr>
                  <?php endforeach; ?>
                <?php else: ?>
                  <tr>
                    <td colspan="4" class="py-4 text-center text-gray-500">No progress data.</td>
                  </tr>
                <?php endif; ?>
              </tbody>
            </table>
          </div>
        <?php else: ?>
          <p class="text-gray-500">No student progress yet.</p>
        <?php endif; ?>
      </section>

      <!-- Peer Forum -->
      <section id="peer-forum" class="tab-content fade-in">
        <h3 class="text-2xl font-semibold mb-6">Peer Review Forum</h3>

        <!-- new post -->
        <form method="post" enctype="multipart/form-data" class="mb-8 bg-gray-50 p-6 rounded shadow">
          <label class="block mb-2 font-medium">Course:</label>
          <select name="course_id" onchange="location='teacher_dashboard.php?course_id='+this.value+'#peer-forum'"
                  class="w-full mb-4 px-3 py-2 border rounded">
            <?php foreach ($taught_courses as $c): ?>
              <option value="<?= $c['id'] ?>"
                <?= $c['id']==$selected_course_id?'selected':''?>>
                <?= htmlspecialchars($c['course_name']) ?>
              </option>
            <?php endforeach; ?>
          </select>

          <label class="block mb-2 font-medium">Content:</label>
          <textarea name="content" rows="4"
                    class="w-full px-3 py-2 mb-4 border rounded"
                    placeholder="Your question or code snippet…" required></textarea>

          <label class="block mb-2 font-medium">Attach file:</label>
          <input type="file" name="file" class="w-full mb-4"/>

          <button type="submit" class="bg-purple-600 text-white px-5 py-2 rounded hover:bg-purple-700">
            Post
          </button>
        </form>

        <!-- posts -->
        <h4 class="text-xl font-semibold mb-4">Recent Posts for “<?= htmlspecialchars($selected_course_name) ?>”</h4>
        <?php if ($posts): ?>
          <div class="space-y-6">
            <?php foreach ($posts as $p): ?>
              <div class="p-6 bg-gray-50 rounded shadow border">
                <div class="flex justify-between mb-2">
                  <span class="font-medium">👤 <?= htmlspecialchars($p['email']) ?></span>
                  <span class="text-sm text-gray-500">🕒 <?= htmlspecialchars($p['created_at']) ?></span>
                </div>
                <p class="mb-2"><?= nl2br(htmlspecialchars($p['content'])) ?></p>
                <?php if ($p['file_path']): ?>
                  <a href="uploads/<?= htmlspecialchars($p['file_path']) ?>" target="_blank"
                     class="text-blue-600 hover:underline">📎 Download Attachment</a>
                <?php endif; ?>
              </div>
            <?php endforeach; ?>
          </div>
        <?php else: ?>
          <p class="text-gray-500">No posts yet. Start the discussion above!</p>
        <?php endif; ?>
      </section>
    </div>
  </main>

  <script>
    const tabs = document.querySelectorAll('.tab-link'),
          contents = document.querySelectorAll('.tab-content');

    tabs.forEach(t => t.addEventListener('click', e => {
      e.preventDefault();
      tabs.forEach(x=>x.classList.remove('border-b-2','border-blue-600','text-blue-600'));
      contents.forEach(x=>x.classList.remove('active','fade-in'));
      t.classList.add('border-b-2','border-blue-600','text-blue-600');
      document.getElementById(t.dataset.tab).classList.add('active','fade-in');
    }));
    document.addEventListener('DOMContentLoaded',_=>{
      const hash = location.hash.slice(1),
            active = document.querySelector(`.tab-link[data-tab="${hash}"]`);
      (active||tabs[0]).click();
    });
  </script>
</body>
</html>
