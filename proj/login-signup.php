<?php require_once "controllerUserData.php"; ?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>FAITH | Login or Register</title>
  <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
  <style>
    @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&display=swap');

    * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
      font-family: 'Poppins', sans-serif;
    }

    body {
      display: flex;
      justify-content: center;
      align-items: center;
      min-height: 100vh;
      background: url('https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcRiB76AIOFB3Nb3bKy1GaVxNAy_b0IWLxln3p45eK836Q&s') no-repeat;
      background-size: cover;
      background-position: center;
      overflow: hidden;
    }

    header {
      position: fixed;
      top: 0;
      left: 0;
      width: 100%;
      padding: 20px 100px;
      display: flex;
      justify-content: space-between;
      align-items: center;
      z-index: 20;
    }

    .logo {
      font-size: 2em;
      color: black;
      user-select: none;
    }

    .navigation a {
      position: relative;
      font-size: 1.1em;
      color: black;
      text-decoration: none;
      font-weight: 500;
      margin-left: 40px;
    }

    .navigation a::after {
      content: '';
      position: absolute;
      left: 0;
      bottom: -6px;
      width: 100%;
      height: 3px;
      background: black;
      border-radius: 5px;
      transform-origin: right;
      transform: scaleX(0);
      transition: transform .5s;
    }

    .navigation a:hover::after {
      transform-origin: left;
      transform: scaleX(1);
    }

    .navigation .btnLogin-popup {
      width: 130px;
      height: 50px;
      background: transparent;
      border: 2px solid black;
      outline: none;
      border-radius: 50px;
      cursor: pointer;
      font-size: 1.1em;
      color: black;
      font-weight: 500;
      margin-left: 40px;
      transition: .5s;
    }

    .navigation .btnLogin-popup:hover {
      background: rgb(243, 241, 241);
      color: black;
    }

    .wrapper {
      position: fixed;
      width: 400px;
      height: 440px;
      background: rgba(255, 255, 255, 0.1);
      border: 2px solid rgba(14, 14, 14, 0.5);
      border-radius: 20px;
      backdrop-filter: blur(20px);
      box-shadow: 0 0 30px rgba(0, 0, 0, .5);
      display: flex;
      justify-content: center;
      align-items: center;
      overflow: hidden;
      transform: scale(0);
      transition: transform .5s ease, height .2s ease;
      z-index: 1000;
    }

    .wrapper.active-popup {
      transform: scale(1);
    }

    .wrapper.active {
      height: 520px;
    }

    .wrapper .form-box {
      width: 100%;
      padding: 40px;
      position: relative;
      z-index: 1010;
    }

    .wrapper .form-box.login {
      transition: transform .18s ease;
      transform: translateX(0);
    }

    .wrapper.active .form-box.login {
      transition: none;
      transform: translateX(-400px);
    }

    .wrapper .form-box.register {
      position: absolute;
      transition: none;
      transform: translateX(400px);
    }

    .wrapper.active .form-box.register {
      transition: transform .18s ease;
      transform: translateX(0);
    }

    .wrapper .icon-close {
      position: absolute;
      top: 0;
      right: 0;
      width: 45px;
      height: 45px;
      background: black;
      font-size: 2em;
      color: white;
      display: flex;
      justify-content: center;
      align-items: center;
      border-bottom-left-radius: 20px;
      cursor: pointer;
      z-index: 1020;
    }

    .form-box h2 {
      font-size: 2em;
      color: black;
      text-align: center;
    }

    .input-box {
      position: relative;
      width: 100%;
      height: 50px;
      border-bottom: 2px solid #162938;
      margin: 30px 0;
    }

    .input-box label {
      position: absolute;
      top: 50%;
      left: 5px;
      transform: translateY(-50%);
      font-size: 1em;
      color: black;
      font-weight: 500;
      pointer-events: none;
      transition: .5s;
    }

    .input-box input:focus~label,
    .input-box input:valid~label {
      top: 5px;
    }

    .input-box input {
      width: 100%;
      height: 100%;
      background: transparent;
      border: none;
      outline: none;
      font-size: 1em;
      color: black;
      font-weight: 600;
      padding: 0 35px 0 5px;
      cursor: text;
    }

    .input-box .icon {
      position: absolute;
      right: 8px;
      font-size: 1.2em;
      color: black;
      line-height: 57px;
    }

    .remember-forgot {
      font-size: .9em;
      color: #162938;
      font-weight: 500;
      margin: -15px 0 15px;
      display: flex;
      justify-content: space-between;
    }

    .remember-forgot label input {
      accent-color: black;
      margin-right: 3px;
    }

    .remember-forgot a {
      color: black;
      text-decoration: none;
    }

    .remember-forgot a:hover {
      text-decoration: underline;
    }

    .btn {
      width: 100%;
      height: 45px;
      background: rgb(255, 253, 253);
      border: none;
      outline: none;
      border-radius: 20px;
      cursor: pointer;
      font-size: 1em;
      color: black;
      font-weight: 500;
    }

    .login-register {
      font-size: .9em;
      color: black;
      text-align: center;
      font-weight: 500;
      margin: 25px 0 10px;
    }

    .login-register p a {
      color: black;
      text-decoration: none;
      font-weight: 600;
    }

    .login-register p a:hover {
      text-decoration: underline;
    }

    .description {
      position: fixed;
      top: 50%;
      left: 50%;
      transform: translate(-50%, -50%);
      max-width: 800px;
      padding: 20px;
      text-align: center;
      transition: opacity 0.5s ease-in-out;
      z-index: 5;
    }

    .description.show {
      opacity: 1;
    }

    .description.blurred {
      filter: blur(70px);
    }

    footer {
      position: fixed;
      bottom: 20px;
      width: calc(100% - 200px);
      margin-left: 100px;
      margin-right: 100px;
      display: flex;
      justify-content: space-between;
      align-items: center;
      color: #162938;
      z-index: 5;
    }

    footer .contact-info,
    footer .phone-info {
      font-size: 1.1em;
    }
  </style>
