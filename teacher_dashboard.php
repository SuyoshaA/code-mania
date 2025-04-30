<?php
// Enable error reporting
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Start session and check authentication
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'teacher') {
    header("Location: login.php");
    exit();
}

// Database configuration
define('DB_HOST', '127.0.0.1');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_NAME', 'tutoring_db');

// Create database connection
try {
    $conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
    if ($conn->connect_error) {
        throw new Exception("Connection failed: " . $conn->connect_error);
    }
} catch (Exception $e) {
    die("Database connection error: " . $e->getMessage());
}

// Initialize variables
$teacherId = $_SESSION['user_id'];
$error = '';
$success = '';
$courses = [];
$teacherName = '';
$activeTab = isset($_GET['tab']) ? $_GET['tab'] : 'courses';

// Get teacher name with improved error handling
try {
    $query = "SELECT email FROM users WHERE id = ?";
    $stmt = $conn->prepare($query);
    
    if (!$stmt) {
        throw new Exception("Prepare failed: " . $conn->error);
    }
    
    $stmt->bind_param("i", $teacherId);
    if (!$stmt->execute()) {
        throw new Exception("Execute failed: " . $stmt->error);
    }
    
    $result = $stmt->get_result();
    if ($result->num_rows === 0) {
        throw new Exception("Teacher not found");
    }
    
    $teacher = $result->fetch_assoc();
    $teacherName = $teacher['email'];
    $stmt->close();
} catch (Exception $e) {
    $error = "Error fetching teacher information: " . $e->getMessage();
}

// Get courses and students data
try {
    // Query 1: Get all courses taught by this teacher with module count
    $coursesQuery = "SELECT c.id, c.course_name, c.description, 
                    COUNT(m.id) as module_count
                    FROM courses c
                    JOIN course_teachers ct ON c.id = ct.course_id
                    LEFT JOIN modules m ON c.id = m.course_id
                    WHERE ct.teacher_id = ?
                    GROUP BY c.id";
    $stmt = $conn->prepare($coursesQuery);
    
    if (!$stmt) {
        throw new Exception("Prepare failed: " . $conn->error);
    }
    
    $stmt->bind_param("i", $teacherId);
    if (!$stmt->execute()) {
        throw new Exception("Execute failed: " . $stmt->error);
    }
    
    $courses = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    $stmt->close();
    
    // Query 2: Get students for each course with progress details
    foreach ($courses as &$course) {
        $studentsQuery = "SELECT u.id, u.email,
                         u.email as display_name,
                         sp.completed, sp.last_accessed,
                         (SELECT COUNT(*) FROM certificates WHERE student_id = u.id AND course_id = ?) as has_certificate
                         FROM users u
                         JOIN student_progress sp ON u.id = sp.student_id
                         WHERE sp.course_id = ? AND u.role = 'student'
                         ORDER BY sp.last_accessed DESC";
        $stmt = $conn->prepare($studentsQuery);
        
        if (!$stmt) {
            $course['students'] = [];
            $course['avg_progress'] = 0;
            continue;
        }
        
        $stmt->bind_param("ii", $course['id'], $course['id']);
        if (!$stmt->execute()) {
            $course['students'] = [];
            $course['avg_progress'] = 0;
            $stmt->close();
            continue;
        }
        
        $course['students'] = $stmt->get_result()->fetch_all(MYSQLI_ASSOC) ?: [];
        $stmt->close();
        
        // Calculate average progress for the course
        $totalStudents = count($course['students']);
        $totalProgress = 0;
        foreach ($course['students'] as $student) {
            $totalProgress += $student['completed'];
        }
        $course['avg_progress'] = $totalStudents > 0 ? round($totalProgress / $totalStudents) : 0;
    }
    
} catch (Exception $e) {
    $error = "Error fetching data: " . $e->getMessage();
}

