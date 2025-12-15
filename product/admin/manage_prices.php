<?php
session_start();
require_once "../../classes/eventType.php";

if ($_SESSION["role"] !== "admin") {
    header("Location: ../auth/login.php");
    exit;
}

$eventObj = new EventType();
$events = $eventObj->getEventTypes();
$success = "";
$errors = [];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id = $_POST["event_type_id"];
    $price = $_POST["price"];

    if (!is_numeric($price) || $price < 0) {
        $errors["price"] = "Price must be a valid number.";
    } else {
        if ($eventObj->updateEventPrice($id, $price)) {
            $success = "Price updated!";
            $events = $eventObj->getEventTypes(); // Refresh list
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Manage Prices</title>
    <style>
        body {
            font-family: Arial;
            background: #f4f4f4;
            padding: 40px;
        }

        .container {
            background: #fff;
            padding: 25px;
            max-width: 800px;
            margin: auto;
            border-radius: 12px;
            box-shadow: 0 0 12px rgba(0,0,0,0.1);
        }

        h2 {
            text-align: center;
            color: #2c3e50;
            margin-bottom: 25px;
        }

        table { width: 100%; border-collapse: collapse; margin-top: 25px; }
        th, td { border: 1px solid #ddd; padding: 12px; text-align: left; }
        th { background: #2980b9; color: white; }
        tr:nth-child(even) { background: #f9f9f9; }

        input[type="text"] {
            width: 80%;
            padding: 8px;
            border-radius: 6px;
            border: 1px solid #ccc;
        }

        .save-btn {
            padding: 8px 18px;
            background: #007bff;
            color: white;
            border: none;
            border-radius: 6px;
            font-size: 14px;
            cursor: pointer;
            transition: 0.3s;
        }

        .save-btn:hover {
            background: #0056b3;
        }

        .success {
            color: #27ae60;
            background: #eafaf1;
            padding: 10px;
            border-radius: 6px;
            text-align: center;
            margin-bottom: 20px;
        }

        .error {
            color: #e74c3c;
            font-size: 0.9rem;
            margin-top: 4px;
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

    </style>
</head>

<body>
<div class="container">
    <h2>Manage Event Prices</h2>

    <?php if (!empty($success)): ?>
        <p class="success"><?= $success ?></p>
    <?php endif; ?>

    <table>
        <tr>
            <th>Event</th>
            <th>Price (₱)</th>
            <th style="width: 120px;">Action</th>
        </tr>

        <?php foreach ($events as $event): ?>
            <tr>
                <form action="" method="post">
                    <td><?= htmlspecialchars($event["event_name"]) ?></td>

                    <td>
                        <input 
                            type="text" 
                            name="price" 
                            value="<?= htmlspecialchars($event["price"]) ?>"
                        >
                        <p class="error"><?= $errors["price"] ?? "" ?></p>
                    </td>

                    <td>
                        <input type="hidden" name="event_type_id" value="<?= $event["event_type_id"] ?>">
                        <button type="submit" class="save-btn">Save</button>
                    </td>
                </form>
            </tr>
        <?php endforeach; ?>
    </table>

    <a href="admin_dashboard.php" class="btn">← Back to Dashboard</a>
</div>
</body>
</html>

