<?php
session_start();
if (!isset($_SESSION["user_id"]) || $_SESSION["role"] !== "admin") {
    header("Location: ../auth/login.php");
    exit();
}

require_once "../../classes/admin.php";
$adminObj = new Admin();

if (isset($_POST["action"], $_POST["reservation_id"])) {
    $status = $_POST["action"] === "approve" ? "approved" : "declined";
    $adminObj->updateReservationStatus($_POST["reservation_id"], $status);
}

$reservations = $adminObj->getAllReservations();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Reservations</title>
    <style>
        body { font-family: Arial, sans-serif; background: #f4f4f4; padding: 40px; }
        .container { background: #fff; padding: 30px; max-width: 1000px; margin: auto; border-radius: 12px; box-shadow: 0 0 12px rgba(0,0,0,0.1); }
        h2 { text-align: center; color: #2c3e50; }

        table { width: 100%; border-collapse: collapse; margin-top: 25px; }
        th, td { border: 1px solid #ddd; padding: 12px; text-align: left; }
        th { background: #2980b9; color: white; }
        tr:nth-child(even) { background: #f9f9f9; }

        .status {
            font-weight: bold;
            text-transform: capitalize;
            border-radius: 5px;
            padding: 5px 10px;
        }
        .status.pending { color: #e67e22; background: #fce8d5; }
        .status.approved { color: #27ae60; background: #d4efdf; }
        .status.declined { color: #c0392b; background: #f9d6d5; }
        .status.cancelled { color: #ddd; background: #6c757d; }

        .btn {
            display: inline-block;
            padding: 8px 14px;
            background-color: #007bff;
            color: white;
            border-radius: 6px;
            text-decoration: none;
            transition: background 0.3s;
            border: none;
            cursor: pointer;
        }

        .btn:hover { background-color: #0056b3; }
        .btn.approve { background-color: #27ae60; }
        .btn.approve:hover { background-color: #1e8449; }
        .btn.decline { background-color: #e74c3c; }
        .btn.decline:hover { background-color: #c0392b; }
        .back { margin-top: 25px; display: block; text-align: center; }
    </style>
</head>
<body>
    <div class="container">
        <h2>All Reservations</h2>

        <table>
            <thead>
                <tr>
                    <th>#</th>
                    <th>User</th>
                    <th>Event</th>
                    <th>Date</th>
                    <th>Time</th>
                    <th>Participants</th>
                    <th>Notes</th>
                    <th>Status</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($reservations): ?>
                    <?php foreach ($reservations as $index => $r): ?>
                        <tr>
                            <td><?= $index + 1 ?></td>
                            <td><?= htmlspecialchars($r["user_name"]) ?></td>
                            <td><?= htmlspecialchars($r["event_name"]) ?></td>
                            <td><?= htmlspecialchars($r["date"]) ?></td>
                            <td><?= htmlspecialchars($r["time"]) ?></td>
                            <td><?= htmlspecialchars($r["participants"]) ?></td>
                            <td><?= htmlspecialchars($r["notes"]) ?></td>
                            <td><span class="status <?= $r["status"] ?>"><?= $r["status"] ?></span></td>
                            <td>
                                <?php if ($r["status"] === "pending"): ?>
                                    <form action="approve_reservation.php" method="get" style="display:inline;">
                                        <input type="hidden" name="id" value="<?= htmlspecialchars($r["reservation_id"]) ?>">
                                        <button type="submit" class="btn approve">Approve</button>
                                    </form>
                                    <form action="" method="post" style="display:inline;">
                                        <input type="hidden" name="reservation_id" value="<?= $r["reservation_id"] ?>">
                                        <button type="submit" name="action" value="decline" class="btn decline">Decline</button>
                                    </form>
                                <?php else: ?>
                                    <em>No action</em>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr><td colspan="9" style="text-align:center;">No reservations found.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>

        <a href="admin_dashboard.php" class="btn back">← Go Back to Dashboard</a>
    </div>
</body>
</html>
