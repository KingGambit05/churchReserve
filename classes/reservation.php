<?php
require_once "database.php";

class Reservation extends Database {
    public $user_id;
    public $event_type_id;
    public $date;
    public $time;
    public $participants;
    public $notes;
    public $status = 'pending';


    public function addReservation() {
        $sql = "INSERT INTO reservations (user_id, event_type_id, date, time, participants, notes, status) VALUES (:user_id, :event_type_id, :date, :time, :participants, :notes, :status)";
        
        $query = $this->connect()->prepare($sql);
        $query->bindParam(':user_id', $this->user_id);
        $query->bindParam(':event_type_id', $this->event_type_id);
        $query->bindParam(':date', $this->date);
        $query->bindParam(':time', $this->time);
        $query->bindParam(':participants', $this->participants);
        $query->bindParam(':notes', $this->notes);
        $query->bindParam(':status', $this->status);
        
        return $query->execute();
    }

    public function getUserReservations($user_id) {
        $sql = "SELECT r.*, e.event_name, e.price FROM reservations r JOIN event_types e ON r.event_type_id = e.event_type_id WHERE r.user_id = :user_id ORDER BY r.date DESC, r.time DESC";
        
        $query = $this->connect()->prepare($sql);
        $query->bindParam(':user_id', $user_id);
        $query->execute();
        
        return $query->fetchAll();
    }

    public function isDateConflict($date) {
        $sql = "SELECT COUNT(*) FROM reservations WHERE date = :date";
        $query = $this->connect()->prepare($sql);
        $query->bindParam(":date", $date);
        $query->execute();
        $count = $query->fetchColumn();
        return $count > 0; 
    }

    public function getAllReservationsWithEventNames() {
        $sql = "SELECT r.date, e.event_name, r.status FROM reservations r JOIN event_types e ON r.event_type_id = e.event_type_id WHERE r.status = 'approved'";
        $query = $this->connect()->prepare($sql);
        $query->execute();
        return $query->fetchAll();
    }
    public function cancelReservation($reservation_id, $user_id) {
        $sql = "UPDATE reservations SET status = 'cancelled' WHERE reservation_id = :id AND user_id = :user_id AND status = 'pending'";

        $query = $this->connect()->prepare($sql);
        $query->bindParam(":id", $reservation_id);
        $query->bindParam(":user_id", $user_id);
        return $query->execute();
    }

    public function getFilteredReservations($filters = []) {
        $sql = "SELECT r.*, u.userName as user_name, et.event_name
                FROM reservations r
                JOIN users u ON r.user_id = u.user_id
                JOIN event_types et ON r.event_type_id = et.event_type_id
                WHERE 1=1";

        if(!empty($filters['status'])) {
            $sql .= " AND r.status = :status";
        }
        if(!empty($filters['event_type_id'])) {
            $sql .= " AND r.event_type_id = :event_type_id";
        }
        if(!empty($filters['user_id'])) {
            $sql .= " AND r.user_id = :user_id";
        }
        if(!empty($filters['from_date'])) {
            $sql .= " AND r.date >= :from_date";
        }
        if(!empty($filters['to_date'])) {
            $sql .= " AND r.date <= :to_date";
        }

        $query = $this->connect()->prepare($sql);

        if(!empty($filters['status'])) $query->bindParam(':status', $filters['status']);
        if(!empty($filters['event_type_id'])) $query->bindParam(':event_type_id', $filters['event_type_id']);
        if(!empty($filters['user_id'])) $query->bindParam(':user_id', $filters['user_id']);
        if(!empty($filters['from_date'])) $query->bindParam(':from_date', $filters['from_date']);
        if(!empty($filters['to_date'])) $query->bindParam(':to_date', $filters['to_date']);

        $query->execute();
        return $query->fetchAll();
    }

    public function approveReservation($id) {
        $sql = "UPDATE reservations SET status = 'approved', updated_at = NOW() WHERE reservation_id = :id";
        $query = $this->connect()->prepare($sql);
        $query->bindParam(":id", $id);
        return $query->execute();
    }

    public function declineReservation($id) {
        $sql = "UPDATE reservations SET status = 'declined', updated_at = NOW() WHERE reservation_id = :id";
        $query = $this->connect()->prepare($sql);
        $query->bindParam(":id", $id);
        return $query->execute();
    }

    public function getReservationById($id) {
        $sql = "SELECT r.*, e.event_name FROM reservations r JOIN event_types e ON r.event_type_id = e.event_type_id WHERE r.reservation_id = :id";
        $query = $this->connect()->prepare($sql);
        $query->bindParam(":id", $id);
        $query->execute();
        return $query->fetch();
    }
}
