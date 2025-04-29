<?php
// peer_review.php
include('db.php');
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}
$user_id = $_SESSION['user_id'];

// Get course list for dropdown (add only specific allowed courses)
$allowedCourses = ['C++ Course', 'Java Course', 'Python Course', 'Web Development Course'];
$courses = [];

$courseQuery = "SELECT c.id, c.course_name FROM courses c 
               JOIN student_progress sp ON sp.course_id = c.id 
               WHERE sp.student_id = ?";
if ($courseStmt = $conn->prepare($courseQuery)) {
    $courseStmt->bind_param("i", $user_id);
    $courseStmt->execute();
    $courseResult = $courseStmt->get_result();
    while ($row = $courseResult->fetch_assoc()) {
        if (in_array($row['course_name'], $allowedCourses)) {
            $courses[] = $row;
        }
    }
    $courseStmt->close();
}

$selected_course_id = $_GET['course_id'] ?? ($courses[0]['id'] ?? null);

// Handle new post with file upload
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['content']) && isset($_POST['course_id'])) {
    $content = trim($_POST['content']);
    $selected_course_id = intval($_POST['course_id']);
    $filename = null;

    if (!empty($_FILES['file']['name'])) {
        $allowed_extensions = ['cpp', 'java', 'py', 'php', 'txt'];
        $upload_dir = 'uploads/';
        if (!is_dir($upload_dir)) mkdir($upload_dir);
        $original_name = basename($_FILES['file']['name']);
        $ext = pathinfo($original_name, PATHINFO_EXTENSION);

        if (in_array(strtolower($ext), $allowed_extensions)) {
            $filename = uniqid() . "_" . $original_name;
            move_uploaded_file($_FILES['file']['tmp_name'], $upload_dir . $filename);
        }
    }

    if (!empty($content) && $selected_course_id > 0) {
        $stmt = $conn->prepare("INSERT INTO peer_posts (user_id, content, course_id, file_path) VALUES (?, ?, ?, ?)");
        if ($stmt) {
            $stmt->bind_param("isis", $user_id, $content, $selected_course_id, $filename);
            $stmt->execute();
            $stmt->close();
            header("Location: peer_review.php?course_id=$selected_course_id");
            exit();
        }
    }
}

// Retrieve posts for the selected course
$posts = [];
if ($selected_course_id) {
    $postQuery = "SELECT pp.id, pp.content, pp.created_at, pp.file_path, u.email FROM peer_posts pp 
                  JOIN users u ON pp.user_id = u.id 
                  WHERE pp.course_id = ? ORDER BY pp.created_at DESC";
    $postStmt = $conn->prepare($postQuery);
    if ($postStmt) {
        $postStmt->bind_param("i", $selected_course_id);
        $postStmt->execute();
        $result = $postStmt->get_result();
        while ($row = $result->fetch_assoc()) {
            $posts[] = $row;
        }
        $postStmt->close();
    }
}

$selected_course_name = 'Selected Course';
foreach ($courses as $course) {
    if ($course['id'] == $selected_course_id) {
        $selected_course_name = $course['course_name'];
        break;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Peer Review Forum</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gradient-to-br from-indigo-100 to-purple-100 min-h-screen">
  <div class="max-w-4xl mx-auto mt-12 p-6 bg-white rounded-xl shadow-lg">
    <h1 class="text-4xl font-bold text-purple-700 text-center mb-8">💬 Peer Review Forum</h1>

    <!-- New Post Form -->
    <div class="mb-10">
      <h2 class="text-2xl font-semibold text-gray-800 mb-4">Post Something</h2>
      <form method="post" action="peer_review.php" enctype="multipart/form-data">
        <div class="mb-4">
          <label for="course_id" class="block text-sm font-medium text-gray-700 mb-1">Select Course:</label>
          <select name="course_id" id="course_id" class="w-full border border-purple-300 rounded px-3 py-2" onchange="location = 'peer_review.php?course_id=' + this.value;">
            <?php foreach ($courses as $course): ?>
              <option value="<?= $course['id'] ?>" <?= $course['id'] == $selected_course_id ? 'selected' : '' ?>><?= htmlspecialchars($course['course_name']) ?></option>
            <?php endforeach; ?>
          </select>
        </div>
        <textarea name="content" rows="4" class="w-full p-4 border border-purple-300 rounded focus:outline-none focus:ring-2 focus:ring-purple-400" placeholder="Share your question, feedback, or a code snippet..." required></textarea>
        <div class="mt-4">
          <label class="block text-sm font-medium text-gray-700 mb-1">Upload File (C++, Java, Python, PHP, TXT):</label>
          <input type="file" name="file" class="w-full border border-purple-300 rounded px-3 py-2" />
        </div>
        <button type="submit" class="mt-3 bg-purple-600 hover:bg-purple-700 text-white font-semibold px-5 py-2 rounded">Post</button>
      </form>
    </div>

    <!-- All Posts for Selected Course -->
    <div>
      <h2 class="text-2xl font-semibold text-gray-800 mb-6">Recent Posts (<?= htmlspecialchars($selected_course_name) ?>)</h2>
      <?php if (!empty($posts)): ?>
        <div class="space-y-4">
        <?php foreach ($posts as $post): ?>
          <div class="p-5 bg-gray-50 border border-purple-200 rounded-lg shadow">
            <div class="flex justify-between items-center mb-2">
              <span class="font-semibold text-purple-700">👤 <?= htmlspecialchars($post['email']) ?></span>
              <span class="text-sm text-gray-500">🕒 <?= htmlspecialchars($post['created_at']) ?></span>
            </div>
            <p class="text-gray-800 whitespace-pre-line mb-2"><?= nl2br(htmlspecialchars($post['content'])) ?></p>
            <?php if (!empty($post['file_path'])): ?>
              <a href="uploads/<?= htmlspecialchars($post['file_path']) ?>" target="_blank" class="text-blue-600 hover:underline">📎 Download Attached File</a>
            <?php endif; ?>
          </div>
        <?php endforeach; ?>
        </div>
      <?php else: ?>
        <p class="text-gray-600">No posts yet for this course. Be the first to share your thoughts!</p>
      <?php endif; ?>
    </div>

   
  </div>
</body>
</html>