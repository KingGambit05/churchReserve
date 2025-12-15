<?php
session_start();
if (!isset($_SESSION["user_id"]) || $_SESSION["role"] !== "admin") {
    header("Location: ../auth/login.php");
    exit();
}

require_once "../../classes/eventType.php";

$eventTypeObj = new EventType();
$events = $eventTypeObj->getEventTypes();

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: #f8f9fa;
            color: #333;
            margin: 0;
            padding: 0;
        }
        .container {
            max-width: 900px;
            margin: 50px auto;
            background: #fff;
            padding: 30px 40px;
            border-radius: 15px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
        }
        h1 {
            color: #007bff;
            text-align: center;
            margin-bottom: 30px;
        }
        table { width: 100%; border-collapse: collapse; margin-top: 25px; }
        th, td { border: 1px solid #ddd; padding: 12px; text-align: left; }
        th { background: #2980b9; color: white; }
        tr:nth-child(even) { background: #f9f9f9; }
        .btn-home:hover {
            background: #0056b3;
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
        .btn-delete {
            padding: 6px 12px;
            background: #dc3545;
            color: white;
            text-decoration: none;
            border-radius: 6px;
            font-size: 14px;
            transition: 0.2s;
        }
    </style>
</head>
<body>
    <div class="container">
        <?php if (isset($_SESSION["success_message"])): ?>
            <p style="color: green; text-align:center;"><?= $_SESSION["success_message"]; ?></p>
            <?php unset($_SESSION["success_message"]); ?>
        <?php endif; ?>

        <?php if (isset($_SESSION["error_message"])): ?>
            <p style="color: red; text-align:center;"><?= $_SESSION["error_message"]; ?></p>
            <?php unset($_SESSION["error_message"]); ?>
        <?php endif; ?>
        <table>
        <tr>
            <th>Event ID</th>
            <th>Event Name</th>
            <th>price</th>
            <th>Description</th>
            <th>Action</th>
        </tr>
        <?php
        $no = 1;
        foreach ($events as $event) : ?>
        <tr>
            <td><?= $no++ ?></td>
            <td><?= htmlspecialchars($event["event_name"]) ?></td>
            <td><?= htmlspecialchars($event["price"])?></td>
            <td><?= htmlspecialchars($event["description"]) ?></td>
            <td>
                <a href="deleteEventType.php?id=<?= $event['event_type_id'] ?>" 
                onclick="return confirm('Are you sure you want to delete this event type?')"
                class="btn-delete">Delete</a>
            </td>
        </tr>
        <?php endforeach; ?>
    </table><br>

    <a href="admin_dashboard.php" class="btn">Go back home</a>
    </div>
</body>
</html>