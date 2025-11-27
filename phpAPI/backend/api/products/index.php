<?php
/**
 * Products API Endpoint
 * Handles all product-related operations (GET, POST, PUT, DELETE)
 */

require_once '../../config/cors.php';
require_once '../../config/database.php';
require_once '../../models/Product.php';

// Get request method and URI
$method = $_SERVER['REQUEST_METHOD'];
$request_uri = $_SERVER['REQUEST_URI'];
$path = parse_url($request_uri, PHP_URL_PATH);

// Product ID from query parameter (?id=123)
$product_id = null;
if (!empty($_GET['id'])) {
    $id_value = intval($_GET['id']);
    if ($id_value > 0) {
        $product_id = $id_value;
    }
}

// Initialize database connection
$database = new Database();
$db = $database->getConnection();

if (!$db) {
    http_response_code(500);
    echo json_encode([
        "status" => "error",
        "message" => "Database connection failed",
        "code" => 500
    ]);
    exit();
}

// Initialize Product model
$product = new Product($db);

// Route based on HTTP method
switch ($method) {
    case 'GET':
        // GET /api/products or GET /api/products/{id}
        if ($product_id !== null) {
            // Get single product by ID
            $product->id = $product_id;
            $result = $product->readOne();
            
            if ($result) {
                http_response_code(200);
                echo json_encode([
                    "status" => "success",
                    "data" => $result,
                    "message" => "Product retrieved successfully"
                ]);
            } else {
                http_response_code(404);
                echo json_encode([
                    "status" => "error",
                    "message" => "Product not found",
                    "code" => 404
                ]);
            }
        } else {
            // Get all products
            $stmt = $product->readAll();
            $products = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
            http_response_code(200);
            echo json_encode([
                "status" => "success",
                "data" => $products,
                "pagination" => [
                    "total" => count($products),
                    "page" => 1,
                    "per_page" => count($products)
                ]
            ]);
        }
        break;

    case 'POST':
        // POST /api/products - Create new product
        $data = json_decode(file_get_contents("php://input"), true);
        
        // Validate required fields
        if (empty($data['name']) || empty($data['price'])) {
            http_response_code(400);
            echo json_encode([
                "status" => "error",
                "message" => "Name and price are required fields",
                "code" => 400
            ]);
            break;
        }
        
        // Sanitize and assign data
        $product->name = htmlspecialchars(strip_tags($data['name']));
        $product->description = isset($data['description']) ? htmlspecialchars(strip_tags($data['description'])) : '';
        $product->price = floatval($data['price']);
        $product->category = isset($data['category']) ? htmlspecialchars(strip_tags($data['category'])) : '';
        $product->stock_quantity = isset($data['stock_quantity']) ? intval($data['stock_quantity']) : 0;
        
        if ($product->create()) {
            http_response_code(201);
            echo json_encode([
                "status" => "success",
                "data" => [
                    "id" => $product->id,
                    "name" => $product->name,
                    "description" => $product->description,
                    "price" => $product->price,
                    "category" => $product->category,
                    "stock_quantity" => $product->stock_quantity
                ],
                "message" => "Product created successfully"
            ]);
        } else {
            http_response_code(500);
            echo json_encode([
                "status" => "error",
                "message" => "Failed to create product",
                "code" => 500
            ]);
        }
        break;

    case 'PUT':
        // PUT /api/products/{id} - Update product
        if ($product_id === null) {
            http_response_code(400);
            echo json_encode([
                "status" => "error",
                "message" => "Product ID is required",
                "code" => 400
            ]);
            break;
        }
        
        $product->id = $product_id;
        $data = json_decode(file_get_contents("php://input"), true);
        
        // Check if product exists
        if (!$product->readOne()) {
            http_response_code(404);
            echo json_encode([
                "status" => "error",
                "message" => "Product not found",
                "code" => 404
            ]);
            break;
        }
        
        // Update fields if provided
        if (isset($data['name'])) {
            $product->name = htmlspecialchars(strip_tags($data['name']));
        }
        if (isset($data['description'])) {
            $product->description = htmlspecialchars(strip_tags($data['description']));
        }
        if (isset($data['price'])) {
            $product->price = floatval($data['price']);
        }
        if (isset($data['category'])) {
            $product->category = htmlspecialchars(strip_tags($data['category']));
        }
        if (isset($data['stock_quantity'])) {
            $product->stock_quantity = intval($data['stock_quantity']);
        }
        
        if ($product->update()) {
            http_response_code(200);
            echo json_encode([
                "status" => "success",
                "data" => [
                    "id" => $product->id,
                    "name" => $product->name,
                    "description" => $product->description,
                    "price" => $product->price,
                    "category" => $product->category,
                    "stock_quantity" => $product->stock_quantity
                ],
                "message" => "Product updated successfully"
            ]);
        } else {
            http_response_code(500);
            echo json_encode([
                "status" => "error",
                "message" => "Failed to update product",
                "code" => 500
            ]);
        }
        break;

    case 'DELETE':
        // DELETE /api/products/{id} - Delete product
        if ($product_id === null) {
            http_response_code(400);
            echo json_encode([
                "status" => "error",
                "message" => "Product ID is required",
                "code" => 400
            ]);
            break;
        }
        
        $product->id = $product_id;
        
        // Check if product exists
        if (!$product->readOne()) {
            http_response_code(404);
            echo json_encode([
                "status" => "error",
                "message" => "Product not found",
                "code" => 404
            ]);
            break;
        }
        
        if ($product->delete()) {
            http_response_code(200);
            echo json_encode([
                "status" => "success",
                "message" => "Product deleted successfully"
            ]);
        } else {
            http_response_code(500);
            echo json_encode([
                "status" => "error",
                "message" => "Failed to delete product",
                "code" => 500
            ]);
        }
        break;

    default:
        http_response_code(405);
        echo json_encode([
            "status" => "error",
            "message" => "Method not allowed",
            "code" => 405
        ]);
        break;
}

