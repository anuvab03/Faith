<?php
session_start();
require_once "controllerUserData.php";

if (isset($_POST['login'])) {
    $email = mysqli_real_escape_string($con, $_POST['email']);
    $password = mysqli_real_escape_string($con, $_POST['password']);

    $check_email = "SELECT * FROM usertable WHERE email = '$email'";
    $res = mysqli_query($con, $check_email);

    if (mysqli_num_rows($res) > 0) {
        $fetch = mysqli_fetch_assoc($res);
        $fetch_pass = $fetch['password'];

        if (password_verify($password, $fetch_pass)) {
            $_SESSION['email'] = $email;
            header('Location:http://127.0.0.1:5000'); // Redirect to the Flask server
            exit(); // Make sure to call exit to stop further execution
        } else {
            $errors['login'] = "Incorrect email or password!";
        }
    } else {
        $errors['login'] = "It looks like you're not yet a member! Click on the bottom link to sign up.";
    }
}
?>

<!-- Include your login form here -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
</head>
<body>
    <form action="your_login_script.php" method="post">
        <input type="email" name="email" placeholder="Email" required>
        <input type="password" name="password" placeholder="Password" required>
        <button type="submit" name="login">Login</button>
    </form>
    <?php
    if (isset($errors['login'])) {
        echo '<p>' . $errors['login'] . '</p>';
    }
    ?>
</body>
</html>
