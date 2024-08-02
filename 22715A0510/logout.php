<?php
session_start();
session_unset();
session_destroy();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Logout</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background-color: #f0f2f5;
            color: #333;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
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
        p {
            font-size: 18px;
            margin-bottom: 20px;
        }
        .message {
            font-size: 16px;
            padding: 10px;
            border-radius: 5px;
            background-color: #007bff;
            color: #fff;
            margin-bottom: 20px;
        }
        .message.success {
            background-color: #28a745;
        }
        a {
            color: #007bff;
            text-decoration: none;
            font-size: 16px;
        }
        a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Logout</h1>
        <div class="message success">You have been successfully logged out.</div>
        <a href="login.php">Back to Login</a>
    </div>
</body>
</html>
