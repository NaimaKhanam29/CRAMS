<?php
session_start();
require 'dbFiles/database_connection.php';

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    die("Invalid request");
}

$course_id   = $_POST['course_id'] ?? null;
$student_ids = $_POST['student_ids'] ?? [];

if (!$course_id) {
    $_SESSION['course_error'] = "No course selected!";
    header("Location: ../assign_course_view.php");
    exit;
}

if (empty($student_ids)) {
    $_SESSION['course_error'] = "No students selected!";
    header("Location: ../assign_course_view.php");
    exit;
}

$successCount = 0;

// Insert query (status will be 1)
$insert = $conn->prepare("
    INSERT INTO student_courses_table (student_id, course_id, status)
    VALUES (?, ?, 1)
");

foreach ($student_ids as $sid) {

    // Check duplicate assignment
    $check = $conn->prepare("
        SELECT id 
        FROM student_courses_table 
        WHERE student_id = ? AND course_id = ?
    ");
    $check->bind_param("ii", $sid, $course_id);
    $check->execute();

    $res = $check->get_result();

    // If already assigned → skip
    if ($res->num_rows === 0) {
        $insert->bind_param("ii", $sid, $course_id);
        if ($insert->execute()) {
            $successCount++;
        }
    }

    $check->close();
}

$insert->close();
$conn->close();

$_SESSION['course_success'] = "$successCount student(s) assigned successfully!";
header("Location: ../advisor.php");
exit;
?>
