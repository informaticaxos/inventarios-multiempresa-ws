<?php

namespace App\Models;

class Store {
    public $id_store;
    public $code_store;
    public $name_store;
    public $address_store;
    public $location_store;
    public $fk_id_company;

    public function __construct() {
    }
}