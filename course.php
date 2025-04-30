<?php
// course.php
include('db.php');
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}
if (!isset($_GET['course_id'])) {
    header("Location: student_dashboard.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$course_id = $_GET['course_id'];

// Retrieve course details
$query = "SELECT * FROM courses WHERE id = ?";
$stmt = $conn->prepare($query);
if (!$stmt) {
    die("Database error: " . $conn->error);
}
$stmt->bind_param("i", $course_id);
$stmt->execute();
$result = $stmt->get_result();
$course = $result->fetch_assoc();
$stmt->close();

if (!$course) {
    echo "Course not found.";
    exit();
}

// Retrieve modules
$modulesQuery = "SELECT * FROM modules WHERE course_id = ? ORDER BY sort_order ASC";
$stmt = $conn->prepare($modulesQuery);
if (!$stmt) {
    die("Database error: " . $conn->error);
}
$stmt->bind_param("i", $course_id);
$stmt->execute();
$modulesResult = $stmt->get_result();
$modules = [];
while ($row = $modulesResult->fetch_assoc()) {
    $modules[] = $row;
}
$stmt->close();

// Check if last module is a capstone
$has_capstone = false;
$last_module_id = null;
if (!empty($modules)) {
    $last_module_id = end($modules)['id'];
    $capstone_check = $conn->prepare("SELECT is_capstone FROM modules WHERE id = ?");
    if ($capstone_check) {
        $capstone_check->bind_param("i", $last_module_id);
        $capstone_check->execute();
        $capstone_result = $capstone_check->get_result();
        if ($row = $capstone_result->fetch_assoc()) {
            $has_capstone = $row['is_capstone'];
        }
        $capstone_check->close();
    }
}

// Retrieve progress
$progressQuery = "SELECT completed FROM student_progress WHERE student_id = ? AND course_id = ?";
$stmt = $conn->prepare($progressQuery);
if (!$stmt) {
    die("Database error: " . $conn->error);
}
$stmt->bind_param("ii", $user_id, $course_id);
$stmt->execute();
$progressResult = $stmt->get_result();
if ($progressRow = $progressResult->fetch_assoc()) {
    $currentProgress = $progressRow['completed'];
} else {
    $currentProgress = 0;
    $insertQuery = "INSERT INTO student_progress (student_id, course_id, completed) VALUES (?, ?, ?)";
    $stmtInsert = $conn->prepare($insertQuery);
    if ($stmtInsert) {
        $stmtInsert->bind_param("iii", $user_id, $course_id, $currentProgress);
        $stmtInsert->execute();
        $stmtInsert->close();
    }
}
$stmt->close();

$totalModules = count($modules);

// Check if student has submitted capstone
$capstone_submitted = null;
if ($has_capstone) {
    $capstone_query = $conn->prepare("SELECT * FROM capstone_projects WHERE student_id = ? AND course_id = ?");
    if ($capstone_query) {
        $capstone_query->bind_param("ii", $user_id, $course_id);
        $capstone_query->execute();
        $capstone_result = $capstone_query->get_result();
        $capstone_submitted = $capstone_result->fetch_assoc();
        $capstone_query->close();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title><?= htmlspecialchars($course['course_name']) ?> - Course Overview</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <style>
    @keyframes fadeIn {
      from { opacity: 0; transform: translateY(20px); }
      to { opacity: 1; transform: translateY(0); }
    }
    .fade-in { animation: fadeIn 0.5s ease-out; }
    .accordion-content { display: none; }
    .accordion-content.active { display: block; }
    .tab-content { display: none; }
    .tab-content.active { display: block; }
    .rotate-180 { transform: rotate(180deg); }
  </style>
</head>
<body class="bg-gradient-to-r from-gray-200 to-gray-100">
  <!-- Sticky Top Navigation Bar -->
  <header class="sticky top-0 bg-gray-800 text-white flex justify-between items-center px-6 py-4 shadow-lg z-10">
    <h1 class="text-xl font-bold">Code Academy</h1>
    <nav>
      <a href="student_dashboard.php" class="text-sm font-semibold hover:text-gray-300 transition-colors duration-300 mr-4">Dashboard</a>
      <a href="logout.php" class="text-sm font-semibold hover:text-gray-300 transition-colors duration-300">Logout</a>
    </nav>
  </header>
  
  <div class="max-w-6xl mx-auto mt-16 p-8 bg-white rounded-lg shadow-xl">
    <!-- Page Header -->
    <div class="text-center mb-10">
      <h2 class="text-4xl font-bold text-gray-800"><?= htmlspecialchars($course['course_name']) ?></h2>
      <?php if (!empty($course['description'])): ?>
        <p class="text-gray-600 mt-2"><?= nl2br(htmlspecialchars($course['description'])) ?></p>
      <?php endif; ?>
    </div>
    
    <!-- Quick Stats -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-10">
      <div class="bg-blue-100 p-6 rounded-lg shadow">
        <h3 class="text-lg font-semibold text-blue-800">Course Progress</h3>
        <p class="text-3xl font-bold text-blue-600 mt-2"><?= $currentProgress ?>%</p>
      </div>
      <div class="bg-green-100 p-6 rounded-lg shadow">
        <h3 class="text-lg font-semibold text-green-800">Modules Completed</h3>
        <p class="text-3xl font-bold text-green-600 mt-2"><?= floor($currentProgress / (100 / max(1, $totalModules))) ?>/<?= $totalModules ?></p>
      </div>
      <div class="bg-purple-100 p-6 rounded-lg shadow">
        <h3 class="text-lg font-semibold text-purple-800">Current Badge</h3>
        <?php
        $badge = '';
        if ($currentProgress >= 80) {
            $badge = '🌟 Pro Badge';
        } elseif ($currentProgress >= 60) {
            $badge = '🔥 Intermediate Badge';
        } elseif ($currentProgress >= 20) {
            $badge = '✨ Beginner Badge';
        } else {
            $badge = '📘 No badge yet';
        }
        ?>
        <p class="text-2xl font-bold text-purple-600 mt-2"><?= $badge ?></p>
      </div>
    </div>
    
    <!-- Tab Navigation -->
    <nav class="mb-10 border-b border-gray-300">
      <ul class="flex justify-center space-x-6">
        <li>
          <a href="#" class="tab-link inline-block py-2 px-4 font-semibold text-gray-700 hover:text-blue-600 transition-colors duration-300 border-b-2 border-blue-600" data-tab="modules">Modules</a>
        </li>
        <li>
          <a href="#" class="tab-link inline-block py-2 px-4 font-semibold text-gray-700 hover:text-blue-600 transition-colors duration-300" data-tab="resources">Resources</a>
        </li>
        <?php if ($has_capstone): ?>
        <li>
          <a href="#" class="tab-link inline-block py-2 px-4 font-semibold text-gray-700 hover:text-blue-600 transition-colors duration-300" data-tab="project-submission">Project</a>
        </li>
        <?php endif; ?>
      </ul>
    </nav>
    
    <!-- Tab Contents -->
    <div class="space-y-10">
      <!-- Modules Tab -->
      <div id="modules" class="tab-content fade-in active">
        <?php if (count($modules) > 0): ?>
          <div class="space-y-6">
            <?php foreach ($modules as $module): ?>
              <div class="bg-white p-6 rounded-lg shadow border border-gray-200 hover:border-blue-500 transition-colors duration-300">
                <div class="flex justify-between items-center cursor-pointer" onclick="toggleAccordion(this)">
                  <h3 class="text-xl font-semibold text-gray-800">📘 <?= htmlspecialchars($module['module_name']) ?></h3>
                  <svg class="w-6 h-6 transition-transform duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                  </svg>
                </div>
                
                <div class="accordion-content mt-4">
                  <?php if (!empty($module['module_description'])): ?>
                    <p class="mb-4 text-gray-700"><?= nl2br(htmlspecialchars($module['module_description'])) ?></p>
                  <?php endif; ?>
                  
                  <?php
                  // Check if student can access this module
                  $can_access = true;
                  if ($module['sort_order'] > 1) {
                      $prev_module_order = $module['sort_order'] - 1;
                      $prev_module_query = $conn->prepare("SELECT id FROM modules WHERE course_id = ? AND sort_order = ?");
                      if ($prev_module_query) {
                          $prev_module_query->bind_param("ii", $course_id, $prev_module_order);
                          $prev_module_query->execute();
                          $prev_module_result = $prev_module_query->get_result();
                          if ($prev_module = $prev_module_result->fetch_assoc()) {
                              // Check if student passed all quizzes in previous module
                              $quizzes_query = $conn->prepare("SELECT q.id FROM quizzes q 
                                                             JOIN chapters ch ON q.chapter_id = ch.id 
                                                             JOIN modules m ON ch.module_id = m.id 
                                                             WHERE m.id = ?");
                              if ($quizzes_query) {
                                  $quizzes_query->bind_param("i", $prev_module['id']);
                                  $quizzes_query->execute();
                                  $quizzes_result = $quizzes_query->get_result();
                                  $quiz_ids = [];
                                  while ($quiz_row = $quizzes_result->fetch_assoc()) {
                                      $quiz_ids[] = $quiz_row['id'];
                                  }
                                  
                                  foreach ($quiz_ids as $quiz_id) {
                                      $quiz_score_query = $conn->prepare("SELECT score FROM quiz_scores 
                                                                        WHERE student_id = ? AND quiz_id = ?");
                                      if ($quiz_score_query) {
                                          $quiz_score_query->bind_param("ii", $user_id, $quiz_id);
                                          $quiz_score_query->execute();
                                          $quiz_score_result = $quiz_score_query->get_result();
                                          if ($quiz_score = $quiz_score_result->fetch_assoc()) {
                                              if ($quiz_score['score'] < 80) {
                                                  $can_access = false;
                                                  break;
                                              }
                                          } else {
                                              $can_access = false;
                                              break;
                                          }
                                      }
                                  }
                              }
                          }
                      }
                  }
                  ?>
                  
                  <?php if (!$can_access): ?>
                    <div class="bg-yellow-100 border-l-4 border-yellow-500 text-yellow-700 p-4 mb-4">
                      <p>You must complete all quizzes in the previous module with at least 80% to unlock this module.</p>
                    </div>
                  <?php else: ?>
                    <?php
                    $chaptersQuery = "SELECT * FROM chapters WHERE module_id = ? ORDER BY sort_order ASC";
                    $stmt = $conn->prepare($chaptersQuery);
                    if ($stmt) {
                        $stmt->bind_param("i", $module['id']);
                        $stmt->execute();
                        $chaptersResult = $stmt->get_result();
                        $chapters = [];
                        while ($row = $chaptersResult->fetch_assoc()) {
                            $chapters[] = $row;
                        }
                        $stmt->close();
                    }
                    ?>
                    
                    <?php if (count($chapters) > 0): ?>
                      <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <?php foreach ($chapters as $chapter): ?>
                          <div class="bg-gray-50 border rounded-lg p-4">
                            <h4 class="text-lg font-bold text-purple-700 mb-2">📘 <?= htmlspecialchars($chapter['chapter_name']) ?></h4>
                            <p class="text-gray-600 mb-2"><?= nl2br(htmlspecialchars($chapter['chapter_description'])) ?></p>
                            
                            <?php
                            $lessonsQuery = "SELECT * FROM lessons WHERE chapter_id = ? ORDER BY sort_order ASC";
                            $stmt = $conn->prepare($lessonsQuery);
                            if ($stmt) {
                                $stmt->bind_param("i", $chapter['id']);
                                $stmt->execute();
                                $lessonsResult = $stmt->get_result();
                                $lessons = [];
                                while ($row = $lessonsResult->fetch_assoc()) {
                                    $lessons[] = $row;
                                }
                                $stmt->close();
                            }
                            ?>
                            
                            <?php if (count($lessons) > 0): ?>
                              <div class="mb-4">
                                <h5 class="font-semibold mb-2">Lessons:</h5>
                                <ul class="space-y-2">
                                  <?php foreach ($lessons as $lesson): ?>
                                    <li class="flex items-start">
                                      <span class="mr-2">📝</span>
                                      <div>
                                        <a href="lesson.php?lesson_id=<?= $lesson['id'] ?>" class="text-blue-600 hover:underline"><?= htmlspecialchars($lesson['lesson_title']) ?></a>
                                        <?php if (!empty($lesson['video_url'])): ?>
                                          <div class="mt-1">
                                            <a href="<?= htmlspecialchars($lesson['video_url']) ?>" target="_blank" class="text-sm text-blue-500 hover:underline flex items-center">
                                              <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z" />
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                              </svg>
                                              Watch Video
                                            </a>
                                          </div>
                                        <?php endif; ?>
                                      </div>
                                    </li>
                                  <?php endforeach; ?>
                                </ul>
                              </div>
                            <?php endif; ?>
                            
                            <?php
                            $quizQuery = "SELECT * FROM quizzes WHERE chapter_id = ? ORDER BY sort_order ASC";
                            $stmt = $conn->prepare($quizQuery);
                            if ($stmt) {
                                $stmt->bind_param("i", $chapter['id']);
                                $stmt->execute();
                                $quizResult = $stmt->get_result();
                                $quizzes = [];
                                while ($row = $quizResult->fetch_assoc()) {
                                    $quizzes[] = $row;
                                }
                                $stmt->close();
                            }
                            ?>
                            
                            <?php if (count($quizzes) > 0): ?>
                              <div class="mb-4">
                                <h5 class="font-semibold mb-2">Quizzes:</h5>
                                <ul class="space-y-2">
                                  <?php foreach ($quizzes as $quiz): ?>
                                    <li>
                                      <a href="quiz.php?quiz_id=<?= $quiz['id'] ?>" class="text-blue-600 hover:underline flex items-center">
                                        <span class="mr-2">🧠</span>
                                        <?= htmlspecialchars($quiz['quiz_title']) ?>
                                      </a>
                                    </li>
                                  <?php endforeach; ?>
                                </ul>
                              </div>
                            <?php endif; ?>
                            
                            <div class="mt-4">
                              <a href="welcome.php?course_id=<?= $course_id ?>&chapter_id=<?= $chapter['id'] ?>" class="inline-flex items-center bg-purple-600 hover:bg-purple-700 text-white px-4 py-2 rounded transition duration-300">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4" />
                                </svg>
                                Enter Coding Environment
                              </a>
                            </div>
                          </div>
                        <?php endforeach; ?>
                      </div>
                    <?php else: ?>
                      <p class="text-gray-500">No chapters available for this module.</p>
                    <?php endif; ?>
                  <?php endif; ?>
                  
                  <?php
                  $moduleThreshold = ceil(($module['sort_order'] / $totalModules) * 100);
                  ?>
                  <div class="mt-6 pt-4 border-t border-gray-200">
                    <?php if ($currentProgress >= $moduleThreshold): ?>
                      <span class="inline-flex items-center bg-green-100 text-green-800 px-3 py-1 rounded-full text-sm font-medium">
                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                        </svg>
                        Module Completed
                      </span>
                    <?php else: ?>
                      <a href="complete_module.php?course_id=<?= $course_id ?>&module_id=<?= $module['id'] ?>" class="inline-flex items-center bg-yellow-500 hover:bg-yellow-600 text-white px-4 py-2 rounded transition duration-300">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                        </svg>
                        Mark Module Completed
                      </a>
                    <?php endif; ?>
                  </div>
                </div>
              </div>
            <?php endforeach; ?>
          </div>
        <?php else: ?>
          <div class="bg-white p-6 rounded-lg shadow border border-gray-200 text-center">
            <p class="text-gray-500">No modules available for this course.</p>
          </div>
        <?php endif; ?>
      </div>
      
      <!-- Resources Tab -->
      <div id="resources" class="tab-content">
        <div class="bg-white p-6 rounded-lg shadow border border-gray-200">
          <h3 class="text-2xl font-semibold text-gray-800 mb-4">Course Resources</h3>
          
          <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div class="bg-blue-50 p-4 rounded-lg border border-blue-200">
              <h4 class="font-semibold text-blue-800 mb-2">📚 Recommended Books</h4>
              <ul class="space-y-2">
                <?php if ($course_id == 1): // Java Course ?>
                  <li>
                    <a href="https://www.amazon.com/Effective-Java-Joshua-Bloch/dp/0134685997" target="_blank" class="text-blue-600 hover:underline">
                      Effective Java by Joshua Bloch
                    </a>
                  </li>
                  <li>
                    <a href="https://www.amazon.com/Java-Complete-Reference-Eleventh/dp/1260463419" target="_blank" class="text-blue-600 hover:underline">
                      Java: The Complete Reference by Herbert Schildt
                    </a>
                  </li>
                  <li>
                    <a href="https://www.amazon.com/Head-First-Java-Kathy-Sierra/dp/0596009208" target="_blank" class="text-blue-600 hover:underline">
                      Head First Java by Kathy Sierra
                    </a>
                  </li>
                <?php elseif ($course_id == 2): // Web Development Course ?>
                  <li>
                    <a href="https://www.amazon.com/HTML-CSS-Design-Build-Websites/dp/1118008189" target="_blank" class="text-blue-600 hover:underline">
                      HTML and CSS: Design and Build Websites by Jon Duckett
                    </a>
                  </li>
                  <li>
                    <a href="https://www.amazon.com/JavaScript-JQuery-Interactive-Front-End-Development/dp/1118531647" target="_blank" class="text-blue-600 hover:underline">
                      JavaScript and jQuery: Interactive Front-End Web Development by Jon Duckett
                    </a>
                  </li>
                  <li>
                    <a href="https://www.amazon.com/You-Dont-Know-Js-Book/dp/1491924462" target="_blank" class="text-blue-600 hover:underline">
                      You Don't Know JS (Book Series) by Kyle Simpson
                    </a>
                  </li>
                <?php else: ?>
                  <li>
                    <a href="https://www.amazon.com/C-Programming-Language-4th/dp/0321563840" target="_blank" class="text-blue-600 hover:underline">
                      The C++ Programming Language (4th Edition) by Bjarne Stroustrup
                    </a>
                  </li>
                  <li>
                    <a href="https://www.amazon.com/Effective-Modern-Specific-Ways-Improve/dp/1491903996" target="_blank" class="text-blue-600 hover:underline">
                      Effective Modern C++ by Scott Meyers
                    </a>
                  </li>
                  <li>
                    <a href="https://www.amazon.com/Primer-5th-Stanley-B-Lippman/dp/0321714113" target="_blank" class="text-blue-600 hover:underline">
                      C++ Primer (5th Edition) by Stanley B. Lippman, Josée Lajoie, and Barbara E. Moo
                    </a>
                  </li>
                <?php endif; ?>
              </ul>
            </div>
            
            <div class="bg-green-50 p-4 rounded-lg border border-green-200">
              <h4 class="font-semibold text-green-800 mb-2">🔗 Useful Links</h4>
              <ul class="space-y-2">
                <?php if ($course_id == 1): // Java Course ?>
                  <li><a href="https://docs.oracle.com/javase/tutorial/" class="text-blue-600 hover:underline">Official Java Tutorials</a></li>
                  <li><a href="https://www.javatpoint.com/" class="text-blue-600 hover:underline">JavaTpoint</a></li>
                  <li><a href="https://spring.io/guides" class="text-blue-600 hover:underline">Spring Guides</a></li>
                <?php elseif ($course_id == 2): // Web Development Course ?>
                  <li><a href="https://developer.mozilla.org" class="text-blue-600 hover:underline">MDN Web Docs</a></li>
                  <li><a href="https://www.w3schools.com/" class="text-blue-600 hover:underline">W3Schools</a></li>
                  <li><a href="https://css-tricks.com/" class="text-blue-600 hover:underline">CSS-Tricks</a></li>
                <?php else: ?>
                  <li><a href="https://developer.mozilla.org" class="text-blue-600 hover:underline">MDN Web Docs</a></li>
                  <li><a href="https://stackoverflow.com" class="text-blue-600 hover:underline">Stack Overflow</a></li>
                  <li><a href="https://github.com" class="text-blue-600 hover:underline">GitHub</a></li>
                <?php endif; ?>
              </ul>
            </div>
          </div>
        </div>
      </div>
      
      <!-- Project Submission Tab -->
      <?php if ($has_capstone): ?>
      <div id="project-submission" class="tab-content">
        <div class="bg-white p-6 rounded-lg shadow border border-gray-200">
          <h3 class="text-2xl font-semibold text-gray-800 mb-4">Project Submission</h3>
          
          <div class="space-y-6">
            <?php if ($capstone_submitted): ?>
              <?php if ($capstone_submitted['status'] == 'approved'): ?>
                <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 rounded">
                  <h4 class="font-bold mb-2">✅ Project Approved</h4>
                  <p>Congratulations! Your capstone project has been approved by your instructor.</p>
                  <p class="mt-2">You have successfully completed this course!</p>
                </div>
              <?php elseif ($capstone_submitted['status'] == 'resubmit'): ?>
                <div class="bg-yellow-100 border-l-4 border-yellow-500 text-yellow-700 p-4 rounded">
                  <h4 class="font-bold mb-2">⚠️ Resubmission Required</h4>
                  <p>Your instructor has requested that you resubmit your project with improvements.</p>
                  <p class="mt-2">Please review the feedback and submit an updated version.</p>
                </div>
                
                <form action="submit_capstone.php" method="post" enctype="multipart/form-data" class="mt-4">
                  <input type="hidden" name="course_id" value="<?= $course_id ?>">
                  <div class="mb-4">
                    <label class="block text-gray-700 mb-2">Project Title</label>
                    <input type="text" name="project_title" required 
                           class="w-full px-3 py-2 border rounded" 
                           value="<?= htmlspecialchars($capstone_submitted['project_title']) ?>">
                  </div>
                  <div class="mb-4">
                    <label class="block text-gray-700 mb-2">Project Description</label>
                    <textarea name="project_description" rows="4" required
                              class="w-full px-3 py-2 border rounded"><?= htmlspecialchars($capstone_submitted['project_description']) ?></textarea>
                  </div>
                  <div class="mb-4">
                    <label class="block text-gray-700 mb-2">Updated Project Files (ZIP)</label>
                    <input type="file" name="project_files" required class="w-full px-3 py-2 border rounded">
                  </div>
                  <button type="submit" class="bg-purple-600 hover:bg-purple-700 text-white px-4 py-2 rounded transition duration-300">
                    Resubmit Project
                  </button>
                </form>
              <?php else: ?>
                <div class="bg-blue-100 border-l-4 border-blue-500 text-blue-700 p-4 rounded">
                  <h4 class="font-bold mb-2">⏳ Project Under Review</h4>
                  <p>Your capstone project has been submitted and is currently under review by your instructor.</p>
                  <p class="mt-2">You will be notified when the review is complete.</p>
                </div>
              <?php endif; ?>
            <?php else: ?>
              <?php 
              // Check if student has completed all modules (except capstone)
              $completed_modules = floor($currentProgress / (100 / max(1, $totalModules)));
              if ($completed_modules >= ($totalModules - 1)): ?>
                <div class="bg-white p-4 rounded-lg border border-gray-200">
                  <h4 class="text-xl font-semibold text-purple-700 mb-4">Submit Your Capstone Project</h4>
                  <p class="mb-4">This is your final project to demonstrate what you've learned in this course. Please submit all required files and documentation.</p>
                  
                  <form action="submit_capstone.php" method="post" enctype="multipart/form-data">
                    <input type="hidden" name="course_id" value="<?= $course_id ?>">
                    <div class="mb-4">
                      <label class="block text-gray-700 mb-2">Project Title</label>
                      <input type="text" name="project_title" required class="w-full px-3 py-2 border rounded">
                    </div>
                    <div class="mb-4">
                      <label class="block text-gray-700 mb-2">Project Description</label>
                      <textarea name="project_description" rows="4" required
                                class="w-full px-3 py-2 border rounded"></textarea>
                    </div>
                    <div class="mb-4">
                      <label class="block text-gray-700 mb-2">Project Files (ZIP)</label>
                      <input type="file" name="project_files" required class="w-full px-3 py-2 border rounded">
                      <p class="text-sm text-gray-500 mt-1">Please compress all your project files into a single ZIP file</p>
                    </div>
                    <button type="submit" class="bg-purple-600 hover:bg-purple-700 text-white px-4 py-2 rounded transition duration-300">
                      Submit Project
                    </button>
                  </form>
                </div>
              <?php else: ?>
                <div class="bg-gray-100 border-l-4 border-gray-500 text-gray-700 p-4 rounded">
                  <h4 class="font-bold mb-2">🔒 Project Submission Locked</h4>
                  <p>You must complete all modules and quizzes before you can submit your capstone project.</p>
                  <p class="mt-2">Current progress: <?= $completed_modules ?>/<?= $totalModules-1 ?> modules completed</p>
                </div>
              <?php endif; ?>
            <?php endif; ?>
          </div>
        </div>
      </div>
      <?php endif; ?>
    </div>
  </div>

  <script>
    // Tab switching functionality
    const tabLinks = document.querySelectorAll('.tab-link');
    const tabContents = document.querySelectorAll('.tab-content');

    tabLinks.forEach(link => {
      link.addEventListener('click', function(e) {
        e.preventDefault();
        
        // Remove active classes
        tabLinks.forEach(l => l.classList.remove('border-b-2', 'border-blue-600'));
        tabContents.forEach(c => c.classList.remove('active', 'fade-in'));
        
        // Add active classes to clicked tab
        const tabId = this.getAttribute('data-tab');
        const activeTab = document.getElementById(tabId);
        this.classList.add('border-b-2', 'border-blue-600');
        activeTab.classList.add('active', 'fade-in');
      });
    });

    // Accordion functionality
    function toggleAccordion(headerElem) {
      const content = headerElem.nextElementSibling;
      const icon = headerElem.querySelector('svg');
      content.classList.toggle('active');
      icon.classList.toggle('rotate-180');
    }

    // Activate first tab by default
    document.addEventListener('DOMContentLoaded', () => {
      const hash = window.location.hash;
      if (hash) {
        const targetTab = document.querySelector(`.tab-link[data-tab="${hash.substring(1)}"]`);
        if (targetTab) {
          targetTab.click();
          return;
        }
      }
      document.querySelector('.tab-link').click();
    });
  </script>
</body>
</html>