</head>

<body>

  <header>
    <h2 class="faith">FAITH</h2>
    <nav class="navigation">
      <a href="#">About</a>
      <button class="btnLogin-popup">Login</button>
    </nav>
  </header>

  <div class="wrapper">
    <span class="icon-close">
      <ion-icon name="close"></ion-icon>
    </span>
    <div class="form-box login">
      <h2>Login</h2>
      <form action="login-signup.php" method="POST" autocomplete="">
        <div class="input-box">
          <span class="icon"><ion-icon name="mail"></ion-icon></span>
          <input type="email" name="email" required value="<?php echo $email ?>">
          <label>Email</label>
        </div>
        <div class="input-box">
          <span class="icon"><ion-icon name="lock-closed"></ion-icon></span>
          <input type="password" name="password" required>
          <label>Password</label>
        </div>
        <div class="remember-forgot">
						<a href="forgot-pass.php">Forgot Password?</a>
				</div>
        <button type="submit" name="login" value="Login" class="btn">Login</button>
        <div class="login-register">
          <p>Don't have an account?<a href="#" class="register-link">Register</a></p>
        </div>
      </form>
    </div>

    <div class="form-box register">
      <h2>Registration</h2>
      <?php
        if(count($errors) == 1){
          ?>
          <div class="alert alert-danger text-center">
            <?php
              foreach($errors as $showerror){
                echo $showerror;
              }
            ?>
          </div>
          <?php
        }elseif(count($errors) > 1){
          ?>
          <div class="alert alert-danger">
            <?php
              foreach($errors as $showerror){
                ?>
                <li><?php echo $showerror; ?></li>
                <?php
              }
            ?>
          </div>
          <?php
        }
      ?>
      <form action="login-signup.php" method="POST" autocomplete="">
        <div class="input-box">
          <span class="icon"><ion-icon name="person"></ion-icon></span>
          <input type="text" name="name" required value="<?php echo $name ?>">
          <label>Username</label>
        </div>
        <div class="input-box">
          <span class="icon"><ion-icon name="mail"></ion-icon></span>
          <input type="email" name="email" required value="<?php echo $email ?>">
          <label>Email</label>
        </div>
        <div class="input-box">
          <span class="icon"><ion-icon name="lock-closed"></ion-icon></span>
          <input type="password" name="password" required>
          <label>Password</label>
        </div>
        <div class="input-box">
          <span class="icon"><ion-icon name="lock-closed"></ion-icon></span>
          <input type="password" name="confirm-password" required>
          <label>Confirm Password</label>
        </div>
        <button type="submit" name="register" value="Register" class="btn">Register</button>
        <div class="login-register">
          <p>Already have an account?<a href="#" class="login-link">Login</a></p>
        </div>
      </form>
    </div>
  </div>

  <!-- Description Section -->
  <div class="description">
    <h2>Welcome to FAITH:<br> Fake Account Identification and Tracking Hub</h2><br>
    <p><strong>About FAITH</strong></p><br>
    <p>Welcome to FAITH, your trusted partner in identifying fake accounts on social media. Our advanced technology ensures that you interact only with authentic profiles, making your online experience safer.</p>
  </div>

  <!-- Footer -->
  <footer>
    <div class="contact-info">Contact us: faith.authorizor@gmail.com</div>
    <div class="phone-info">Phone: 700XXXXXX0</div>
  </footer>

  <script>
    const wrapper = document.querySelector('.wrapper');
    const loginLink = document.querySelector('.login-link');
    const registerLink = document.querySelector('.register-link');
    const btnPopup = document.querySelector('.btnLogin-popup');
    const iconClose = document.querySelector('.icon-close');
    const description = document.querySelector('.description');

    registerLink.addEventListener('click', () => {
      wrapper.classList.add('active');
      description.classList.add('blurred');
    });

    loginLink.addEventListener('click', () => {
      wrapper.classList.remove('active');
      description.classList.add('blurred');
    });

    btnPopup.addEventListener('click', () => {
      wrapper.classList.add('active-popup');
      description.classList.add('blurred');
    });

    iconClose.addEventListener('click', () => {
      wrapper.classList.remove('active-popup');
      wrapper.classList.remove('active');
      description.classList.remove('blurred');
    });
  </script>
  <script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
  <script nomodule src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.js"></script>
</body>
</html>
