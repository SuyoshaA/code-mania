<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>CodingMania - Learn Coding Online</title>
  <!-- Tailwind CSS CDN -->
  <script src="https://cdn.tailwindcss.com"></script>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>
  <style>
    body {
      font-family: 'Inter', sans-serif;
    }
    .gradient-bg {
      background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    }
    .course-card:hover {
      transform: translateY(-5px);
      box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
    }
    .hero-bg {
      background-image: url('https://images.unsplash.com/photo-1555066931-4365d14bab8c?ixlib=rb-1.2.1&auto=format&fit=crop&w=1350&q=80');
      background-size: cover;
      background-position: center;
      background-attachment: fixed;
    }
    .overlay {
      background: linear-gradient(to right, rgba(0, 0, 0, 0.7), rgba(0, 0, 0, 0.3));
    }
    .icon-box {
      width: 70px;
      height: 70px;
      display: flex;
      align-items: center;
      justify-content: center;
      border-radius: 20px;
    }
    .nav-link {
      position: relative;
    }
    .nav-link:after {
      content: '';
      position: absolute;
      width: 0;
      height: 2px;
      bottom: 0;
      left: 0;
      background-color: #4f46e5;
      transition: width 0.3s ease;
    }
    .nav-link:hover:after {
      width: 100%;
    }
  </style>
