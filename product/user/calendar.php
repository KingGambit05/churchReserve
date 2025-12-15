<?php
session_start();
require_once "../../classes/reservation.php";

if (!isset($_SESSION["user_id"]) || $_SESSION["role"] !== "user") {
    header("Location: ../auth/login.php");
    exit;
}

$reservationObj = new Reservation();
$reservations = $reservationObj->getAllReservationsWithEventNames();
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Church Calendar</title>

  <link href="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.8/index.global.min.css" rel="stylesheet">
  <script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.8/index.global.min.js"></script>

  <style>
    body { font-family: Arial, sans-serif; background: #f4f4f4; padding: 40px; }
    .container { background: #fff; padding: 20px; max-width: 900px; margin: auto; border-radius: 10px; box-shadow: 0 0 10px rgba(0,0,0,0.1); }
    h2 { text-align: center; color: #2c3e50; }
    #calendar { margin-top: 20px; }
    a.back-btn {
        display: inline-block;
        margin-top: 20px;
        padding: 12px 25px;
        background-color: #007bff;
        color: white;
        text-decoration: none;
        border-radius: 8px;
        transition: 0.3s;
    }
    a.back-btn:hover { background-color: #0056b3; }
  </style>
</head>
<body>
  <div class="container">
    <h2> Church Reservation Calendar</h2>
    <div id="calendar"></div>
    <a href="user_dashboard.php" class="back-btn">← Back to Dashboard</a>
  </div>

  <script>
  document.addEventListener("DOMContentLoaded", function() {
    const calendarEl = document.getElementById("calendar");
    const reservations = <?= json_encode($reservations) ?>;

    const events = reservations.map(r => ({
      title: r.event_name,
      start: r.date,
      color: '#e74c3c'
    }));

    const calendar = new FullCalendar.Calendar(calendarEl, {
      initialView: "dayGridMonth",
      height: "auto",
      events: events
    });

    calendar.render();
  });
</script>
</body>
</html>