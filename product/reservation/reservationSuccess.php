<?php
session_start();

if (!isset($_SESSION["user_id"])) {
    header("Location: ../auth/login.php");
    exit;
}

if (!isset($_SESSION["reservation_success"])) {
    header("Location: ../user/user_dashboard.php");
    exit;
}

$reservation = $_SESSION["reservation_success"];

unset($_SESSION["reservation_success"]);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reservation Successful</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f8f9fa;
            color: #333;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            height: 100vh;
        }
        .container {
            background: #fff;
            padding: 30px 40px;
            border-radius: 15px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
            max-width: 500px;
            width: 100%;
            text-align: center;
        }
        h1 {
            color: #28a745;
            margin-bottom: 10px;
        }
        p {
            margin: 8px 0;
            font-size: 16px;
        }
        .details {
            text-align: left;
            margin-top: 20px;
            border-top: 1px solid #ddd;
            padding-top: 15px;
        }
        .btn {
            display: inline-block;
            margin-top: 20px;
            padding: 12px 25px;
            background-color: #007bff;
            color: white;
            text-decoration: none;
            border-radius: 8px;
            transition: 0.3s;
        }
        .btn:hover {
            background-color: #0056b3;
        }
        .price {
            font-size: 18px;
            font-weight: bold;
            color: #2c3e50;
            margin-top: 12px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Reservation Confirmed </h1>
        <p>Thank you, your reservation has been successfully submitted.</p>

        <div class="details">
            <p><strong>Event Type:</strong> <?= htmlspecialchars($reservation["event_name"]) ?></p>
            <p><strong>Date:</strong> <?= htmlspecialchars($reservation["date"]) ?></p>
            <p><strong>Time:</strong> <?= htmlspecialchars($reservation["time"]) ?></p>
            <p><strong>Participants:</strong> <?= htmlspecialchars($reservation["participants"]) ?></p>
            <p><strong>Notes:</strong> <?= htmlspecialchars($reservation["notes"] ?? "None") ?></p>
            <p><strong>Status:</strong> <?= htmlspecialchars($reservation["status"]) ?></p>
            <p class="price"><strong>Amount Due:</strong> ₱<?= number_format($reservation["price"], 2) ?></p>
        </div>

        <a href="../user/user_dashboard.php" class="btn">Go Back Home</a>
        <a href="viewReservation.php" class="btn">View Reservation</a>
    </div>
</body>
</html>
