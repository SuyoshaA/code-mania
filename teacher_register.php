<?php
include('db.php');
session_start();

// Define the preset teacher credentials
$allowedTeachers = [
  $allowedTeachers = [
    'suyosha@codemania.com' => 'python12345',
    'david@codemania.com'   => 'c++12345',
    'fallon@codemania.com'  => 'webdev12345', // Add Fallon's password
    'liam@codemania.com'    => 'java12345'  // Add Liam's password
];

$teacherCodes = [
  'teacher6' => 'suyosha@codemania.com', // Python
  'teacher7' => 'david@codemania.com',   // C++
  'teacher8' => 'fallon@codemania.com',  // Web Dev
  'teacher9' => 'liam@codemania.com'     // Java
];
];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];
    
    // Check if the provided credentials match one of the allowed teacher accounts
    if (isset($allowedTeachers[$email]) && $allowedTeachers[$email] === $password) {
        // Check if teacher already exists
        $checkUser = $conn->query("SELECT * FROM users WHERE email='$email'");
        if ($checkUser->num_rows > 0) {
            echo "<script>alert('Teacher already registered!'); window.location.href='login.php';</script>";
        } else {
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);
            $sql = "INSERT INTO users (email, password, role) VALUES ('$email', '$hashed_password', 'teacher')";
            if ($conn->query($sql) === TRUE) {
                echo "<script>alert('Teacher registration successful!'); window.location.href='login.php';</script>";
            } else {
                echo "Error: " . $conn->error;
            }
        }
    } else {
        echo "<script>alert('Invalid teacher credentials!'); window.location.href='teacher_register.php';</script>";
    }
    $conn->close();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Teacher Registration - CodingMania</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-50 flex items-center justify-center h-screen">
   <div class="bg-white p-8 rounded-lg shadow-lg w-96">
     <h2 class="text-2xl font-bold text-center mb-4">Teacher Registration</h2>
     <p class="text-center text-gray-600 mb-4">Enter your approved teacher credentials.</p>
     <form action="teacher_register.php" method="POST">
       <div class="mb-4">
         <label class="block text-gray-700 text-sm mb-2">Email</label>
         <input type="email" name="email" required placeholder="suyosha@codemania.com"
                class="w-full p-3 border border-gray-300 rounded-md">
       </div>
       <div class="mb-4">
         <label class="block text-gray-700 text-sm mb-2">Password</label>
         <input type="password" name="password" required placeholder="12345"
                class="w-full p-3 border border-gray-300 rounded-md">
       </div>
       <button type="submit" class="w-full p-3 bg-blue-600 text-white font-bold rounded-md hover:bg-blue-700">
          Register as Teacher
       </button>
     </form>
     <p class="text-gray-600 text-sm text-center mt-4">
       Already have an account? <a href="login.php" class="text-blue-600 hover:underline">Login here</a>
     </p>
   </div>
</body>
</html>
