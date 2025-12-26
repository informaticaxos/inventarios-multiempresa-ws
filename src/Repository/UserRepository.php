<?php

namespace App\Repository;

use PDO;
use App\Models\User;

class UserRepository {
    private $conn;
    private $table_name = "user";

    public function __construct($db) {
        $this->conn = $db;
    }

    // Paginación de usuarios
    public function readPaginated($limit, $offset) {
        $query = "SELECT id_user, dni_user, username_user, email_user FROM " . $this->table_name . " ORDER BY id_user DESC LIMIT :limit OFFSET :offset";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
        $stmt->bindParam(':offset', $offset, PDO::PARAM_INT);
        $stmt->execute();
        $users = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $totalQuery = "SELECT COUNT(*) as total FROM " . $this->table_name;
        $totalStmt = $this->conn->prepare($totalQuery);
        $totalStmt->execute();
        $total = $totalStmt->fetch(PDO::FETCH_ASSOC)['total'];
        return [
            'users' => $users,
            'total' => (int)$total
        ];
    }

    public function create(User $user) {
        // Check if user with this DNI already exists
        $query_check = "SELECT id_user FROM " . $this->table_name . " WHERE dni_user = :dni LIMIT 1";
        $stmt_check = $this->conn->prepare($query_check);
        $stmt_check->bindParam(":dni", $user->dni_user);
        $stmt_check->execute();
        if ($stmt_check->rowCount() > 0) {
            return ['success' => false, 'message' => 'User with this DNI already exists.'];
        }

        // Check if user with this username already exists
        $query_check_username = "SELECT id_user FROM " . $this->table_name . " WHERE username_user = :username LIMIT 1";
        $stmt_check_username = $this->conn->prepare($query_check_username);
        $stmt_check_username->bindParam(":username", $user->username_user);
        $stmt_check_username->execute();
        if ($stmt_check_username->rowCount() > 0) {
            return ['success' => false, 'message' => 'User with this username already exists.'];
        }

        $query = "INSERT INTO " . $this->table_name . " SET dni_user=:dni, username_user=:username, email_user=:email, password_user=:password";
        $stmt = $this->conn->prepare($query);
        $user->password_user = password_hash($user->password_user, PASSWORD_DEFAULT);
        $stmt->bindParam(":dni", $user->dni_user);
        $stmt->bindParam(":username", $user->username_user);
        $stmt->bindParam(":email", $user->email_user);
        $stmt->bindParam(":password", $user->password_user);
        if($stmt->execute()) {
            return ['success' => true, 'message' => ''];
        }
        return ['success' => false, 'message' => 'Unable to create user.'];
    }

    public function read() {
        $query = "SELECT id_user, dni_user, username_user, email_user FROM " . $this->table_name . " ORDER BY id_user DESC";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }

    public function readOne($id) {
        $query = "SELECT id_user, dni_user, username_user, email_user FROM " . $this->table_name . " WHERE id_user = ? LIMIT 0,1";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $id);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        if($row) {
            $user = new User();
            $user->id_user = $row['id_user'];
            $user->dni_user = $row['dni_user'];
            $user->username_user = $row['username_user'];
            $user->email_user = $row['email_user'];
            return $user;
        }
        return null;
    }

    public function update(User $user) {
        $query = "UPDATE " . $this->table_name . " SET dni_user=:dni, username_user=:username, email_user=:email WHERE id_user=:id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":dni", $user->dni_user);
        $stmt->bindParam(":username", $user->username_user);
        $stmt->bindParam(":email", $user->email_user);
        $stmt->bindParam(":id", $user->id_user);
        if($stmt->execute()) {
            return true;
        }
        return false;
    }

    public function delete($id) {
        $query = "DELETE FROM " . $this->table_name . " WHERE id_user = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $id);
        if($stmt->execute()) {
            return true;
        }
        return false;
    }

    // Login con detalle de error
    public function loginWithDetail($username, $password) {
        $query = "SELECT id_user, dni_user, username_user, email_user, password_user FROM " . $this->table_name . " WHERE username_user = ? LIMIT 0,1";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $username);
        $stmt->execute();
        if ($stmt->rowCount() === 0) {
            return [
                'success' => false,
                'message' => 'Usuario no encontrado.'
            ];
        }
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        if (!password_verify($password, $row['password_user'])) {
            return [
                'success' => false,
                'message' => 'Contraseña incorrecta.'
            ];
        }
        $user = new User();
        $user->id_user = $row['id_user'];
        $user->dni_user = $row['dni_user'];
        $user->username_user = $row['username_user'];
        $user->email_user = $row['email_user'];
        return [
            'success' => true,
            'user' => $user
        ];
    }
}