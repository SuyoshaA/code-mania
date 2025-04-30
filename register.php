<?php 
include('db.php');
session_start();

// Enable error reporting for debugging
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Define teacher codes for approved teachers
$teacherCodes = [
    'teacher6' => 'suyosha@codemania.com',
    'teacher7' => 'david@codemania.com',
    'teacher8' => 'fallon@codemania.com',
    'teacher9' => 'liam@codemania.com'
];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Validate all required fields
    if (empty($_POST['email']) || empty($_POST['password']) || empty($_POST['cpassword'])) {
        $_SESSION['error'] = "All fields are required!";
        header("Location: register.php");
        exit();
    }

    $email = trim($_POST['email']);
    $password = trim($_POST['password']);
    $user_type = $_POST['user_type'];
    
    // Password confirmation check
    if ($password !== $_POST['cpassword']) {
        $_SESSION['error'] = "Passwords do not match!";
        header("Location: register.php");
        exit();
    }
    
    // Validate email format
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $_SESSION['error'] = "Invalid email format!";
        header("Location: register.php");
        exit();
    }
    
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);
    
    // Handle teacher registration
    if ($user_type === 'teacher') {
        if (empty($_POST['teacher_code'])) {
            $_SESSION['error'] = "Teacher code is required!";
            header("Location: register.php?type=teacher");
            exit();
        }
        
        $teacher_code = trim($_POST['teacher_code']);
        
        // Check if teacher code is valid and matches email
        if (!array_key_exists($teacher_code, $teacherCodes) || 
            $teacherCodes[$teacher_code] !== $email) {
            $_SESSION['error'] = "Invalid teacher code or email combination!";
            header("Location: register.php?type=teacher");
            exit();
        }
        
        // Check if teacher already registered
        $checkTeacher = $conn->prepare("SELECT id FROM users WHERE email=? AND role='teacher'");
        $checkTeacher->bind_param("s", $email);
        $checkTeacher->execute();
        $checkTeacher->store_result();
        
        if ($checkTeacher->num_rows > 0) {
            $_SESSION['error'] = "Teacher already registered! Please login.";
            header("Location: login.php");
            exit();
        }
        
        // Insert new teacher record
        $sql = "INSERT INTO users (email, password, role, teacher_code) VALUES (?, ?, 'teacher', ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sss", $email, $hashed_password, $teacher_code);
    } 
    // Handle student registration
    else {
        // Check if student already exists
        $checkStudent = $conn->prepare("SELECT id FROM users WHERE email=?");
        $checkStudent->bind_param("s", $email);
        $checkStudent->execute();
        $checkStudent->store_result();
        
        if ($checkStudent->num_rows > 0) {
            $_SESSION['error'] = "Email already registered!";
            header("Location: register.php");
            exit();
        }
        
        // Insert new student record with explicit 'student' role
        $sql = "INSERT INTO users (email, password, role) VALUES (?, ?, 'student')";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ss", $email, $hashed_password);
    }
    
    // Execute the query
    if ($stmt->execute()) {
        $_SESSION['success'] = "Registration successful! Please login.";
        header("Location: login.php");
        exit();
    } else {
        $_SESSION['error'] = "Registration failed. Please try again.";
        error_log("Database error: " . $conn->error); // Log the error
        header("Location: register.php?type=$user_type");
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Register - CodingMania</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <style>
    .teacher-fields { display: none; }
    .active-tab { background-color: #2563eb; color: white; }
    .inactive-tab { background-color: #e5e7eb; color: #4b5563; }
  </style>
</head>
<body class="bg-gray-50 font-sans relative">
    <div class="absolute inset-0 bg-cover bg-center" style="background-image: url('coding.png');">
        <div class="absolute inset-0 bg-black opacity-50"></div>
    </div>
    
    <div class="relative flex flex-col justify-center min-h-screen p-4">
        <div class="max-w-md w-full mx-auto border border-gray-300 rounded-2xl p-8 bg-white bg-opacity-90 shadow-lg">
            <div class="text-center mb-8">
                <span class="self-center text-2xl font-semibold whitespace-nowrap text-black">CodingMania</span>
                <p class="text-gray-600 mt-2">Join our learning community</p>
            </div>
            
            <!-- Toggle between student/teacher registration -->
            <div class="flex mb-6 rounded-md overflow-hidden">
                <button type="button" id="student-tab" class="flex-1 py-2 px-4 text-sm font-medium rounded-l-md active-tab" 
                        onclick="toggleRegistrationType('student')">
                    Student
                </button>
                <button type="button" id="teacher-tab" class="flex-1 py-2 px-4 text-sm font-medium rounded-r-md inactive-tab" 
                        onclick="toggleRegistrationType('teacher')">
                    Teacher
                </button>
            </div>
            
            <!-- Error/Success Messages -->
            <?php if (isset($_SESSION['error'])): ?>
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                    <?php echo $_SESSION['error']; unset($_SESSION['error']); ?>
                </div>
            <?php endif; ?>
            
            <?php if (isset($_SESSION['success'])): ?>
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                    <?php echo $_SESSION['success']; unset($_SESSION['success']); ?>
                </div>
            <?php endif; ?>
            
            <form action="register.php" method="POST" id="register-form">
                <input type="hidden" name="user_type" id="user_type" value="student">
                
                <div class="space-y-4">
                    <div>
                        <label for="email" class="text-gray-800 text-sm mb-1 block">Email</label>
                        <input id="email" name="email" type="email" required
                            class="text-gray-800 bg-white border border-gray-300 w-full text-sm px-4 py-3 rounded-md outline-blue-500"
                            placeholder="Enter your email" />
                    </div>
                    
                    <div>
                        <label for="password" class="text-gray-800 text-sm mb-1 block">Password</label>
                        <input id="password" name="password" type="password" required minlength="6"
                            class="text-gray-800 bg-white border border-gray-300 w-full text-sm px-4 py-3 rounded-md outline-blue-500"
                            placeholder="Enter password (min 6 characters)" />
                    </div>
                    
                    <div>
                        <label for="cpassword" class="text-gray-800 text-sm mb-1 block">Confirm Password</label>
                        <input id="cpassword" name="cpassword" type="password" required minlength="6"
                            class="text-gray-800 bg-white border border-gray-300 w-full text-sm px-4 py-3 rounded-md outline-blue-500"
                            placeholder="Confirm password" />
                    </div>
                    
                    <!-- Teacher-specific fields (hidden by default) -->
                    <div id="teacher-fields" class="teacher-fields space-y-4">
                        <div>
                            <label for="teacher_code" class="text-gray-800 text-sm mb-1 block">Teacher Code</label>
                            <input id="teacher_code" name="teacher_code" type="text"
                                class="text-gray-800 bg-white border border-gray-300 w-full text-sm px-4 py-3 rounded-md outline-blue-500"
                                placeholder="Enter your teacher code (e.g., teacher6)" />
                            <p class="text-gray-500 text-xs mt-1">You'll receive this code after approval</p>
                        </div>
                        
                        <div class="text-center">
                            <a href="teacher_apply.php" class="text-blue-600 text-sm hover:underline">
                                Apply to become a teacher
                            </a>
                        </div>
                    </div>
                    
                    <div class="mt-6">
                        <button type="submit" id="submit-btn"
                            class="w-full py-3 px-4 text-sm tracking-wider font-semibold rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none">
                            Create Account
                        </button>
                    </div>
                </div>
            </form>
            
            <p class="text-gray-800 text-sm mt-6 text-center">Already have an account?
                <a href="login.php" class="text-blue-600 font-semibold hover:underline ml-1">Login here</a>
            </p>
        </div>
    </div>

    <script>
        // Toggle between student and teacher registration
        function toggleRegistrationType(type) {
            const teacherFields = document.getElementById('teacher-fields');
            const studentTab = document.getElementById('student-tab');
            const teacherTab = document.getElementById('teacher-tab');
            const userType = document.getElementById('user_type');
            
            if (type === 'teacher') {
                teacherFields.style.display = 'block';
                userType.value = 'teacher';
                studentTab.classList.remove('active-tab');
                studentTab.classList.add('inactive-tab');
                teacherTab.classList.add('active-tab');
                teacherTab.classList.remove('inactive-tab');
                
                // Make teacher code field required when shown
                document.getElementById('teacher_code').required = true;
            } else {
                teacherFields.style.display = 'none';
                userType.value = 'student';
                teacherTab.classList.remove('active-tab');
                teacherTab.classList.add('inactive-tab');
                studentTab.classList.add('active-tab');
                studentTab.classList.remove('inactive-tab');
                
                // Remove required attribute when hidden
                document.getElementById('teacher_code').required = false;
            }
        }
        
        // Client-side form validation
        document.getElementById('register-form').addEventListener('submit', function(e) {
            const password = document.getElementById('password').value;
            const cpassword = document.getElementById('cpassword').value;
            
            if (password !== cpassword) {
                e.preventDefault();
                alert('Passwords do not match!');
                return false;
            }
            
            if (password.length < 6) {
                e.preventDefault();
                alert('Password must be at least 6 characters long!');
                return false;
            }
            
            return true;
        });
        
        // Check URL for type parameter on page load
        document.addEventListener('DOMContentLoaded', function() {
            const urlParams = new URLSearchParams(window.location.search);
            const type = urlParams.get('type');
            
            if (type === 'teacher') {
                toggleRegistrationType('teacher');
            }
        });
    </script>
</body>
</html>
