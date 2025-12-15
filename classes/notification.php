<?php

require_once "database.php";

class Notification extends Database {


    public function createNotification($user_id, $message) {
        $sql = "INSERT INTO notifications (user_id, message) VALUES (:user_id, :message)";
        $query = $this->connect()->prepare($sql);
        $query->bindParam(":user_id", $user_id);
        $query->bindParam(":message", $message);
        return $query->execute();
    }


    public function getUserNotifications($user_id) {
        $sql = "SELECT * FROM notifications WHERE user_id = :user_id ORDER BY created_at DESC";
        $query = $this->connect()->prepare($sql);
        $query->bindParam(":user_id", $user_id);
        $query->execute();
        return $query->fetchAll();
    }


    public function markAsRead($notification_id) {
        $sql = "UPDATE notifications SET is_read = 1 WHERE notification_id = :id";
        $query = $this->connect()->prepare($sql);
        $query->bindParam(":id", $notification_id);
        return $query->execute();
    }

    public function getUnreadCount($user_id) {
        $sql = "SELECT COUNT(*) AS unread_count FROM notifications WHERE user_id = :user_id AND is_read = 0";
        $query = $this->connect()->prepare($sql);
        $query->bindParam(":user_id", $user_id);
        $query->execute();
        $result = $query->fetch(PDO::FETCH_ASSOC);
        return $result ? $result["unread_count"] : 0;
    }
}
