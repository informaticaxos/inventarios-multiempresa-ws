<?php

/**
 * API Documentation and Postman Examples
 *
 * This file contains detailed documentation for all API endpoints,
 * including request methods, URLs, headers, body examples, and expected responses.
 * Use this as a guide to test the API in Postman.
 *
 * Base URL: http://localhost:8000
 * Content-Type: application/json for all requests with body.
 */

/**
 * ===========================================
 * USER ENDPOINTS
 * ===========================================
 */

/**
 * 1. CREATE USER
 * Method: POST
 * URL: http://localhost:8000/users
 * Headers:
 *   - Content-Type: application/json
 * Body (JSON):
 * {
 *   "dni_user": "12345678",
 *   "username_user": "johndoe",
 *   "email_user": "john@example.com",
 *   "password_user": "mypassword"
 * }
 * Expected Response (Success - 201):
 * {
 *   "state": 1,
 *   "message": "User was created.",
 *   "data": []
 * }
 * Expected Response (Error - 400/503):
 * {
 *   "state": 0,
 *   "message": "User with this DNI already exists." or "User with this username already exists." or "Unable to create user. Data is incomplete." or "Unable to create user.",
 *   "data": []
 * }
 */

/**
 * 2. GET ALL USERS
 * Method: GET
 * URL: http://localhost:8000/users
 * Headers: None required
 * Body: None
 * Expected Response (Success - 200):
 * {
 *   "state": 1,
 *   "message": "Users found.",
 *   "data": [
 *     {
 *       "id_user": 1,
 *       "dni_user": "12345678",
 *       "username_user": "johndoe",
 *       "email_user": "john@example.com"
 *     }
 *   ]
 * }
 * Expected Response (Error - 404):
 * {
 *   "state": 0,
 *   "message": "No users found.",
 *   "data": []
 * }
 */

/**
 * 3. GET USER BY ID
 * Method: GET
 * URL: http://localhost:8000/users/{id} (e.g., http://localhost:8000/users/1)
 * Headers: None required
 * Body: None
 * Expected Response (Success - 200):
 * {
 *   "state": 1,
 *   "message": "User found.",
 *   "data": [
 *     {
 *       "id_user": 1,
 *       "dni_user": "12345678",
 *       "username_user": "johndoe",
 *       "email_user": "john@example.com"
 *     }
 *   ]
 * }
 * Expected Response (Error - 404):
 * {
 *   "state": 0,
 *   "message": "User not found.",
 *   "data": []
 * }
 */

/**
 * 4. UPDATE USER
 * Method: PUT
 * URL: http://localhost:8000/users/{id} (e.g., http://localhost:8000/users/1)
 * Headers:
 *   - Content-Type: application/json
 * Body (JSON):
 * {
 *   "dni_user": "87654321",
 *   "username_user": "janedoe",
 *   "email_user": "jane@example.com"
 * }
 * Expected Response (Success - 200):
 * {
 *   "state": 1,
 *   "message": "User was updated.",
 *   "data": []
 * }
 * Expected Response (Error - 400/503):
 * {
 *   "state": 0,
 *   "message": "Unable to update user. Data is incomplete.",
 *   "data": []
 * }
 */

/**
 * 5. DELETE USER
 * Method: DELETE
 * URL: http://localhost:8000/users/{id} (e.g., http://localhost:8000/users/1)
 * Headers: None required
 * Body: None
 * Expected Response (Success - 200):
 * {
 *   "state": 1,
 *   "message": "User was deleted.",
 *   "data": []
 * }
 * Expected Response (Error - 503):
 * {
 *   "state": 0,
 *   "message": "Unable to delete user.",
 *   "data": []
 * }
 */

/**
 * 6. LOGIN USER
 * Method: POST
 * URL: http://localhost:8000/login
 * Headers:
 *   - Content-Type: application/json
 * Body (JSON):
 * {
 *   "username": "johndoe",
 *   "password": "mypassword"
 * }
 * Expected Response (Success - 200):
 * {
 *   "state": 1,
 *   "message": "Login successful.",
 *   "data": [
 *     {
 *       "id_user": 1
 *     }
 *   ]
 * }
 * Expected Response (Error - 400/401):
 * {
 *   "state": 0,
 *   "message": "Login failed.",
 *   "data": []
 * }
 */

