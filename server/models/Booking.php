<?php
require_once __DIR__ . '/../config/Database.php';

class Booking {
    private $conn;

    public function __construct() {
        $database = new Database();
        $this->conn = $database->connect();
    }

    public function create($userId, $name, $email, $date, $time, $persons) {
        $query = "INSERT INTO bookings (user_id, name, email, date, time, persons) 
                  VALUES (:user_id, :name, :email, :date, :time, :persons)";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':user_id', $userId);
        $stmt->bindParam(':name', $name);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':date', $date);
        $stmt->bindParam(':time', $time);
        $stmt->bindParam(':persons', $persons, PDO::PARAM_INT);
        return $stmt->execute();
    }

    public function findByUserId($userId) {
        $query = "SELECT * FROM bookings WHERE user_id = :user_id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':user_id', $userId);
        $stmt->execute();
        return $stmt->fetchAll();
    }
}
?>