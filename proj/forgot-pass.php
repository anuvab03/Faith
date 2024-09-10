<?php require_once "controllerUserData.php"; ?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Forgot Password</title>
  <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
  <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container">
        <form action="forgot-pass.php" method="POST" autocomplete="">
            <h2>Forgot Password</h2>
            <div class="input-text">
                <input type="email" name="email" required>
                <label>Enter your Email</label>
            </div>
            <button type="submit" name="continue" value="Continue">Continue</button>
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
    width: 100%;
    padding: 0 10px;
    display: flex;
    align-items: center;
    justify-content: center;
    background: url('https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcRiB76AIOFB3Nb3bKy1GaVxNAy_b0IWLxln3p45eK836Q&s') no-repeat;
    background-size: cover;
}
.container {
    width: 400px;
    border-radius: 8px;
    padding: 30px;
    text-align: center;
    border: 1px solid rgba(255,255,255,0.15);
    backdrop-filter: blur(15px);
    --webkit-backdrop-filter: blur(15px);
}
form {
    display: flex;
    flex-direction: column;
}
h2 {
    font-size: 2rem;
    margin-bottom: 20px;
    color: #000;
}
.input-text {
    position: relative;
    border-bottom: 2px solid #000;
    margin: 15px 0;
}
.input-text label {
    position: absolute;
    top: 50%;
    left: 0%;
    transform: translateY(-50%);
    color: #000;
    font-size: 16px;
    pointer-events: none;
    transition: 0.15s ease;
}
.input-text input {
    width: 100%;
    height: 40px;
    background: transparent;
    border: none;
    outline: none;
    font-size: 16px;
    color: #000;
}
.input-text input:focus ~ label,
.input-text input:valid ~ label {
    font-size: 0.8rem;
    top: 10px;
    transform: translateY(-120%);
}
button {
    background: #000;
    color: #fff;
    font-weight: 600;
    border: none;
    padding: 8px 100px;
    cursor: pointer;
    border-radius: 3px;
    font-size: 16px;
    border: 2px solid transparent;
    transition: 0.3s ease;
}
button:hover {
    color:#000;
    background: rgba(255,255,255,0.15);
    border-color: #000;
}
</style>
