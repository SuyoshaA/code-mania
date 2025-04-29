<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>About CodingMania</title>
  <!-- Tailwind CSS CDN -->
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-50 font-sans">
  <!-- Navigation Bar (can be similar to default.php) -->
  <nav class="border-gray-200 bg-blue-600">
    <div class="max-w-screen-xl flex flex-wrap items-center justify-between mx-auto p-4">
      <a href="default.php" class="flex items-center space-x-3">
        <img src="https://flowbite.com/docs/images/logo.svg" class="h-8" alt="Flowbite Logo">
        <span class="text-2xl font-semibold text-white">CodingMania</span>
      </a>
      <div class="hidden w-full md:block md:w-auto" id="navbar">
        <ul class="flex flex-col md:flex-row md:space-x-8">
          <li>
            <a href="retrieve.php" class="block py-2 px-3 text-white hover:bg-blue-700">Home</a>
          </li>
          <li>
            <a href="about.php" class="block py-2 px-3 text-white hover:bg-blue-700">About</a>
          </li>
          <li>
            <a href="language.php" class="block py-2 px-3 text-white hover:bg-blue-700">Language</a>
          </li>
          <li>
            <a href="login.php" class="block py-2 px-3 text-white hover:bg-blue-700">Login</a>
          </li>
          <li>
            <a href="register.php" class="block py-2 px-3 text-white hover:bg-blue-700">Register</a>
          </li>
        </ul>
      </div>
    </div>
  </nav>
  
  <!-- About Content -->
  <div class="max-w-3xl mx-auto p-8">
    <h1 class="text-4xl font-bold text-gray-800 mb-4">About CodingMania</h1>
    <p class="text-gray-700 mb-2">
      CodingMania is a modern Code Tutor Web App designed to empower learners in mastering various programming languages. Our platform provides interactive lessons, coding challenges, and community support to help users from beginners to advanced programmers.
    </p>
    <p class="text-gray-700">
      With a sleek, responsive design powered by Tailwind CSS, CodingMania offers a seamless experience on any device. Join us today and take the next step in your coding journey!
    </p>
    <div class="mt-6">
      <a href="default.php" class="text-blue-600 hover:underline">Back to Sign In</a>
    </div>
  </div>
</body>
</html>
