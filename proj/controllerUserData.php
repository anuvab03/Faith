<?php
session_start();
require "db.php";
$email = "";
$name = "";
$errors = array();

//if user signup button
if(isset($_POST['register'])){
    $name = mysqli_real_escape_string($con, $_POST['name']);
    $email = mysqli_real_escape_string($con, $_POST['email']);
    $password = mysqli_real_escape_string($con, $_POST['password']);
    $confirm_password = mysqli_real_escape_string($con, $_POST['confirm-password']);
    if($password !== $confirm_password){
        $errors['password'] = "Confirm password not matched!";
    }
    $email_check = "SELECT * FROM usertable WHERE email = '$email'";
    $res = mysqli_query($con, $email_check);
    if(mysqli_num_rows($res) > 0){
        $errors['email'] = "Email that you have entered already exist!";
    }
    if(count($errors) === 0){
        $encpass = password_hash($password, PASSWORD_BCRYPT);
        $code = rand(999999, 111111);
        $status = "notverified";
        $insert_data = "INSERT INTO usertable (name, email, password, code, status)
                        values('$name', '$email', '$encpass', '$code', '$status')";
        $data_check = mysqli_query($con, $insert_data);
        if($data_check){
            $subject = "Email Verification Code";
            $message = "Your verification code is $code";
            $sender = "From: shaarmilam6@gmail.com";
            if(mail($email, $subject, $message, $sender)){
                $info = "We've sent a verification code to your email - $email";
                $_SESSION['info'] = $info;
                $_SESSION['email'] = $email;
                $_SESSION['password'] = $password;
                header('location: user-otp.php');
                exit();
            }else{
                $errors['otp-error'] = "Failed while sending code!";
            }
        }else{
            $errors['db-error'] = "Failed while inserting data into database!";
        }
    }
}

//if user click verification code submit button
if(isset($_POST['verify-otp'])){
    $_SESSION['info'] = "";
    $otp = mysqli_real_escape_string($con, $_POST['otp']);
    $check_code = $con->prepare("SELECT * FROM usertable WHERE code = ?");
    $check_code->bind_param("i", $otp);
    $check_code->execute();
    $code_res = $check_code->get_result();
    if($code_res->num_rows > 0){
        $fetch_data = $code_res->fetch_assoc();
        $fetch_code = $fetch_data['code'];
        $email = $fetch_data['email'];
        $code = 0;
        $status = 'verified';
        $update_otp = $con->prepare("UPDATE usertable SET code = ?, status = ? WHERE code = ?");
        $update_otp->bind_param("isi", $code, $status, $fetch_code);
        $update_res = $update_otp->execute();
        if($update_res){
            $_SESSION['name'] = $name;
            $_SESSION['email'] = $email;
            header('location: http://127.0.0.1:5000');
            exit();
        }else{
            $errors['otp-error'] = "Failed while updating code!";
        }
    }
}

//if user click login button
if(isset($_POST['login'])){
    $email = mysqli_real_escape_string($con, $_POST['email']);
    $password = mysqli_real_escape_string($con, $_POST['password']);
    $check_email = "SELECT * FROM usertable WHERE email = '$email'";
    $res = mysqli_query($con, $check_email);
    if(mysqli_num_rows($res) > 0){
        $fetch = mysqli_fetch_assoc($res);
        $fetch_pass = $fetch['password'];
        if(password_verify($password, $fetch_pass)){
            $_SESSION['email'] = $email;
            $status = $fetch['status'];
            if($status == 'verified'){
              $_SESSION['email'] = $email;
              $_SESSION['password'] = $password;
                header('location: http://127.0.0.1:5000');
            }else{
                $info = "It looks like you haven't still verified your email - $email";
                $_SESSION['info'] = $info;
                header('location: user-otp.php');
            }
        }else{
            $errors['email'] = "Incorrect email or password!";
        }
    }else{
        $errors['email'] = "It looks like you're not yet a member! Click on the bottom link to signup.";
    }
}

//if user click continue button in forgot password form
if(isset($_POST['continue'])){
    $email = mysqli_real_escape_string($con, $_POST['email']);
    $check_email = "SELECT * FROM usertable WHERE email='$email'";
    $run_sql = mysqli_query($con, $check_email);
    if(mysqli_num_rows($run_sql) > 0){
       /* $fcode = rand(999999, 111111);
        $insert_code = "UPDATE usertable SET fcode = $fcode WHERE email = '$email'";
        $run_query =  mysqli_query($con, $insert_code);
        if($run_query){
            $subject = "Password Reset Code";
            $message = "Your password reset code is $code";
            $sender = "From: shaarmilam6@gmail.com";
            if(mail($email, $subject, $message, $sender)){
               /* $info = "We've sent a password reset otp to your email - $email";
                $_SESSION['info'] = $info;*/
                $_SESSION['email'] = $email;
                header('location: new-pass.php');
                exit();
            }/*else{
                $errors['otp-error'] = "Failed while sending code!";
            }*/
        else{
            $errors['db-error'] = "Something went wrong!";
        }
    }
    /*else{
        $errors['email'] = "This email address does not exist!";
    }*/



//if user click change password button
if(isset($_POST['change-password'])){
    $_SESSION['info'] = "";
    $new_password = mysqli_real_escape_string($con, $_POST['new-password']);
    $confirm_password = mysqli_real_escape_string($con, $_POST['confirm-password']);
    if($new_password !== $confirm_password){
        $errors['password'] = "Confirm password not matched!";
    }else{
        //$code = 0;
        $email = $_SESSION['email']; //getting this email using session
        $encpass = password_hash($new_password, PASSWORD_BCRYPT);
        $update_pass = "UPDATE usertable SET password = '$encpass' WHERE email = '$email'";
        $run_query = mysqli_query($con, $update_pass);
        if($run_query){
            $info = "Your password changed. Now you can login with your new password.";
            $_SESSION['info'] = $info;
            header('Location: pass-changed.php');
        }else{
            $errors['db-error'] = "Failed to change your password!";
        }
    }
}

//if login now button click
if(isset($_POST['login-now'])){
    header('Location: login-signup.php');
}
?>
