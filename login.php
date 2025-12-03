<?php
  session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>CRAMS | Login</title>

  <!-- Tailwind & DaisyUI -->
  <link href="https://cdn.jsdelivr.net/npm/daisyui@4.12.23/dist/full.min.css" rel="stylesheet" type="text/css" />
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gradient-to-br from-blue-50 to-blue-100 min-h-screen flex flex-col">

  <!-- Navbar -->
  <nav class="bg-blue-600 text-white p-4 flex justify-between items-center shadow-md">
    <div class="font-bold text-2xl tracking-wide">CRAMS</div>
    <div class="flex space-x-6 text-sm md:text-base">
      <a href="index.php" class="hover:text-gray-300">Home</a>
      <a href="advisor.php" class="hover:text-gray-300">Advisor</a>
      <a href="courses.php" class="hover:text-gray-300">Courses</a>
    </div>
    <div class="flex space-x-4 text-sm md:text-base">
      <a href="login.php" class="hover:text-gray-300 font-semibold underline">Login</a>
      <a href="register.php" class="hover:text-gray-300">Register</a>
    </div>
  </nav>

  <!-- Login Form Section -->
  <div class="flex flex-1 justify-center items-center px-4">
    <div class="bg-white shadow-2xl rounded-2xl p-8 w-full max-w-md border border-blue-100">
      <h1 class="text-3xl font-bold text-center text-blue-700 mb-2">Welcome Back</h1>
      <p class="text-center text-gray-500 mb-6">Login to your CRAMS account</p>

      <form action="php/login_user.php" class="space-y-5" method="post">
        <div>
          <label class="block text-gray-700 font-medium mb-2">Email</label>
          <input type="email" name="email" id="userid" class="input input-bordered w-full focus:outline-none focus:ring-2 focus:ring-blue-400" placeholder="e.g. jhon@exm.com" required>
        </div>

        <div>
          <label class="block text-gray-700 font-medium mb-2">Password</label>
          <input type="password" name="password" id="password" class="input input-bordered w-full focus:outline-none focus:ring-2 focus:ring-blue-400" placeholder="Enter password" required>
        </div>

        <button type="submit" class="btn btn-primary w-full text-lg font-semibold">Login</button>
      </form>

      <p class="text-sm text-center text-gray-500 mt-6">
        Course Registration and Advising Management System
      </p>
    </div>
  </div>
</body>
</html>
