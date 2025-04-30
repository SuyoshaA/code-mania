<?php
// peer_review.php
include('db.php');
session_start();

// 1) Require login
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}
$user_id = $_SESSION['user_id'];

// 2) Define which course names should appear
$allowedCourses = [
    'Complete Python Mastery',
    'Complete C++ Mastery',
    'Complete Web Development',
    'Complete Java Mastery'
];

// 3) Fetch only the courses this student is enrolled in AND in our allowed list
$courses = [];
$sql = "
    SELECT c.id, c.course_name
      FROM courses c
      JOIN student_progress sp 
        ON sp.course_id = c.id
     WHERE sp.student_id = ?
";
if ($stmt = $conn->prepare($sql)) {
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $res = $stmt->get_result();
    while ($row = $res->fetch_assoc()) {
        if (in_array($row['course_name'], $allowedCourses)) {
            $courses[] = $row;
        }
    }
    $stmt->close();
}

// 4) Determine which course is selected (or default to the first)
$selected_course_id = $_GET['course_id'] 
    ?? ($courses[0]['id'] ?? null);

// 5) Handle new post submission
if ($_SERVER['REQUEST_METHOD'] === 'POST'
    && isset($_POST['content'], $_POST['course_id'])
) {
    $content = trim($_POST['content']);
    $cid     = intval($_POST['course_id']);
    $filename = null;

    // Only allow posts to courses you’re actually enrolled in
    $valid = false;
    foreach ($courses as $c) {
        if ($c['id'] === $cid) {
            $valid = true;
            break;
        }
    }

    if ($valid && $content !== '') {
        // File upload
        if (!empty($_FILES['file']['name'])) {
            $ext = pathinfo($_FILES['file']['name'], PATHINFO_EXTENSION);
            $allowed_ext = ['cpp','java','py','php','txt'];
            if (in_array(strtolower($ext), $allowed_ext)) {
                if (!is_dir('uploads')) {
                    mkdir('uploads', 0755, true);
                }
                $filename = uniqid() . "_" . basename($_FILES['file']['name']);
                move_uploaded_file(
                    $_FILES['file']['tmp_name'],
                    __DIR__ . "/uploads/$filename"
                );
            }
        }

        // Insert
        $ins = $conn->prepare("
            INSERT INTO peer_posts
                (user_id, content, course_id, file_path)
            VALUES (?,?,?,?)
        ");
        $ins->bind_param("isis", $user_id, $content, $cid, $filename);
        $ins->execute();
        $ins->close();
    }

    // Redirect back to avoid resubmission
    header("Location: peer_review.php?course_id=$cid");
    exit();
}

// 6) Fetch posts for the selected course
$posts = [];
if ($selected_course_id) {
    $pq = $conn->prepare("
        SELECT pp.content, pp.created_at, pp.file_path, u.username, u.email
          FROM peer_posts pp
          JOIN users u ON pp.user_id = u.id
         WHERE pp.course_id = ?
         ORDER BY pp.created_at DESC
    ");
    $pq->bind_param("i", $selected_course_id);
    $pq->execute();
    $pr = $pq->get_result();
    while ($row = $pr->fetch_assoc()) {
        $posts[] = $row;
    }
    $pq->close();
}

// 7) Find the name of the selected course
$selected_course_name = '';
foreach ($courses as $c) {
    if ($c['id'] === (int)$selected_course_id) {
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
  <title>Peer Review Forum</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gradient-to-br from-indigo-100 to-purple-100 min-h-screen">
  <div class="max-w-4xl mx-auto mt-12 p-6 bg-white rounded-xl shadow-lg">
    <h1 class="text-4xl font-bold text-purple-700 text-center mb-8">
      💬 Peer Review Forum
    </h1>

    <?php if (empty($courses)): ?>
      <p class="text-center text-gray-500">
        You’re not enrolled in any peer-reviewable courses yet.
      </p>
    <?php else: ?>
      <!-- Course selector -->
      <div class="mb-6">
        <label class="block text-sm font-medium text-gray-700 mb-1">
          Select Course:
        </label>
        <select
          onchange="location='peer_review.php?course_id='+this.value"
          class="w-full border border-purple-300 rounded px-3 py-2"
        >
          <?php foreach ($courses as $c): ?>
            <option
              value="<?= $c['id'] ?>"
              <?= $c['id']==$selected_course_id?'selected':'' ?>
            >
              <?= htmlspecialchars($c['course_name']) ?>
            </option>
          <?php endforeach; ?>
        </select>
      </div>

      <!-- New Post Form -->
      <div class="mb-10">
        <form method="post" enctype="multipart/form-data">
          <input type="hidden" name="course_id" value="<?= $selected_course_id ?>"/>
          <textarea
            name="content"
            rows="4"
            required
            class="w-full p-4 border border-purple-300 rounded mb-2"
            placeholder="Your question, feedback, or code snippet…"
          ></textarea>
          <input
            type="file"
            name="file"
            class="block mb-2 border border-purple-300 rounded px-3 py-2 w-full"
          />
          <button
            type="submit"
            class="bg-purple-600 hover:bg-purple-700 text-white px-5 py-2 rounded"
          >
            Post
          </button>
        </form>
      </div>

      <!-- Posts List -->
      <h2 class="text-2xl font-semibold text-gray-800 mb-4">
        Recent Posts (<?= htmlspecialchars($selected_course_name) ?>)
      </h2>
      <?php if ($posts): ?>
        <div class="space-y-4">
          <?php foreach ($posts as $p): ?>
            <div class="p-5 bg-gray-50 border border-purple-200 rounded-lg shadow-sm">
              <div class="flex justify-between text-sm text-gray-600 mb-2">
                <span>👤 <?= htmlspecialchars($p['username'] ?: $p['email']) ?></span>
                <span>🕒 <?= $p['created_at'] ?></span>
              </div>
              <p class="text-gray-800 whitespace-pre-line mb-2">
                <?= nl2br(htmlspecialchars($p['content'])) ?>
              </p>
              <?php if ($p['file_path']): ?>
                <a
                  href="uploads/<?= htmlspecialchars($p['file_path']) ?>"
                  target="_blank"
                  class="text-blue-600 hover:underline"
                >
                  📎 Download Attached File
                </a>
              <?php endif; ?>
            </div>
          <?php endforeach; ?>
        </div>
      <?php else: ?>
        <p class="text-gray-600">No posts yet. Be the first to share!</p>
      <?php endif; ?>
    <?php endif; ?>
  </div>
</body>
</html>
