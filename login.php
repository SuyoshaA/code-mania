<?php
include('db.php');
session_start();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Retrieve form data
    $email = $_POST['email'];
    $password = $_POST['password'];
    $login_role = $_POST['role'];  // Expected to be either "teacher" or "student"
    
    // Query database for user with matching email
    $query = "SELECT * FROM users WHERE email=?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
        // Verify password using password_verify
        if (password_verify($password, $user['password'])) {
            // Check if the user's role in the DB matches the selected role
            if ($user['role'] !== $login_role) {
                echo "<script>alert('The selected role does not match our records for this account!'); window.location.href='login.php';</script>";
                exit();
            }
            
            // Start session and set session variables
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['user_email'] = $email;
            $_SESSION['role'] = $user['role'];
            
            // Redirect based on the user role
            if ($user['role'] === 'teacher') {
                header('Location: teacher_dashboard.php');
            } else {
                header('Location: student_dashboard.php');
            }
            exit();
        } else {
            echo "<script>alert('Incorrect password!'); window.location.href='login.php';</script>";
        }
    } else {
        echo "<script>alert('No user found with this email!'); window.location.href='login.php';</script>";
    }
    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Login - CodingMania</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <!-- Optionally, link to your own CSS file -->
  <link rel="stylesheet" href="assets/css/style.css">
</head>
<body class="bg-gray-100 flex items-center justify-center h-screen">
  <div class="bg-white p-8 rounded-lg shadow-lg w-96">
    <h2 class="text-2xl font-bold text-center mb-4">Sign In</h2>
    
    <!-- (Optional) Display any error passed in the session -->
    <?php if (isset($error)): ?>
      <p class="text-red-500 text-sm text-center mb-4"><?php echo $error; ?></p>
    <?php endif; ?>

    <form action="login.php" method="POST">
      <div class="mb-4">
        <label class="block text-gray-700 text-sm mb-2">Email</label>
        <input type="email" name="email" required
               class="w-full p-3 border border-gray-300 rounded-md focus:ring focus:ring-blue-300">
      </div>
      <div class="mb-4">
        <label class="block text-gray-700 text-sm mb-2">Password</label>
        <input type="password" name="password" required
               class="w-full p-3 border border-gray-300 rounded-md focus:ring focus:ring-blue-300">
      </div>
      <div class="mb-4">
        <label class="block text-gray-700 text-sm mb-2">I am a:</label>
        <select name="role" required class="w-full p-3 border border-gray-300 rounded-md focus:ring focus:ring-blue-300">
          <option value="student">Student</option>
          <option value="teacher">Teacher</option>
        </select>
      </div>
      <button type="submit" 
              class="w-full p-3 bg-blue-600 text-white font-bold rounded-md hover:bg-blue-700">
        Login
      </button>
    </form>

    <p class="text-gray-600 text-sm text-center mt-4">
      Don't have an account? 
      <a href="register.php" class="text-blue-600 font-semibold">Register</a>
    </p>
  </div>
</body>
</html>
