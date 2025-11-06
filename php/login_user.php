<?php 
    session_start();
    require 'dbFiles/database_connection.php';

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $email = $_POST['email'];
        $password = $_POST['password'];
        
     
        $stmt = $conn->prepare("SELECT * FROM users WHERE email = ? AND password = ?");
        $stmt->bind_param("ss", $email, $password);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result->num_rows == 1) {
           
            $user = $result->fetch_assoc();    
            $_SESSION['name'] = $user['name']; 
            $_SESSION['email'] = $user['email'];
            $_SESSION['is_login'] = true;
            $_SESSION['user_type'] = $user['role'];
            $_SESSION['year'] = $user['year'];

            unset($_SESSION['error']);
            header("Location: login_check.php");
            exit;
        } else {
            $_SESSION['error'] = "Invalid email or password.";
            header("Location: ../login.php");
        }

        $stmt->close();
        $conn->close();
    }
?>