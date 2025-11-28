<?php
header('Content-Type: application/json');

require_once __DIR__ . '/dbFiles/database_connection.php';

// Retrieve POST data
$name = $_POST['name'] ?? '';
$email = $_POST['email'] ?? '';
$password = $_POST['password'] ?? '';
$role = $_POST['role'] ?? '';
$major = $_POST['major'] ?? null;
$year = $_POST['year'] ?? null;
$department = $_POST['department'] ?? null;

if (empty($name) || empty($email) || empty($password) || empty($role)) {
    echo json_encode(["success" => false, "message" => "All fields are required."]);
    exit;
}

// Check if email already exists (DB column is Email with capital E)
$stmt = $conn->prepare("SELECT userId FROM users WHERE Email = ?");
$stmt->bind_param("s", $email);
$stmt->execute();
$stmt->store_result();

if ($stmt->num_rows > 0) {
    echo json_encode(["success" => false, "message" => "Email already exists."]);
    exit;
}

$stmt->close();

// Hash password (DB column name is password)
$hashed_password = password_hash($password, PASSWORD_DEFAULT);

// Correct INSERT query
$stmt = $conn->prepare("
    INSERT INTO users (name, Email, password, role, major, year, department)
    VALUES (?, ?, ?, ?, ?, ?, ?)
");

$stmt->bind_param("sssssss", $name, $email, $hashed_password, $role, $major, $year, $department);

if ($stmt->execute()) {
    echo json_encode(["success" => true, "message" => "Registration successful!"]);
} else {
    echo json_encode(["success" => false, "message" => "Error: " . $stmt->error]);
}

$stmt->close();
$conn->close();
?>