// Handle peer review forum course selection
$selected_course_id = $_GET['course_id'] ?? ($courses[0]['id'] ?? null);
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
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Teacher Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <style>
        :root {
            --primary-color: #4e73df;
            --secondary-color: #1cc88a;
            --accent-color: #f6c23e;
            --dark-color: #5a5c69;
        }
        
        body {
            background-color: #f8f9fc;
            font-family: 'Nunito', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif;
        }
        
        .sidebar {
            background: linear-gradient(180deg, var(--primary-color) 10%, #224abe 100%);
            min-height: 100vh;
            transition: all 0.3s;
        }
        
        .sidebar .nav-link {
            color: rgba(255, 255, 255, 0.8);
            padding: 1rem;
            font-weight: 600;
            border-left: 0.25rem solid transparent;
        }
        
        .sidebar .nav-link.active {
            color: white;
            background: rgba(255, 255, 255, 0.1);
            border-left-color: white;
        }
        
        .sidebar .nav-link:hover {
            color: white;
            background: rgba(255, 255, 255, 0.1);
        }
        
        .sidebar .nav-link i {
            margin-right: 0.5rem;
        }
        
        .navbar {
            box-shadow: 0 0.15rem 1.75rem 0 rgba(58, 59, 69, 0.15);
        }
        
        .card {
            border: none;
            border-radius: 0.35rem;
            box-shadow: 0 0.15rem 1.75rem 0 rgba(58, 59, 69, 0.1);
            margin-bottom: 1.5rem;
        }
        
        .card-header {
            background-color: #f8f9fc;
            border-bottom: 1px solid #e3e6f0;
            padding: 1rem 1.35rem;
        }
        
        .course-card {
            transition: transform 0.2s;
            margin-bottom: 20px;
            border-left: 4px solid var(--primary-color);
        }
        
        .course-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 0.5rem 1.5rem 0.25rem rgba(0, 0, 0, 0.075);
        }
        
        .progress-thin {
            height: 8px;
        }
        
        .student-card {
            border-left: 3px solid var(--secondary-color);
            margin-bottom: 10px;
        }
        
        .cert-badge {
            background-color: var(--accent-color);
            color: #000;
        }
        
        .last-access {
            font-size: 0.85rem;
            color: #858796;
        }
        
        .avatar {
            width: 40px;
            height: 40px;
            background-color: #f8f9fa;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-right: 10px;
            font-weight: bold;
            color: #495057;
        }
        
        .tab-content {
            display: none;
        }
        
        .tab-content.active {
            display: block;
        }
        
        .nav-tabs .nav-link {
            color: var(--dark-color);
            border: none;
            padding: 0.75rem 1.5rem;
        }
        
        .nav-tabs .nav-link.active {
            color: var(--primary-color);
            border-bottom: 2px solid var(--primary-color);
            font-weight: 600;
        }
        
        .badge-primary {
            background-color: var(--primary-color);
        }
        
        .badge-success {
            background-color: var(--secondary-color);
        }
        
        .badge-warning {
            background-color: var(--accent-color);
        }
    </style>
