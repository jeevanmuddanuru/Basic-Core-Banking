<?php
require 'config.php';

session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $stmt = $conn->prepare("SELECT id, password FROM users WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        $stmt->bind_result($id, $hashed_password);
        $stmt->fetch();

        if (password_verify($password, $hashed_password)) {
            $_SESSION['user_id'] = $id;
            header("Location: dashboard.php");
            exit();
        } else {
            $login_error = "Invalid password.";
        }
    } else {
        $login_error = "No user found with that username.";
    }

    $stmt->close();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Login</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background-color: #f0f2f5;
            color: #333;
            margin: 0;
            padding: 0;
             justify-content: center;
            align-items: center;
            height: 100vh;
            display:flex;
            flex-direction:column;
            background-image:url("https://i.pinimg.com/564x/2d/1f/a3/2d1fa34f5d7db544d50f43e29c9f78d3.jpg");
            background-repeat:no-repeat;
            background-size:cover;
        }
        .container {
            background: #fff;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            text-align: center;
            max-width: 400px;
            width: 100%;
        }
        h1 {
            margin-bottom: 20px;
            font-size: 28px;
        }
        form {
            display: flex;
            flex-direction: column;
        }
        label {
            font-size: 18px;
            margin-bottom: 10px;
        }
        input[type="text"],
        input[type="password"] {
            padding: 10px;
            font-size: 16px;
            margin-bottom: 20px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }
        button {
            padding: 10px;
            font-size: 16px;
            background-color: #007bff;
            color: #fff;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }
        button:hover {
            background-color: #0056b3;
        }
        .error-message {
            margin-top: 10px;
            font-size: 16px;
            color: #dc3545;
        }
        .heading{
            text-align:center;
        }
    </style>
</head>
<body>
<h1 class="heading">Jeevan Bank</h1>

    <div class="container">
        <h1>Login</h1>
        <form method="POST">
            <label>Username: </label>
            <input type="text" name="username" required><br>
            <label>Password: </label>
            <input type="password" name="password" required><br>
            <button type="submit">Login</button>
        </form>
        <?php if (isset($login_error)): ?>
            <div class="error-message"><?php echo $login_error; ?></div>
        <?php endif; ?>
    </div>
</body>
</html>
