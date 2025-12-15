<?php
session_start();

if (!isset($_SESSION["user_id"]) || $_SESSION["role"] !== "admin") {
    header("Location: ../auth/login.php");
    exit();
}

if (!isset($_GET["id"])) {
    header("Location: viewEventType.php");
    exit();
}

require_once "../../classes/eventType.php";

$eventTypeObj = new EventType();
$id = $_GET["id"];

if ($eventTypeObj->isEventTypeInUse($id)) {
    $_SESSION["error_message"] = "Cannot delete event type. It is used in existing reservations.";
    header("Location: viewEventType.php");
    exit();
}

if ($eventTypeObj->deleteEventType($id)) {
    $_SESSION["success_message"] = "Event type deleted successfully.";
} else {
    $_SESSION["error_message"] = "Failed to delete event type.";
}

header("Location: viewEventType.php");
exit();
