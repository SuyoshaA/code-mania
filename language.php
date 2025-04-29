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
    
  <nav class="border-gray-200 bg-blue-600 dark:bg-blue-600 dark:border-gray-700">
    <div class="max-w-screen-xl flex flex-wrap items-center justify-between mx-auto p-4">
      <a href="#" class="flex items-center space-x-3 rtl:space-x-reverse">
        <img src="https://flowbite.com/docs/images/logo.svg" class="h-8" alt="Flowbite Logo" />
        <span class="self-center text-2xl font-semibold whitespace-nowrap text-white">CodingMania</span>
      </a>
      <button data-collapse-toggle="navbar-solid-bg" type="button" class="inline-flex items-center p-2 w-10 h-10 justify-center text-sm text-gray-500 rounded-lg md:hidden hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-gray-200 dark:text-gray-400 dark:hover:bg-gray-700 dark:focus:ring-gray-600" aria-controls="navbar-solid-bg" aria-expanded="false">
        <span class="sr-only">Open main menu</span>
        <svg class="w-5 h-5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 17 14">
          <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M1 1h15M1 7h15M1 13h15"/>
        </svg>
      </button>
      <div class="hidden w-full md:block md:w-auto" id="navbar-solid-bg">
        <ul class="flex flex-col font-medium mt-4 rounded-lg bg-blue-600 md:space-x-8 rtl:space-x-reverse md:flex-row md:mt-0 md:bg-transparent dark:bg-gray-800 md:dark:bg-transparent dark:border-gray-700">
          <li>
            <a href="default.php" class="block py-2 px-3 md:p-0 text-white rounded hover:bg-blue-600 md:hover:bg-transparent md:border-0 md:hover:text-white dark:text-white md:dark:hover:text-blue-500 dark:hover:bg-gray-700 dark:hover:text-white md:dark:hover:bg-transparent">Home</a>
          </li>
          <li>
            <a href="#" class="block py-2 px-3 md:p-0 text-white rounded hover:bg-blue-600 md:hover:bg-transparent md:border-0 md:hover:text-white dark:text-white md:dark:hover:text-blue-500 dark:hover:bg-gray-700 dark:hover:text-white md:dark:hover:bg-transparent">About</a>
          </li>
          <li>
            <a href="language.php" class="block py-2 px-3 md:p-0 text-white rounded hover:bg-blue-600 md:hover:bg-transparent md:border-0 md:hover:text-white dark:text-white md:dark:hover:text-blue-500 dark:hover:bg-gray-700 dark:hover:text-white md:dark:hover:bg-transparent">Language</a>
          </li>
          <li>
            <a href="#" class="block py-2 px-3 md:p-0 text-white rounded hover:bg-blue-600 md:hover:bg-transparent md:border-0 md:hover:text-white dark:text-white md:dark:hover:text-blue-500 dark:hover:bg-gray-700 dark:hover:text-white md:dark:hover:bg-transparent">Login</a>
          </li>
          <li>
            <a href="#" class="block py-2 px-3 md:p-0 text-white rounded hover:bg-blue-600 md:hover:bg-transparent md:border-0 md:hover:text-white dark:text-white md:dark:hover:text-blue-500 dark:hover:bg-gray-700 dark:hover:text-white md:dark:hover:bg-transparent">Register</a>
          </li>
        </ul>
      </div>
    </div>
  </nav>

  <!-- Centered Container for images with a white background and rounded border -->
  <div class="flex justify-center items-center h-screen">
    <div class="bg-white p-8 rounded-lg shadow-lg w-full h-[80%] mt-[-1%] max-w-7xl">
        <h2 class="text-3xl font-semibold dark:text-center text-blue-600 mb-8">Select Programming Language</h2>
      <div class="flex flex-col md:flex-row justify-center items-center space-y-4 md:space-y-0 md:space-x-6 h-full">
        <figure class="relative max-w-sm transition-all duration-300 cursor-pointer filter hover:grayscale grayscale-0">
          <a href="#">
            <img class="rounded-lg w-60 h-60 object-cover" src="https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcQyXkCNULZ3_bldMhlxdY122oA4xEvL1NQGXQ&s" alt="Python">
          </a>
        </figure>

        <figure class="relative max-w-sm transition-all duration-300 cursor-pointer filter hover:grayscale grayscale-0">
          <a href="#">
            <img class="rounded-lg w-60 h-60 object-cover" src="https://upload.wikimedia.org/wikipedia/commons/1/18/ISO_C%2B%2B_Logo.svg" alt="C++">
          </a>
        </figure>
        
        <figure class="relative max-w-sm transition-all duration-300 cursor-pointer filter hover:grayscale grayscale-0">
          <a href="#">
            <img class="rounded-lg w-60 h-60 object-cover" src="https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcRmu5MgIGTg-BKBysedwIJwJS6UseXA0J9k7w&s" alt="C++">
          </a>
        </figure>

        <figure class="relative max-w-sm transition-all duration-300 cursor-pointer filter hover:grayscale grayscale-0">
          <a href="#">
            <img class="rounded-lg w-60 h-60 object-cover" src="https://upload.wikimedia.org/wikipedia/commons/6/6a/JavaScript-logo.png" alt="JavaScript">
          </a>
        </figure>
      </div>
    </div>
  </div>

</body>
</html>
