<?php
// quiz.php
include('db.php');
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}
if (!isset($_GET['quiz_id'])) {
    header("Location: dashboard.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$quiz_id = $_GET['quiz_id'];

// Retrieve quiz info
$query = "SELECT * FROM quizzes WHERE id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $quiz_id);
$stmt->execute();
$result = $stmt->get_result();
$quiz = $result->fetch_assoc();
$stmt->close();

if (!$quiz) {
    echo "<div class='max-w-4xl mx-auto p-6 bg-white shadow-md mt-10'>";
    echo "<h1 class='text-3xl font-bold mb-4'>Quiz not found</h1>";
    echo "<a href='dashboard.php' class='text-blue-500 hover:underline'>Back to Dashboard</a>";
    echo "</div>";
    exit();
}

// Retrieve quiz questions from quiz_questions table
$query = "SELECT * FROM quiz_questions WHERE quiz_id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $quiz_id);
$stmt->execute();
$result = $stmt->get_result();
$questions = [];
while ($row = $result->fetch_assoc()) {
    $questions[] = $row;
}
$stmt->close();

// Process form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $score = 0;
    $total = count($questions);
    foreach ($questions as $q) {
        $qid = $q['id'];
        $correct = $q['correct_option']; // Expected to be 'A', 'B', 'C', or 'D'
        $userAnswer = isset($_POST["answer_$qid"]) ? $_POST["answer_$qid"] : '';
        if ($userAnswer === $correct) {
            $score++;
        }
    }
    $percentage = round(($score / $total) * 100, 2);
    
    // Save quiz score
    $saveQuery = "INSERT INTO quiz_scores (student_id, quiz_id, score) 
                 VALUES (?, ?, ?) 
                 ON DUPLICATE KEY UPDATE score = ?";
    $saveStmt = $conn->prepare($saveQuery);
    $saveStmt->bind_param("iiii", $user_id, $quiz_id, $percentage, $percentage);
    $saveStmt->execute();
    $saveStmt->close();
    
    // Check if this unlocks the next module
    $nextModuleUnlocked = false;
    if ($percentage >= 80) {
        // Get current module info
        $moduleQuery = "SELECT m.id, m.sort_order, m.course_id 
                       FROM modules m
                       JOIN chapters c ON m.id = c.module_id
                       JOIN quizzes q ON c.id = q.chapter_id
                       WHERE q.id = ?";
        $moduleStmt = $conn->prepare($moduleQuery);
        $moduleStmt->bind_param("i", $quiz_id);
        $moduleStmt->execute();
        $moduleResult = $moduleStmt->get_result();
        $currentModule = $moduleResult->fetch_assoc();
        $moduleStmt->close();
        
        if ($currentModule) {
            // Get next module
            $nextModuleQuery = "SELECT id FROM modules 
                              WHERE course_id = ? AND sort_order > ? 
                              ORDER BY sort_order ASC LIMIT 1";
            $nextModuleStmt = $conn->prepare($nextModuleQuery);
            $nextModuleStmt->bind_param("ii", $currentModule['course_id'], $currentModule['sort_order']);
            $nextModuleStmt->execute();
            $nextModuleResult = $nextModuleStmt->get_result();
            if ($nextModuleResult->num_rows > 0) {
                $nextModuleUnlocked = true;
            }
            $nextModuleStmt->close();
        }
    }
    
    // Display results
    ?>
    <!DOCTYPE html>
    <html lang="en">
    <head>
      <meta charset="UTF-8">
      <title>Quiz Results</title>
      <meta name="viewport" content="width=device-width, initial-scale=1.0">
      <script src="https://cdn.tailwindcss.com"></script>
    </head>
    <body class="bg-gray-100">
      <div class="max-w-4xl mx-auto p-6 bg-white shadow-md rounded-lg mt-10">
        <div class="text-center mb-6">
          <h1 class="text-3xl font-bold text-purple-700 mb-2">Quiz Results</h1>
          <div class="text-5xl font-bold mb-4 <?= $percentage >= 80 ? 'text-green-600' : 'text-red-600' ?>">
            <?= $percentage ?>%
          </div>
          <p class="text-xl mb-4">You scored <?= $score ?> out of <?= $total ?></p>
          
          <?php if ($percentage >= 80): ?>
            <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6">
              <p class="font-semibold">Congratulations! 🎉</p>
              <p>You passed the quiz with flying colors!</p>
              <?php if ($nextModuleUnlocked): ?>
                <p class="mt-2">You've unlocked the next module!</p>
              <?php endif; ?>
            </div>
          <?php else: ?>
            <div class="bg-yellow-100 border-l-4 border-yellow-500 text-yellow-700 p-4 mb-6">
              <p class="font-semibold">Good try!</p>
              <p>You need at least 80% to unlock the next module.</p>
            </div>
          <?php endif; ?>
          
          <div class="flex justify-center space-x-4">
            <?php if (isset($currentModule['course_id'])): ?>
              <a href="course.php?course_id=<?= $currentModule['course_id'] ?>" 
                 class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-lg font-medium transition duration-300">
                Back to Course
              </a>
            <?php endif; ?>
            <a href="dashboard.php" 
               class="bg-gray-600 hover:bg-gray-700 text-white px-6 py-3 rounded-lg font-medium transition duration-300">
              Back to Dashboard
            </a>
          </div>
        </div>
        
        <?php if ($percentage < 100): ?>
          <div class="border-t pt-6">
            <h2 class="text-xl font-semibold mb-4">Questions to Review</h2>
            <?php foreach ($questions as $index => $q): 
              $qid = $q['id'];
              $userAnswer = isset($_POST["answer_$qid"]) ? $_POST["answer_$qid"] : '';
              $correct = $q['correct_option'];
              if ($userAnswer !== $correct): ?>
                <div class="mb-6 p-4 bg-gray-50 rounded-lg border border-gray-200">
                  <p class="font-semibold text-lg">Question <?= $index + 1 ?>: <?= htmlspecialchars($q['question_text']) ?></p>
                  <p class="mt-2 <?= $userAnswer === 'A' ? 'text-red-600' : '' ?>">
                    <span class="font-medium">Your answer:</span> 
                    <?= $userAnswer ? htmlspecialchars($q['option_' . strtolower($userAnswer)]) : 'No answer' ?>
                  </p>
                  <p class="text-green-600">
                    <span class="font-medium">Correct answer:</span> 
                    <?= htmlspecialchars($q['option_' . strtolower($correct)]) ?>
                  </p>
                  <?php if (!empty($q['explanation'])): ?>
                    <div class="mt-2 p-3 bg-blue-50 rounded">
                      <p class="font-medium">Explanation:</p>
                      <p><?= nl2br(htmlspecialchars($q['explanation'])) ?></p>
                    </div>
                  <?php endif; ?>
                </div>
              <?php endif; ?>
            <?php endforeach; ?>
          </div>
        <?php endif; ?>
      </div>
    </body>
    </html>
    <?php
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title><?php echo htmlspecialchars($quiz['quiz_title']); ?> - Quiz</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <script src="https://cdn.tailwindcss.com"></script>
  <style>
    .quiz-question {
      transition: all 0.3s ease;
    }
    .quiz-question:hover {
      transform: translateY(-2px);
    }
  </style>
</head>
<body class="bg-gray-100">
  <div class="max-w-4xl mx-auto p-6 bg-white shadow-md rounded-lg mt-10">
    <div class="mb-8 text-center">
      <h1 class="text-3xl font-bold text-purple-700 mb-2"><?php echo htmlspecialchars($quiz['quiz_title']); ?></h1>
      <?php if (!empty($quiz['quiz_content'])): ?>
        <p class="text-gray-600"><?php echo nl2br(htmlspecialchars($quiz['quiz_content'])); ?></p>
      <?php endif; ?>
      <div class="mt-4 bg-blue-50 border-l-4 border-blue-500 text-blue-700 p-4">
        <p class="font-semibold">Instructions:</p>
        <p>Answer all questions. You need 80% or higher to pass and unlock the next module.</p>
      </div>
    </div>
    
    <?php if (count($questions) > 0): ?>
      <form method="post" class="space-y-8">
        <?php foreach ($questions as $index => $q): ?>
          <div class="quiz-question p-6 bg-gray-50 rounded-lg border border-gray-200 hover:shadow-md">
            <p class="font-semibold text-lg mb-4">
              <span class="text-purple-600">Question <?= $index + 1 ?>:</span> 
              <?php echo htmlspecialchars($q['question_text']); ?>
            </p>
            
            <div class="space-y-3 ml-4">
              <label class="flex items-center space-x-3 p-3 rounded-lg hover:bg-gray-100 cursor-pointer">
                <input type="radio" name="answer_<?php echo $q['id']; ?>" value="A" required class="h-5 w-5 text-purple-600">
                <span class="text-gray-700">A) <?php echo htmlspecialchars($q['option_a']); ?></span>
              </label>
              <label class="flex items-center space-x-3 p-3 rounded-lg hover:bg-gray-100 cursor-pointer">
                <input type="radio" name="answer_<?php echo $q['id']; ?>" value="B" class="h-5 w-5 text-purple-600">
                <span class="text-gray-700">B) <?php echo htmlspecialchars($q['option_b']); ?></span>
              </label>
              <label class="flex items-center space-x-3 p-3 rounded-lg hover:bg-gray-100 cursor-pointer">
                <input type="radio" name="answer_<?php echo $q['id']; ?>" value="C" class="h-5 w-5 text-purple-600">
                <span class="text-gray-700">C) <?php echo htmlspecialchars($q['option_c']); ?></span>
              </label>
              <label class="flex items-center space-x-3 p-3 rounded-lg hover:bg-gray-100 cursor-pointer">
                <input type="radio" name="answer_<?php echo $q['id']; ?>" value="D" class="h-5 w-5 text-purple-600">
                <span class="text-gray-700">D) <?php echo htmlspecialchars($q['option_d']); ?></span>
              </label>
            </div>
          </div>
        <?php endforeach; ?>
        
        <div class="flex justify-between items-center pt-4">
          <div class="text-gray-500">
            <?= count($questions) ?> questions
          </div>
          <button type="submit" class="bg-purple-600 hover:bg-purple-700 text-white px-8 py-3 rounded-lg font-medium text-lg transition duration-300 transform hover:scale-105">
            Submit Quiz
          </button>
        </div>
      </form>
    <?php else: ?>
      <div class="text-center p-8 bg-yellow-50 rounded-lg">
        <p class="text-xl text-yellow-700">No questions available for this quiz yet.</p>
        <a href="course.php?course_id=<?= $quiz['course_id'] ?>" class="inline-block mt-4 text-blue-600 hover:underline">
          Back to Course
        </a>
      </div>
    <?php endif; ?>
  </div>
</body>
</html>