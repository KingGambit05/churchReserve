<?php

require_once "database.php";
require_once "notification.php";

class Admin extends Database {

    public function getAllReservations() {
        $sql = "SELECT r.*, u.userName AS user_name, e.event_name FROM reservations r JOIN users u ON r.user_id = u.user_id JOIN event_types e ON r.event_type_id = e.event_type_id ORDER BY r.created_at DESC";
        $query = $this->connect()->prepare($sql);
        $query->execute();
        return $query->fetchAll();
    }


    public function updateReservationStatus($reservation_id, $status) {
        $sql = "UPDATE reservations SET status = :status WHERE reservation_id = :id";
        $query = $this->connect()->prepare($sql);
        $query->bindParam(":status", $status);
        $query->bindParam(":id", $reservation_id);
        
        if ($query->execute()) {
            $getUser = $this->connect()->prepare("SELECT user_id FROM reservations WHERE reservation_id = :id");
            $getUser->bindParam(":id", $reservation_id);
            $getUser->execute();
            $user = $getUser->fetch();

            if ($user) {
                $notification = new Notification();
                $msg = "Your reservation (ID: $reservation_id) has been " . strtoupper($status) . ".";
                $notification->createNotification($user["user_id"], $msg);
            }
            return true;
        }
        return false;
    }

    public function getTotalUsers() {
        $sql = "SELECT COUNT(*) as total FROM users";
        $query = $this->connect()->prepare($sql);
        $query->execute();
        $result = $query->fetch();

        return $result['total'] ?? 0;
    }

    public function getTotalReservations() {
        $sql = "SELECT COUNT(*) as total FROM reservations";
        $query = $this->connect()->prepare($sql);
        $query->execute();
        $result = $query->fetch();

        return $result['total'] ?? 0;
    }

    public function getPendingReservations() {
        $sql = "SELECT COUNT(*) as total FROM reservations WHERE status='pending'";
        $query = $this->connect()->prepare($sql);
        $query->execute();
        $result = $query->fetch();

        return $result['total'] ?? 0;
    }

    public function getApprovedReservations() {
        $sql = "SELECT COUNT(*) as total FROM reservations WHERE status='approved'";
        $query = $this->connect()->prepare($sql);
        $query->execute();
        $result = $query->fetch(PDO::FETCH_ASSOC);
        return $result['total'] ?? 0;
    }

    public function getDeclinedReservations() {
        $sql = "SELECT COUNT(*) as total FROM reservations WHERE status='declined'";
        $query = $this->connect()->prepare($sql);
        $query->execute();
        $result = $query->fetch(PDO::FETCH_ASSOC);
        return $result['total'] ?? 0;
    }

    public function getCancelledReservations() {
        $sql = "SELECT COUNT(*) as total FROM reservations WHERE status='cancelled'";
        $query = $this->connect()->prepare($sql);
        $query->execute();
        $result = $query->fetch(PDO::FETCH_ASSOC);
        return $result['total'] ?? 0;
    }

    public function getPopularEventType($limit = 5) {
        $sql = "SELECT et.event_name, COUNT(r.reservation_id) AS total_reservations
                FROM reservations r
                JOIN event_types et ON r.event_type_id = et.event_type_id
                GROUP BY r.event_type_id
                ORDER BY total_reservations DESC
                LIMIT :limit";
        $query = $this->connect()->prepare($sql);
        $query->bindValue(':limit', (int)$limit, PDO::PARAM_INT);
        $query->execute();
        return $query->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getReservationStatsByStatus() {
        $sql = "SELECT status, COUNT(*) as total FROM reservations GROUP BY status";

        $query = $this->connect()->prepare($sql);
        $query->execute();

        $data = [
            'pending' => 0,
            'approved' => 0,
            'declined' => 0,
            'cancelled' => 0
        ];

        while ($row = $query->fetch()) {
            $data[$row['status']] = $row['total'];
        }

        return $data;
    }
}
