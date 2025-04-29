<?php
// student_dashboard.php
include('db.php');
session_start();

// 1) Require login
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}
$user_id = $_SESSION['user_id'];

// 2) Handle enroll/drop
if (isset($_GET['enroll_course'])) {
    $cid = intval($_GET['enroll_course']);
    $chk = $conn->prepare("SELECT 1 FROM student_progress WHERE student_id=? AND course_id=?");
    $chk->bind_param("ii", $user_id, $cid);
    $chk->execute();
    if ($chk->get_result()->num_rows === 0) {
        $ins = $conn->prepare("INSERT INTO student_progress (student_id, course_id, completed) VALUES (?, ?, 0)");
        $ins->bind_param("ii", $user_id, $cid);
        $ins->execute();
        $ins->close();
    }
    $chk->close();
    header("Location: student_dashboard.php#my-courses");
    exit();
}
if (isset($_GET['drop_course'])) {
    $cid = intval($_GET['drop_course']);
    $del = $conn->prepare("DELETE FROM student_progress WHERE student_id=? AND course_id=?");
    $del->bind_param("ii", $user_id, $cid);
    $del->execute();
    $del->close();
    header("Location: student_dashboard.php#new-courses");
    exit();
}

// 3) Handle new peer post
if ($_SERVER['REQUEST_METHOD']==='POST' && isset($_POST['post_course_id'])) {
    $pcid = intval($_POST['post_course_id']);
    $content = trim($_POST['post_content']);
    $filename = null;
    if (!empty($_FILES['post_file']['name'])) {
        $ext = pathinfo($_FILES['post_file']['name'], PATHINFO_EXTENSION);
        if (in_array(strtolower($ext), ['cpp','java','py','php','txt'])) {
            if (!is_dir('uploads')) mkdir('uploads');
            $filename = uniqid() . "_" . basename($_FILES['post_file']['name']);
            move_uploaded_file($_FILES['post_file']['tmp_name'], "uploads/$filename");
        }
    }
    if ($content && $pcid>0) {
        $pst = $conn->prepare("INSERT INTO peer_posts (user_id, content, course_id, file_path) VALUES (?,?,?,?)");
        $pst->bind_param("isis", $user_id, $content, $pcid, $filename);
        $pst->execute();
        $pst->close();
    }
    header("Location: student_dashboard.php#discussion-forum");
    exit();
}

// 4) Fetch enrolled courses
$progress_data = [];
$sql = "
  SELECT c.id AS course_id
       , c.course_name
       , COALESCE(u.username,'Unassigned') AS teacher_name
       , sp.completed
    FROM student_progress sp
    JOIN courses c ON sp.course_id = c.id
    LEFT JOIN users u ON c.teacher_id = u.id
   WHERE sp.student_id = ?
";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$res = $stmt->get_result();
while ($r = $res->fetch_assoc()) {
    $progress_data[] = $r;
}
$stmt->close();

// 5) Fetch available courses
$available_courses = [];
$sql2 = "
  SELECT c.id, c.course_name, c.description
       , COALESCE(u.username,'Unassigned') AS teacher_name
    FROM courses c
    LEFT JOIN users u ON c.teacher_id = u.id
    LEFT JOIN student_progress sp 
      ON sp.course_id = c.id AND sp.student_id = ?
   WHERE sp.course_id IS NULL
";
$stmt2 = $conn->prepare($sql2);
$stmt2->bind_param("i", $user_id);
$stmt2->execute();
$res2 = $stmt2->get_result();
while ($r = $res2->fetch_assoc()) {
    $available_courses[] = $r;
}
$stmt2->close();

// 6) Stats
$totalCourses    = count($progress_data);
$totalProgress   = array_sum(array_column($progress_data,'completed'));
$averageProgress = $totalCourses ? round($totalProgress/$totalCourses,2) : 0;
$badgeCounts = ['pro'=>0,'intermediate'=>0,'beginner'=>0];
foreach ($progress_data as $c) {
    if ($c['completed'] >= 80)        $badgeCounts['pro']++;
    elseif ($c['completed'] >= 60)    $badgeCounts['intermediate']++;
    elseif ($c['completed'] >= 20)    $badgeCounts['beginner']++;
}

