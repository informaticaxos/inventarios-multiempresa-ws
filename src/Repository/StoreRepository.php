<?php

namespace App\Repository;

use PDO;
use App\Models\Store;

class StoreRepository {
    private $conn;
    private $table_name = "store";

    public function __construct($db) {
        $this->conn = $db;
    }

    public function create(Store $store) {
        // Check if store with this code already exists
        $query_check = "SELECT id_store FROM " . $this->table_name . " WHERE code_store = :code LIMIT 1";
        $stmt_check = $this->conn->prepare($query_check);
        $stmt_check->bindParam(":code", $store->code_store);
        $stmt_check->execute();
        if ($stmt_check->rowCount() > 0) {
            return ['success' => false, 'message' => 'Store with this code already exists.'];
        }

        $query = "INSERT INTO " . $this->table_name . " SET code_store=:code, name_store=:name, address_store=:address, location_store=:location, fk_id_company=:fk_company";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":code", $store->code_store);
        $stmt->bindParam(":name", $store->name_store);
        $stmt->bindParam(":address", $store->address_store);
        $stmt->bindParam(":location", $store->location_store);
        $stmt->bindParam(":fk_company", $store->fk_id_company);
        if($stmt->execute()) {
            return ['success' => true, 'message' => ''];
        }
        return ['success' => false, 'message' => 'Unable to create store.'];
    }

    public function read() {
        $query = "SELECT id_store, code_store, name_store, address_store, location_store, fk_id_company FROM " . $this->table_name . " ORDER BY id_store DESC";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }

    public function readOne($id) {
        $query = "SELECT id_store, code_store, name_store, address_store, location_store, fk_id_company FROM " . $this->table_name . " WHERE id_store = ? LIMIT 0,1";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $id);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        if($row) {
            $store = new Store();
            $store->id_store = $row['id_store'];
            $store->code_store = $row['code_store'];
            $store->name_store = $row['name_store'];
            $store->address_store = $row['address_store'];
            $store->location_store = $row['location_store'];
            $store->fk_id_company = $row['fk_id_company'];
            return $store;
        }
        return null;
    }

    public function update(Store $store) {
        $query = "UPDATE " . $this->table_name . " SET code_store=:code, name_store=:name, address_store=:address, location_store=:location, fk_id_company=:fk_company WHERE id_store=:id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":code", $store->code_store);
        $stmt->bindParam(":name", $store->name_store);
        $stmt->bindParam(":address", $store->address_store);
        $stmt->bindParam(":location", $store->location_store);
        $stmt->bindParam(":fk_company", $store->fk_id_company);
        $stmt->bindParam(":id", $store->id_store);
        if($stmt->execute()) {
            return true;
        }
        return false;
    }

    public function delete($id) {
        $query = "DELETE FROM " . $this->table_name . " WHERE id_store = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $id);
        if($stmt->execute()) {
            return true;
        }
        return false;
    }
}