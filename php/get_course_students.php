<?php
header("Content-Type: application/json");
require 'dbFiles/database_connection.php';

$course_id = $_GET['course_id'] ?? null;

if (!$course_id) {
    echo json_encode([]);
    exit;
}

$sql = $conn->prepare("SELECT DISTINCT student_id FROM student_courses_table WHERE course_id = ?");
$sql->bind_param("i", $course_id);
$sql->execute();
$res = $sql->get_result();

$studentIds = [];
while ($row = $res->fetch_assoc()) {
    $studentIds[] = $row['student_id'];
}
$sql->close();

if (empty($studentIds)) {
    echo json_encode([]);
    exit;
}

$ids = implode(",", $studentIds); 

$userQuery = "SELECT userId, name, Email FROM users WHERE userId IN ($ids)";
$resultUsers = $conn->query($userQuery);

$students = [];
while ($u = $resultUsers->fetch_assoc()) {
    $students[] = $u;
}

echo json_encode($students);
$conn->close();
?>
