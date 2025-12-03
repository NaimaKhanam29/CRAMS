<?php
session_start();

if (!isset($_SESSION['user_type']) || $_SESSION['user_type'] != 'advisor') {
    header("Location: ../login.php");
    exit();
}

require 'dbFiles/database_connection.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $course_name = trim($_POST['course_name']);
    $course_code = trim($_POST['course_code']);
    $course_credit = trim($_POST['course_credit']);
    $course_year = trim($_POST['year']);
    
    $course_teacher_id = $_SESSION['user_id']; 
    $course_department = $_SESSION['user_department'];
    

    if (empty($course_name) || empty($course_code) || empty($course_credit) || empty($course_year)) {
        $_SESSION['course_error'] = "All fields are required!";
        header("Location: ../advisor.php");
        exit();
    }

    $stmt = $conn->prepare("INSERT INTO courses (course_name, course_code, course_credit, course_year, course_teacher_id,course_department) VALUES (?, ?, ?, ?, ?,?)");
    $stmt->bind_param("ssisis", $course_name, $course_code, $course_credit, $course_year, $course_teacher_id,$course_department);

    if ($stmt->execute()) {
        $_SESSION['course_success'] = "Course '$course_name' created successfully!";
        header("Location: ../advisor.php");
        exit();
    } else {
        $_SESSION['course_error'] = "Database error: " . $stmt->error;
        header("Location: ../advisor.php");
        exit();
    }

    $stmt->close();
    $conn->close();
} else {
    header("Location: ../advisor.php");
    exit();
}
?>
