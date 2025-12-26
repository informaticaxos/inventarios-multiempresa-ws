<?php

namespace App\Controllers;

use App\Repository\StoreProductRepository;
use App\Models\StoreProduct;
use PDO;

class StoreProductController {
    private $db;
    private $storeProductRepository;

    public function __construct($db) {
        $this->db = $db;
        $this->storeProductRepository = new StoreProductRepository($db);
    }

    public function create() {
        $data = json_decode(file_get_contents("php://input"));
        if (!empty($data->fk_id_store) && !empty($data->fk_id_product)) {
            $sp = new StoreProduct();
            $sp->fk_id_store = $data->fk_id_store;
            $sp->fk_id_product = $data->fk_id_product;
            $sp->stock_product = $data->stock_product ?? 0;
            $result = $this->storeProductRepository->create($sp);
            if ($result) {
                http_response_code(201);
                echo json_encode(["state" => 1, "message" => "Stock asignado/actualizado.", "data" => []]);
            } else {
                http_response_code(400);
                echo json_encode(["state" => 0, "message" => "No se pudo asignar stock.", "data" => []]);
            }
        } else {
            http_response_code(400);
            echo json_encode(["state" => 0, "message" => "Datos incompletos.", "data" => []]);
        }
    }

    public function readByProduct($productId) {
        $stmt = $this->storeProductRepository->readByProduct($productId);
        $arr = [];
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $arr[] = $row;
        }
        echo json_encode(["state" => 1, "message" => "Stock por producto.", "data" => $arr]);
    }

    public function readByStore($storeId) {
        $stmt = $this->storeProductRepository->readByStore($storeId);
        $arr = [];
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $arr[] = $row;
        }
        echo json_encode(["state" => 1, "message" => "Stock por bodega.", "data" => $arr]);
    }

    public function update() {
        $data = json_decode(file_get_contents("php://input"));
        if (!empty($data->fk_id_store) && !empty($data->fk_id_product)) {
            $sp = new StoreProduct();
            $sp->fk_id_store = $data->fk_id_store;
            $sp->fk_id_product = $data->fk_id_product;
            $sp->stock_product = $data->stock_product ?? 0;
            $result = $this->storeProductRepository->update($sp);
            if ($result) {
                echo json_encode(["state" => 1, "message" => "Stock actualizado.", "data" => []]);
            } else {
                echo json_encode(["state" => 0, "message" => "No se pudo actualizar el stock.", "data" => []]);
            }
        } else {
            echo json_encode(["state" => 0, "message" => "Datos incompletos.", "data" => []]);
        }
    }

    public function delete() {
        $data = json_decode(file_get_contents("php://input"));
        if (!empty($data->fk_id_store) && !empty($data->fk_id_product)) {
            $result = $this->storeProductRepository->delete($data->fk_id_store, $data->fk_id_product);
            if ($result) {
                echo json_encode(["state" => 1, "message" => "Relación eliminada.", "data" => []]);
            } else {
                echo json_encode(["state" => 0, "message" => "No se pudo eliminar la relación.", "data" => []]);
            }
        } else {
            echo json_encode(["state" => 0, "message" => "Datos incompletos.", "data" => []]);
        }
    }
}
