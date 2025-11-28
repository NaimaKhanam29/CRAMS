<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>CRAMS | Register</title>

  <!-- DaisyUI + Tailwind (okay for development) -->
  <link href="https://cdn.jsdelivr.net/npm/daisyui@4.12.23/dist/full.min.css" rel="stylesheet" />
  <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gradient-to-br from-blue-50 to-blue-100 min-h-screen flex flex-col items-center justify-center">

  <div class="bg-white shadow-xl rounded-xl w-full max-w-md p-8">
    <h2 class="text-3xl font-bold text-center text-blue-700 mb-6">Create an Account</h2>

    <!-- IMPORTANT: id="registerForm" added -->
    <form id="registerForm" action="php/register_user.php" method="POST" class="space-y-4">

      <div>
        <label class="block font-semibold mb-1">Full Name</label>
        <input type="text" name="name" placeholder="Enter your full name" required class="input input-bordered w-full" />
      </div>

      <div>
        <label class="block font-semibold mb-1">Email</label>
        <input type="email" name="email" placeholder="Enter your email" required class="input input-bordered w-full" />
      </div>

      <div>
        <label class="block font-semibold mb-1">Password</label>
        <input type="password" name="password" placeholder="Enter your password" required class="input input-bordered w-full" />
      </div>

      <div>
        <label class="block font-semibold mb-1">Register As</label>
        <select id="role" name="role" required class="select select-bordered w-full">
          <option value="" disabled selected>Select your role</option>
          <option value="student">Student</option>
          <option value="advisor">Advisor</option>
        </select>
      </div>

      <!-- Student Fields -->
      <div id="studentFields" class="hidden space-y-4">
        <div>
          <label class="block font-semibold mb-1">Major</label>
          <input type="text" name="major" placeholder="Enter your major" class="input input-bordered w-full" />
        </div>
        <div>
          <label class="block font-semibold mb-1">Year</label>
          <select name="year" class="select select-bordered w-full">
            <option value="" disabled selected>Select year</option>
            <option>1st Year</option>
            <option>2nd Year</option>
            <option>3rd Year</option>
            <option>4th Year</option>
          </select>
        </div>
      </div>

      <!-- Advisor Fields -->
      <div id="advisorFields" class="hidden space-y-4">
        <div>
          <label class="block font-semibold mb-1">Department</label>
          <input type="text" name="department" placeholder="Enter your department" class="input input-bordered w-full" />
        </div>
      </div>

      <button type="submit" class="btn btn-primary w-full mt-4">Register</button>
    </form>

    <p id="message" class="text-center mt-4 font-medium"></p>
  </div>

  <script>
    const roleSelect = document.getElementById("role");
    const studentFields = document.getElementById("studentFields");
    const advisorFields = document.getElementById("advisorFields");
    const message = document.getElementById("message");

    roleSelect.addEventListener("change", () => {
      studentFields.classList.toggle("hidden", roleSelect.value !== "student");
      advisorFields.classList.toggle("hidden", roleSelect.value !== "advisor");
    });

    document.getElementById("registerForm").addEventListener("submit", async (e) => {
      e.preventDefault();
      message.textContent = "";
      message.className = "text-center mt-4";

      const form = e.target;
      const formData = new FormData(form);

      if (!formData.get("role")) {
        message.textContent = "Please select your role.";
        message.classList.add("text-red-600", "font-semibold");
        return;
      }

      const submitBtn = form.querySelector("button[type='submit']");
      submitBtn.disabled = true;
      submitBtn.classList.add("opacity-50");

      try {
        const res = await fetch(form.action, {
          method: "POST",
          body: formData,
        });

        const data = await res.json();

        if (data.success) {
          message.textContent = data.message || "Registered successfully! Redirecting...";
          message.classList.add("text-green-600", "font-semibold");

          setTimeout(() => {
            window.location.href = "login.php";
          }, 1400);
        } else {
          message.textContent = data.message || "Registration failed.";
          message.classList.add("text-red-600", "font-semibold");
        }
      } catch (err) {
        console.error(err);
        message.textContent = "Network/server error. Check console & server logs.";
        message.classList.add("text-red-600", "font-semibold");
      } finally {
        submitBtn.disabled = false;
        submitBtn.classList.remove("opacity-50");
      }
    });
  </script>

</body>
</html>
