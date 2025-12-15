<?php
session_start();
require_once "../../classes/reservation.php";

if (!isset($_SESSION["user_id"])) {
    header("Location: ../auth/login.php");
    exit;
}

if (!isset($_GET["id"])) {
    header("Location: viewReservation.php");
    exit;
}

$reservation_id = $_GET["id"];
$user_id = $_SESSION["user_id"];

$reservationObj = new Reservation();

if ($reservationObj->cancelReservation($reservation_id, $user_id)) {
    $_SESSION["success_message"] = "Your reservation has been cancelled.";
} else {
    $_SESSION["error_message"] = "Unable to cancel the reservation.";
}

header("Location: viewReservation.php");
exit;
