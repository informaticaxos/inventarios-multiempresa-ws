<?php

namespace App\Repository;

use PDO;
use App\Models\StoreProduct;

class StoreProductRepository {
    private $conn;
    private $table_name = "store_product";

    public function __construct($db) {
        $this->conn = $db;
    }

    public function create(StoreProduct $sp) {
        $query = "INSERT INTO " . $this->table_name . " (fk_id_store, fk_id_product, stock_product) VALUES (:store, :product, :stock) ON DUPLICATE KEY UPDATE stock_product = :stock";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":store", $sp->fk_id_store);
        $stmt->bindParam(":product", $sp->fk_id_product);
        $stmt->bindParam(":stock", $sp->stock_product);
        return $stmt->execute();
    }

    public function readByProduct($productId) {
        $query = "SELECT * FROM " . $this->table_name . " WHERE fk_id_product = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $productId);
        $stmt->execute();
        return $stmt;
    }

    public function readByStore($storeId) {
        $query = "SELECT * FROM " . $this->table_name . " WHERE fk_id_store = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $storeId);
        $stmt->execute();
        return $stmt;
    }

    public function update(StoreProduct $sp) {
        $query = "UPDATE " . $this->table_name . " SET stock_product = :stock WHERE fk_id_store = :store AND fk_id_product = :product";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":stock", $sp->stock_product);
        $stmt->bindParam(":store", $sp->fk_id_store);
        $stmt->bindParam(":product", $sp->fk_id_product);
        return $stmt->execute();
    }

    public function delete($storeId, $productId) {
        $query = "DELETE FROM " . $this->table_name . " WHERE fk_id_store = ? AND fk_id_product = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $storeId);
        $stmt->bindParam(2, $productId);
        return $stmt->execute();
    }
}