/**
 * ===========================================
 * COMPANY ENDPOINTS
 * ===========================================
 */

/**
 * 1. CREATE COMPANY
 * Method: POST
 * URL: http://localhost:8000/companies
 * Headers:
 *   - Content-Type: application/json
 * Body (JSON):
 * {
 *   "dni_company": "123456789",
 *   "name_company": "Company Name",
 *   "phone_company": "123456789",
 *   "email_company": "company@example.com",
 *   "address_company": "Company Address"
 * }
 * Expected Response (Success - 201):
 * {
 *   "state": 1,
 *   "message": "Company was created.",
 *   "data": []
 * }
 * Expected Response (Error - 400/503):
 * {
 *   "state": 0,
 *   "message": "Company with this DNI already exists." or "Unable to create company. Data is incomplete." or "Unable to create company.",
 *   "data": []
 * }
 */

/**
 * 2. GET ALL COMPANIES
 * Method: GET
 * URL: http://localhost:8000/companies
 * Headers: None required
 * Body: None
 * Expected Response (Success - 200):
 * {
 *   "state": 1,
 *   "message": "Companies found.",
 *   "data": [
 *     {
 *       "id_company": 1,
 *       "dni_company": "123456789",
 *       "name_company": "Company Name",
 *       "phone_company": "123456789",
 *       "email_company": "company@example.com",
 *       "address_company": "Company Address"
 *     }
 *   ]
 * }
 * Expected Response (Error - 404):
 * {
 *   "state": 0,
 *   "message": "No companies found.",
 *   "data": []
 * }
 */

/**
 * 3. GET COMPANY BY ID
 * Method: GET
 * URL: http://localhost:8000/companies/{id} (e.g., http://localhost:8000/companies/1)
 * Headers: None required
 * Body: None
 * Expected Response (Success - 200):
 * {
 *   "state": 1,
 *   "message": "Company found.",
 *   "data": [
 *     {
 *       "id_company": 1,
 *       "dni_company": "123456789",
 *       "name_company": "Company Name",
 *       "phone_company": "123456789",
 *       "email_company": "company@example.com",
 *       "address_company": "Company Address"
 *     }
 *   ]
 * }
 * Expected Response (Error - 404):
 * {
 *   "state": 0,
 *   "message": "Company not found.",
 *   "data": []
 * }
 */

/**
 * 4. UPDATE COMPANY
 * Method: PUT
 * URL: http://localhost:8000/companies/{id} (e.g., http://localhost:8000/companies/1)
 * Headers:
 *   - Content-Type: application/json
 * Body (JSON):
 * {
 *   "dni_company": "987654321",
 *   "name_company": "Updated Company Name",
 *   "phone_company": "987654321",
 *   "email_company": "updated@example.com",
 *   "address_company": "Updated Address"
 * }
 * Expected Response (Success - 200):
 * {
 *   "state": 1,
 *   "message": "Company was updated.",
 *   "data": []
 * }
 * Expected Response (Error - 400/503):
 * {
 *   "state": 0,
 *   "message": "Unable to update company. Data is incomplete.",
 *   "data": []
 * }
 */

/**
 * 5. DELETE COMPANY
 * Method: DELETE
 * URL: http://localhost:8000/companies/{id} (e.g., http://localhost:8000/companies/1)
 * Headers: None required
 * Body: None
 * Expected Response (Success - 200):
 * {
 *   "state": 1,
 *   "message": "Company was deleted.",
 *   "data": []
 * }
 * Expected Response (Error - 503):
 * {
 *   "state": 0,
 *   "message": "Unable to delete company.",
 *   "data": []
 * }
 */

/**
 * ===========================================
 * STORE ENDPOINTS
 * ===========================================
 */

