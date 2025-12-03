<!DOCTYPE php>
<php lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>CRAMS | Courses</title>

  <!-- Tailwind + DaisyUI -->
  <link href="https://cdn.jsdelivr.net/npm/daisyui@4.12.23/dist/full.min.css" rel="stylesheet" />
  <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-blue-50 min-h-screen flex flex-col">

  <!-- Common Navbar -->
  <nav class="bg-blue-600 text-white p-4 flex justify-between items-center shadow-md">
    <div class="font-bold text-2xl tracking-wide">CRAMS</div>
    <div class="hidden md:flex space-x-6 text-sm md:text-base">
      <a href="index.php" class="hover:text-gray-200">Home</a>
      <a href="student.php" class="hover:text-gray-200">Student</a>
      <a href="advisor.php" class="hover:text-gray-200">Advisor</a>
      <a href="courses.php" class="hover:text-gray-200 font-semibold underline">Courses</a>
    </div>
  </nav>

  <!-- Main Content -->
  <main class="p-6 max-w-5xl mx-auto flex-1">
    <h2 class="text-3xl font-bold text-blue-700 mb-6 text-center">Available Courses</h2>

    <!-- Search Bar -->
    <div class="flex justify-center mb-6">
      <input id="searchInput" type="text" placeholder="Search by course code or title..."
        class="input input-bordered w-full max-w-md" onkeyup="filterCourses()" />
    </div>

    <!-- Courses Table -->
    <div class="overflow-x-auto bg-white rounded-xl shadow-lg">
      <table class="table w-full" id="courseTable">
        <thead class="bg-blue-100 text-blue-800">
          <tr>
            <th>Course Code</th>
            <th>Title</th>
            <th>Credits</th>
            <th>Year</th>
            <th>Semester</th>
            <th class="text-center">Action</th>
          </tr>
        </thead>
        <tbody>
          <tr>
            <td>CS101</td>
            <td>Intro to Programming</td>
            <td>3</td>
            <td>1st Year</td>
            <td>Fall</td>
            <td class="text-center">
              <button onclick="applyCourse('CS101')" class="btn btn-sm btn-primary">Apply</button>
            </td>
          </tr>
          <tr>
            <td>CS201</td>
            <td>Data Structures</td>
            <td>3</td>
            <td>2nd Year</td>
            <td>Spring</td>
            <td class="text-center">
              <button onclick="applyCourse('CS201')" class="btn btn-sm btn-primary">Apply</button>
            </td>
          </tr>
          <tr>
            <td>CS301</td>
            <td>Database Systems</td>
            <td>3</td>
            <td>3rd Year</td>
            <td>Fall</td>
            <td class="text-center">
              <button onclick="applyCourse('CS301')" class="btn btn-sm btn-primary">Apply</button>
            </td>
          </tr>
          <tr>
            <td>CS401</td>
            <td>Operating Systems</td>
            <td>3</td>
            <td>4th Year</td>
            <td>Spring</td>
            <td class="text-center">
              <button onclick="applyCourse('CS401')" class="btn btn-sm btn-primary">Apply</button>
            </td>
          </tr>
          <tr>
            <td>CS501</td>
            <td>Artificial Intelligence</td>
            <td>3</td>
            <td>4th Year</td>
            <td>Fall</td>
            <td class="text-center">
              <button onclick="applyCourse('CS501')" class="btn btn-sm btn-primary">Apply</button>
            </td>
          </tr>
        </tbody>
      </table>
    </div>

    <!-- Confirmation message -->
    <p id="message" class="text-center mt-6 font-semibold text-lg"></p>
  </main>

  <!--  Footer -->
  <footer class="bg-blue-700 text-white mt-auto">
    <div class="max-w-7xl mx-auto px-6 py-8 grid md:grid-cols-3 gap-8 text-center md:text-left">
      <div>
        <h3 class="text-xl font-bold mb-3">CRAMS</h3>
        <p class="text-sm text-blue-100">
          Course Registration and Advising Management System — designed to simplify academic planning and communication between students and advisors.
        </p>
      </div>
      <div>
        <h4 class="text-lg font-semibold mb-3">Quick Links</h4>
        <ul class="space-y-2 text-sm">
          <li><a href="student.php" class="hover:underline hover:text-gray-200">Student Dashboard</a></li>
          <li><a href="advisor.php" class="hover:underline hover:text-gray-200">Advisor Panel</a></li>
          <li><a href="courses.php" class="hover:underline hover:text-gray-200">Available Courses</a></li>
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
    // Filter courses in search
    function filterCourses() {
      const input = document.getElementById("searchInput").value.toUpperCase();
      const table = document.getElementById("courseTable");
      const rows = table.getElementsByTagName("tr");
      for (let i = 1; i < rows.length; i++) {
        let td = rows[i].getElementsByTagName("td");
        let txtValue = "";
        for (let j = 0; j < td.length - 1; j++) {
          txtValue += td[j].textContent || td[j].innerText;
        }
        rows[i].style.display = txtValue.toUpperCase().includes(input) ? "" : "none";
      }
    }

    // Simulate course apply
    function applyCourse(courseCode) {
      const msg = document.getElementById("message");
      msg.textContent = `✅ You have applied for ${courseCode}. Waiting for advisor approval.`;
      msg.className = "text-green-700 font-semibold mt-6 text-center";
      setTimeout(() => msg.textContent = "", 4000);
    }
  </script>

</body>
</php>
