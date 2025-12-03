<?php
session_start();
require 'dbFiles/database_connection.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $email = trim($_POST['email']);
    $password = trim($_POST['password']);

    $stmt = $conn->prepare("SELECT * FROM users WHERE Email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();

    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        
        $user = $result->fetch_assoc();

        if (password_verify($password, $user['password'])) {

            $_SESSION['name'] = $user['name'];
            $_SESSION['email'] = $user['Email'];
            $_SESSION['is_login'] = true;
            $_SESSION['user_type'] = $user['role'];
            $_SESSION['year'] = $user['year'];
            $_SESSION['user_id'] = $user['userId'];
            $_SESSION['user_department'] = $user['department'];

            unset($_SESSION['error']);
            header("Location: login_check.php");
            exit;

        } else {
            $_SESSION['error'] = "Invalid email or password.";
            header("Location: ../login.php");
            exit;
        }

    } else {
        $_SESSION['error'] = "Invalid email or password.";
        header("Location: ../login.php");
        exit;
    }

    $stmt->close();
    $conn->close();
}
?>
