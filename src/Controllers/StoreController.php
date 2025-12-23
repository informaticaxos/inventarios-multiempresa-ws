<?php

namespace App\Controllers;

use App\Repository\StoreRepository;
use App\Models\Store;
use PDO;

class StoreController {
    private $db;
    private $storeRepository;

    public function __construct($db) {
        $this->db = $db;
        $this->storeRepository = new StoreRepository($db);
    }

    public function create() {
        $data = json_decode(file_get_contents("php://input"));
        if(!empty($data->code_store) && !empty($data->name_store) && !empty($data->fk_id_company)) {
            $store = new Store();
            $store->code_store = $data->code_store;
            $store->name_store = $data->name_store;
            $store->address_store = $data->address_store ?? '';
            $store->location_store = $data->location_store ?? '';
            $store->fk_id_company = $data->fk_id_company;
            $result = $this->storeRepository->create($store);
            if($result['success']) {
                http_response_code(201);
                echo json_encode(array("state" => 1, "message" => "Store was created.", "data" => array()));
            } else {
                http_response_code(400);
                echo json_encode(array("state" => 0, "message" => $result['message'], "data" => array()));
            }
        } else {
            http_response_code(400);
            echo json_encode(array("state" => 0, "message" => "Unable to create store. Data is incomplete.", "data" => array()));
        }
    }

    public function read() {
        $stmt = $this->storeRepository->read();
        $num = $stmt->rowCount();
        if($num > 0) {
            $stores_arr = array();
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $store_item = array(
                    "id_store" => $row['id_store'],
                    "code_store" => $row['code_store'],
                    "name_store" => $row['name_store'],
                    "address_store" => $row['address_store'],
                    "location_store" => $row['location_store'],
                    "fk_id_company" => $row['fk_id_company']
                );
                array_push($stores_arr, $store_item);
            }
            http_response_code(200);
            echo json_encode(array("state" => 1, "message" => "Stores found.", "data" => $stores_arr));
        } else {
            http_response_code(404);
            echo json_encode(array("state" => 0, "message" => "No stores found.", "data" => array()));
        }
    }

    public function readOne($id) {
        $store = $this->storeRepository->readOne($id);
        if($store) {
            $store_arr = array(
                "id_store" => $store->id_store,
                "code_store" => $store->code_store,
                "name_store" => $store->name_store,
                "address_store" => $store->address_store,
                "location_store" => $store->location_store,
                "fk_id_company" => $store->fk_id_company
            );
            http_response_code(200);
            echo json_encode(array("state" => 1, "message" => "Store found.", "data" => array($store_arr)));
        } else {
            http_response_code(404);
            echo json_encode(array("state" => 0, "message" => "Store not found.", "data" => array()));
        }
    }

    public function update($id) {
        $data = json_decode(file_get_contents("php://input"));
        if(!empty($data->code_store) && !empty($data->name_store) && !empty($data->fk_id_company)) {
            $store = new Store();
            $store->id_store = $id;
            $store->code_store = $data->code_store;
            $store->name_store = $data->name_store;
            $store->address_store = $data->address_store ?? '';
            $store->location_store = $data->location_store ?? '';
            $store->fk_id_company = $data->fk_id_company;
            if($this->storeRepository->update($store)) {
                http_response_code(200);
                echo json_encode(array("state" => 1, "message" => "Store was updated.", "data" => array()));
            } else {
                http_response_code(503);
                echo json_encode(array("state" => 0, "message" => "Unable to update store.", "data" => array()));
            }
        } else {
            http_response_code(400);
            echo json_encode(array("state" => 0, "message" => "Unable to update store. Data is incomplete.", "data" => array()));
        }
    }

    public function delete($id) {
        if($this->storeRepository->delete($id)) {
            http_response_code(200);
            echo json_encode(array("state" => 1, "message" => "Store was deleted.", "data" => array()));
        } else {
            http_response_code(503);
            echo json_encode(array("state" => 0, "message" => "Unable to delete store.", "data" => array()));
        }
    }
}