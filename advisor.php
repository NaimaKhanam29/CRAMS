<?php  
    session_start();
    if(isset($_SESSION['user_type']) && $_SESSION['user_type'] != 'advisor'){
        header("Location: login.php");
    }
    require 'php/dbFiles/database_connection.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>CRAMS | Advisor Panel</title>
  <link href="https://cdn.jsdelivr.net/npm/daisyui@4.12.23/dist/full.min.css" rel="stylesheet" />
  <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-blue-50 min-h-screen flex flex-col">

  <!-- Navbar -->
  <nav class="bg-blue-600 text-white p-4 flex justify-between items-center shadow-md">
    <div class="font-bold text-2xl tracking-wide">CRAMS</div>
    <div class="hidden md:flex space-x-6 text-sm md:text-base">
      <a href="index.php" class="hover:text-gray-200">Home</a>
      <a href="student.php" class="hover:text-gray-200">Student</a>
      <a href="advisor.php" class="hover:text-gray-200 underline font-semibold">Advisor</a>
      <a href="courses.php" class="hover:text-gray-200">Courses</a>
    </div>
    <div>
      <a href="php/logout.php" class="btn btn-sm bg-red-500 border-none">Logout</a>
    </div>
  </nav>

  <!-- Advisor Dashboard -->
  <main class="p-8 space-y-8 flex-1">

    <h2 class="text-2xl font-semibold text-blue-700 mb-4">Welcome, Advisor</h2>

    <?php if(isset($_SESSION['course_success'])): ?>
      <div id="successToast" class="toast toast-top toast-end">
        <div class="alert alert-success shadow-lg">
          <div>
            <span>✅ <?php echo $_SESSION['course_success']; ?></span>
          </div>
        </div>
      </div>
      <?php unset($_SESSION['course_success']); ?>
    <?php endif; ?>

    <?php if(isset($_SESSION['course_error'])): ?>
      <div id="errorToast" class="toast toast-top toast-end">
        <div class="alert alert-error shadow-lg">
          <div>
            <span>❌ <?php echo $_SESSION['course_error']; ?></span>
          </div>
        </div>
      </div>
      <?php unset($_SESSION['course_error']); ?>
    <?php endif; ?>


    <!-- TABS -->
    <div role="tablist" class="tabs tabs-boxed bg-white p-2 rounded-xl shadow-md">
      <a role="tab" class="tab tab-active" onclick="switchTab(event, 'pendingTab')">Pending Course Requests</a>
      <a role="tab" class="tab" onclick="switchTab(event, 'studentsTab')">Course Students</a>
      <a role="tab" class="tab" onclick="switchTab(event, 'createTab')">Create Course</a>
      <a role="tab" class="tab" onclick="switchTab(event, 'assignTab')">Assign Course View</a>
    </div>

    <!-- TAB CONTENT WRAPPER -->
    <div class="mt-6">

      <!-- TAB 1: Pending Course Requests -->
      <div id="pendingTab" class="tabContent bg-white p-6 rounded-xl shadow-lg">

        <h3 class="text-lg font-bold text-blue-700 mb-4">📋 Pending Course Requests</h3>

        <table class="table w-full table-auto mx-auto text-center">
          <thead>
            <tr class="bg-blue-100 text-blue-700">
              <th>Student Name</th>
              <th>Requested Course</th>
              <th>Status</th>
              <th>Action</th>
            </tr>
          </thead>

          <tbody id="requestTable">
            <!-- SAMPLE DATA -->
            <tr>
              <td>Sample Student</td>
              <td>CS501 - AI</td>
              <td><span class="text-yellow-600 font-semibold">Pending</span></td>
              <td>
                <button class="btn btn-success btn-sm" onclick="approveRequest(this)">Approve</button>
                <button class="btn btn-error btn-sm" onclick="rejectRequest(this)">Reject</button>
              </td>
            </tr>
          </tbody>
        </table>

      </div>

      <!-- TAB 2: Course Students -->
      <div id="studentsTab" class="tabContent hidden bg-white p-6 rounded-xl shadow-lg">
        <h3 class="text-lg font-bold text-blue-700 mb-4">🎓 Course Students</h3>
      </div>

      <!-- TAB 3: Create Course -->
      <div id="createTab" class="tabContent hidden bg-white p-6 rounded-xl shadow-lg">
        <h3 class="text-lg font-bold text-blue-700 mb-4">📘 Create New Course</h3>

        <form class="space-y-4" action="php/course_create.php" class="space-y-5" method="post">
          <input class="input input-bordered w-full" placeholder="Course Title" name="course_name">
          <input class="input input-bordered w-full" placeholder="Course Code (e.g., CS501)" name="course_code">
          <input class="input input-bordered w-full" placeholder="Credit (e.g., 3)" name="course_credit">
          <select name="year" class="select select-bordered w-full" name="course_year">
            <option disabled selected>Select Course Year</option>
            <option value="1st Year">1st Year</option>
            <option value="2st Year">2nd Year</option>
            <option value="3st Year">3rd Year</option>
            <option value="4st Year">4th Year</option>
          </select>

          <button class="btn btn-primary w-full">Create Course</button>
        </form>
      </div>

      <!-- TAB 4: Assign Course View -->
      <div id="assignTab" class="tabContent hidden bg-white p-6 rounded-xl shadow-lg">
        <h3 class="text-lg font-bold text-blue-700 mb-4">📌 Assign Course View</h3>
       <?php
          $course_options = "";
          $stmt = $conn->prepare("SELECT * FROM courses WHERE course_teacher_id = ?");
          $stmt->bind_param("i", $_SESSION['user_id']);
          $stmt->execute();
          $result = $stmt->get_result();

          if($result->num_rows > 0){
              while($row = $result->fetch_assoc()){
                  $course_options .= "<option value='".$row['id']."' data-year='".$row['course_year']."' data-dept='".$row['course_department']."'>".$row['course_name']." (".$row['course_code'].")</option>";
              }
          } else {
              $course_options = "<option disabled>No courses available</option>";
          }
        ?>


      <form method="post" action="php/assign_course_students.php" class="space-y-4">
          <label class="font-semibold">Select Course</label>
          <select id="courseSelect" name="course_id" class="select select-bordered w-full">
            <option disabled selected>Choose a course</option>
            <?php echo $course_options; ?>
          </select>
          <div id="studentsContainer" class="mt-4"></div>

          <button type="submit" class="btn btn-primary w-full mt-4">Assign Selected Students</button>
      </form>


      </div>

      <!-- Student Feedback Form (Always Visible Below Tabs) -->
      <div class="bg-white p-6 rounded-xl shadow-lg mt-6">
        <h3 class="text-lg font-bold mb-4 text-blue-700">📊 Student Progress Feedback</h3>
        <form method="post" action="php/submit_feedback.php" >
          <div class="mt-4">
            <label class="font-semibold">Select Course</label>
            <select id="courseSelectFeedback" name="course_id" class="select select-bordered w-full">
              <option disabled selected>Choose a course</option>
              <?php echo $course_options; ?>
            </select>
            </div>
            
            <div class="mt-4">
              <label class="font-semibold">Select Student</label>
              <select id="student_id" name="student_id" class="select select-bordered w-full" >
                
              </select>
            </div>

              <div class="mt-4">
                <label class="font-semibold">Overall Progress</label>
                <select name="progress" class="select select-bordered w-full">
                  <option disabled selected>Select progress level</option>
                  <option>Excellent</option>
                  <option>Good</option>
                  <option>Average</option>
                  <option>Needs Improvement</option>
                </select>
              </div>
           

          <div class="mt-4">
            <label class="font-semibold">Feedback Comments</label>
            <textarea id="feedbackText" class="textarea textarea-bordered w-full" 
                      placeholder="Write advisor feedback..." name="feedback"></textarea>
          </div>

          <button type="submit" class="btn btn-primary mt-4">Submit Feedback</button>

          <p id="feedbackMessage" class="mt-3 font-medium"></p>
        </form>
         </div>
      </div>

    </div>
  </main>

  <!-- Footer -->
  <footer class="bg-blue-700 text-white mt-auto">
    <div class="max-w-7xl mx-auto px-6 py-8 grid md:grid-cols-3 gap-8 text-center md:text-left">
      <div>
        <h3 class="text-xl font-bold mb-3">CRAMS</h3>
        <p class="text-sm text-blue-100">
          Course Registration and Advising Management System — designed to simplify academic planning.
        </p>
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

  <!-- JS -->
  <script>
    function switchTab(event, tabId) {
      document.querySelectorAll(".tab").forEach(t => t.classList.remove("tab-active"));
      event.target.classList.add("tab-active");

      document.querySelectorAll(".tabContent").forEach(c => c.classList.add("hidden"));
      document.getElementById(tabId).classList.remove("hidden");
    }

    function approveRequest(button) {
      const row = button.closest('tr');
      const statusCell = row.querySelector('td:nth-child(3)');
      statusCell.innerHTML = '<span class="text-green-600 font-semibold">Approved</span>';
      button.parentElement.innerHTML = '<span class="text-green-700 font-semibold">Approved ✔</span>';
    }

    function rejectRequest(button) {
      const row = button.closest('tr');
      const statusCell = row.querySelector('td:nth-child(3)');
      statusCell.innerHTML = '<span class="text-red-600 font-semibold">Rejected</span>';
      button.parentElement.innerHTML = '<span class="text-red-700 font-semibold">Rejected ✖</span>';
    }

    function giveFeedback(event) {
      event.preventDefault();
      const student = document.getElementById("studentSelect").value;
      const progress = document.getElementById("progressSelect").value;
      const feedback = document.getElementById("feedbackText").value;
      const message = document.getElementById("feedbackMessage");

      if (!student || !progress || !feedback.trim()) {
        message.textContent = "Please fill all fields before submitting feedback.";
        message.className = "text-red-600 font-semibold mt-3";
        return;
      }

      message.textContent = `Feedback submitted for ${student} (${progress} progress).`;
      message.className = "text-green-600 font-semibold mt-3";
      document.getElementById("feedbackForm").reset();
    }
  </script>

  <script>
    setTimeout(() => {
      const successToast = document.getElementById('successToast');
      if(successToast) successToast.remove();

      const errorToast = document.getElementById('errorToast');
      if(errorToast) errorToast.remove();
    }, 4000); // 4 seconds
  </script>


