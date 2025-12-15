<?php
session_start();

if (!isset($_SESSION["user_id"]) || $_SESSION["role"] !== "user") {
    header("Location: ../auth/login.php");
    exit;
}

require_once "../../classes/notification.php";
$notifObj = new Notification();
$unreadCount = $notifObj->getUnreadCount($_SESSION["user_id"]);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Dashboard</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: #f5f6fa;
            margin: 0;
            padding: 0;
        }
        header {
            background: #34495e;
            color: white;
            padding: 1rem 2rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .notif-bell {
            font-size: 22px;
            text-decoration: none;
            position: relative;
        }

        .notif-badge {
            position: absolute;
            top: -8px;
            right: -10px;
            background: red;
            color: white;
            font-size: 12px;
            font-weight: bold;
            border-radius: 50%;
            padding: 3px 7px;
        }
        header h1 {
            font-size: 1.5rem;
            margin: 0;
        }
        nav a {
            color: white;
            text-decoration: none;
            margin-left: 1.5rem;
            font-weight: 500;
        }
        nav a:hover {
            text-decoration: underline;
        }
        .container {
            max-width: 1000px;
            margin: 2rem auto;
            background: white;
            padding: 2rem;
            border-radius: 10px;
            box-shadow: 0 4px 10px rgba(0,0,0,0.1);
        }
        .welcome {
            margin-bottom: 2rem;
        }
        .card-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 1.5rem;
        }
        .card {
            background: #ecf0f1;
            padding: 1.5rem;
            border-radius: 8px;
            text-align: center;
            transition: 0.3s ease;
        }
        .card:hover {
            background: #dcdde1;
            transform: translateY(-5px);
        }
        .card a {
            text-decoration: none;
            color: #2c3e50;
            font-weight: 600;
        }
        footer {
            text-align: center;
            padding: 1rem;
            color: #555;
            margin-top: 3rem;
        }
    </style>
</head>
<body>
    <header>
        <h1>Church Reservation System</h1>
        <nav>
            <a href="../reservation/addReservation.php">Make Reservation</a>
            <a href="../reservation/viewReservation.php">My Reservations</a>
            <a href="../auth/logout.php">Logout</a>

        
            <a href="viewNotification.php" class="notif-bell">
                🔔
                <?php if ($unreadCount > 0): ?>
                    <span class="notif-badge"><?= $unreadCount ?></span>
                <?php endif; ?>
            </a>
        </nav>
    </header>

    <div class="container">
        <div class="welcome">
            <h2>Welcome</h2>
            <p>Thank you for being part of our church community. You can easily reserve for events, check your reservations, or update your information.</p>
        </div>

        <div class="card-grid">
            <div class="card">
                <a href="../reservation/addReservation.php"> Make a Reservation</a>
            </div>
            <div class="card">
                <a href="../reservation/viewReservation.php"> View My Reservations</a>
            </div>
            <div class="card">
                <a href="calendar.php"> Church Calendar</a>
            </div>
            <div class="card">
                <a href="../auth/logout.php"> Logout</a>
            </div>
        </div>
    </div>

    <footer>
        © <?= date('Y') ?> Church Reservation System. All rights reserved.
    </footer>
</body>
</html>
