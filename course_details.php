<?php
// course_details.php
// This page is publicly accessible and showcases details of our top four courses.

$courses = [
    [
        'course_name' => 'C++ Course',
        'description' => "Learn the fundamentals of C++ programming, one of the most powerful and versatile languages in the industry. "
                        . "This course covers object-oriented programming, memory management, and performance optimization. Enjoy interactive videos, "
                        . "live coding sessions, and real-world examples that make complex concepts easier to grasp.",
    ],
    [
        'course_name' => 'Java Course',
        'description' => "Explore Java, a robust object-oriented programming language widely used in enterprise and Android development. "
                        . "Our course offers interactive lectures, dynamic coding challenges, and practical projects designed to prepare you for building scalable applications.",
    ],
    [
        'course_name' => 'Python Course',
        'description' => "Dive into Python, one of the most versatile languages used in web development, data science, automation, and more. "
                        . "The course features interactive tutorials, hands-on labs, and engaging projects to help you learn coding efficiently and solve real-world problems.",
    ],
    [
        'course_name' => 'Web Development Course',
        'description' => "Master the art of building modern, responsive websites using HTML, CSS, JavaScript, and popular frameworks. "
                        . "This course is packed with interactive content, including detailed videos, live demos, and project-based learning that equips you with practical skills for the digital world.",
    ]
];
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Course Details - Code Academy</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <style>
    body {
      background: linear-gradient(to right, #F0F4F8, #D9E2EC);
    }
  </style>
</head>
<body>
  <!-- Header -->
  <header class="bg-gray-800 text-white p-6 shadow">
    <div class="container mx-auto text-center">
      <h1 class="text-3xl font-bold">Course Details</h1>
    </div>
  </header>
  
  <!-- Main Content -->
  <main class="container mx-auto my-8 p-4">
    <section class="mb-12 text-center">
      <h2 class="text-4xl font-bold mb-4">Discover Our Courses</h2>
      <p class="text-xl text-gray-700">
        Our courses are designed to offer an immersive, interactive learning experience. With expert-led interactive videos, live coding challenges, and hands-on projects, you will gain the skills needed for a successful career in tech.
      </p>
    </section>
    
    <section>
      <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
        <?php foreach ($courses as $course): ?>
        <div class="bg-white rounded-lg shadow-lg p-6">
          <h3 class="text-2xl font-semibold text-gray-800 mb-4"><?= htmlspecialchars($course['course_name']) ?></h3>
          <p class="text-gray-700 mb-4"><?= nl2br(htmlspecialchars($course['description'])) ?></p>
          <!-- When the visitor clicks "Enroll Now", they go to register.php -->
          <a href="register.php" class="inline-block bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 transition">
            Enroll Now
          </a>
        </div>
        <?php endforeach; ?>
      </div>
    </section>
  </main>
  
  <!-- Footer -->
  <footer class="bg-gray-800 text-white p-6 text-center">
    <p>&copy; <?= date("Y") ?> Code Academy. All rights reserved.</p>
  </footer>
</body>
</html>