<script>
    document.getElementById('courseSelect').addEventListener('change', function() {
    const select = this;
    const courseId = select.value;
    const courseYear = select.selectedOptions[0].dataset.year;
    const courseDept = select.selectedOptions[0].dataset.dept;

    fetch(`php/get_students.php?year=${courseYear}&department=${courseDept}`)
    .then(res => res.json())
    .then(data => {
        let html = "";

        if(data.length > 0){
            html += `
            <table class="table w-full text-black">
                <thead class="bg-gray-200 text-black">
                    <tr>
                        <th class="border border-gray-300">Select</th>
                        <th class="border border-gray-300">Student Name</th>
                        <th class="border border-gray-300">Email</th>
                    </tr>
                </thead>
                <tbody>
            `;

            data.forEach(student => {
                html += `
                <tr class="hover:bg-gray-100">
                    <td class="border border-gray-300 p-2">
                        <input type="checkbox" 
                               name="student_ids[]" 
                               value="${student.userId}" 
                               class="checkbox checkbox-sm border-2 border-black">
                    </td>
                    <td class="border border-gray-300 p-2">${student.name}</td>
                    <td class="border border-gray-300 p-2">${student.Email}</td>
                </tr>
                `;
            });

            html += `</tbody></table>`;

        } else {
            html = "<p class='text-red-600 font-semibold'>No students found in this department/year.</p>";
        }

        document.getElementById('studentsContainer').innerHTML = html;
    })
    .catch(err => console.error(err));
});
</script>