// 7) Discussion: which course?
$disc_course_id   = $_GET['disc_course'] ?? ($progress_data[0]['course_id'] ?? null);
$disc_posts = [];
if ($disc_course_id) {
    $pq = $conn->prepare("SELECT pp.*, u.username, u.email 
                           FROM peer_posts pp
                           JOIN users u ON pp.user_id=u.id
                          WHERE pp.course_id=? ORDER BY pp.created_at DESC");
    $pq->bind_param("i", $disc_course_id);
    $pq->execute();
    $pr = $pq->get_result();
    while ($row = $pr->fetch_assoc()) {
        $disc_posts[] = $row;
    }
    $pq->close();
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
    @keyframes fadeIn{from{opacity:0;transform:translateY(20px)}to{opacity:1;transform:translateY(0)}}
    .fade-in{animation:fadeIn .5s ease-out}
    .tab-content{display:none}
    .tab-content.active{display:block}
  </style>
</head>
<body class="bg-gradient-to-r from-gray-200 to-gray-100">
  <header class="sticky top-0 bg-gray-800 text-white p-4 flex justify-between">
    <h1 class="text-xl">Code Academy</h1>
    <a href="logout.php" class="hover:text-gray-300">Logout</a>
  </header>

  <div class="max-w-6xl mx-auto mt-8 p-8 bg-white rounded shadow">
    <!-- Quick Stats -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
      <div class="bg-blue-100 p-4 rounded">
        <h3 class="font-semibold">Enrolled</h3>
        <p class="text-2xl"><?= $totalCourses ?></p>
      </div>
      <div class="bg-green-100 p-4 rounded">
        <h3 class="font-semibold">Progress</h3>
        <p class="text-2xl"><?= $averageProgress ?>%</p>
      </div>
      <div class="bg-purple-100 p-4 rounded">
        <h3 class="font-semibold">Badges</h3>
        <p class="text-2xl"><?= array_sum($badgeCounts) ?></p>
      </div>
    </div>

    <!-- Tabs -->
    <nav class="border-b mb-6">
      <ul class="flex space-x-4">
        <li><a href="#" class="tab-link border-b-2 border-blue-600 pb-2" data-tab="my-courses">My Courses</a></li>
        <li><a href="#" class="tab-link pb-2" data-tab="new-courses">New Courses</a></li>
        <li><a href="#" class="tab-link pb-2" data-tab="discussion-forum">Discussion Forum</a></li>
      </ul>
    </nav>

    <!-- My Courses -->
    <div id="my-courses" class="tab-content fade-in active">
      <h2 class="text-2xl mb-4">My Enrolled Courses</h2>
      <div class="overflow-x-auto">
        <table class="w-full">
          <thead class="bg-blue-600 text-white">
            <tr>
              <th class="p-2 text-left">Course</th>
              <th class="p-2 text-left">Teacher</th>
              <th class="p-2 text-left">Progress</th>
              <th class="p-2 text-left">Actions</th>
            </tr>
          </thead>
          <tbody class="divide-y">
            <?php if ($progress_data): foreach($progress_data as $c): ?>
            <tr class="hover:bg-gray-50">
              <td class="p-2"><?= htmlspecialchars($c['course_name']) ?></td>
              <td class="p-2"><?= htmlspecialchars($c['teacher_name']) ?></td>
              <td class="p-2">
                <?= $c['completed'] ?>%
                <div class="bg-gray-200 h-2 rounded mt-1">
                  <div class="bg-green-600 h-2 rounded" style="width:<?= $c['completed'] ?>%"></div>
                </div>
              </td>
              <td class="p-2 space-x-2">
                <a href="student_dashboard.php?drop_course=<?= $c['course_id'] ?>#new-courses" class="bg-red-600 text-white px-2 py-1 rounded">Drop</a>
                <a href="course.php?course_id=<?= $c['course_id'] ?>" class="bg-blue-600 text-white px-2 py-1 rounded">Continue</a>
              </td>
            </tr>
            <?php endforeach; else: ?>
            <tr>
              <td colspan="4" class="p-4 text-center text-gray-500">No courses enrolled.</td>
            </tr>
            <?php endif;?>
          </tbody>
        </table>
      </div>
    </div>

    <!-- New Courses -->
    <div id="new-courses" class="tab-content">
      <h2 class="text-2xl mb-4">Available Courses</h2>
      <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        <?php if ($available_courses): foreach($available_courses as $c): ?>
        <div class="border p-4 rounded shadow hover:border-blue-500">
          <h3 class="font-bold"><?= htmlspecialchars($c['course_name']) ?></h3>
          <p class="text-sm text-gray-600 mb-2"><?= htmlspecialchars($c['teacher_name']) ?></p>
          <p class="text-sm mb-4"><?= htmlspecialchars($c['description']?:'No description') ?></p>
          <a href="student_dashboard.php?enroll_course=<?= $c['id'] ?>#my-courses" class="block bg-blue-600 text-white text-center py-2 rounded">
            Enroll Now
          </a>
        </div>
        <?php endforeach; else: ?>
        <p class="text-gray-500">No new courses right now.</p>
        <?php endif;?>
      </div>
    </div>

    <!-- Discussion Forum -->
    <div id="discussion-forum" class="tab-content">
      <h2 class="text-2xl mb-4">Discussion Forum</h2>

      <?php if (empty($progress_data)): ?>
        <p class="text-gray-500">Enroll in a course to join its forum.</p>
      <?php else: ?>
        <!-- Course selector -->
        <label class="block mb-2 font-medium">Select Course:</label>
        <select id="disc_course" class="mb-6 border px-3 py-2 rounded"
                onchange="location='student_dashboard.php?disc_course='+this.value+'#discussion-forum'">
          <?php foreach($progress_data as $c): ?>
            <option value="<?= $c['course_id'] ?>"
              <?= $c['course_id']==$disc_course_id ? 'selected':''?>>
              <?= htmlspecialchars($c['course_name']) ?>
            </option>
          <?php endforeach;?>
        </select>

        <!-- New Post Form -->
        <form method="post" enctype="multipart/form-data" class="mb-8">
          <input type="hidden" name="post_course_id" value="<?= $disc_course_id ?>"/>
          <textarea name="post_content" rows="3" required
            class="w-full border p-3 rounded mb-2" placeholder="Your question or code snippet..."></textarea>
          <input type="file" name="post_file" class="mb-2"/>
          <button type="submit" class="bg-purple-600 text-white px-4 py-2 rounded">Post</button>
        </form>

        <!-- Posts -->
        <?php if ($disc_posts): foreach($disc_posts as $p): ?>
          <div class="border-b py-4">
            <div class="flex justify-between text-sm text-gray-600">
              <span><?= htmlspecialchars($p['username'] ?: $p['email']) ?></span>
              <span><?= $p['created_at'] ?></span>
            </div>
            <p class="mt-2"><?= nl2br(htmlspecialchars($p['content'])) ?></p>
            <?php if ($p['file_path']): ?>
              <a href="uploads/<?= htmlspecialchars($p['file_path']) ?>" class="text-blue-600">Download file</a>
            <?php endif;?>
          </div>
        <?php endforeach; else: ?>
          <p class="text-gray-500">No posts yet. Be the first!</p>
        <?php endif;?>
      <?php endif;?>
    </div>
  </div>

  <script>
    // Tab switching
    const tabs = document.querySelectorAll('.tab-link'),
          panes = document.querySelectorAll('.tab-content');
    tabs.forEach(t=>t.addEventListener('click',e=>{
      e.preventDefault();
      tabs.forEach(x=>x.classList.remove('border-b-2','border-blue-600'));
      panes.forEach(p=>p.classList.remove('active','fade-in'));
      t.classList.add('border-b-2','border-blue-600');
      document.getElementById(t.dataset.tab).classList.add('active','fade-in');
    }));
    // Activate first
    document.addEventListener('DOMContentLoaded',()=>tabs[0].click());
  </script>
</body>
</html>
