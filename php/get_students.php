<?php
require 'dbFiles/database_connection.php';
$year = $_GET['year'] ?? '';
$department = $_GET['department'] ?? '';

$stmt = $conn->prepare("SELECT userId, name, Email FROM users WHERE role='student' AND year=? AND department=?");
$stmt->bind_param("ss", $year, $department);
$stmt->execute();
$result = $stmt->get_result();

$students = [];
while($row = $result->fetch_assoc()){
    $students[] = $row;
}

echo json_encode($students);
?>
