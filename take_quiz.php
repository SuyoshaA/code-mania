<?php
include('db.php');
session_start();
if (!isset($_SESSION['user_id'])) { 
    header("Location: login.php"); 
    exit(); 
}
if (!isset($_GET['quiz_id'])) { 
    header("Location: modules.php"); 
    exit(); 
}

$quiz_id = $_GET['quiz_id'];

$stmt = $conn->prepare("SELECT * FROM quizzes WHERE id = ?");
$stmt->bind_param("i", $quiz_id);
$stmt->execute();
$quiz = $stmt->get_result()->fetch_assoc();
$stmt->close();

if (!$quiz) { 
    echo "Quiz not found."; 
    exit(); 
}

$stmt = $conn->prepare("SELECT * FROM quiz_questions WHERE quiz_id = ?");
$stmt->bind_param("i", $quiz_id);
$stmt->execute();
$result = $stmt->get_result();
$questions = [];
while($row = $result->fetch_assoc()){
  $questions[] = $row;
}
$stmt->close();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $score = 0;
    $total = count($questions);
    foreach ($questions as $q) {
        $qid = $q['id'];
        $correct = $q['correct_option'];
        $userAnswer = isset($_POST["answer_$qid"]) ? $_POST["answer_$qid"] : '';
        if ($userAnswer === $correct) {
            $score++;
        }
    }
    $percentage = round(($score / $total) * 100, 2);
    
    echo "<div class='max-w-4xl mx-auto p-6 bg-white shadow-md mt-10'>";
    echo "<h1 class='text-3xl font-bold mb-4'>Quiz Results</h1>";
    echo "<p>You scored $score out of $total ($percentage%).</p>";
    
    if ($percentage >= 70) {
        // Update student progress here if desired, e.g.:
        // UPDATE student_progress SET completed = module_threshold WHERE student_id = ? AND course_id = ?
        echo "<p class='text-green-600 font-bold'>Congratulations! You passed this module. You may now proceed to the next module.</p>";
        // Redirect to next module or show link to next module
        echo "<a href='modules.php?course_id=" . $quiz['course_id'] . "' class='text-blue-500 hover:underline'>Go to Modules</a>";
    } else {
        echo "<p class='text-red-600 font-bold'>You did not reach 70%. Please retake the quiz to proceed.</p>";
        echo "<a href='take_quiz.php?quiz_id=" . $quiz_id . "' class='text-blue-500 hover:underline'>Retake Quiz</a>";
    }
    
    echo "</div>";
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title><?= htmlspecialchars($quiz['quiz_title']); ?> - Take Quiz</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100">
<div class="max-w-4xl mx-auto p-6 bg-white shadow-md mt-10">
  <h1 class="text-3xl font-bold mb-4"><?= htmlspecialchars($quiz['quiz_title']); ?></h1>
  <?php if (count($questions) > 0): ?>
    <form method="post">
      <?php foreach ($questions as $index => $q): ?>
        <div class="mb-6">
          <p class="font-semibold"><?= ($index + 1) . ". " . htmlspecialchars($q['question_text']); ?></p>
          <label class="block">
            <input type="radio" name="answer_<?= $q['id']; ?>" value="A" required> A) <?= htmlspecialchars($q['option_a']); ?>
          </label>
          <label class="block">
            <input type="radio" name="answer_<?= $q['id']; ?>" value="B"> B) <?= htmlspecialchars($q['option_b']); ?>
          </label>
          <label class="block">
            <input type="radio" name="answer_<?= $q['id']; ?>" value="C"> C) <?= htmlspecialchars($q['option_c']); ?>
          </label>
          <label class="block">
            <input type="radio" name="answer_<?= $q['id']; ?>" value="D"> D) <?= htmlspecialchars($q['option_d']); ?>
          </label>
        </div>
      <?php endforeach; ?>
      <button type="submit" class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded">Submit Quiz</button>
    </form>
  <?php else: ?>
    <p>No questions available for this quiz.</p>
  <?php endif; ?>
</div>
</body>
</html>
