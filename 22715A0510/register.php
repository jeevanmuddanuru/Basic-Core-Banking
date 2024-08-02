<?php
require 'config.php';

function generateAccountNumber() {
    return 'AC' . rand(1000000000, 9999999999);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $email = $_POST['email'];
    $account_number = generateAccountNumber();

    $stmt = $conn->prepare("INSERT INTO users (username, password, email, account_number) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("ssss", $username, $password, $email, $account_number);

    if ($stmt->execute()) {
        header("Location: login.php");
        exit();
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
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
        input[type="password"],
        input[type="email"] {
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
        a {
            display: block;
            margin-top: 20px;
            color: #007bff;
            text-decoration: none;
            font-size: 16px;
        }
        a:hover {
            text-decoration: underline;
        }
        .heading{
            text-align:center;
        }
    </style>
</head>
<body>
<h1 class="heading">Jeevan Bank</h1>

    <div class="container">
        <h1>Register</h1>
        <form method="POST">
            <label>Username: </label>
            <input type="text" name="username" required><br>
            <label>Password: </label>
            <input type="password" name="password" required><br>
            <label>Email: </label>
            <input type="email" name="email" required><br>
            <button type="submit">Register</button>
        </form>
        <?php if (isset($registration_error)): ?>
            <div class="error-message"><?php echo $registration_error; ?></div>
        <?php endif; ?>
        <a href="login.php">Already have an account? Login here</a>
    </div>
</body>
</html>