/**
 * 1. CREATE STORE
 * Method: POST
 * URL: http://localhost:8000/stores
 * Headers:
 *   - Content-Type: application/json
 * Body (JSON):
 * {
 *   "code_store": "ST001",
 *   "name_store": "Store Name",
 *   "address_store": "Store Address",
 *   "location_store": "Store Location",
 *   "fk_id_company": 1
 * }
 * Expected Response (Success - 201):
 * {
 *   "state": 1,
 *   "message": "Store was created.",
 *   "data": []
 * }
 * Expected Response (Error - 400/503):
 * {
 *   "state": 0,
 *   "message": "Store with this code already exists." or "Unable to create store. Data is incomplete." or "Unable to create store.",
 *   "data": []
 * }
 */

/**
 * 2. GET ALL STORES
 * Method: GET
 * URL: http://localhost:8000/stores
 * Headers: None required
 * Body: None
 * Expected Response (Success - 200):
 * {
 *   "state": 1,
 *   "message": "Stores found.",
 *   "data": [
 *     {
 *       "id_store": 1,
 *       "code_store": "ST001",
 *       "name_store": "Store Name",
 *       "address_store": "Store Address",
 *       "location_store": "Store Location",
 *       "fk_id_company": 1
 *     }
 *   ]
 * }
 * Expected Response (Error - 404):
 * {
 *   "state": 0,
 *   "message": "No stores found.",
 *   "data": []
 * }
 */

/**
 * 3. GET STORE BY ID
 * Method: GET
 * URL: http://localhost:8000/stores/{id} (e.g., http://localhost:8000/stores/1)
 * Headers: None required
 * Body: None
 * Expected Response (Success - 200):
 * {
 *   "state": 1,
 *   "message": "Store found.",
 *   "data": [
 *     {
 *       "id_store": 1,
 *       "code_store": "ST001",
 *       "name_store": "Store Name",
 *       "address_store": "Store Address",
 *       "location_store": "Store Location",
 *       "fk_id_company": 1
 *     }
 *   ]
 * }
 * Expected Response (Error - 404):
 * {
 *   "state": 0,
 *   "message": "Store not found.",
 *   "data": []
 * }
 */

/**
 * 4. UPDATE STORE
 * Method: PUT
 * URL: http://localhost:8000/stores/{id} (e.g., http://localhost:8000/stores/1)
 * Headers:
 *   - Content-Type: application/json
 * Body (JSON):
 * {
 *   "code_store": "ST002",
 *   "name_store": "Updated Store Name",
 *   "address_store": "Updated Address",
 *   "location_store": "Updated Location",
 *   "fk_id_company": 1
 * }
 * Expected Response (Success - 200):
 * {
 *   "state": 1,
 *   "message": "Store was updated.",
 *   "data": []
 * }
 * Expected Response (Error - 400/503):
 * {
 *   "state": 0,
 *   "message": "Unable to update store. Data is incomplete.",
 *   "data": []
 * }
 */

/**
 * 5. DELETE STORE
 * Method: DELETE
 * URL: http://localhost:8000/stores/{id} (e.g., http://localhost:8000/stores/1)
 * Headers: None required
 * Body: None
 * Expected Response (Success - 200):
 * {
 *   "state": 1,
 *   "message": "Store was deleted.",
 *   "data": []
 * }
 * Expected Response (Error - 503):
 * {
 *   "state": 0,
 *   "message": "Unable to delete store.",
 *   "data": []
 * }
 */

/**
 * ===========================================
 * PRODUCT ENDPOINTS
 * ===========================================
 */

/**
 * 1. CREATE PRODUCT
 * Method: POST
 * URL: http://localhost:8000/products
 * Headers:
 *   - Content-Type: application/json
 * Body (JSON):
 * {
 *   "code_product": "P001",
 *   "name_product": "Product Name",
 *   "description_product": "Product Description",
 *   "pvp_product": 10.50,
 *   "min_product": 5.00,
 *   "max_product": 20.00,
 *   "state_product": 1
 * }
 * Expected Response (Success - 201):
 * {
 *   "state": 1,
 *   "message": "Product was created.",
 *   "data": []
 * }
 * Expected Response (Error - 400/503):
 * {
 *   "state": 0,
 *   "message": "Unable to create product. Data is incomplete." or "Unable to create product.",
 *   "data": []
 * }
 */