</head>
<body class="bg-gray-50">

  <!-- Navigation Bar -->
  <header class="bg-white shadow-sm sticky top-0 z-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
      <div class="flex justify-between items-center h-20">
        <!-- Logo / Site Name -->
        <div class="flex-shrink-0 flex items-center">
          <a href="index.php" class="text-2xl font-bold text-indigo-600 flex items-center">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4" />
            </svg>
            CodingMania
          </a>
        </div>
        <!-- Navigation Links -->
        <nav class="hidden md:flex space-x-8">
          <a href="index.php" class="nav-link text-gray-900 inline-flex items-center px-1 pt-1 text-sm font-medium">Home</a>
          <a href="#courses" class="nav-link text-gray-500 hover:text-gray-900 inline-flex items-center px-1 pt-1 text-sm font-medium">Courses</a>
          <a href="#features" class="nav-link text-gray-500 hover:text-gray-900 inline-flex items-center px-1 pt-1 text-sm font-medium">Features</a>
          
          <a href="#join" class="nav-link text-gray-500 hover:text-gray-900 inline-flex items-center px-1 pt-1 text-sm font-medium">Join Our Team</a>
        </nav>
        <!-- Sign In / Register Buttons -->
        <div class="flex items-center space-x-4">
          <a href="login.php" class="text-gray-600 hover:text-indigo-600 font-medium">Sign In</a>
          <a href="register.php" class="px-5 py-2 bg-indigo-600 text-white font-medium rounded-md hover:bg-indigo-700 transition duration-300 shadow-sm">Register</a>
        </div>
      </div>
    </div>
  </header>

  <!-- Hero Section -->
  <section class="hero-bg h-screen flex items-center justify-center relative">
    <div class="absolute inset-0 overlay"></div>
    <div class="relative z-10 text-center text-white px-4 max-w-4xl mx-auto animate__animated animate__fadeIn">
      <h1 class="text-4xl md:text-6xl font-bold mb-6 leading-tight">Master Coding Skills That Matter in 2025</h1>
      <p class="text-xl md:text-2xl mb-8 text-indigo-100">Join our students learning committee with our project-based courses in Python, JavaScript, Web Development and more. Get job-ready with real-world projects and 1:1 mentorship.</p>
      <div class="flex flex-col sm:flex-row justify-center gap-4">
        <a href="register.php" class="px-8 py-4 bg-indigo-600 text-white text-lg font-semibold rounded-lg hover:bg-indigo-700 transition duration-300 shadow-lg">Start Learning Free</a>
        <a href="#courses" class="px-8 py-4 bg-transparent border-2 border-white text-white text-lg font-semibold rounded-lg hover:bg-white hover:text-indigo-600 transition duration-300">Explore Courses</a>
      </div>
    </div>
    <div class="absolute bottom-10 left-0 right-0 flex justify-center">
      <a href="#features" class="text-white animate-bounce">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10" fill="none" viewBox="0 0 24 24" stroke="currentColor">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 14l-7 7m0 0l-7-7m7 7V3" />
        </svg>
      </a>
    </div>
  </section>

  <!-- Stats Section -->
  <section class="py-16 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
      <div class="grid grid-cols-2 md:grid-cols-4 gap-8 text-center">
        <div class="p-6">
          <div class="text-4xl font-bold text-indigo-600 mb-2">10</div>
          <div class="text-gray-600">Students Enrolled</div>
        </div>
        <div class="p-6">
          <div class="text-4xl font-bold text-indigo-600 mb-2">120+</div>
          <div class="text-gray-600">Hours of Content</div>
        </div>
        <div class="p-6">
          <div class="text-4xl font-bold text-indigo-600 mb-2">4</div>
          <div class="text-gray-600">Expert Instructors</div>
        </div>
        <div class="p-6">
          <div class="text-4xl font-bold text-indigo-600 mb-2">24/7</div>
          <div class="text-gray-600">Support Available</div>
        </div>
      </div>
    </div>
  </section>

  <!-- Features Section -->
  <section id="features" class="py-16 bg-gray-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
      <div class="text-center mb-16">
        <h2 class="text-3xl font-bold text-gray-900 mb-4">Why Learn With CodingMania?</h2>
        <p class="text-xl text-gray-600 max-w-3xl mx-auto">Our unique approach to coding education sets you up for success</p>
      </div>
      <div class="grid grid-cols-1 md:grid-cols-3 gap-10">
        <div class="bg-white p-8 rounded-xl shadow-md hover:shadow-lg transition duration-300">
          <div class="icon-box bg-indigo-100 text-indigo-600 mb-6">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z" />
            </svg>
          </div>
          <h3 class="text-xl font-semibold text-gray-900 mb-3">Project-Based Learning</h3>
          <p class="text-gray-600">Build real-world projects that you can showcase in your portfolio to potential employers.</p>
        </div>
        <div class="bg-white p-8 rounded-xl shadow-md hover:shadow-lg transition duration-300">
          <div class="icon-box bg-green-100 text-green-600 mb-6">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
            </svg>
          </div>
          <h3 class="text-xl font-semibold text-gray-900 mb-3">Comprehensive Curriculum</h3>
          <p class="text-gray-600">From beginner to advanced levels, our courses cover everything you need to know.</p>
        </div>
        <div class="bg-white p-8 rounded-xl shadow-md hover:shadow-lg transition duration-300">
          <div class="icon-box bg-purple-100 text-purple-600 mb-6">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z" />
            </svg>
          </div>
          <h3 class="text-xl font-semibold text-gray-900 mb-3">1:1 Mentorship</h3>
          <p class="text-gray-600">Get personalized guidance from industry experts to help you overcome challenges.</p>
        </div>
      </div>
    </div>
  </section>

  <!-- Courses Section -->
  <section id="courses" class="py-16 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
      <div class="text-center mb-16">
        <h2 class="text-3xl font-bold text-gray-900 mb-4">Popular Courses</h2>
        <p class="text-xl text-gray-600 max-w-3xl mx-auto">Choose from our most in-demand coding courses</p>
      </div>
      <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
        <!-- Python Course Card -->
        <div class="course-card bg-white rounded-xl overflow-hidden shadow-md transition duration-300">
          <div class="h-40 bg-gradient-to-r from-blue-500 to-blue-400 flex items-center justify-center">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-20 w-20 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
            </svg>
          </div>
          <div class="p-6">
            <div class="flex justify-between items-center mb-2">
              <span class="text-sm font-medium text-blue-600">Beginner - Advanced</span>
              <span class="text-xs bg-blue-100 text-blue-800 px-2 py-1 rounded-full">Bestseller</span>
            </div>
            <h3 class="text-xl font-semibold text-gray-900 mb-2">Python Programming</h3>
            <p class="text-gray-600 text-sm mb-4">Master Python from basics to advanced concepts with real-world applications.</p>
            <div class="flex justify-between items-center">
              <div class="flex items-center">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-yellow-400" viewBox="0 0 20 20" fill="currentColor">
                  <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                </svg>
                <span class="text-gray-700 ml-1">4.9</span>
              </div>
              <a href="course_details.php?course_id=1" class="text-indigo-600 hover:text-indigo-800 font-medium">View →</a>
            </div>
          </div>
        </div>
        
        <!-- Web Development Course Card -->
        <div class="course-card bg-white rounded-xl overflow-hidden shadow-md transition duration-300">
          <div class="h-40 bg-gradient-to-r from-purple-500 to-purple-400 flex items-center justify-center">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-20 w-20 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 15a4 4 0 004 4h9a5 5 0 10-.1-9.999 5.002 5.002 0 10-9.78 2.096A4.001 4.001 0 003 15z" />
            </svg>
          </div>
          <div class="p-6">
            <div class="flex justify-between items-center mb-2">
              <span class="text-sm font-medium text-purple-600">Intermediate</span>
              <span class="text-xs bg-green-100 text-green-800 px-2 py-1 rounded-full">New</span>
            </div>
            <h3 class="text-xl font-semibold text-gray-900 mb-2">Full Stack Web Dev</h3>
            <p class="text-gray-600 text-sm mb-4">Build modern web applications with React, Node.js, and MongoDB.</p>
            <div class="flex justify-between items-center">
              <div class="flex items-center">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-yellow-400" viewBox="0 0 20 20" fill="currentColor">
                  <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                </svg>
                <span class="text-gray-700 ml-1">4.8 </span>
              </div>
              <a href="course_details.php?course_id=3" class="text-indigo-600 hover:text-indigo-800 font-medium">View →</a>
            </div>
          </div>
        </div>
        
        <!-- Java Course Card -->
        <div class="course-card bg-white rounded-xl overflow-hidden shadow-md transition duration-300">
          <div class="h-40 bg-gradient-to-r from-red-500 to-red-400 flex items-center justify-center">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-20 w-20 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 12h14M5 12a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v4a2 2 0 01-2 2M5 12a2 2 0 00-2 2v4a2 2 0 002 2h14a2 2 0 002-2v-4a2 2 0 00-2-2m-2-4h.01M17 16h.01" />
            </svg>
          </div>
          <div class="p-6">
            <div class="flex justify-between items-center mb-2">
              <span class="text-sm font-medium text-red-600">Beginner - Advanced</span>
            </div>
            <h3 class="text-xl font-semibold text-gray-900 mb-2">Java Masterclass</h3>
            <p class="text-gray-600 text-sm mb-4">Learn Java programming with OOP concepts, data structures and algorithms.</p>
            <div class="flex justify-between items-center">
              <div class="flex items-center">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-yellow-400" viewBox="0 0 20 20" fill="currentColor">
                  <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                </svg>
                <span class="text-gray-700 ml-1">4.7</span>
              </div>
              <a href="course_details.php?course_id=2" class="text-indigo-600 hover:text-indigo-800 font-medium">View →</a>
            </div>
          </div>
        </div>
        
        <!-- C++ Course Card -->
        <div class="course-card bg-white rounded-xl overflow-hidden shadow-md transition duration-300">
          <div class="h-40 bg-gradient-to-r from-gray-600 to-gray-500 flex items-center justify-center">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-20 w-20 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 3v2m6-2v2M9 19v2m6-2v2M5 9H3m2 6H3m18-6h-2m2 6h-2M7 19h10a2 2 0 002-2V7a2 2 0 00-2-2H7a2 2 0 00-2 2v10a2 2 0 002 2zM9 9h6v6H9V9z" />
            </svg>
          </div>
          <div class="p-6">
            <div class="flex justify-between items-center mb-2">
              <span class="text-sm font-medium text-gray-600">Advanced</span>
            </div>
            <h3 class="text-xl font-semibold text-gray-900 mb-2">C++ for Professionals</h3>
            <p class="text-gray-600 text-sm mb-4">Master C++ with advanced memory management and performance optimization.</p>
            <div class="flex justify-between items-center">
              <div class="flex items-center">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-yellow-400" viewBox="0 0 20 20" fill="currentColor">
                  <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                </svg>
                <span class="text-gray-700 ml-1">4.8 </span>
              </div>
              <a href="course_details.php?course_id=4" class="text-indigo-600 hover:text-indigo-800 font-medium">View →</a>
            </div>
          </div>
        </div>
      </div>
      <div class="text-center mt-12">
        <a href="courses.php" class="px-8 py-3 border border-indigo-600 text-indigo-600 font-medium rounded-md hover:bg-indigo-600 hover:text-white transition duration-300">View All Courses</a>
      </div>
    </div>
  </section>

 
            
  <!-- CTA Section -->
  <section class="py-20 gradient-bg">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
      <h2 class="text-3xl md:text-4xl font-bold text-white mb-6">Ready to Start Your Coding Journey?</h2>
      <p class="text-xl text-indigo-100 mb-8 max-w-3xl mx-auto">Join thousands of students who've transformed their careers with our coding courses.</p>
      <div class="flex flex-col sm:flex-row justify-center gap-4">
        <a href="register.php" class="px-8 py-4 bg-white text-indigo-600 text-lg font-semibold rounded-lg hover:bg-gray-100 transition duration-300 shadow-lg">Get Started for Free</a>
        <a href="#courses" class="px-8 py-4 bg-transparent border-2 border-white text-white text-lg font-semibold rounded-lg hover:bg-white hover:text-indigo-600 transition duration-300">Browse Courses</a>
      </div>
    </div>
  </section>

  <!-- Teacher Application Section -->
  <section id="join" class="py-16 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
      <div class="bg-gray-50 rounded-2xl p-12 text-center">
        <h2 class="text-3xl font-bold text-gray-900 mb-4">Want to Join Our Team of Expert Instructors?</h2>
        <p class="text-xl text-gray-600 mb-8 max-w-3xl mx-auto">
          We're looking for passionate educators to help shape the next generation of developers. Share your knowledge and get paid for it!
        </p>
        <div class="flex flex-col sm:flex-row justify-center gap-4">
          <a href="teacher_apply.php" class="px-8 py-4 bg-indigo-600 text-white font-semibold rounded-lg hover:bg-indigo-700 transition duration-300 shadow-lg">
            Apply as an Instructor
          </a>
          <a href="teacher_benefits.php" class="px-8 py-4 bg-white border border-gray-300 text-gray-700 font-semibold rounded-lg hover:bg-gray-50 transition duration-300">
            Learn About Benefits
          </a>
        </div>
      </div>
    </div>
  </section>

  <!-- Footer -->
  <footer class="bg-gray-900 text-gray-300 pt-16 pb-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
      <div class="grid grid-cols-2 md:grid-cols-4 gap-8 mb-12">
        <div>
          <h3 class="text-white text-lg font-semibold mb-4">CodingMania</h3>
          <p class="text-gray-400 text-sm">Learn in-demand tech skills with our project-based courses and expert mentorship.</p>
        </div>
        <div>
          <h3 class="text-white text-lg font-semibold mb-4">Courses</h3>
          <ul class="space-y-2">
            <li><a href="#" class="text-gray-400 hover:text-white text-sm">Python Programming</a></li>
            <li><a href="#" class="text-gray-400 hover:text-white text-sm">Web Development</a></li>
            <li><a href="#" class="text-gray-400 hover:text-white text-sm">Data Science</a></li>
            <li><a href="#" class="text-gray-400 hover:text-white text-sm">Mobile Development</a></li>
          </ul>
        </div>
        <div>
          <h3 class="text-white text-lg font-semibold mb-4">Company</h3>
          <ul class="space-y-2">
            <li><a href="#" class="text-gray-400 hover:text-white text-sm">About Us</a></li>
            <li><a href="#" class="text-gray-400 hover:text-white text-sm">Careers</a></li>
            <li><a href="#" class="text-gray-400 hover:text-white text-sm">Blog</a></li>
            <li><a href="#" class="text-gray-400 hover:text-white text-sm">Contact</a></li>
          </ul>
        </div>
        <div>
          <h3 class="text-white text-lg font-semibold mb-4">Connect</h3>
          <div class="flex space-x-4">
            <a href="#" class="text-gray-400 hover:text-white">
              <svg class="h-6 w-6" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                <path fill-rule="evenodd" d="M22 12c0-5.523-4.477-10-10-10S2 6.477 2 12c0 4.991 3.657 9.128 8.438 9.878v-6.987h-2.54V12h2.54V9.797c0-2.506 1.492-3.89 3.777-3.89 1.094 0 2.238.195 2.238.195v2.46h-1.26c-1.243 0-1.63.771-1.63 1.562V12h2.773l-.443 2.89h-2.33v6.988C18.343 21.128 22 16.991 22 12z" clip-rule="evenodd" />
              </svg>
            </a>
            <a href="#" class="text-gray-400 hover:text-white">
              <svg class="h-6 w-6" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                <path d="M8.29 20.251c7.547 0 11.675-6.253 11.675-11.675 0-.178 0-.355-.012-.53A8.348 8.348 0 0022 5.92a8.19 8.19 0 01-2.357.646 4.118 4.118 0 001.804-2.27 8.224 8.224 0 01-2.605.996 4.107 4.107 0 00-6.993 3.743 11.65 11.65 0 01-8.457-4.287 4.106 4.106 0 001.27 5.477A4.072 4.072 0 012.8 9.713v.052a4.105 4.105 0 003.292 4.022 4.095 4.095 0 01-1.853.07 4.108 4.108 0 003.834 2.85A8.233 8.233 0 012 18.407a11.616 11.616 0 006.29 1.84" />
              </svg>
            </a>
            <a href="#" class="text-gray-400 hover:text-white">
              <svg class="h-6 w-6" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                <path fill-rule="evenodd" d="M12.315 2c2.43 0 2.784.013 3.808.06 1.064.049 1.791.218 2.427.465a4.902 4.902 0 011.772 1.153 4.902 4.902 0 011.153 1.772c.247.636.416 1.363.465 2.427.048 1.067.06 1.407.06 4.123v.08c0 2.643-.012 2.987-.06 4.043-.049 1.064-.218 1.791-.465 2.427a4.902 4.902 0 01-1.153 1.772 4.902 4.902 0 01-1.772 1.153c-.636.247-1.363.416-2.427.465-1.067.048-1.407.06-4.123.06h-.08c-2.643 0-2.987-.012-4.043-.06-1.064-.049-1.791-.218-2.427-.465a4.902 4.902 0 01-1.772-1.153 4.902 4.902 0 01-1.153-1.772c-.247-.636-.416-1.363-.465-2.427-.047-1.024-.06-1.379-.06-3.808v-.63c0-2.43.013-2.784.06-3.808.049-1.064.218-1.791.465-2.427a4.902 4.902 0 011.153-1.772A4.902 4.902 0 015.45 2.525c.636-.247 1.363-.416 2.427-.465C8.901 2.013 9.256 2 11.685 2h.63zm-.081 1.802h-.468c-2.456 0-2.784.011-3.807.058-.975.045-1.504.207-1.857.344-.467.182-.8.398-1.15.748-.35.35-.566.683-.748 1.15-.137.353-.3.882-.344 1.857-.047 1.023-.058 1.351-.058 3.807v.468c0 2.456.011 2.784.058 3.807.045.975.207 1.504.344 1.857.182.466.399.8.748 1.15.35.35.683.566 1.15.748.353.137.882.3 1.857.344 1.054.048 1.37.058 4.041.058h.08c2.597 0 2.917-.01 3.96-.058.976-.045 1.505-.207 1.858-.344.466-.182.8-.398 1.15-.748.35-.35.566-.683.748-1.15.137-.353.3-.882.344-1.857.048-1.055.058-1.37.058-4.041v-.08c0-2.597-.01-2.917-.058-3.96-.045-.976-.207-1.505-.344-1.858a3.097 3.097 0 00-.748-1.15 3.098 3.098 0 00-1.15-.748c-.353-.137-.882-.3-1.857-.344-1.023-.047-1.351-.058-3.807-.058zM12 6.865a5.135 5.135 0 110 10.27 5.135 5.135 0 010-10.27zm0 1.802a3.333 3.333 0 100 6.666 3.333 3.333 0 000-6.666zm5.338-3.205a1.2 1.2 0 110 2.4 1.2 1.2 0 010-2.4z" clip-rule="evenodd" />
              </svg>
            </a>
            <a href="#" class="text-gray-400 hover:text-white">
              <svg class="h-6 w-6" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                <path d="M19 0h-14c-2.761 0-5 2.239-5 5v14c0 2.761 2.239 5 5 5h14c2.762 0 5-2.239 5-5v-14c0-2.761-2.238-5-5-5zm-11 19h-3v-11h3v11zm-1.5-12.268c-.966 0-1.75-.79-1.75-1.764s.784-1.764 1.75-1.764 1.75.79 1.75 1.764-.783 1.764-1.75 1.764zm13.5 12.268h-3v-5.604c0-3.368-4-3.113-4 0v5.604h-3v-11h3v1.765c1.396-2.586 7-2.777 7 2.476v6.759z" />
              </svg>
            </a>
          </div>
        </div>
      </div>
      <div class="border-t border-gray-800 pt-8 flex flex-col md:flex-row justify-between items-center">
        <p class="text-gray-400 text-sm">&copy; <?php echo date("Y"); ?> CodingMania. All rights reserved.</p>
        <div class="mt-4 md:mt-0">
          <a href="#" class="text-gray-400 hover:text-white text-sm mr-6">Privacy Policy</a>
          <a href="#" class="text-gray-400 hover:text-white text-sm mr-6">Terms of Service</a>
          <a href="#" class="text-gray-400 hover:text-white text-sm">Cookie Policy</a>
        </div>
      </div>
    </div>
  </footer>

</body>
</html>