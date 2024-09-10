<?php require_once "controllerUserData.php"; ?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Dashboard</title>
  <link rel="stylesheet" href="https://unicons.iconscout.com/release/v4.0.8/css/line.css">
  <link rel="stylesheet" href="style1.css">
  <!-- Include jQuery (optional, for easier AJAX handling) -->
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <style>
    .spinner {
      display: none;
      width: 50px;
      height: 50px;
      border: 8px solid #f3f3f3;
      border-top: 8px solid #3498db;
      border-radius: 50%;
      animation: spin 1s linear infinite;
      margin: 20px auto;
    }

    @keyframes spin {
      0% { transform: rotate(0deg); }
      100% { transform: rotate(360deg); }
    }
  </style>
</head>

<body>
    <nav>
        <div class="logo-name">
            <div class="logo-image">
                <img src="C:\Users\HP\Downloads\logo-removebg-preview.png" alt="">
            </div>
            <span class="logo_name">FAITH</span>
        </div>

        <div class="menu-items">
            <ul class="nav-links">
               <li><a href="#">
                    <i class="uil uil-estate"></i>
                    <span class="link-name">Home</span>
               </a></li> 
               <li><a href="#">
                    <i class="uil uil-user"></i>
                    <span class="link-name">Profile</span>
               </a></li> 
               <li><a href="#">
                     <i class="uil uil-question-circle"></i>
                    <span class="link-name">Help</span>
               </a></li> 
            </ul>

            <ul class="logout-mode">
                <li>
                    <a href="http://127.0.0.1:5000/logout">
                    <i class="uil uil-signout"></i>
                    <span class="link-name">Logout</span>
                    </a>
                </li>
                <li class="mode">
                    <a href="#">
                    <i class="uil uil-moon"></i>
                    <span class="link-name">Dark Mode</span>
                    </a>
                    <div class="mode-toggle">
                        <span class="switch"></span>
                    </div>
                </li> 
            </ul>
        </div>
    </nav>

    <section class="dashboard">
        <div class="top">
            <i class="uil uil-bars sidebar-toggle"></i>
        </div>

        <div class="dash-content">
            <div class="overview">
                <div class="title">
                    <i class="uil uil-tachometer-fast-alt"></i>
                    <span class="text">Dashboard</span>
                </div>
            </div>

            <!-- Username Prediction Form -->
            <div class="prediction-form">
                <h1>Fake Account Prediction</h1>
                <form id="usernameForm" method="post">
                    <label for="username">Enter Username:</label>
                    <input type="text" id="username" name="username" required>
                    <button type="submit" class="my-button">Predict</button>
                </form>
                <div class="spinner" id="spinner"></div>
                <div id="predictionResult"></div>
            </div>
        </div>
    </section>

    <script>
        document.getElementById('usernameForm').addEventListener('submit', async function(event) {
            event.preventDefault(); // Prevent the default form submission
            const formData = new FormData(event.target);

            // Show the spinner
            document.getElementById('spinner').style.display = 'block';

            try {
                const response = await fetch('/predict', {
                    method: 'POST',
                    body: formData
                });

                const result = await response.json();
                document.getElementById('predictionResult').innerText = `Prediction: ${result.prediction}`;
            } catch (error) {
                console.error('Error during fetch:', error);
                document.getElementById('predictionResult').innerText = 'Error occurred during prediction.';
            } finally {
                // Hide the spinner
                document.getElementById('spinner').style.display = 'none';
            }
        });

        const body = document.querySelector('body'),
            modeToggle = body.querySelector('.mode-toggle'),
            sidebar = body.querySelector('nav'),
            sidebarToggle = body.querySelector('.sidebar-toggle');

        let getMode = localStorage.getItem('mode');
        if (getMode && getMode === 'dark') {
            body.classList.add('dark');
        }

        let getStatus = localStorage.getItem('status');
        if (getStatus && getStatus === 'close') {
            sidebar.classList.add('close');
        }

        modeToggle.addEventListener('click', () => {
            body.classList.toggle('dark');
            if (body.classList.contains('dark')) {
                localStorage.setItem('mode', 'dark');
            } else {
                localStorage.setItem('mode', 'light');
            }
        });

        sidebarToggle.addEventListener('click', () => {
            sidebar.classList.toggle('close');
            if (sidebar.classList.contains('close')) {
                localStorage.setItem('status', 'close');
            } else {
                localStorage.setItem('status', 'open');
            }
        });
    </script>

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

    :root {
        /*==== Colors ====*/
        --primary-color: #69f7eb;
        --panel-color: #fff;
        --text-color: #000;
        --black-light-color: #707070;
        --border-color: #e6e5e5;
        --toggle-color: #ddd;
        --box1-color: #4da3ff;
        --box2-color: #ffe6ac;
        --title-icon-color: #fff;

        /* ==== Transition === */
        --tran-05: all 0.5s ease;
        --tran-03: all 0.3s ease;
        --tran-02: all 0.2s ease;
    }

    body {
        min-height: 100vh;
        background-color: var(--primary-color);
    }

    body.dark {
        --primary-color: #3a3b3c;
        --panel-color: #242526;
        --text-color: #ccc;
        --black-light-color: #ccc;
        --border-color: #4d4c4c;
        --toggle-color: #fff;
        --box1-color: #3a3b3c;
        --box2-color: #3a3b3c;
        --title-icon-color: #ccc;
    }

    nav {
        position: fixed;
        top: 0;
        left: 0;
        height: 100%;
        width: 250px;
        padding: 10px 14px;
        background-color: var(--panel-color);
        border-right: 1px solid var(--border-color);
        transition: var(--tran-05);
    }

    nav.close {
        width: 73px;
    }

    nav .logo-name {
        display: flex;
        align-items: center;
    }

    nav .logo-image {
        display: flex;
        justify-content: center;
        min-width: 45px;
    }

    nav .logo-image img {
        width: 40px;
        object-fit: cover;
        border-radius: 50%;
    }

    nav .logo-name .logo_name {
        font-size: 22px;
        font-weight: 800;
        color: var(--text-color);
        margin-left: 14px;
        transition: var(--tran-05);
    }

    nav.close .logo_name {
        opacity: 0;
        pointer-events: none;
    }

    nav .menu-items {
        margin-top: 40px;
        height: calc(100% - 90px);
        display: flex;
        flex-direction: column;
        justify-content: space-between;
    }

    .menu-items li a {
        display: flex;
        align-items: center;
        height: 50px;
        text-decoration: none;
        position: relative;
    }

    .nav-links li a:hover:before {
        content: "";
        position: absolute;
        left: -7px;
        height: 5px;
        width: 5px;
        border-radius: 50%;
        background-color: var(--box1-color);
    }

    body.dark li a:hover:before {
        background-color: var(--box1-color);
    }

    .menu-items li a i {
        font-size: 24px;
        min-width: 45px;
        height: 100%;
        display: flex;
        align-items: center;
        justify-content: center;
        color: var(--black-light-color);
    }

    .menu-items li a .link-name {
        font-size: 18px;
        font-weight: 400;
        color: var(--text-color);
        transition: var(--tran-05);
    }

    nav.close li a .link-name {
        opacity: 0;
        pointer-events: none;
    }

    .nav-links li a:hover i,
    .nav-links li a:hover .link-name {
        color: var(--box1-color);
    }

    body.dark .nav-links li a:hover i,
    body.dark .nav-links li a:hover .link-name {
        color: var(--box1-color);
    }

    .menu-items .logout-mode {
        padding-top: 10px;
        border-top: 1px solid var(--border-color);
    }

    .menu-items .mode {
        display: flex;
        align-items: center;
        white-space: nowrap;
    }

    .menu-items .mode-toggle {
        position: absolute;
        right: 14px;
        height: 50px;
        min-width: 45px;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
    }

    .mode-toggle .switch {
        position: relative;
        display: inline-block;
        height: 22px;
        width: 40px;
        border-radius: 25px;
        background-color: var(--toggle-color);
    }

    .switch::before {
        content: "";
        position: absolute;
        left: 5px;
        top: 50%;
        transform: translateY(-50%);
        height: 15px;
        width: 15px;
        background-color: var(--panel-color);
        border-radius: 50%;
        transition: var(--tran-03);
    }

    body.dark .switch::before {
        left: 20px;
    }

    .dashboard {
        position: relative;
        left: 250px;
        background-color: var(--panel-color);
        height: 100vh;
        width: calc(100% - 250px);
        padding: 10px 14px;
        transition: var(--tran-05);
    }

    nav.close ~ .dashboard {
        left: 73px;
        width: calc(100% - 73px);
    }

    .dashboard .top {
        position: fixed;
        top: 0;
        left: 250px;
        display: flex;
        width: calc(100% - 250px);
        justify-content: space-between;
        align-items: center;
        padding: 10px 14px;
        background-color: var(--panel-color);
        transition: var(--tran-05);
    }

    nav.close ~ .dashboard .top {
        left: 73px;
        width: calc(100% - 73px);
    }

    .dashboard .top .sidebar-toggle {
        font-size: 26px;
        color: var(--text-color);
        cursor: pointer;
    }

    .dashboard .dash-content {
        padding-top: 50px;
    }

    .dash-content .title {
        display: flex;
        align-items: center;
        margin: 70px 0 30px 0;
    }

    .dash-content .title i {
        position: relative;
        height: 35px;
        width: 35px;
        background-color: var(--primary-color);
        border-radius: 6px;
        color: var(--title-icon-color);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 24px;
    }

    .dash-content .title .text {
        font-size: 24px;
        font-weight: 500;
        color: var(--text-color);
        margin-left: 10px;
    }

    .prediction-form {
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        height: 70vh;
        text-align: center;
    }

    .prediction-form h1 {
        margin-bottom: 20px;
        color: var(--text-color);
    }