/**
 * 2. GET ALL PRODUCTS
 * Method: GET
 * URL: http://localhost:8000/products
 * Headers: None required
 * Body: None
 * Expected Response (Success - 200):
 * {
 *   "state": 1,
 *   "message": "Products found.",
 *   "data": [
 *     {
 *       "id_product": 1,
 *       "code_product": "P001",
 *       "name_product": "Product Name",
 *       "description_product": "Product Description",
 *       "pvp_product": 10.50,
 *       "min_product": 5.00,
 *       "max_product": 20.00,
 *       "created_product": "2023-01-01 00:00:00",
 *       "updated_product": "2023-01-01 00:00:00",
 *       "state_product": 1
 *     }
 *   ]
 * }
 * Expected Response (Error - 404):
 * {
 *   "state": 0,
 *   "message": "No products found.",
 *   "data": []
 * }
 */

/**
 * 3. GET PRODUCT BY ID
 * Method: GET
 * URL: http://localhost:8000/products/{id} (e.g., http://localhost:8000/products/1)
 * Headers: None required
 * Body: None
 * Expected Response (Success - 200):
 * {
 *   "state": 1,
 *   "message": "Product found.",
 *   "data": [
 *     {
 *       "id_product": 1,
 *       "code_product": "P001",
 *       "name_product": "Product Name",
 *       "description_product": "Product Description",
 *       "pvp_product": 10.50,
 *       "min_product": 5.00,
 *       "max_product": 20.00,
 *       "created_product": "2023-01-01 00:00:00",
 *       "updated_product": "2023-01-01 00:00:00",
 *       "state_product": 1
 *     }
 *   ]
 * }
 * Expected Response (Error - 404):
 * {
 *   "state": 0,
 *   "message": "Product not found.",
 *   "data": []
 * }
 */

/**
 * 4. UPDATE PRODUCT
 * Method: PUT
 * URL: http://localhost:8000/products/{id} (e.g., http://localhost:8000/products/1)
 * Headers:
 *   - Content-Type: application/json
 * Body (JSON):
 * {
 *   "code_product": "P002",
 *   "name_product": "Updated Product Name",
 *   "description_product": "Updated Description",
 *   "pvp_product": 12.00,
 *   "min_product": 6.00,
 *   "max_product": 25.00,
 *   "state_product": 1
 * }
 * Expected Response (Success - 200):
 * {
 *   "state": 1,
 *   "message": "Product was updated.",
 *   "data": []
 * }
 * Expected Response (Error - 400/503):
 * {
 *   "state": 0,
 *   "message": "Unable to update product. Data is incomplete.",
 *   "data": []
 * }
 */

/**
 * 5. DELETE PRODUCT
 * Method: DELETE
 * URL: http://localhost:8000/products/{id} (e.g., http://localhost:8000/products/1)
 * Headers: None required
 * Body: None
 * Expected Response (Success - 200):
 * {
 *   "state": 1,
 *   "message": "Product was deleted.",
 *   "data": []
 * }
 * Expected Response (Error - 503):
 * {
 *   "state": 0,
 *   "message": "Unable to delete product.",
 *   "data": []
 * }
 */

/**
 * ===========================================
 * FUTURE TABLES ENDPOINTS (TEMPLATE)
 * ===========================================
 *
 * For each new table (e.g., UserCompany, Client, etc.), follow this structure:
 *
 * 1. CREATE [TABLE]
 *    Method: POST
 *    URL: http://localhost:8000/[table]s
 *    Body: JSON with required fields
 *
 * 2. GET ALL [TABLE]S
 *    Method: GET
 *    URL: http://localhost:8000/[table]s
 *
 * 3. GET [TABLE] BY ID
 *    Method: GET
 *    URL: http://localhost:8000/[table]s/{id}
 *
 * 4. UPDATE [TABLE]
 *    Method: PUT
 *    URL: http://localhost:8000/[table]s/{id}
 *    Body: JSON with fields to update
 *
 * 5. DELETE [TABLE]
 *    Method: DELETE
 *    URL: http://localhost:8000/[table]s/{id}
 *
 * Add specific endpoints as needed (e.g., login for users).
 *
 * Remember to update this documentation file for each new endpoint.
 */