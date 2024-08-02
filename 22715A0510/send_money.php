<?php
require 'config.php';

session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $user_id = $_SESSION['user_id'];
    $amount = $_POST['amount'];
    $recipient_account_number = $_POST['recipient_account_number'];

    $conn->begin_transaction();

    try {
        $stmt = $conn->prepare("SELECT id, balance FROM users WHERE account_number = ?");
        $stmt->bind_param("s", $recipient_account_number);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows == 0) {
            throw new Exception("Recipient not found.");
        }

        $stmt->bind_result($recipient_id, $recipient_balance);
        $stmt->fetch();
        $stmt->close();

        $stmt = $conn->prepare("SELECT balance FROM users WHERE id = ?");
        $stmt->bind_param("i", $user_id);
        $stmt->execute();
        $stmt->bind_result($balance);
        $stmt->fetch();
        $stmt->close();

        if ($balance < $amount) {
            throw new Exception("Insufficient funds.");
        }

        $new_balance = $balance - $amount;
        $stmt = $conn->prepare("UPDATE users SET balance = ? WHERE id = ?");
        $stmt->bind_param("di", $new_balance, $user_id);
        $stmt->execute();
        $stmt->close();

        $new_recipient_balance = $recipient_balance + $amount;
        $stmt = $conn->prepare("UPDATE users SET balance = ? WHERE id = ?");
        $stmt->bind_param("di", $new_recipient_balance, $recipient_id);
        $stmt->execute();
        $stmt->close();

        $stmt = $conn->prepare("INSERT INTO transactions (user_id, type, amount) VALUES (?, 'debit', ?)");
        $stmt->bind_param("id", $user_id, $amount);
        $stmt->execute();
        $stmt->close();

        $stmt = $conn->prepare("INSERT INTO transactions (user_id, type, amount) VALUES (?, 'credit', ?)");
        $stmt->bind_param("id", $recipient_id, $amount);
        $stmt->execute();
        $stmt->close();

        $conn->commit();

        $transaction_message = "Transaction successful.";
    } catch (Exception $e) {
        $conn->rollback();
        $transaction_message = "Transaction failed: " . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Send Money</title>
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
        form {
            display: flex;
            flex-direction: column;
        }
        label {
            font-size: 18px;
            margin-bottom: 10px;
        }
        input[type="text"],
        input[type="number"] {
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
        .message {
            margin-top: 20px;
            font-size: 16px;
            padding: 10px;
            border-radius: 5px;
            background-color: #28a745;
            color: #fff;
        }
        .error-message {
            margin-top: 20px;
            font-size: 16px;
            padding: 10px;
            border-radius: 5px;
            background-color: #dc3545;
            color: #fff;
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
    </style>
</head>
<body>
    <div class="container">
        <h1>Send Money</h1>
        <form method="POST">
            <label>Recipient Account Number: </label>
            <input type="text" name="recipient_account_number" required><br>
            <label>Amount: </label>
            <input type="number" step="0.01" name="amount" required><br>
            <button type="submit">Send</button>
        </form>
        <?php if (isset($transaction_message)): ?>
            <?php if (strpos($transaction_message, 'successful') !== false): ?>
                <div class="message"><?php echo $transaction_message; ?></div>
            <?php else: ?>
                <div class="error-message"><?php echo $transaction_message; ?></div>
            <?php endif; ?>
        <?php endif; ?>
        <a href="dashboard.php">Back to Dashboard</a>
    </div>
</body>
</html>
