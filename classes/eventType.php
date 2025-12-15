<?php

require_once "database.php";

class EventType extends Database {
    public $event_type_id = "";
    public $event_name = "";
    public $description = "";
    public $price = "";

    public function getEventTypes() {
        $sql = "SELECT * FROM event_types ORDER BY event_name ASC";
        $query = $this->connect()->prepare($sql);
        
        if ($query->execute()) {
            return $query->fetchAll();
        } else {
            return null;
        }
    }

    public function getEventById($event_type_id) {
        $sql = "SELECT * FROM event_types WHERE event_type_id = :event_type_id";
        $query = $this->connect()->prepare($sql);
        $query->bindParam(":event_type_id", $event_type_id);
        
        if ($query->execute()) {
            return $query->fetch();
        } else {
            return null;
        }
    }
    
    public function addEventType(){
        $sql = "INSERT INTO event_types (event_type_id, event_name, description, price) VALUES (:event_type_id, :event_name, :description, :price)";


        $query = $this->connect()->prepare($sql);
        $query->bindParam(':event_type_id', $this->event_type_id);
        $query->bindParam(':event_name', $this->event_name);
        $query->bindParam(':description', $this->description);
        $query->bindParam("price", $this->price);

        return $query->execute();
    }

    public function viewEventType(){
        $sql = "SELECT * FROM event_types";
        $query = $this->connect()->prepare($sql);

        if($query->execute()){
            return $query->fetchAll();
        }else{
            return null;
        }
    }

    public function updateEventPrice($event_type_id, $price) {
        $sql = "UPDATE event_types SET price = :price WHERE event_type_id = :id";

        $query = $this->connect()->prepare($sql);
        $query->bindParam(":price", $price);
        $query->bindParam(":id", $event_type_id);

        return $query->execute();
    }

    public function deleteEventType($id) {
        $sql = "DELETE FROM event_types WHERE event_type_id = :id";
        $query = $this->connect()->prepare($sql);
        $query->bindParam(":id", $id);
        return $query->execute();
    }
    
    public function isEventTypeInUse($id) {
        $sql = "SELECT COUNT(*) FROM reservations WHERE event_type_id = :id";
        $query = $this->connect()->prepare($sql);
        $query->bindParam(":id", $id);
        $query->execute();
        return $query->fetchColumn() > 0;
    }
}
