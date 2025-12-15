<?php
session_start();

if (!isset($_SESSION["user_id"]) || $_SESSION["role"] !== "admin") {
    header("Location: ../auth/login.php");
    exit();
}

if (!isset($_GET["id"])) {
    header("Location: view_reservation.php");
    exit();
}

require_once "../../classes/reservation.php";
require_once "../../classes/user.php";

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require '../../phpmailer/src/PHPMailer.php';
require '../../phpmailer/src/SMTP.php';
require '../../phpmailer/src/Exception.php';

$reservationObj = new Reservation();
$userObj = new User();

$id = $_GET["id"];

$reservationObj->approveReservation($id);


$res = $reservationObj->getReservationById($id);
$user = $userObj->getUserById($res["user_id"]);
$email = $user["email"];
$name = $user["userName"];

$mail = new PHPMailer(true);

try {
    $mail->isSMTP();
    $mail->Host = "smtp.sendgrid.net";
    $mail->SMTPAuth = true;
    $mail->Username = "apikey";
    $mail->Password = $_ENV['SENDGRID_API_KEY'];
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
    $mail->Port = 587;

    $mail->setFrom($_ENV['SMTP_FROM_EMAIL'], $_ENV['SMTP_FROM_NAME']);
    $mail->addAddress($email, $name);

    $mail->isHTML(true);
    $mail->Subject = "Your Reservation Has Been Approved";
    $mail->Body = "
        <h2>Hello $name,</h2>
        <p>Your reservation for <strong>{$res['event_name']}</strong> on <strong>{$res['date']}</strong> 
        has been <span style='color:green;'>APPROVED</span>.</p>
        <p>Thank you!</p>
    ";

    $mail->send();
} catch (Exception $e) {
    echo "Email could not be sent. Error: {$mail->ErrorInfo}";
    exit;
}

header("Location: view_reservation.php");
exit();