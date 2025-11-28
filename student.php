<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>CRAMS | Student Dashboard</title>
  <link href="https://cdn.jsdelivr.net/npm/daisyui@4.12.23/dist/full.min.css" rel="stylesheet" />
  <script src="https://cdn.tailwindcss.com"></script>
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body class="bg-blue-50 min-h-screen flex flex-col">

  <!-- Navbar -->
  <nav class="bg-blue-600 text-white p-4 flex justify-between items-center shadow-md">
    <div class="font-bold text-2xl tracking-wide">CRAMS</div>
    <div class="hidden md:flex space-x-6 text-sm md:text-base">
      <a href="index.html" class="hover:text-gray-200">Home</a>
      <a href="student.html" class="hover:text-gray-200 underline font-semibold">Student</a>
      <a href="advisor.html" class="hover:text-gray-200">Advisor</a>
      <a href="courses.html" class="hover:text-gray-200">Courses</a>
    </div>
    <div>
      <a href="logout.html" class="btn btn-sm bg-red-500 border-none">Logout</a>
    </div>
  </nav>

  <!-- Dashboard -->
  <main class="p-8 space-y-8 flex-1">
    <h2 class="text-2xl font-semibold text-blue-700 mb-4">Student Dashboard</h2>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
      <div class="bg-white p-6 rounded-xl shadow-lg">
        <h3 class="text-lg font-bold mb-3 text-blue-700">Registered Courses</h3>
        <ul id="registeredList" class="list-disc list-inside text-gray-700">
          <!-- Dynamic content will load here -->
        </ul>
      </div>

      <div class="bg-white p-6 rounded-xl shadow-lg">
        <h3 class="text-lg font-bold mb-3 text-blue-700">Completed Courses</h3>
        <ul id="completedList" class="list-disc list-inside text-gray-700">
          <!-- Dynamic content will load here -->
        </ul>
      </div>
    </div>

    <div class="bg-white p-6 rounded-xl shadow-lg">
      <h3 class="text-lg font-bold mb-3 text-blue-700">Apply for New Course Registration</h3>
      <select id="courseSelect" class="select select-bordered w-full max-w-xs">
        <option disabled selected>Choose a course to apply</option>
        <option>CS501 - Artificial Intelligence</option>
        <option>CS502 - Machine Learning</option>
        <option>CS503 - Computer Networks</option>
      </select>
      <button class="btn btn-primary mt-4" onclick="submitToAdvisor()">Submit to Advisor</button>
      <p id="message" class="mt-4 font-medium"></p>
    </div>

    <div class="bg-white p-4 rounded-xl shadow-lg max-w-xs mx-auto">
      <h3 class="text-md font-bold mb-3 text-blue-700 text-center">Course Completion Overview</h3>
      <div class="w-40 h-40 mx-auto">
        <canvas id="courseChart"></canvas>
      </div>
    </div>
  </main>

  <!-- Footer -->
  <footer class="bg-blue-700 text-white mt-auto">
    <div class="max-w-7xl mx-auto px-6 py-8 grid md:grid-cols-3 gap-8 text-center md:text-left">
      <div>
        <h3 class="text-xl font-bold mb-3">CRAMS</h3>
        <p class="text-sm text-blue-100">Course Registration and Advising Management System — designed to simplify academic planning and communication between students and advisors.</p>
      </div>
      <div>
        <h4 class="text-lg font-semibold mb-3">Quick Links</h4>
        <ul class="space-y-2 text-sm">
          <li><a href="student.html" class="hover:underline hover:text-gray-200">Student Dashboard</a></li>
          <li><a href="advisor.html" class="hover:underline hover:text-gray-200">Advisor Panel</a></li>
          <li><a href="courses.html" class="hover:underline hover:text-gray-200">Available Courses</a></li>
        </ul>
      </div>
      <div>
        <h4 class="text-lg font-semibold mb-3">Contact Us</h4>
        <p class="text-sm text-blue-100">Email: support@crams.edu</p>
        <p class="text-sm text-blue-100">Phone: +880 1234 567890</p>
        <p class="text-sm text-blue-100">Dhaka, Bangladesh</p>
      </div>
    </div>
    <div class="bg-blue-800 py-3 text-center text-sm text-blue-200">
      © 2025 CRAMS — All Rights Reserved
    </div>
  </footer>

  <script>
    // Example JS for frontend-only demo
    function loadDashboardData() {
      // Dummy data for frontend demo
      const registered = [
        { course_code: "CS501", course_name: "Artificial Intelligence" },
        { course_code: "CS502", course_name: "Machine Learning" }
      ];
      const completed = [
        { course_code: "CS500", course_name: "Intro to CS" }
      ];
      const pending = [
        { course_code: "CS503", course_name: "Computer Networks" }
      ];

      const regList = document.getElementById("registeredList");
      regList.innerHTML = "";
      registered.forEach(course => {
        regList.innerHTML += `<li>${course.course_code} - ${course.course_name}</li>`;
      });

      const compList = document.getElementById("completedList");
      compList.innerHTML = "";
      completed.forEach(course => {
        compList.innerHTML += `<li>${course.course_code} - ${course.course_name}</li>`;
      });

      const completedCount = completed.length;
      const registeredCount = registered.length;
      const pendingCount = pending.length;

      const ctx = document.getElementById('courseChart');
      new Chart(ctx, {
        type: 'doughnut',
        data: {
          labels: ['Completed', 'Pending', 'Approved'],
          datasets: [{
            data: [completedCount, pendingCount, registeredCount - completedCount],
            backgroundColor: ['#3b82f6', '#fbbf24', '#94a3b8']
          }]
        },
        options: {
          cutout: '65%',
          plugins: { legend: { display: false } }
        }
      });
    }

    document.addEventListener("DOMContentLoaded", loadDashboardData);

    function submitToAdvisor() {
      const course = document.getElementById("courseSelect").value;
      const msg = document.getElementById("message");
      if(!course){
        msg.textContent = "Please select a course to submit.";
        msg.className = "text-red-600 font-semibold mt-4";
        return;
      }
      msg.textContent = `Request for "${course}" sent to Advisor for approval.`;
      msg.className = "text-green-600 font-semibold mt-4";
    }
  </script>

</body>
</html>
