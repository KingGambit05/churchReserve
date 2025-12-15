<?php
session_start();

if (!isset($_SESSION["user_id"]) || $_SESSION["role"] !== "admin") {
    header("Location: ../auth/login.php");
    exit();
}

require_once "../../classes/eventType.php";

$eventTypeObj = new EventType();
$errors = [];
$success = "";

if ($_SERVER["REQUEST_METHOD"] == "POST"){
    $eventTypeObj->event_name = trim($_POST["eventName"]);
    $eventTypeObj->description = trim($_POST["desc"]);
    $eventTypeObj->price = trim($_POST["price"]);


    if (empty($eventTypeObj->event_name)) {
        $errors["eventName"] = "Please select an event Name.";
    }
    if (empty($eventTypeObj->price) && $eventTypeObj->price !== "0") {
        $errors["price"] = "Price is required.";
    } elseif (!is_numeric($eventTypeObj->price) || $eventTypeObj->price < 0) {
        $errors["price"] = "Price must be a valid number.";
    }

    if (empty($errors)) {

        if ($eventTypeObj->addEventType()) {
            header("Location: viewEventType.php");
        exit;
        } else {
            $errors["general"] = "Failed to save reservation.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>

<style>
    body { font-family: Arial;
        background: #f4f4f4; 
        padding: 40px; 
    }
    .container { 
        background: #fff; 
        padding: 20px; 
        max-width: 600px; 
        margin: auto; 
        border-radius: 10px; 
        box-shadow: 0 0 10px rgba(0,0,0,0.1); 
    }
    h2 { 
        text-align: center; 
        color: #2c3e50; 
    }
    label { 
        display: block; 
        margin-top: 15px; 
    }
    input, select, textarea {
        width: 100%; 
        padding: 8px; 
        margin-top: 5px; 
        border-radius: 5px; 
        border: 1px solid #ccc; 
    }
    .error { 
        color: red; 
        font-size: 0.9rem; 
    }
    .success { 
        color: green; 
        text-align: center; 
    }
    button { 
        background: #007bff; 
        color: white; 
        padding: 12px 25px; 
        border: none; 
        margin-top: 20px; 
        border-radius: 8px; 
        cursor: pointer; 
        width: 100%; }

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
        <h1>Event Type</h1>

        <form action="" method="post">
            <label for="eventName">Event Name:</label><br>
            <input type="text" name="eventName" id="eventName">
            <p class="error"><?= $errors["eventName"] ?? "" ?></p><br>

            <label for="price">Price (₱):</label>
            <input type="text" name="price" id="price" value="<?= $_POST["price"] ?? "" ?>">
            <p class="error"><?= $errors["price"] ?? "" ?></p>

            <label for="desc">Description:</label><br>
            <textarea name="desc" id="desc"></textarea>

            <button type="submit">Submit Reservation</button>
        </form>

        

        <a href="admin_dashboard.php" class="btn">Go back home</a>
        <a href="viewEventType.php" class="btn">Event Type</a>
    </div>
</body>
</html>