<?php

namespace App\Models;

class Product {
    public $id_product;
    public $code_product;
    public $name_product;
    public $description_product;
    public $pvp_product;
    public $min_product;
    public $max_product;
    public $created_product;
    public $updated_product;
    public $state_product;

    public function __construct() {
    }
}