<script>
    document.getElementById('courseSelectFeedback').addEventListener('change', function () {

    const courseId = this.value;

    fetch(`php/get_course_students.php?course_id=${courseId}`)
    .then(res => res.json())
    .then(data => {
        const studentSelect = document.getElementById('student_id');
        studentSelect.innerHTML = "";

        if (data.length === 0) {
            studentSelect.innerHTML = `<option disabled>No students assigned</option>`;
            return;
        }

        let options = `<option disabled selected>Select a student</option>`;

        data.forEach(std => {
            options += `<option value="${std.userId}">${std.name} (${std.Email})</option>`;
        });

        studentSelect.innerHTML = options;
    })
    .catch(err => console.error(err));

});
</script>

<script>
function giveFeedback(e) {
    e.preventDefault();

    const formData = new FormData(document.getElementById("feedbackForm"));

    fetch("php/submit_feedback.php", {
        method: "POST",
        body: formData
    })
    .then(res => res.json())
    .then(data => {
        const msg = document.getElementById("feedbackMessage");

        if (data.success) {
            msg.innerHTML = data.message;
            msg.className = "text-green-600 font-semibold";
            document.getElementById("feedbackForm").reset();
        } else {
            msg.innerHTML = data.message;
            msg.className = "text-red-600 font-semibold";
        }
    })
    .catch(err => console.error(err));
}
</script>


</body>
</html>
