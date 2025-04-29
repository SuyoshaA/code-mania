<?php
include('db.php');
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}
$user_id = $_SESSION['user_id'];
// For simplicity, we use the existing "peer_posts" table for chat messages.

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['message'])) {
    $message = $_POST['message'];
    $stmt = $conn->prepare("INSERT INTO peer_posts (user_id, content) VALUES (?, ?)");
    $stmt->bind_param("is", $user_id, $message);
    $stmt->execute();
    $stmt->close();
    header("Location: talk_to_teachers.php");
    exit();
}

// Query chat messages (for demonstration, we list all messages)
$query = "SELECT peer_posts.content, users.username, peer_posts.created_at
          FROM peer_posts
          JOIN users ON peer_posts.user_id = users.id
          ORDER BY peer_posts.created_at DESC";
$result = $conn->query($query);
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Talk to Teachers - Code Academy</title>
  <link href="https://cdn.tailwindcss.com" rel="stylesheet">
</head>
<body class="bg-gray-100">
  <div class="max-w-2xl mx-auto p-6 mt-10 bg-white rounded shadow">
    <h2 class="text-2xl font-bold mb-4 text-center">Talk to Teachers</h2>
    <div class="mb-6">
      <form action="talk_to_teachers.php" method="POST">
        <textarea name="message" rows="3" placeholder="Type your message here..." class="w-full p-2 border rounded" required></textarea>
        <button type="submit" class="mt-2 bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 transition duration-300">
          Send Message
        </button>
      </form>
    </div>
    <div class="space-y-4">
      <?php while ($row = $result->fetch_assoc()): ?>
        <div class="p-4 bg-gray-50 rounded shadow">
          <p class="text-gray-800"><strong><?= htmlspecialchars($row['username']) ?>:</strong> <?= htmlspecialchars($row['content']) ?></p>
          <p class="text-gray-500 text-sm"><?= htmlspecialchars($row['created_at']) ?></p>
        </div>
      <?php endwhile; ?>
    </div>
  </div>
</body>
</html>
