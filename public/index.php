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

$database = new Database();
$db = $database->getConnection();

$userController = new UserController($db);

$request = $_SERVER['REQUEST_URI'];

$method = $_SERVER['REQUEST_METHOD'];

$request = str_replace('/inventarios-multiempresa-ws', '', $request); // since hosted in subfolder

if ($request == '/users' || $request == '/users/') {
    if ($method == 'GET') {
        $userController->read();
    } elseif ($method == 'POST') {
        $userController->create();
    }
} elseif (preg_match('/\/users\/(\d+)/', $request, $matches)) {
    $id = $matches[1];
    if ($method == 'GET') {
        $userController->readOne($id);
    } elseif ($method == 'PUT') {
        $userController->update($id);
    } elseif ($method == 'DELETE') {
        $userController->delete($id);
    }
} elseif ($request == '/login' || $request == '/login/') {
    if ($method == 'POST') {
        $userController->login();
    }
} else {
    http_response_code(404);
    echo json_encode(array("message" => "Endpoint not found."));
}