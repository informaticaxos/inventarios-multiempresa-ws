<?php

namespace App\Repository;

use PDO;
use App\Models\Product;

class ProductRepository {
    private $conn;
    private $table_name = "product";

    public function __construct($db) {
        $this->conn = $db;
    }

    public function create(Product $product) {
        $query = "INSERT INTO " . $this->table_name . " SET code_product=:code, name_product=:name, description_product=:description, pvp_product=:pvp, min_product=:min, max_product=:max, state_product=:state";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":code", $product->code_product);
        $stmt->bindParam(":name", $product->name_product);
        $stmt->bindParam(":description", $product->description_product);
        $stmt->bindParam(":pvp", $product->pvp_product);
        $stmt->bindParam(":min", $product->min_product);
        $stmt->bindParam(":max", $product->max_product);
        $stmt->bindParam(":state", $product->state_product);
        if($stmt->execute()) {
            return ['success' => true, 'message' => ''];
        }
        return ['success' => false, 'message' => 'Unable to create product.'];
    }

    public function read() {
        $query = "SELECT id_product, code_product, name_product, description_product, pvp_product, min_product, max_product, created_product, updated_product, state_product FROM " . $this->table_name . " ORDER BY id_product DESC";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }

    public function readOne($id) {
        $query = "SELECT id_product, code_product, name_product, description_product, pvp_product, min_product, max_product, created_product, updated_product, state_product FROM " . $this->table_name . " WHERE id_product = ? LIMIT 0,1";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $id);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        if($row) {
            $product = new Product();
            $product->id_product = $row['id_product'];
            $product->code_product = $row['code_product'];
            $product->name_product = $row['name_product'];
            $product->description_product = $row['description_product'];
            $product->pvp_product = $row['pvp_product'];
            $product->min_product = $row['min_product'];
            $product->max_product = $row['max_product'];
            $product->created_product = $row['created_product'];
            $product->updated_product = $row['updated_product'];
            $product->state_product = $row['state_product'];
            return $product;
        }
        return null;
    }

    public function update(Product $product) {
        $query = "UPDATE " . $this->table_name . " SET code_product=:code, name_product=:name, description_product=:description, pvp_product=:pvp, min_product=:min, max_product=:max, state_product=:state WHERE id_product=:id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":code", $product->code_product);
        $stmt->bindParam(":name", $product->name_product);
        $stmt->bindParam(":description", $product->description_product);
        $stmt->bindParam(":pvp", $product->pvp_product);
        $stmt->bindParam(":min", $product->min_product);
        $stmt->bindParam(":max", $product->max_product);
        $stmt->bindParam(":state", $product->state_product);
        $stmt->bindParam(":id", $product->id_product);
        if($stmt->execute()) {
            return true;
        }
        return false;
    }

    public function delete($id) {
        $query = "DELETE FROM " . $this->table_name . " WHERE id_product = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $id);
        if($stmt->execute()) {
            return true;
        }
        return false;
    }
}