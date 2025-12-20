<?php

namespace App\Models;

use PDO;

class User {
    private $conn;
    private $table_name = "user";

    public $id_user;
    public $dni_user;
    public $username_user;
    public $email_user;
    public $password_user;

    public function __construct($db) {
        $this->conn = $db;
    }

    public function create() {
        $query = "INSERT INTO " . $this->table_name . " SET dni_user=:dni, username_user=:username, email_user=:email, password_user=:password";
        $stmt = $this->conn->prepare($query);
        $this->password_user = password_hash($this->password_user, PASSWORD_DEFAULT);
        $stmt->bindParam(":dni", $this->dni_user);
        $stmt->bindParam(":username", $this->username_user);
        $stmt->bindParam(":email", $this->email_user);
        $stmt->bindParam(":password", $this->password_user);
        if($stmt->execute()) {
            return true;
        }
        return false;
    }

    public function read() {
        $query = "SELECT id_user, dni_user, username_user, email_user FROM " . $this->table_name . " ORDER BY id_user DESC";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }

    public function readOne() {
        $query = "SELECT id_user, dni_user, username_user, email_user FROM " . $this->table_name . " WHERE id_user = ? LIMIT 0,1";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $this->id_user);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        $this->dni_user = $row['dni_user'];
        $this->username_user = $row['username_user'];
        $this->email_user = $row['email_user'];
    }

    public function update() {
        $query = "UPDATE " . $this->table_name . " SET dni_user=:dni, username_user=:username, email_user=:email WHERE id_user=:id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":dni", $this->dni_user);
        $stmt->bindParam(":username", $this->username_user);
        $stmt->bindParam(":email", $this->email_user);
        $stmt->bindParam(":id", $this->id_user);
        if($stmt->execute()) {
            return true;
        }
        return false;
    }

    public function delete() {
        $query = "DELETE FROM " . $this->table_name . " WHERE id_user = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $this->id_user);
        if($stmt->execute()) {
            return true;
        }
        return false;
    }

    public function login($username, $password) {
        $query = "SELECT id_user, password_user FROM " . $this->table_name . " WHERE username_user = ? LIMIT 0,1";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $username);
        $stmt->execute();
        if($stmt->rowCount() > 0) {
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            if(password_verify($password, $row['password_user'])) {
                return $row['id_user'];
            }
        }
        return false;
    }
}