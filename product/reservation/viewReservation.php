<?php
session_start();
require_once "../../classes/reservation.php";
require_once "../../classes/eventType.php";

if (!isset($_SESSION["user_id"])) {
    header("Location: ../auth/login.php");
    exit;
}

$reservationObj = new Reservation();
$reservations = $reservationObj->getUserReservations($_SESSION["user_id"]);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Reservations</title>
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
        table {
            width: 100%;
            border-collapse: collapse;
        }
        table th, table td {
            padding: 12px 15px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }
        table th {
            background: #007bff;
            color: white;
        }
        table tr:hover {
            background: #f1f1f1;
        }
        .status {
            padding: 5px 10px;
            border-radius: 5px;
            text-transform: capitalize;
        }
        .pending { background: #ffc107; color: #fff; }
        .approved { background: #28a745; color: #fff; }
        .declined { background: #dc3545; color: #fff; }
        .cancelled { background: #6c757d; color: #fff; }
        .btn-home {
            display: block;
            text-align: center;
            margin-top: 30px;
            text-decoration: none;
            background: #007bff;
            color: white;
            padding: 12px 25px;
            border-radius: 8px;
            width: fit-content;
            margin-left: auto;
            margin-right: auto;
            transition: 0.3s;
        }
        .btn-home:hover {
            background: #0056b3;
        }
        .cancel-btn {
            padding: 6px 12px;
            background: #dc3545;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            font-size: 14px;
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
        <h1>My Reservations</h1>

        <?php if (empty($reservations)) : ?>
            <p style="text-align:center;">You have no reservations yet.</p>
        <?php else : ?>
            <table>
                <tr>
                    <th>Event</th>
                    <th>Date</th>
                    <th>Time</th>
                    <th>Participants</th>
                    <th>Price</th>
                    <th>Status</th>
                    <th>Action</th>
                </tr>
                <?php foreach ($reservations as $r) : ?>
                <tr>
                    <td><?= htmlspecialchars($r["event_name"]) ?></td>
                    <td><?= htmlspecialchars($r["date"]) ?></td>
                    <td><?= htmlspecialchars($r["time"]) ?></td>
                    <td><?= htmlspecialchars($r["participants"]) ?></td>
                    <td>₱<?= number_format($r["price"], 2) ?></td>
                    <td><span class="status <?= htmlspecialchars($r["status"]) ?>"><?= htmlspecialchars($r["status"]) ?></span></td>
                    <td>
                        <?php if ($r["status"] === "pending"): ?>
                            <a href="cancelReservation.php?id=<?= $r['reservation_id'] ?>" 
                            onclick="return confirm('Are you sure you want to cancel this reservation?')"
                            class="cancel-btn">
                            Cancel
                            </a>
                        <?php else: ?>
                            <span style="color: #888;">—</span>
                        <?php endif; ?>
                    </td>
                </tr>
                <?php endforeach; ?>
            </table>
        <?php endif; ?>

        <a href="../user/user_dashboard.php" class="btn-home">Go Back Home</a>
    </div>
</body>
</html>
