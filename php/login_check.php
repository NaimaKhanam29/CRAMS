<?php
session_start();

// Get base URL dynamically
$protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? "https://" : "http://";
$baseURL = $protocol . $_SERVER['HTTP_HOST'] . "/CRAMS";

// Check login
if (!isset($_SESSION['is_login']) || $_SESSION['is_login'] !== true) {
    header("Location: {$baseURL}/login.php");
    exit;
}

// Check user type and redirect accordingly
if (isset($_SESSION['user_type'])) {
    if ($_SESSION['user_type'] == 'student') {
        header("Location: {$baseURL}/student.php");
        exit;
    } elseif ($_SESSION['user_type'] == 'advisor') {
        header("Location: {$baseURL}/advisor.php");
        exit;
    }else{
        echo "bad req";
    }
}
?>