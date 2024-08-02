<?php
require 'config.php';

session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

$stmt = $conn->prepare("SELECT username, balance, account_number FROM users WHERE id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$stmt->bind_result($username, $balance, $account_number);
$stmt->fetch();
$stmt->close();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Dashboard</title>
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
        }
        h1 {
            margin-bottom: 20px;
            font-size: 28px;
        }
        p {
            font-size: 18px;
            margin: 10px 0;
        }
        .balance {
            font-size: 24px;
            font-weight: bold;
            color: #28a745;
            margin-bottom: 20px;
        }
        a {
            color: #007bff;
            text-decoration: none;
            font-size: 16px;
            margin: 0 10px;
        }
        a:hover {
            text-decoration: underline;
        }
        .link-group {
            margin-top: 20px;
        }
        .heading{
            text-align:center;
        }
    </style>
</head>
<body>
    <h1 class="heading">Jeevan Bank</h1>
    <div class="container">
        <h1>Welcome, <?php echo htmlspecialchars($username); ?></h1>
        <p>Your account number is: <?php echo htmlspecialchars($account_number); ?></p>
        <p class="balance">Your balance is: Rs<?php echo number_format($balance, 2); ?></p>
        <div class="link-group">
            <a href="send_money.php">Send Money</a>
            <a href="deposit_money.php">Deposit Money</a>
            <a href="transactions.php">View Transactions</a>
            <a href="logout.php">Logout</a>
        </div>
    </div>
</body>
</html>
