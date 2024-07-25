<?php
session_start();
require_once 'db_connection.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['name'];
    $password = $_POST['password'];

    // Check in students table
    $stmt = $conn->prepare("SELECT * FROM etudiant WHERE nom = ? AND pass = ?");
    $stmt->bind_param("ss", $name, $password);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows == 1) {
        $row = $result->fetch_assoc();
        $_SESSION['loggedin'] = true;
        $_SESSION['user_name'] = $row['nom'];
        $_SESSION['user_id'] = $row['id']; // Ensure user_id is set in the session
        $_SESSION['role'] = 'student';
        header("Location: s_TodayMenu.php");
        exit();
    }

    // Check in admins table
    $stmt = $conn->prepare("SELECT * FROM admin WHERE nom = ? AND pass = ?");
    $stmt->bind_param("ss", $name, $password);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows == 1) {
        $row = $result->fetch_assoc();
        $_SESSION['loggedin'] = true;
        $_SESSION['user_name'] = $row['nom'];
        $_SESSION['role'] = 'admin';
        header("Location: index.php");
        exit();
    }

    $_SESSION['error'] = "Invalid username or password.";
    header("Location: logout.php");
    exit();
}
?>
