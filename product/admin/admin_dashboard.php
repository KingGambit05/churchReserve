<?php
session_start();

if (!isset($_SESSION["user_id"]) || $_SESSION["role"] !== "admin") {
    header("Location: ../auth/login.php");
    exit();
}

require_once "../../classes/admin.php";

$adminObj = new Admin();

$reservationStats = $adminObj->getReservationStatsByStatus();

$totalUsers = $adminObj->getTotalUsers();
$totalReservations = $adminObj->getTotalReservations();
$pendingReservations = $adminObj->getPendingReservations();
$totalApproved = $adminObj->getApprovedReservations();
$totalDeclined = $adminObj->getDeclinedReservations();
$totalCancelled = $adminObj->getCancelledReservations();
$popularEvents = $adminObj->getPopularEventType();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <style>
        body { 
            font-family: Arial, sans-serif; 
            background: #f4f4f4; 
            padding: 40px; 
        }

        .container { 
            background: #fff; 
            padding: 30px; 
            max-width: 800px; 
            margin: auto; 
            border-radius: 12px; 
            box-shadow: 0 0 12px rgba(0,0,0,0.1); 
        }

        h2 { 
            text-align: center; 
            color: #2c3e50; 
        }

        .stats {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
            gap: 20px;
            margin-top: 30px;
        }

        .card {
            background: #2980b9;
            color: white;
            border-radius: 10px;
            padding: 20px;
            text-align: center;
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
            transition: transform 0.2s ease;
        }

        .card:hover {
            transform: translateY(-5px);
        }

        .card h3 {
            font-size: 2.2rem;
            margin: 0;
        }

        .card p {
            margin: 5px 0 0;
            font-size: 1rem;
            opacity: 0.9;
        }

        .buttons {
            text-align: center;
            margin-top: 40px;
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

        .btn:hover {
            background-color: #0056b3;
            transform: translateY(-2px);
        }

        .btn.logout {
            background-color: #e74c3c;
        }

        .btn.logout:hover {
            background-color: #c0392b;
        }
    </style>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body>
    <div class="container">
        <h2>Welcome, Admin</h2>

        <div class="stats">
            <div class="card">
                <h3><?= $totalUsers ?></h3>
                <p>Total Users</p>
            </div>
            <div class="card">
                <h3><?= $totalReservations ?></h3>
                <p>Total Reservations</p>
            </div>
            <div class="card">
                <h3><?= $pendingReservations ?></h3>
                <p>Pending Reservations</p>
            </div>
            <div class="card">
                <h3><?= $totalApproved ?></h3>
                <p>Approved Reservations</p>
            </div>
            <div class="card">
                <h3><?= $totalDeclined ?></h3>
                <p>Declined Reservations</p>
            </div>
            <div class="card">
                <h3><?= $totalCancelled ?></h3>
                <p>Cancelled Reservations</p>
            </div>
        </div>

        <div style="margin-top:40px;">
            <h3 style="text-align:center;">Reservations by Status</h3>
            <canvas id="reservationChart"></canvas>
        </div>  


        <div class="buttons">
            <a href="viewEventType.php" class="btn">Event Type</a>
            <a href="eventType_dashboard.php" class="btn">Add Event Type</a>
            <a href="view_reservation.php" class="btn">View Reservations</a>
            <a href="manage_prices.php" class="btn">Manage Price</a>
            <a href="reports.php" class="btn">Reports</a>
            <a href="../auth/logout.php" class="btn logout">Logout</a>
        </div>
    </div>

</body>
<script>
const ctx = document.getElementById('reservationChart').getContext('2d');

new Chart(ctx, {
    type: 'bar',
    data: {
        labels: ['Pending', 'Approved', 'Declined', 'Cancelled'],
        datasets: [{
            label: 'Reservations',
            data: [
                <?= $reservationStats['pending'] ?>,
                <?= $reservationStats['approved'] ?>,
                <?= $reservationStats['declined'] ?>,
                <?= $reservationStats['cancelled'] ?>
            ],
            backgroundColor: [
                '#f1c40f',
                '#2ecc71',
                '#e74c3c',
                '#95a5a6'
            ]
        }]
    },
    options: {
        responsive: true,
        plugins: {
            legend: { display: false }
        },
        scales: {
            y: {
                beginAtZero: true
            }
        }
    }
});
</script>
</html>
