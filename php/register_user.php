<?php
        header("Content-Type: application/json");

        require 'dbFiles/database_connection.php';

        if ($_SERVER["REQUEST_METHOD"] !== "POST") {
            echo json_encode(["success" => false, "message" => "Invalid request"]);
            exit;
        }

        $name       = trim($_POST['name'] ?? '');
        $email      = trim($_POST['email'] ?? '');
        $password   = trim($_POST['password'] ?? '');
        $role       = trim($_POST['role'] ?? '');
        $year       = trim($_POST['year'] ?? null);
        $department = trim($_POST['department'] ?? null);

        if ($name === "" || $email === "" || $password === "" || $role === "" || $department === "") {
            echo json_encode(["success" => false, "message" => "Please fill all required fields"]);
            exit;
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            echo json_encode(["success" => false, "message" => "Invalid email"]);
            exit;
        }

        $hash = password_hash($password, PASSWORD_BCRYPT);

        // Check if email already exists
        $check = $conn->prepare("SELECT userId FROM users WHERE Email = ?");
        $check->bind_param("s", $email);
        $check->execute();
        $res = $check->get_result();

        if ($res->num_rows > 0) {
            echo json_encode(["success" => false, "message" => "Email already exists"]);
            exit;
        }
        $check->close();

        // Insert user without 'major'
        $query = "
            INSERT INTO users (name, Email, password, role, year, department)
            VALUES (?, ?, ?, ?, ?, ?)
        ";

        $stmt = $conn->prepare($query);
        $stmt->bind_param(
            "ssssss",
            $name,
            $email,
            $hash,
            $role,
            $year,
            $department
        );

        if ($stmt->execute()) {
            echo json_encode([
                "success" => true,
                "message" => "User registered successfully!"
            ]);
        } else {
            echo json_encode([
                "success" => false,
                "message" => "Database error: " . $stmt->error
            ]);
        }

        $stmt->close();
        $conn->close();
?>
