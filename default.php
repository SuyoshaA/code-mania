<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Sign in Page</title>
  <!-- Tailwind CSS CDN -->
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-50 font-sans" style="background-image: url('codding.png'); background-size: cover; background-repeat: no-repeat; background-position: center;">
  <!-- Navigation Bar -->
  <nav class="border-gray-200 bg-blue-600">
    <div class="max-w-screen-xl flex flex-wrap items-center justify-between mx-auto p-4">
      <a href="#" class="flex items-center space-x-3">
        <img src="https://flowbite.com/docs/images/logo.svg" class="h-8" alt="Flowbite Logo" />
        <span class="text-2xl font-semibold text-white">CodingMania</span>
      </a>
      <button data-collapse-toggle="navbar-solid-bg" type="button" class="inline-flex items-center p-2 w-10 h-10 justify-center text-sm text-gray-500 rounded-lg md:hidden hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-gray-200" aria-controls="navbar-solid-bg" aria-expanded="false">
        <span class="sr-only">Open main menu</span>
        <svg class="w-5 h-5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 17 14">
          <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M1 1h15M1 7h15M1 13h15"/>
        </svg>
      </button>
      <div class="hidden w-full md:block md:w-auto" id="navbar-solid-bg">
        <ul class="flex flex-col md:flex-row md:space-x-8 mt-4 md:mt-0 bg-blue-600 md:bg-transparent">
          <li>
            <a href="retrieve.php" class="block py-2 px-3 text-white rounded hover:bg-blue-700">Home</a>
          </li>
          <li>
            <!-- About tab now clickable -->
            <a href="about.php" class="block py-2 px-3 text-white rounded hover:bg-blue-700">About</a>
          </li>
          <li>
            <a href="language.php" class="block py-2 px-3 text-white rounded hover:bg-blue-700">Language</a>
          </li>
          <li>
            <a href="login.php" class="block py-2 px-3 text-white rounded hover:bg-blue-700">Login</a>
          </li>
          <li>
            <a href="register.php" class="block py-2 px-3 text-white rounded hover:bg-blue-700">Register</a>
          </li>
        </ul>
      </div>
    </div>
  </nav>

  <!-- Sign in Form -->
  <div class="min-h-screen flex flex-col items-center justify-center py-6 px-4">
    <div class="max-w-md w-full">
      <div class="p-8 rounded-2xl bg-white shadow">
        <h2 class="text-center text-2xl font-bold text-gray-800">Sign in</h2>
        <!-- Display error message if exists -->
        <?php if (isset($_GET['error'])) { ?>
          <p class="text-red-500 text-center mt-2"><?php echo htmlspecialchars($_GET['error']); ?></p>
        <?php } ?>
        <form action="login.php" method="POST" class="mt-8 space-y-4">
          <!-- Username Input -->
          <div>
            <label class="block text-sm text-gray-800 mb-2">User name</label>
            <div class="relative flex items-center">
              <input name="email" type="text" required class="w-full text-sm border border-gray-300 px-4 py-3 rounded-md outline-blue-600" placeholder="Enter user name" />
              <svg xmlns="http://www.w3.org/2000/svg" fill="#bbb" stroke="#bbb" class="w-4 h-4 absolute right-4" viewBox="0 0 24 24">
                <circle cx="10" cy="7" r="6"></circle>
                <path d="M14 15H6a5 5 0 0 0-5 5 3 3 0 0 0 3 3h12a3 3 0 0 0 3-3 5 5 0 0 0-5-5zm8-4h-2.59l.3-.29a1 1 0 0 0-1.42-1.42l-2 2a1 1 0 0 0 0 1.42l2 2a1 1 0 0 0 1.42 0 1 1 0 0 0 0-1.42l-.3-.29H22a1 1 0 0 0 0-2z"></path>
              </svg>
            </div>
          </div>
          <!-- Password Input -->
          <div>
            <label class="block text-sm text-gray-800 mb-2">Password</label>
            <div class="relative flex items-center">
              <input name="password" type="password" required class="w-full text-sm border border-gray-300 px-4 py-3 rounded-md outline-blue-600" placeholder="Enter password" />
              <svg xmlns="http://www.w3.org/2000/svg" fill="#bbb" stroke="#bbb" class="w-4 h-4 absolute right-4 cursor-pointer" viewBox="0 0 128 128">
                <path d="M64 104C22.127 104 1.367 67.496.504 65.943a4 4 0 0 1 0-3.887C1.367 60.504 22.127 24 64 24s62.633 36.504 63.496 38.057a4 4 0 0 1 0 3.887C126.633 67.496 105.873 104 64 104zM8.707 63.994C13.465 71.205 32.146 96 64 96c31.955 0 50.553-24.775 55.293-31.994C114.535 56.795 95.854 32 64 32 32.045 32 13.447 56.775 8.707 63.994zM64 88c-13.234 0-24-10.766-24-24s10.766-24 24-24 24 10.766 24 24-10.766 24-24 24zm0-40c-8.822 0-16 7.178-16 16s7.178 16 16 16 16-7.178 16-16-7.178-16-16-16z"></path>
              </svg>
            </div>
          </div>
          <!-- Remember me and Forgot password -->
          <div class="flex flex-wrap items-center justify-between gap-4">
            <div class="flex items-center">
              <input id="remember-me" name="remember-me" type="checkbox" class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded" />
              <label for="remember-me" class="ml-3 block text-sm text-gray-800">Remember me</label>
            </div>
            <div class="text-sm">
              <a href="javascript:void(0);" class="text-blue-600 hover:underline font-semibold">Forgot your password?</a>
            </div>
          </div>
          <!-- Sign in Button -->
          <div class="mt-8">
            <button type="submit" class="w-full py-3 px-4 text-sm rounded-lg text-white bg-blue-600 hover:bg-blue-700 focus:outline-none">
              Sign in
            </button>
          </div>
          <!-- Register Link -->
          <p class="text-center text-sm text-gray-800 mt-8">Don't have an account? <a href="register.php" class="text-blue-600 hover:underline ml-1 font-semibold">Register here</a></p>
        </form>
      </div>
    </div>
  </div>
</body>
</html>
