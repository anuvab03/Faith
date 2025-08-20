<?php
session_start();
require "db.php"; // now db.php uses pg_connect()
$email = "";
$name = "";
$errors = array();

// USER SIGNUP
if (isset($_POST['register'])) {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm-password'];

    if ($password !== $confirm_password) {
        $errors['password'] = "Confirm password not matched!";
    }

    // check if email already exists
    $res = pg_query_params($con, "SELECT * FROM usertable WHERE email = $1", array($email));
    if (pg_num_rows($res) > 0) {
        $errors['email'] = "Email that you have entered already exists!";
    }

    if (count($errors) === 0) {
        $encpass = password_hash($password, PASSWORD_BCRYPT);
        $code = rand(111111, 999999);
        $status = "notverified";

        $insert_query = "INSERT INTO usertable (name, email, password, code, status) VALUES ($1,$2,$3,$4,$5)";
        $data_check = pg_query_params($con, $insert_query, array($name, $email, $encpass, $code, $status));

        if ($data_check) {
            $subject = "Email Verification Code";
            $message = "Your verification code is $code";
            $sender = "From: shaarmilam6@gmail.com";

            if (mail($email, $subject, $message, $sender)) {
                $_SESSION['info'] = "We've sent a verification code to your email - $email";
                $_SESSION['email'] = $email;
                $_SESSION['password'] = $password;
                header('location: user-otp.php');
                exit();
            } else {
                $errors['otp-error'] = "Failed while sending code!";
            }
        } else {
            $errors['db-error'] = "Failed while inserting data into database!";
        }
    }
}

// VERIFY OTP
if (isset($_POST['verify-otp'])) {
    $_SESSION['info'] = "";
    $otp = $_POST['otp'];

    $res = pg_query_params($con, "SELECT * FROM usertable WHERE code = $1", array($otp));
    if (pg_num_rows($res) > 0) {
        $fetch_data = pg_fetch_assoc($res);
        $email = $fetch_data['email'];

        $update_res = pg_query_params($con,
            "UPDATE usertable SET code = $1, status = $2 WHERE email = $3",
            array(0, 'verified', $email)
        );

        if ($update_res) {
            $_SESSION['name'] = $fetch_data['name'];
            $_SESSION['email'] = $email;
            header('location: http://127.0.0.1:5000');
            exit();
        } else {
            $errors['otp-error'] = "Failed while updating code!";
        }
    } else {
        $errors['otp-error'] = "Invalid OTP!";
    }
}

// LOGIN
if (isset($_POST['login'])) {
    $email = $_POST['email'];
    $password = $_POST['password'];

    $res = pg_query_params($con, "SELECT * FROM usertable WHERE email = $1", array($email));
    if (pg_num_rows($res) > 0) {
        $fetch = pg_fetch_assoc($res);
        if (password_verify($password, $fetch['password'])) {
            $_SESSION['email'] = $email;

            if ($fetch['status'] == 'verified') {
                $_SESSION['name'] = $fetch['name'];
                header('location: http://127.0.0.1:5000');
            } else {
                $_SESSION['info'] = "It looks like you haven't verified your email - $email";
                header('location: user-otp.php');
            }
        } else {
            $errors['email'] = "Incorrect email or password!";
        }
    } else {
        $errors['email'] = "You are not registered yet!";
    }
}

// PASSWORD RESET - CONTINUE
if (isset($_POST['continue'])) {
    $email = $_POST['email'];

    $res = pg_query_params($con, "SELECT * FROM usertable WHERE email=$1", array($email));
    if (pg_num_rows($res) > 0) {
        $_SESSION['email'] = $email;
        header('location: new-pass.php');
        exit();
    } else {
        $errors['email'] = "This email address does not exist!";
    }
}

// CHANGE PASSWORD
if (isset($_POST['change-password'])) {
    $_SESSION['info'] = "";
    $new_password = $_POST['new-password'];
    $confirm_password = $_POST['confirm-password'];

    if ($new_password !== $confirm_password) {
        $errors['password'] = "Confirm password not matched!";
    } else {
        $email = $_SESSION['email'];
        $encpass = password_hash($new_password, PASSWORD_BCRYPT);

        $update_res = pg_query_params($con,
            "UPDATE usertable SET password = $1 WHERE email = $2",
            array($encpass, $email)
        );

        if ($update_res) {
            $_SESSION['info'] = "Your password changed. Now you can login with your new password.";
            header('Location: pass-changed.php');
        } else {
            $errors['db-error'] = "Failed to change your password!";
        }
    }
}

// LOGIN-NOW button
if (isset($_POST['login-now'])) {
    header('Location: login-signup.php');
}
?>
