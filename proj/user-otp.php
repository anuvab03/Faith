<?php
require_once "controllerUserData.php";

// Check if the user is logged in by checking the session
$email = $_SESSION['email'] ?? false;
if ($email == false) {
    header('Location: login-signup.php');
    exit();
}

// Check if the form has been submitted
if (isset($_POST['verify-otp'])) {
    $entered_otp = $_POST['otp'] ?? '';
    $correct_otp = $_SESSION['otp'] ?? '';

    if ($entered_otp == $correct_otp) {
        header('Location: dashboard1.php');
        exit();
    } else {
        $errors[] = "Invalid OTP. Please try again.";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>OTP Verification</title>
  <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
  <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container">
        <header>
            <i class="bx bxs-check-shield"></i>
        </header>
        <form action="user-otp.php" method="POST" autocomplete="off">
            <h4>Enter OTP Code</h4>
            <?php 
            if (isset($_SESSION['info'])) {
                echo '<div class="alert alert-success text-center">' . $_SESSION['info'] . '</div>';
            }

            if (!empty($errors)) {
                echo '<div class="alert alert-danger text-center">';
                foreach ($errors as $showerror) {
                    echo $showerror;
                }
                echo '</div>';
            }
            ?>
            <div class="input-field">
                <input type="text" name="otp" maxlength="6" required>
            </div>
            <button type="submit" name="verify-otp" value="Submit" class="btn">Verify OTP</button>
        </form>
    </div>
</body>
</html>

<style>
    @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&display=swap');

* {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
    font-family: 'Poppins', sans-serif;
}

body {
    min-height: 100vh;
    display: flex;
    align-items: center;
    justify-content: center;
    background: url('https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcRiB76AIOFB3Nb3bKy1GaVxNAy_b0IWLxln3p45eK836Q&s') no-repeat;
    background-size: cover;
}

:where(.container, form, .input-field, header) {
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
}

.container {
    background: #fff;
    padding: 30px 65px;
    border-radius: 12px;
    row-gap: 40px;
    box-shadow: 0 5px 10px rgba(0,0,0,0.1);
}

.container header {
    height: 65px;
    width: 65px;
    background: #000;
    color: #fff;
    font-size: 2.5rem;
    border-radius: 50%;
}

.container h4 {
    font-size: 1.25rem;
    color: #333;
    font-weight: 500;
}

form .input-field {
    flex-direction: row;
    column-gap: 10px;
}

.input-field input {
    height: 45px;
    width: 42px;
    border-radius: 6px;
    outline: none;
    font-size: 1.125rem;
    text-align: center;
    border: 1px solid #ddd;
}

.input-field input::-webkit-inner-spin-button,
.input-field input::-webkit-outer-spin-button {
    display: none;
}

form button {
    margin-top: 25px;
    width: 100%;
    background: #000;
    color: #fff;
    font-size: 1rem;
    border: none;
    padding: 9px 0;
    cursor: pointer;
    border-radius: 6px;
    pointer-events: auto;
    background: #000;
    transition: all 0.2s ease;
}

form button:hover {
    background: #a9a9a9;
}
</style>

<script>
const input = document.querySelector("input[name='otp']");
const button = document.querySelector("button");

input.addEventListener("input", () => {
    if (input.value.length === 6) {
        button.classList.add("active");
        button.style.pointerEvents = "auto";
        button.style.backgroundColor = "#fff";
    } else {
        button.classList.remove("active");
        button.style.pointerEvents = "none";
        button.style.backgroundColor = "#a9a9a9";
    }
});

// Focus the input on window load
window.addEventListener("load", () => input.focus());
</script>
