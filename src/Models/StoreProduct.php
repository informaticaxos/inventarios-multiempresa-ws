<?php

namespace App\Models;

class StoreProduct {
    public $id_store_product;
    public $fk_id_store;
    public $fk_id_product;
    public $stock_product;

    public function __construct() {}
}
