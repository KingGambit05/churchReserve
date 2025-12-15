<?php
session_start();
require_once "../../classes/admin.php";
require_once "../../classes/eventType.php";
require_once "../../classes/reservation.php";
require_once "../../classes/user.php";

if (!isset($_SESSION["user_id"]) || $_SESSION["role"] !== "admin") {
    header("Location: ../auth/login.php");
    exit();
}

$adminObj = new Admin();
$reservationObj = new Reservation();
$eventTypeObj = new EventType();
$userObj = new User();

$filters = [
    "status" => $_GET['status'] ?? '',
    "event_type_id" => $_GET['event_type_id'] ?? '',
    "user_id" => $_GET['user_id'] ?? '',
    "from_date" => $_GET['from_date'] ?? '',
    "to_date" => $_GET['to_date'] ?? ''
];

$reservations = $reservationObj->getFilteredReservations($filters);

// $users = $userObj->getAllUsers();
// $eventTypes = $eventTypeObj->getEventTypes();
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Admin Reports</title>
<style>
body { font-family: Arial; background: #f4f4f4; padding: 40px; }
.container { background: #fff; padding: 20px; max-width: 1000px; margin: auto; border-radius: 10px; box-shadow: 0 0 10px rgba(0,0,0,0.1); }
h2 { text-align: center; color: #2c3e50; margin-bottom: 20px; }
label { display: block; margin-top: 10px; }
input, select { padding: 8px; margin-top: 5px; border-radius: 5px; border: 1px solid #ccc; }
button, .btn-print { margin-top: 15px; padding: 10px 20px; border: none; border-radius: 5px; background: #007bff; color: white; cursor: pointer; }
button:hover, .btn-print:hover { background: #0056b3; }
table { width: 100%; border-collapse: collapse; margin-top: 20px; }
th, td { padding: 10px; border: 1px solid #ddd; text-align: left; }
th { background: #007bff; color: white; }
tr:hover { background: #f1f1f1; }
.status { padding: 3px 8px; border-radius: 5px; text-transform: capitalize; color: #fff; }
.pending { background: #ffc107; }
.approved { background: #28a745; }
.declined { background: #dc3545; }
.cancelled { background: #6c757d; }

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
</style>
</head>
<body>
<div class="container">
    <h2>Admin Reports</h2>

    <form method="get" style="margin-bottom: 20px;">
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 15px;">

            <div>
                <label>Status</label>
                <select name="status">
                    <option value="">All</option>
                    <option value="pending" <?= ($filters['status']=='pending')?'selected':'' ?>>Pending</option>
                    <option value="approved" <?= ($filters['status']=='approved')?'selected':'' ?>>Approved</option>
                    <option value="declined" <?= ($filters['status']=='declined')?'selected':'' ?>>Declined</option>
                    <option value="cancelled" <?= ($filters['status']=='cancelled')?'selected':'' ?>>Cancelled</option>
                </select>
            </div>

            <div>
                <label>Event Type</label>
                <select name="event_type_id">
                    <option value="">All</option>
                    <?php
                    if (!empty($eventTypes)) {
                        foreach($eventTypes as $et): ?>
                            <option value="<?= $et['event_type_id'] ?>" <?= ($filters['event_type_id']==$et['event_type_id'])?'selected':'' ?>>
                                <?= htmlspecialchars($et['event_name']) ?>
                            </option>
                        <?php endforeach;
                    } else { ?>
                        <option value="">No Event Types Found</option>
                    <?php } ?>
                </select>
            </div>

            <div>
                <label>From Date</label>
                <input type="date" name="from_date" value="<?= htmlspecialchars($filters['from_date']) ?>">
            </div>

            <div>
                <label>To Date</label>
                <input type="date" name="to_date" value="<?= htmlspecialchars($filters['to_date']) ?>">
            </div>
        </div>

        <div style="margin-top: 20px; display: flex; gap: 15px; flex-wrap: wrap;">
            <button type="submit">Filter</button>
            <button type="button" class="btn-print" onclick="window.location.href='print_reservation.php'">Print Report</button>
        </div>
    </form>



    <table>
        <tr>
            <th>#</th>
            <th>User</th>
            <th>Event</th>
            <th>Date</th>
            <th>Participants</th>
            <th>Status</th>
        </tr>
        <?php if(empty($reservations)): ?>
        <tr><td colspan="6" style="text-align:center;">No reservations found</td></tr>
        <?php else: ?>
        <?php foreach($reservations as $i => $r): ?>
        <tr>
            <td><?= $i+1 ?></td>
            <td><?= htmlspecialchars($r['user_name']) ?></td>
            <td><?= htmlspecialchars($r['event_name']) ?></td>
            <td><?= htmlspecialchars($r['date']) ?></td>
            <td><?= htmlspecialchars($r['participants']) ?></td>
            <td><span class="status <?= $r['status'] ?>"><?= $r['status'] ?></span></td>
        </tr>
        <?php endforeach; ?>
        <?php endif; ?>
    </table><br><br>

    <a href="admin_dashboard.php" class="btn back">← Go Back to Dashboard</a>
</div>
</body>
</html>
