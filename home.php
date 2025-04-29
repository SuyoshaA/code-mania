<?php
// home.php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}
include('db.php');

// Fetch available courses from the database
$query = "SELECT * FROM courses";
$result = $conn->query($query);
$courses = [];
while ($row = $result->fetch_assoc()) {
    $courses[] = $row;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Coding Mania - Courses</title>
   <!-- Link to the Tailwind-compiled custom CSS file -->
   <link rel="stylesheet" href="home.css">
</head>
<body class="bg-gray-100 animate-fadeIn">
   <header class="header">
     <div class="container text-center">
       <h1 class="main-heading">Welcome to Coding Mania</h1>
       <p class="subheading">We teach you how to code and build amazing applications.</p>
     </div>
   </header>

   <main class="container mt-10">
     <h2 class="text-3xl font-semibold mb-6 text-center">Available Courses</h2>
     <div class="card-grid">
       <?php foreach($courses as $course): ?>
         <div class="course-card">
           <h3 class="text-xl font-bold mb-2"><?= htmlspecialchars($course['course_name']) ?></h3>
           <p class="mb-4">
             <?= htmlspecialchars($course['description'] ?? 'No description available.') ?>
           </p>
           <!-- When clicked, the course id is passed to dashboard.php -->
           <a href="dashboard.php?course_id=<?= $course['id'] ?>" 
              class="bg-blue-500 text-white py-2 px-4 rounded hover:bg-blue-600 transition">
             Enroll & View Dashboard
           </a>
         </div>
       <?php endforeach; ?>
     </div>
   </main>
</body>
</html>
