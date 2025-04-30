<?php
include('db.php');
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}
$user_id = $_SESSION['user_id'];

// Get enrolled courses with progress
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

// Get available courses
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

// Get recent messages from teachers
$recent_messages = [];
if ($stmt = $conn->prepare("
    SELECT m.message, u.email AS teacher_name, c.course_name, m.created_at
    FROM messages m
    JOIN users u ON m.sender_id = u.id
    LEFT JOIN courses c ON m.course_id = c.id
    WHERE m.receiver_id = ? AND m.is_teacher_message = TRUE
    ORDER BY m.created_at DESC
    LIMIT 5
")) {
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $recent_messages = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    $stmt->close();
}

// Calculate stats
$totalCourses = count($progress_data);
$totalProgress = array_sum(array_column($progress_data,'completed'));
$averageProgress = $totalCourses ? round($totalProgress/$totalCourses,2) : 0;

// Badges calculation
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
  .progress-bar {
    transition: width 0.5s ease-in-out;
  }
  .badge {
    display: inline-flex;
    align-items: center;
    padding: 0.25rem 0.5rem;
    border-radius: 9999px;
    font-size: 0.75rem;
    font-weight: 600;
  }
  .badge-pro {
    background-color: #10B981;
    color: white;
  }
  .badge-intermediate {
    background-color: #3B82F6;
    color: white;
  }
  .badge-beginner {
    background-color: #F59E0B;
    color: white;
  }
</style>
</head>
<body class="bg-gray-100">
  <header class="bg-indigo-600 text-white p-4 flex justify-between items-center">
    <h1 class="text-xl font-bold">Code Academy</h1>
    <div class="flex items-center space-x-4">
      <span class="text-sm">Welcome, <?= $_SESSION['email'] ?? 'Student' ?></span>
      <a href="logout.php" class="hover:text-gray-300 text-sm">Logout</a>
    </div>
  </header>

  <main class="max-w-6xl mx-auto mt-8 p-6 bg-white rounded-lg shadow-md">
    <h2 class="text-3xl font-bold mb-6 text-center text-indigo-700">Student Dashboard</h2>

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-8">
      <div class="bg-indigo-50 p-4 rounded-lg text-center border border-indigo-100">
        <div class="text-sm text-indigo-600 font-medium">Enrolled Courses</div>
        <div class="text-2xl font-bold text-indigo-800"><?= $totalCourses ?></div>
      </div>
      <div class="bg-green-50 p-4 rounded-lg text-center border border-green-100">
        <div class="text-sm text-green-600 font-medium">Avg Progress</div>
        <div class="text-2xl font-bold text-green-800"><?= $averageProgress ?>%</div>
      </div>
      <div class="bg-purple-50 p-4 rounded-lg text-center border border-purple-100">
        <div class="text-sm text-purple-600 font-medium">Badges Earned</div>
        <div class="text-2xl font-bold text-purple-800"><?= array_sum($badgeCounts) ?></div>
      </div>
      <div class="bg-blue-50 p-4 rounded-lg text-center border border-blue-100">
        <div class="text-sm text-blue-600 font-medium">Messages</div>
        <div class="text-2xl font-bold text-blue-800"><?= count($recent_messages) ?></div>
      </div>
    </div>

    <!-- Tabs Navigation -->
    <nav class="mb-6 border-b border-gray-200">
      <ul class="flex flex-wrap space-x-6 justify-center">
        <li><a href="#" data-tab="my-courses" class="tab-link inline-block py-2 px-1 border-b-2 border-indigo-500 font-medium text-sm text-indigo-600">My Courses</a></li>
        <li><a href="#" data-tab="new-courses" class="tab-link inline-block py-2 px-1 border-b-2 border-transparent font-medium text-sm text-gray-500 hover:text-gray-700 hover:border-gray-300">Available Courses</a></li>
        <li><a href="#" data-tab="discussion" class="tab-link inline-block py-2 px-1 border-b-2 border-transparent font-medium text-sm text-gray-500 hover:text-gray-700 hover:border-gray-300">Class Discussion</a></li>
        <li><a href="#" data-tab="progress" class="tab-link inline-block py-2 px-1 border-b-2 border-transparent font-medium text-sm text-gray-500 hover:text-gray-700 hover:border-gray-300">My Progress</a></li>
      </ul>
    </nav>

    <!-- My Courses Tab -->
    <section id="my-courses" class="tab-content active">
      <h3 class="text-xl font-semibold mb-4 text-gray-800">My Enrolled Courses</h3>
      <?php if($progress_data): ?>
        <div class="grid gap-6 md:grid-cols-2 lg:grid-cols-3">
          <?php foreach($progress_data as $course): ?>
            <div class="bg-white border border-gray-200 rounded-lg shadow-sm overflow-hidden hover:shadow-md transition-shadow">
              <div class="p-5">
                <h4 class="font-bold text-lg mb-2 text-gray-800"><?= htmlspecialchars($course['course_name']) ?></h4>
                <div class="mb-4">
                  <div class="flex justify-between text-sm mb-1">
                    <span class="text-gray-600">Progress</span>
                    <span class="font-medium"><?= $course['completed'] ?>%</span>
                  </div>
                  <div class="w-full bg-gray-200 h-2 rounded-full">
                    <div class="bg-indigo-600 h-full rounded-full progress-bar" style="width:<?= $course['completed']?>%"></div>
                  </div>
                </div>
                <div class="flex justify-between space-x-2">
                  <a href="course.php?course_id=<?= $course['course_id'] ?>" class="flex-1 bg-indigo-600 hover:bg-indigo-700 text-white text-center py-2 px-4 rounded transition-colors">
                    Continue
                  </a>
                  <a href="drop_course.php?course_id=<?= $course['course_id'] ?>" onclick="return confirm('Are you sure you want to drop this course?')" class="bg-red-100 hover:bg-red-200 text-red-700 py-2 px-4 rounded transition-colors">
                    Drop
                  </a>
                </div>
              </div>
            </div>
          <?php endforeach; ?>
        </div>
      <?php else: ?>
        <div class="text-center py-8 bg-gray-50 rounded-lg">
          <p class="text-gray-500">You haven't enrolled in any courses yet.</p>
          <a href="#new-courses" class="text-indigo-600 hover:underline mt-2 inline-block">Browse available courses</a>
        </div>
      <?php endif; ?>
    </section>

    <!-- Available Courses Tab -->
    <section id="new-courses" class="tab-content">
      <h3 class="text-xl font-semibold mb-4 text-gray-800">Available Courses</h3>
      <?php if($available_courses): ?>
        <div class="grid gap-6 md:grid-cols-2 lg:grid-cols-3">
          <?php foreach($available_courses as $course): ?>
            <div class="bg-white border border-gray-200 rounded-lg shadow-sm overflow-hidden hover:shadow-md transition-shadow">
              <div class="p-5">
                <h4 class="font-bold text-lg mb-2 text-gray-800"><?= htmlspecialchars($course['course_name']) ?></h4>
                <p class="text-gray-600 mb-4 text-sm"><?= htmlspecialchars($course['description']?:'No description available') ?></p>
                <a href="enroll_course.php?course_id=<?= $course['id'] ?>" class="block w-full bg-green-600 hover:bg-green-700 text-white text-center py-2 px-4 rounded transition-colors">
                  Enroll Now
                </a>
              </div>
            </div>
          <?php endforeach; ?>
        </div>
      <?php else: ?>
        <div class="text-center py-8 bg-gray-50 rounded-lg">
          <p class="text-gray-500">You're enrolled in all available courses.</p>
        </div>
      <?php endif; ?>
    </section>

    <!-- Class Discussion Tab -->
    <section id="discussion" class="tab-content">
      <div class="flex justify-between items-center mb-6">
        <h3 class="text-xl font-semibold text-gray-800">Class Discussion</h3>
        <a href="peer_review.php" class="bg-indigo-600 hover:bg-indigo-700 text-white py-2 px-4 rounded-lg transition-colors">
          Go to Discussion Forum
        </a>
      </div>
      
      <div class="bg-gray-50 p-6 rounded-lg border border-gray-200">
        <div class="text-center">
          <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
          </svg>
          <h4 class="mt-2 text-lg font-medium text-gray-900">Course Discussions</h4>
          <p class="mt-1 text-sm text-gray-500">Participate in discussions with your classmates and instructors.</p>
          <div class="mt-6">
            <a href="peer_review.php" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
              Go to Forum
            </a>
          </div>
        </div>
      </div>
    </section>

    <!-- Progress Tab -->
    <section id="progress" class="tab-content">
      <h3 class="text-xl font-semibold mb-6 text-gray-800">My Learning Progress</h3>
      
      <div class="grid gap-6 md:grid-cols-2">
        <!-- Progress Overview -->
        <div class="bg-white p-6 rounded-lg shadow-sm border border-gray-200">
          <h4 class="font-medium text-lg mb-4 text-gray-800">Overall Progress</h4>
          <div class="space-y-4">
            <div>
              <div class="flex justify-between text-sm mb-1">
                <span class="text-gray-600">Average Completion</span>
                <span class="font-medium"><?= $averageProgress ?>%</span>
              </div>
              <div class="w-full bg-gray-200 h-3 rounded-full">
                <div class="bg-indigo-600 h-full rounded-full progress-bar" style="width:<?= $averageProgress ?>%"></div>
              </div>
            </div>
            <div>
              <div class="flex justify-between text-sm mb-1">
                <span class="text-gray-600">Courses Enrolled</span>
                <span class="font-medium"><?= $totalCourses ?></span>
              </div>
            </div>
          </div>
        </div>
        
        <!-- Badges Earned -->
        <div class="bg-white p-6 rounded-lg shadow-sm border border-gray-200">
          <h4 class="font-medium text-lg mb-4 text-gray-800">Badges Earned</h4>
          <div class="grid grid-cols-3 gap-4 text-center">
            <div>
              <div class="badge badge-pro mx-auto">
                Pro
              </div>
              <div class="mt-2 text-2xl font-bold"><?= $badgeCounts['pro'] ?></div>
              <div class="text-xs text-gray-500">80%+ Courses</div>
            </div>
            <div>
              <div class="badge badge-intermediate mx-auto">
                Intermediate
              </div>
              <div class="mt-2 text-2xl font-bold"><?= $badgeCounts['intermediate'] ?></div>
              <div class="text-xs text-gray-500">60%+ Courses</div>
            </div>
            <div>
              <div class="badge badge-beginner mx-auto">
                Beginner
              </div>
              <div class="mt-2 text-2xl font-bold"><?= $badgeCounts['beginner'] ?></div>
              <div class="text-xs text-gray-500">20%+ Courses</div>
            </div>
          </div>
        </div>
      </div>
      
      <!-- Course-wise Progress -->
      <div class="mt-6 bg-white p-6 rounded-lg shadow-sm border border-gray-200">
        <h4 class="font-medium text-lg mb-4 text-gray-800">Course-wise Progress</h4>
        <div class="overflow-x-auto">
          <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
              <tr>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Course</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Progress</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
              </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
              <?php if($progress_data): foreach($progress_data as $course): ?>
              <tr>
                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900"><?= htmlspecialchars($course['course_name']) ?></td>
                <td class="px-6 py-4 whitespace-nowrap">
                  <div class="flex items-center">
                    <div class="w-full bg-gray-200 h-2 rounded-full mr-2">
                      <div class="bg-indigo-600 h-full rounded-full progress-bar" style="width:<?= $course['completed']?>%"></div>
                    </div>
                    <span class="text-sm text-gray-600"><?= $course['completed'] ?>%</span>
                  </div>
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                  <?php if($course['completed'] >= 80): ?>
                    <span class="badge badge-pro">Pro Level</span>
                  <?php elseif($course['completed'] >= 60): ?>
                    <span class="badge badge-intermediate">Intermediate</span>
                  <?php elseif($course['completed'] >= 20): ?>
                    <span class="badge badge-beginner">Beginner</span>
                  <?php else: ?>
                    <span class="text-sm text-gray-500">Just Started</span>
                  <?php endif; ?>
                </td>
              </tr>
              <?php endforeach; else: ?>
              <tr>
                <td colspan="3" class="px-6 py-4 text-center text-sm text-gray-500">No progress data available</td>
              </tr>
              <?php endif; ?>
            </tbody>
          </table>
        </div>
      </div>
    </section>
  </main>

  <script>
    // Tab switching functionality
    document.addEventListener('DOMContentLoaded', function() {
      const tabs = document.querySelectorAll('.tab-link');
      const panes = document.querySelectorAll('.tab-content');
      
      tabs.forEach(tab => {
        tab.addEventListener('click', e => {
          e.preventDefault();
          
          // Remove active classes from all tabs and panes
          tabs.forEach(t => {
            t.classList.remove('border-indigo-500', 'text-indigo-600');
            t.classList.add('border-transparent', 'text-gray-500');
          });
          panes.forEach(p => p.classList.remove('active'));
          
          // Add active classes to clicked tab and corresponding pane
          tab.classList.remove('border-transparent', 'text-gray-500');
          tab.classList.add('border-indigo-500', 'text-indigo-600');
          document.getElementById(tab.dataset.tab).classList.add('active');
        });
      });
      
      // Animate progress bars on tab switch
      function animateProgressBars() {
        const progressBars = document.querySelectorAll('.progress-bar');
        progressBars.forEach(bar => {
          const width = bar.style.width;
          bar.style.width = '0';
          setTimeout(() => {
            bar.style.width = width;
          }, 100);
        });
      }
      
      // Initial animation
      animateProgressBars();
      
      // Re-animate when switching to progress tab
      const progressTab = document.querySelector('[data-tab="progress"]');
      if (progressTab) {
        progressTab.addEventListener('click', animateProgressBars);
      }
    });
  </script>
</body>
</html>
