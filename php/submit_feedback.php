<?php
session_start();
require 'dbFiles/database_connection.php';

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    die("Invalid request.");
}

$teacher_id = $_SESSION['user_id'];
$course_id  = $_POST['course_id'];
$student_id = $_POST['student_id'];
$progress   = $_POST['progress'];
$feedback   = $_POST['feedback'];


$check = $conn->prepare("
    SELECT id FROM feedback 
    WHERE student_id = ? AND course_id = ?
");
$check->bind_param("ii", $student_id, $course_id);
$check->execute();
$result = $check->get_result();

if ($result->num_rows > 0) {
    $_SESSION['course_error'] = "<span class='text-red-600'>❌ Feedback already exists for this student in this course.</span>";
    header("Location: ../advisor.php");
    exit();
}

$stmt = $conn->prepare("
    INSERT INTO feedback (student_id, teacher_id, progress, feedback, course_id)
    VALUES (?, ?, ?, ?, ?)
");
$stmt->bind_param("iissi", $student_id, $teacher_id, $progress, $feedback, $course_id);

if ($stmt->execute()) {
    $_SESSION['course_success'] = "<span class='text-green-600'>✅ Feedback submitted successfully!</span>";
} else {
    $_SESSION['course_error'] = "<span class='text-red-600'>❌ Error submitting feedback.</span>";
}

header("Location: ../advisor.php");
exit();
