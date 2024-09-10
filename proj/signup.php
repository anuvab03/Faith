<?php
session_start();
require_once "controllerUserData.php";

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['register'])) {
    // Handle registration form submission
    $name = $_POST['name'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm-password'];
    
    // Your registration validation logic goes here
    // Assuming registration is successful
    
    // You may choose to display a success message or redirect to a different page
    // For example, redirect to a welcome page
    header('Location: http://127.0.0.1:5000');
    exit();
}
?>
