<?php

namespace App\Controllers;

use App\Repository\ProductRepository;
use App\Models\Product;
use PDO;

class ProductController {
    private $db;
    private $productRepository;

    public function __construct($db) {
        $this->db = $db;
        $this->productRepository = new ProductRepository($db);
    }

    public function create() {
        $data = json_decode(file_get_contents("php://input"));
        if(!empty($data->code_product) && !empty($data->name_product)) {
            $product = new Product();
            $product->code_product = $data->code_product;
            $product->name_product = $data->name_product;
            $product->description_product = $data->description_product ?? '';
            $product->pvp_product = $data->pvp_product ?? 0;
            $product->min_product = $data->min_product ?? 0;
            $product->max_product = $data->max_product ?? 0;
            $product->state_product = $data->state_product ?? 1;
            $result = $this->productRepository->create($product);
            if($result['success']) {
                http_response_code(201);
                echo json_encode(array("state" => 1, "message" => "Product was created.", "data" => array()));
            } else {
                http_response_code(400);
                echo json_encode(array("state" => 0, "message" => $result['message'], "data" => array()));
            }
        } else {
            http_response_code(400);
            echo json_encode(array("state" => 0, "message" => "Unable to create product. Data is incomplete.", "data" => array()));
        }
    }

    public function read() {
        $stmt = $this->productRepository->read();
        $num = $stmt->rowCount();
        if($num > 0) {
            $products_arr = array();
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $product_item = array(
                    "id_product" => $row['id_product'],
                    "code_product" => $row['code_product'],
                    "name_product" => $row['name_product'],
                    "description_product" => $row['description_product'],
                    "pvp_product" => $row['pvp_product'],
                    "min_product" => $row['min_product'],
                    "max_product" => $row['max_product'],
                    "created_product" => $row['created_product'],
                    "updated_product" => $row['updated_product'],
                    "state_product" => $row['state_product']
                );
                array_push($products_arr, $product_item);
            }
            http_response_code(200);
            echo json_encode(array("state" => 1, "message" => "Products found.", "data" => $products_arr));
        } else {
            http_response_code(404);
            echo json_encode(array("state" => 0, "message" => "No products found.", "data" => array()));
        }
    }

    public function readOne($id) {
        $product = $this->productRepository->readOne($id);
        if($product) {
            $product_arr = array(
                "id_product" => $product->id_product,
                "code_product" => $product->code_product,
                "name_product" => $product->name_product,
                "description_product" => $product->description_product,
                "pvp_product" => $product->pvp_product,
                "min_product" => $product->min_product,
                "max_product" => $product->max_product,
                "created_product" => $product->created_product,
                "updated_product" => $product->updated_product,
                "state_product" => $product->state_product
            );
            http_response_code(200);
            echo json_encode(array("state" => 1, "message" => "Product found.", "data" => array($product_arr)));
        } else {
            http_response_code(404);
            echo json_encode(array("state" => 0, "message" => "Product not found.", "data" => array()));
        }
    }

    public function update($id) {
        $data = json_decode(file_get_contents("php://input"));
        if(!empty($data->code_product) && !empty($data->name_product)) {
            $product = new Product();
            $product->id_product = $id;
            $product->code_product = $data->code_product;
            $product->name_product = $data->name_product;
            $product->description_product = $data->description_product ?? '';
            $product->pvp_product = $data->pvp_product ?? 0;
            $product->min_product = $data->min_product ?? 0;
            $product->max_product = $data->max_product ?? 0;
            $product->state_product = $data->state_product ?? 1;
            if($this->productRepository->update($product)) {
                http_response_code(200);
                echo json_encode(array("state" => 1, "message" => "Product was updated.", "data" => array()));
            } else {
                http_response_code(503);
                echo json_encode(array("state" => 0, "message" => "Unable to update product.", "data" => array()));
            }
        } else {
            http_response_code(400);
            echo json_encode(array("state" => 0, "message" => "Unable to update product. Data is incomplete.", "data" => array()));
        }
    }

    public function delete($id) {
        if($this->productRepository->delete($id)) {
            http_response_code(200);
            echo json_encode(array("state" => 1, "message" => "Product was deleted.", "data" => array()));
        } else {
            http_response_code(503);
            echo json_encode(array("state" => 0, "message" => "Unable to delete product.", "data" => array()));
        }
    }
}