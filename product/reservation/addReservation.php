<?php
session_start();
require_once "../../classes/database.php";
require_once "../../classes/reservation.php";
require_once "../../classes/eventType.php";

if (!isset($_SESSION["user_id"]) || $_SESSION["role"] !== "user") {
    header("Location: ../auth/login.php");
    exit;
}

$eventTypeObj = new EventType();
$reservationObj = new Reservation();
$errors = [];
$reservation = [];

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $reservation["event_type_id"] = trim(htmlspecialchars($_POST["event_type_id"]));
    $reservation["date"] = trim(htmlspecialchars($_POST["date"]));
    $reservation["time"] = trim(htmlspecialchars($_POST["time"]));
    $reservation["participants"] = trim(htmlspecialchars($_POST["participants"]));
    $reservation["notes"] = trim(htmlspecialchars($_POST["notes"]));

    
    $today = date('Y-m-d');

    
    if (empty($reservation["event_type_id"])) {
        $errors["event_type_id"] = "Please select an event type.";
    }

    
    if (empty($reservation["date"])) {
        $errors["date"] = "Date is required.";
    } elseif ($reservation["date"] < $today) {
        $errors["date"] = "You cannot select a past date for reservation.";
    }

    
    if (empty($reservation["time"])) {
        $errors["time"] = "Please select a time.";
    }

   
    if (empty($reservation["participants"]) || $reservation["participants"] < 1) {
        $errors["participants"] = "Please enter a valid number of participants.";
    }

    if ($reservationObj->isDateConflict($_POST["date"])) {
        $errors["date"] = "This date are already reserved. Please choose another schedule.";
    }

    
    if (empty(array_filter($errors))) {
        $reservationObj->user_id = $_SESSION["user_id"];
        $reservationObj->event_type_id = $reservation["event_type_id"];
        $reservationObj->date = $reservation["date"];
        $reservationObj->time = $reservation["time"];
        $reservationObj->participants = $reservation["participants"];
        $reservationObj->notes = $reservation["notes"];
        $reservationObj->status = "pending";

        if ($reservationObj->isDateConflict($reservationObj->date)) {
            $errors["date"] = "This date and time are already reserved. Please choose another schedule.";
        } else {
            if ($reservationObj->addReservation()) {
                
                $event = $eventTypeObj->getEventById($reservationObj->event_type_id);

                
                $_SESSION["reservation_success"] = [
                    "event_name" => $event["event_name"],
                    "date" => $reservationObj->date,
                    "time" => $reservationObj->time,
                    "participants" => $reservationObj->participants,
                    "notes" => $reservationObj->notes,
                    "status" => "pending",
                    "price" => $event["price"]
                ];

                header("Location: reservationSuccess.php");
                exit;
            } else {
                $errors["general"] = "Failed to save reservation. Please try again.";
            }
        
        }
    }
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Make a Reservation</title>
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
    <h2>Make a Reservation</h2>

    <a href="../user/user_dashboard.php" class="btn">Go back home</a>

    <?php if (!empty($success)): ?>
        <p class="success"><?= $success ?></p>
    <?php endif; ?>

    <?php if (!empty($errors["general"])): ?>
        <p class="error"><?= $errors["general"] ?></p>
    <?php endif; ?>

    <form method="post">
        <label>Event Type</label>
        <select name="event_type_id">
            <option value="">-- Select Event --</option>
            <?php
                foreach($eventTypeObj->getEventTypes() as $eventType){
            ?>
                <option value="<?= $eventType["event_type_id"] ?>"
                    <?= (isset($reservation["event_type_id"]) && $reservation["event_type_id"] == $eventType["event_type_id"]) ? "selected" : "" ?>>
                    <?= $eventType["event_name"] ?> — ₱<?= number_format($eventType["price"], 2) ?>
                </option>
            <?php
            }
            ?>
        </select>
        <p class="error"><?= $errors["event_type_id"] ?? "" ?></p>

        <label for="date">Reservation Date</label>
        <input type="date" name="date" id="date" min="<?= date('Y-m-d'); ?>">
        <p class="error"><?= $errors["date"] ?? "" ?></p>

        <label>Time</label>
        <input type="time" name="time">
        <p class="error"><?= $errors["time"] ?? "" ?></p>
        <small style="color: green;" >Today is <?= date('F j, Y');?></small>

        <label>Number of Participants</label>
        <input type="number" name="participants" min="1">
        <p class="error"><?= $errors["participants"] ?? "" ?></p>

        <label>Notes (optional)</label>
        <textarea name="notes" rows="3"></textarea>

        <button type="submit">Submit Reservation</button>
    </form>
</div>
</body>
</html>