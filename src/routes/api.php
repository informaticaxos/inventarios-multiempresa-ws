<?php

/**
 * API Routes
 *
 * This file defines all the API routes for the application.
 * It maps HTTP requests to controller methods.
 */

// Get the request URI and method
$request = $_SERVER['REQUEST_URI'];
$method = $_SERVER['REQUEST_METHOD'];

// Adjust for subfolder hosting (if needed)
$request = str_replace('/inventarios-multiempresa-ws', '', $request);

// Route for /api/users
if ($request == '/api/users' || $request == '/api/users/') {
    if ($method == 'GET') {
        // Get all users
        $userController->read();
    } elseif ($method == 'POST') {
        // Create a new user
        $userController->create();
    }
}
// Route for /api/users/{id}
elseif (preg_match('/\/api\/users\/(\d+)/', $request, $matches)) {
    $id = $matches[1];
    if ($method == 'GET') {
        // Get user by ID
        $userController->readOne($id);
    } elseif ($method == 'PUT') {
        // Update user by ID
        $userController->update($id);
    } elseif ($method == 'DELETE') {
        // Delete user by ID
        $userController->delete($id);
    }
}
// Route for /api/login
elseif ($request == '/api/login' || $request == '/api/login/') {
    if ($method == 'POST') {
        // User login
        $userController->login();
    }
}
// Route for /api/companies
elseif ($request == '/api/companies' || $request == '/api/companies/') {
    if ($method == 'GET') {
        // Get all companies
        $companyController->read();
    } elseif ($method == 'POST') {
        // Create a new company
        $companyController->create();
    }
}
// Route for /api/companies/{id}
elseif (preg_match('/\/api\/companies\/(\d+)/', $request, $matches)) {
    $id = $matches[1];
    if ($method == 'GET') {
        // Get company by ID
        $companyController->readOne($id);
    } elseif ($method == 'PUT') {
        // Update company by ID
        $companyController->update($id);
    } elseif ($method == 'DELETE') {
        // Delete company by ID
        $companyController->delete($id);
    }
}
// Route for /api/stores
elseif ($request == '/api/stores' || $request == '/api/stores/') {
    if ($method == 'GET') {
        // Get all stores
        $storeController->read();
    } elseif ($method == 'POST') {
        // Create a new store
        $storeController->create();
    }
}
// Route for /api/stores/{id}
elseif (preg_match('/\/api\/stores\/(\d+)/', $request, $matches)) {
    $id = $matches[1];
    if ($method == 'GET') {
        // Get store by ID
        $storeController->readOne($id);
    } elseif ($method == 'PUT') {
        // Update store by ID
        $storeController->update($id);
    } elseif ($method == 'DELETE') {
        // Delete store by ID
        $storeController->delete($id);
    }
}
// Route for /api/products
elseif ($request == '/api/products' || $request == '/api/products/') {
    if ($method == 'GET') {
        // Get all products
        $productController->read();
    } elseif ($method == 'POST') {
        // Create a new product
        $productController->create();
    }
}
// Route for /api/products/{id}
elseif (preg_match('/\/api\/products\/(\d+)/', $request, $matches)) {
    $id = $matches[1];
    if ($method == 'GET') {
        // Get product by ID
        $productController->readOne($id);
    } elseif ($method == 'PUT') {
        // Update product by ID
        $productController->update($id);
    } elseif ($method == 'DELETE') {
        // Delete product by ID
        $productController->delete($id);
    }
}
// Default: Endpoint not found
else {
    http_response_code(404);
    echo json_encode(array("message" => "Endpoint not found."));
}