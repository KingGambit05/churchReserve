<?php
session_start();
if (!isset($_SESSION["user_id"]) || $_SESSION["role"] !== "admin") {
    header("Location: ../auth/login.php");
    exit();
}

require_once "../../classes/admin.php";
$admin = new Admin();

$reservations = $admin->getAllReservations();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reservation Report</title>

    <style>
        body {
            font-family: Arial, sans-serif;
            padding: 30px;
        }

        h1 {
            text-align: center;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        th, td {
            border: 1px solid #000;
            padding: 8px;
            font-size: 12px;
        }

        th {
            background: #eee;
        }

        /* 🔥 PRINT ONLY */
        @media print {
            .no-print {
                display: none;
            }

            body {
                margin: 0;
            }
        }
        .btn {
            display: inline-block;
            padding: 12px 25px;
            background-color: #007bff;
            color: white;
            text-decoration: none;
            border-radius: 8px;
            margin: 5px;
            transition: background 0.3s, transform 0.2s;
        }
    </style>
</head>
<body>
    
    <h1>Church Reservation Report</h1>
    <p>Date Generated: <?= date("F d, Y") ?></p>

    <table>
        <tr>
            <th>User</th>
            <th>Event</th>
            <th>Date</th>
            <th>Status</th>
        </tr>

        <?php foreach ($reservations as $r): ?>
            <tr>
                <td><?= htmlspecialchars($r["user_name"]) ?></td>
                <td><?= htmlspecialchars($r["event_name"]) ?></td>
                <td><?= htmlspecialchars($r["date"]) ?></td>
                <td><?= htmlspecialchars($r["status"]) ?></td>
            </tr>
        <?php endforeach; ?>
    </table>

    <button class="no-print btn" onclick="window.print()">Print</button>
    <a href="admin_dashboard.php" class="btn back">← Go Back to Dashboard</a>
    
</body>
</html>