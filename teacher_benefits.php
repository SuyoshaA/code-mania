<?php
// teacher_benefit.php
// This page is accessible publicly and showcases the benefits for teachers.
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Teacher Benefits - Code Academy</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <style>
    @keyframes fadeIn {
      from { opacity: 0; transform: translateY(20px); }
      to { opacity: 1; transform: translateY(0); }
    }
    .fade-in { animation: fadeIn 0.5s ease-out; }
  </style>
</head>
<body class="bg-gradient-to-r from-gray-200 to-gray-100">
  <!-- Header without login or dashboard links -->
  <header class="fixed top-0 left-0 right-0 bg-gray-800 text-white p-4 shadow z-50">
    <div class="container mx-auto">
      <h1 class="text-xl font-bold text-center">Code Academy</h1>
    </div>
  </header>

  <!-- Main Content -->
  <main class="container mx-auto mt-20 p-6 bg-white rounded-lg shadow-lg fade-in">
    <h2 class="text-3xl font-bold text-center mb-6">Teacher Benefits</h2>
    <p class="text-lg text-gray-700 mb-8 text-center">
      Discover the powerful features and advantages that Code Academy offers to teachers.
    </p>
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
      <!-- Benefit 1: Easy Course Management -->
      <div class="bg-blue-100 p-6 rounded-lg shadow">
        <h3 class="text-2xl font-semibold text-blue-800 mb-3">Easy Course Management</h3>
        <p class="text-gray-700">
          Create, edit, and manage courses effortlessly. Organize modules, lessons, and quizzes in one place and update content in real-time.
        </p>
      </div>
      <!-- Benefit 2: Student Progress Tracking -->
      <div class="bg-green-100 p-6 rounded-lg shadow">
        <h3 class="text-2xl font-semibold text-green-800 mb-3">Student Progress Tracking</h3>
        <p class="text-gray-700">
          Monitor student progress with detailed analytics. View course completion rates, quiz scores, and overall performance through interactive dashboards.
        </p>
      </div>
      <!-- Benefit 3: Efficient Grading System -->
      <div class="bg-purple-100 p-6 rounded-lg shadow">
        <h3 class="text-2xl font-semibold text-purple-800 mb-3">Efficient Grading System</h3>
        <p class="text-gray-700">
          Grade assignments quickly using our integrated grading tools. Provide feedback, update student progress instantly, and award badges automatically.
        </p>
      </div>
      <!-- Benefit 4: Interactive Peer Review -->
      <div class="bg-yellow-100 p-6 rounded-lg shadow">
        <h3 class="text-2xl font-semibold text-yellow-800 mb-3">Interactive Peer Review</h3>
        <p class="text-gray-700">
          Engage with students through peer reviews. Facilitate collaboration and critical feedback with a dedicated forum that encourages discussion and learning.
        </p>
      </div>
      <!-- Benefit 5: Real-Time Communication -->
      <div class="bg-indigo-100 p-6 rounded-lg shadow">
        <h3 class="text-2xl font-semibold text-indigo-800 mb-3">Real-Time Communication</h3>
        <p class="text-gray-700">
          Communicate with students instantly via chat and messaging features. Provide support, answer questions, and share insights without delay.
        </p>
      </div>
      <!-- Benefit 6: Reward System -->
      <div class="bg-red-100 p-6 rounded-lg shadow">
        <h3 class="text-2xl font-semibold text-red-800 mb-3">Reward System</h3>
        <p class="text-gray-700">
          Motivate and recognize top performers with our badge system. Award badges such as the Pro Badge to incentivize excellence and reward achievements.
        </p>
      </div>
    </div>
  </main>
</body>
</html>
