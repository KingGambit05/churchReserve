<?php
session_start();
if (!isset($_SESSION["user_id"]) || $_SESSION["role"] !== "user") {
    header("Location: ../auth/login.php");
    exit();
}

require_once "../../classes/notification.php";
$notifObj = new Notification();

$user_id = $_SESSION["user_id"];
$notifications = $notifObj->getUserNotifications($user_id);

$conn = $notifObj->connect();
$markRead = $conn->prepare("UPDATE notifications SET is_read = 1 WHERE user_id = :uid");
$markRead->bindParam(":uid", $user_id);
$markRead->execute();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Your Notifications</title>
    <style>
        body { font-family: Arial, sans-serif; background: #f4f4f4; padding: 40px; }
        .container { background: #fff; padding: 25px; max-width: 700px; margin: auto; border-radius: 10px; box-shadow: 0 0 10px rgba(0,0,0,0.1); }
        h2 { text-align: center; color: #2c3e50; }
        .notif {
            background: #ecf0f1;
            padding: 15px;
            border-radius: 8px;
            margin-bottom: 15px;
            border-left: 5px solid #2980b9;
        }
        .notif.read { opacity: 0.6; }
        .notif p { margin: 0; }
        .notif small { display: block; color: #7f8c8d; margin-top: 5px; }
        .btn {
            display: inline-block;
            margin-top: 20px;
            padding: 10px 20px;
            background: #007bff;
            color: white;
            border-radius: 6px;
            text-decoration: none;
        }
        .btn:hover { background: #0056b3; }
    </style>
</head>
<body>
    <div class="container">
        <h2>Your Notifications</h2>

        <?php if ($notifications): ?>
            <?php foreach ($notifications as $n): ?>
                <div class="notif <?= $n["is_read"] ? "read" : "" ?>">
                    <p><?= htmlspecialchars($n["message"]) ?></p>
                    <small><?= date("F j, Y, g:i a", strtotime($n["created_at"])) ?></small>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <p style="text-align:center;">You have no notifications.</p>
        <?php endif; ?>

        <a href="user_dashboard.php" class="btn">← Go Back Home</a>
    </div>
</body>
</html>