</head>
<body>
    <div class="d-flex">
        <!-- Sidebar -->
        <div class="sidebar d-none d-md-block" style="width: 14rem;">
            <div class="text-center py-4">
                <h4 class="text-white">CodeMania</h4>
            </div>
            <hr class="bg-white mx-3">
            <ul class="nav flex-column">
                <li class="nav-item">
                    <a href="?tab=courses" class="nav-link <?php echo $activeTab === 'courses' ? 'active' : ''; ?>">
                        <i class="bi bi-book"></i> My Courses
                    </a>
                </li>
                <li class="nav-item">
                    <a href="?tab=forum" class="nav-link <?php echo $activeTab === 'forum' ? 'active' : ''; ?>">
                        <i class="bi bi-people"></i> Discussion Forum
                    </a>
                </li>
            </ul>
        </div>

        <!-- Main Content -->
        <div class="flex-grow-1">
            <!-- Top Navigation -->
            <nav class="navbar navbar-expand navbar-light bg-white shadow">
                <div class="container-fluid">
                    <button class="btn btn-link d-md-none" type="button" id="sidebarToggle">
                        <i class="bi bi-list"></i>
                    </button>
                    
                    <ul class="navbar-nav ms-auto">
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown">
                                <i class="bi bi-person-circle me-1"></i>
                                <span class="d-none d-lg-inline"><?php echo htmlspecialchars($teacherName); ?></span>
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end">
                                <li><a class="dropdown-item" href="profile.php"><i class="bi bi-person me-2"></i> Profile</a></li>
                                <li><hr class="dropdown-divider"></li>
                                <li><a class="dropdown-item" href="logout.php"><i class="bi bi-box-arrow-right me-2"></i> Logout</a></li>
                            </ul>
                        </li>
                    </ul>
                </div>
            </nav>

            <!-- Main Content Area -->
            <div class="container-fluid py-4">
                <?php if ($error): ?>
                    <div class="alert alert-danger alert-dismissible fade show">
                        <?php echo $error; ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                <?php endif; ?>
                
                <?php if ($success): ?>
                    <div class="alert alert-success alert-dismissible fade show">
                        <?php echo $success; ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                <?php endif; ?>
                
                <!-- Mobile Tabs -->
                <ul class="nav nav-tabs d-md-none mb-4">
                    <li class="nav-item">
                        <a class="nav-link <?php echo $activeTab === 'courses' ? 'active' : ''; ?>" href="?tab=courses">Courses</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?php echo $activeTab === 'forum' ? 'active' : ''; ?>" href="?tab=forum">Forum</a>
                    </li>
                </ul>
                
                <!-- Courses Tab -->
                <div id="courses-tab" class="tab-content <?php echo $activeTab === 'courses' ? 'active' : ''; ?>">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <h2 class="h4 text-gray-800">Your Courses</h2>
                        <span class="badge bg-primary">
                            <?php echo count($courses); ?> course<?php echo count($courses) !== 1 ? 's' : ''; ?>
                        </span>
                    </div>
                    
                    <?php if (empty($courses)): ?>
                        <div class="card">
                            <div class="card-body text-center py-5">
                                <i class="bi bi-book" style="font-size: 3rem; color: #6c757d;"></i>
                                <h5 class="mt-3">No courses assigned</h5>
                                <p class="text-muted">You are not currently assigned to any courses.</p>
                            </div>
                        </div>
                    <?php else: ?>
                        <div class="row">
                            <?php foreach ($courses as $course): ?>
                                <div class="col-md-6">
                                    <div class="card course-card h-100">
                                        <div class="card-body">
                                            <div class="d-flex justify-content-between align-items-start">
                                                <div>
                                                    <h4 class="card-title"><?php echo htmlspecialchars($course['course_name']); ?></h4>
                                                    <p class="card-text text-muted"><?php echo htmlspecialchars($course['description']); ?></p>
                                                </div>
                                                <span class="badge bg-info"><?php echo $course['module_count']; ?> modules</span>
                                            </div>
                                            
                                            <div class="mt-3">
                                                <div class="d-flex justify-content-between mb-1">
                                                    <small>Average progress</small>
                                                    <small><?php echo $course['avg_progress'] ?? 0; ?>%</small>
                                                </div>
                                                <div class="progress progress-thin">
                                                    <div class="progress-bar bg-success" role="progressbar" 
                                                         style="width: <?php echo $course['avg_progress'] ?? 0; ?>%" 
                                                         aria-valuenow="<?php echo $course['avg_progress'] ?? 0; ?>" 
                                                         aria-valuemin="0" aria-valuemax="100"></div>
                                                </div>
                                            </div>
                                            
                                            <hr>
                                            
                                            <h5 class="mt-3">
                                                <i class="bi bi-people-fill"></i> 
                                                Enrolled Students
                                                <span class="badge bg-secondary ms-2"><?php echo count($course['students'] ?? []); ?></span>
                                            </h5>
                                            
                                            <?php if (empty($course['students'] ?? [])): ?>
                                                <div class="alert alert-info mt-3">
                                                    No students enrolled in this course yet.
                                                </div>
                                            <?php else: ?>
                                                <div class="mt-3">
                                                    <?php foreach ($course['students'] as $student): ?>
                                                        <div class="card student-card mb-2">
                                                            <div class="card-body py-2">
                                                                <div class="d-flex align-items-center">
                                                                    <div class="avatar">
                                                                        <?php echo strtoupper(substr($student['display_name'], 0, 1)); ?>
                                                                    </div>
                                                                    <div class="flex-grow-1">
                                                                        <div class="d-flex justify-content-between align-items-center">
                                                                            <div>
                                                                                <h6 class="mb-0"><?php echo htmlspecialchars($student['display_name']); ?></h6>
                                                                                <small class="text-muted"><?php echo htmlspecialchars($student['email']); ?></small>
                                                                            </div>
                                                                            <?php if ($student['has_certificate']): ?>
                                                                                <span class="badge cert-badge">
                                                                                    <i class="bi bi-award"></i> Certified
                                                                                </span>
                                                                            <?php endif; ?>
                                                                        </div>
                                                                        <div class="last-access">
                                                                            Last active: <?php echo date('M j, Y', strtotime($student['last_accessed'])); ?>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                
                                                                <div class="mt-2">
                                                                    <div class="d-flex justify-content-between mb-1">
                                                                        <small>Progress</small>
                                                                        <small><?php echo $student['completed']; ?>%</small>
                                                                    </div>
                                                                    <div class="progress progress-thin">
                                                                        <div class="progress-bar" role="progressbar" 
                                                                             style="width: <?php echo $student['completed']; ?>%" 
                                                                             aria-valuenow="<?php echo $student['completed']; ?>" 
                                                                             aria-valuemin="0" aria-valuemax="100"></div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    <?php endforeach; ?>
                                                </div>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                </div>
                
                <!-- Discussion Forum Tab -->
                <div id="forum-tab" class="tab-content <?php echo $activeTab === 'forum' ? 'active' : ''; ?>">
                    <h3 class="text-xl font-semibold mb-4">Discussion Forum</h3>
                    
                    <!-- Course selector -->
                    <div class="mb-6">
                        <label class="block text-sm font-medium text-gray-700 mb-1">
                            Select Course:
                        </label>
                        <select
                            onchange="location='teacher_dashboard.php?tab=forum&course_id='+this.value"
                            class="w-full border border-gray-300 rounded px-3 py-2"
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

                    <!-- Forum Content -->
                    <div class="bg-white rounded-xl shadow-md p-6">
                        <?php if ($selected_course_id): ?>
                            <h4 class="text-2xl font-bold mb-4"><?= htmlspecialchars($selected_course_name) ?> Discussions</h4>
                            
                            <!-- New Post Form -->
                            <form method="post" enctype="multipart/form-data" class="mb-6">
                                <input type="hidden" name="course_id" value="<?= $selected_course_id ?>"/>
                                <textarea
                                    name="content"
                                    rows="4"
                                    required
                                    class="w-full p-4 border border-gray-300 rounded mb-2"
                                    placeholder="Post a question or discussion topic..."
                                ></textarea>
                                <input
                                    type="file"
                                    name="file"
                                    class="block mb-2 border border-gray-300 rounded px-3 py-2 w-full"
                                />
                                <button
                                    type="submit"
                                    class="bg-indigo-600 hover:bg-indigo-700 text-white px-5 py-2 rounded"
                                >
                                    Post
                                </button>
                            </form>
                            
                            <!-- Discussion Posts -->
                            <div class="space-y-4">
                                <?php
                                // Fetch posts for the selected course
                                $posts = [];
                                if ($stmt = $conn->prepare("
                                    SELECT pp.content, pp.created_at, pp.file_path, u.email
                                    FROM peer_posts pp
                                    JOIN users u ON pp.user_id = u.id
                                    WHERE pp.course_id = ?
                                    ORDER BY pp.created_at DESC
                                ")) {
                                    $stmt->bind_param("i", $selected_course_id);
                                    $stmt->execute();
                                    $posts = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
                                    $stmt->close();
                                }
                                ?>
                                
                                <?php if (!empty($posts)): ?>
                                    <?php foreach ($posts as $post): ?>
                                        <div class="border-b pb-4 mb-4">
                                            <div class="flex justify-between items-center mb-2">
                                                <div class="font-medium"><?= htmlspecialchars($post['email']) ?></div>
                                                <div class="text-sm text-gray-500">
                                                    <?= date('M j, g:i a', strtotime($post['created_at'])) ?>
                                                </div>
                                            </div>
                                            <p class="whitespace-pre-line"><?= nl2br(htmlspecialchars($post['content'])) ?></p>
                                            <?php if ($post['file_path']): ?>
                                                <div class="mt-2">
                                                    <a href="uploads/<?= htmlspecialchars($post['file_path']) ?>" 
                                                       class="text-indigo-600 hover:text-indigo-800 text-sm flex items-center">
                                                        <i class="fas fa-paperclip mr-1"></i>
                                                        Download attached file
                                                    </a>
                                                </div>
                                            <?php endif; ?>
                                        </div>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <div class="text-center py-8 text-gray-500">
                                        No discussion posts yet. Be the first to start a discussion!
                                    </div>
                                <?php endif; ?>
                            </div>
                        <?php else: ?>
                            <div class="text-center py-8 text-gray-500">
                                Select a course to view discussions
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
            
            <footer class="bg-white py-4 mt-auto">
                <div class="container-fluid">
                    <div class="d-flex align-items-center justify-content-between small">
                        <div class="text-muted">Copyright &copy; CodeMania <?php echo date('Y'); ?></div>
                        <div>
                            <a href="#">Privacy Policy</a>
                            &middot;
                            <a href="#">Terms &amp; Conditions</a>
                        </div>
                    </div>
                </div>
            </footer>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Simple animation for progress bars
        document.addEventListener('DOMContentLoaded', function() {
            const progressBars = document.querySelectorAll('.progress-bar');
            progressBars.forEach(bar => {
                const width = bar.style.width;
                bar.style.width = '0';
                setTimeout(() => {
                    bar.style.width = width;
                }, 100);
            });
            
            // Mobile sidebar toggle
            document.getElementById('sidebarToggle').addEventListener('click', function() {
                document.querySelector('.sidebar').classList.toggle('d-none');
            });
        });
    </script>
</body>
</html>

<?php
// Close database connection
$conn->close();
?>
