<?php require_once "controllerUserData.php"; ?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Create a New Password</title>
  <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
</head>
<body>
    <div class="container">
        <form action="new-pass.php" method="POST">
            <h2>New Password</h2>
            <?php if(isset($error)): ?>
                <div class="alert alert-danger"><?php echo $error; ?></div>
            <?php endif; ?>
            <div class="input-box">
                <span class="icon"><ion-icon name="lock-closed"></ion-icon></span>
                <input type="password" name="new-password" required>
                <label>Create a new password</label>
            </div>
            <div class="input-box">
                <span class="icon"><ion-icon name="lock-closed"></ion-icon></span>
                <input type="password" name="confirm-password" required>
                <label>Confirm your new password</label>
            </div>
            <button type="submit" name="change-password" value="Change">Change</button>
        </form>
    </div>

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
            border-radius: 20px;
            padding: 30px;
            text-align: center;
            border: 1px solid rgba(255, 255, 255, 0.15);
            backdrop-filter: blur(15px);
            background: rgba(255, 255, 255, 0.1);
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
            color: #000;
            font-weight: 500;
            pointer-events: none;
            transition: .5s;
        }
        .input-box input:focus ~ label,
        .input-box input:valid ~ label {
            top: 5px;
        }
        .input-box input {
            width: 100%;
            height: 100%;
            background: transparent;
            border: none;
            outline: none;
            font-size: 1em;
            color: #000;
            font-weight: 600;
            padding: 0 35px 0 5px;
        }
        .input-box .icon {
            position: absolute;
            right: 8px;
            font-size: 1.2em;
            color: #000;
            line-height: 57px;
        }
        button {
            width: 100%;
            height: 45px;
            background: rgb(255, 253, 253);
            border: none;
            outline: none;
            border-radius: 20px;
            cursor: pointer;
            font-size: 1em;
            color: #000;
            font-weight: 500;
            margin-top: 20px; /* Added margin to separate from input fields */
        }
        button:hover {
            background: rgba(255, 255, 255, 0.15);
            color: #000;
        }
    </style>
    <script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
    <script nomodule src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.js"></script>
</body>
</html>
