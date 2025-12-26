<?php

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: OPTIONS,GET,POST,PUT,DELETE");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
    http_response_code(200);
    exit();
}

require_once '../vendor/autoload.php';

use App\Config\Database;
use App\Controllers\UserController;
use App\Controllers\CompanyController;
use App\Controllers\StoreController;

use App\Controllers\ProductController;
use App\Controllers\StoreProductController;

$database = new Database();
$db = $database->getConnection();

$userController = new UserController($db);
$companyController = new CompanyController($db);
$storeController = new StoreController($db);

$productController = new ProductController($db);
$storeProductController = new StoreProductController($db);

require_once '../src/routes/index.php';