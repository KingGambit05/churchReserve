<?php 

require_once "database.php";

class User extends Database{
    public $user_id = "";
    public $userName = "";
    public $lastName = "";
    public $email = "";
    public $password = "";
    public $phone = "";
    public $role = "";
    public $created_at = "";
    public $updated_at = "";

    public function userRegister(){
        $hashedPassword = password_hash($this->password, PASSWORD_DEFAULT);

        $sql = "INSERT INTO users (userName, lastName, email, password, phone, role) VALUES (:userName, :lastName, :email, :password, :phone, :role)";

        $query = $this->connect()->prepare($sql);

        $query->bindParam(":userName", $this->userName);
        $query->bindParam(":lastName", $this->lastName);
        $query->bindParam(":email", $this->email);
        $query->bindParam(":password", $hashedPassword);
        $query->bindParam(":phone", $this->phone);
        $query->bindParam(":role", $this->role);

        return $query->execute();
    }

    public function userLogin() {
        $sql = "SELECT * FROM users WHERE email = :email LIMIT 1";
        $query = $this->connect()->prepare($sql);
        $query->bindParam(":email", $this->email);
        $query->execute();

        $user = $query->fetch();

    
        if ($user && password_verify($this->password, $user['password'])) {
        
            if (session_status() === PHP_SESSION_NONE) {
            session_start();
            }

        
            $_SESSION['user_id'] = $user['user_id'];
            $_SESSION['name'] = $user['name'];
            $_SESSION['role'] = $user['role'];

            return true; 
        } else {
            return false; 
        }
    }

    public function getUserById($id) {
        $sql = "SELECT * FROM users WHERE user_id = :id";
        $stmt = $this->connect()->prepare($sql);
        $stmt->bindParam(":id", $id);
        $stmt->execute();
        return $stmt->fetch();
    }
}