</style>
<style>
    @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&display=swap');

* {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
    font-family: 'Poppins', sans-serif;
}
:root {
        /*==== Colors ====*/
        --primary-color: #cdfffb;
        --panel-color: #fff;
        --text-color: #000;
        --black-light-color: #707070;
        --border-color: #e6e5e5;
        --toggle-color: #ddd;
        --box1-color: #4da3ff;
        --box2-color: #ffe6ac;
        --title-icon-color: #fff;

        /* ==== Transition === */
        --tran-05: all 0.5s ease;
        --tran-03: all 0.3s ease;
        --tran-02: all 0.2s ease;
    }

    body {
        min-height: 100vh;
        background-color: var(--primary-color);
    }
    body.dark {
        --primary-color: #3a3b3c;
        --panel-color: #242526;
        --text-color: #ccc;
        --black-light-color: #ccc;
        --border-color: #4d4c4c;
        --toggle-color: #fff;
        --box1-color: #3a3b3c;
        --box2-color: #3a3b3c;
        --title-icon-color: #ccc;
    }
.prediction-form {
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        height: 70vh;
        text-align: center;
    }

    .prediction-form h1 {
        margin-bottom: 20px;
        color: var(--text-color);
    }

    .prediction-form form {
        display: flex;
        flex-direction: column;
        align-items: center;
    }

    .prediction-form form label {
        font-size: 18px;
        margin-bottom: 10px;
        color: var(--text-color);
    }

    .prediction-form form input {
        padding: 10px;
        font-size: 16px;
        margin-bottom: 20px;
        border: 1px solid var(--border-color);
        border-radius: 4px;
        background-color: var(--panel-color);
        color: var(--text-color);
    }

    .prediction-form form button {
        padding: 10px 20px;
        font-size: 16px;
        background-color: var(--primary-color);
        color: var(--text-color);
        border: none;
        border-radius: 4px;
        cursor: pointer;
        transition: var(--tran-02);
    }

    .prediction-form form button:hover {
        background-color: var(--box1-color);
    }

    #predictionResult {
        margin-top: 20px;
        font-size: 18px;
        color: var(--text-color);
    }
</style>