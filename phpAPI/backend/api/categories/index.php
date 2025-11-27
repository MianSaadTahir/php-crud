<?php
/**
 * Categories API Endpoint
 * Handles category-related operations (GET all, GET products by category)
 */

require_once '../../config/cors.php';
require_once '../../config/database.php';
require_once '../../models/Category.php';
require_once '../../models/Product.php';

// Get request method and URI
$method = $_SERVER['REQUEST_METHOD'];
$request_uri = $_SERVER['REQUEST_URI'];
$path = parse_url($request_uri, PHP_URL_PATH);

// Category ID from query parameter or last numeric URI segment
$category_id = null;
$is_products_route = isset($_GET['products']);
if (isset($_GET['category_id'])) {
    $id_value = trim($_GET['category_id']);
    // Check if it's a valid positive integer
    if (is_numeric($id_value) && intval($id_value) > 0 && ctype_digit($id_value)) {
        $category_id = intval($id_value);
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

// Route based on HTTP method and path
switch ($method) {
    case 'GET':
        // GET /api/categories/{id}/products
        if ($is_products_route && $category_id !== null) {
            // Get products by category ID
            $category = new Category($db);
            $category->id = $category_id;
            
            if (!$category->readOne()) {
                http_response_code(404);
                echo json_encode([
                    "status" => "error",
                    "message" => "Category not found",
                    "code" => 404
                ]);
                break;
            }
            
            $product = new Product($db);
            $stmt = $product->readByCategory($category->name);
            $products = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
            http_response_code(200);
            echo json_encode([
                "status" => "success",
                "data" => $products,
                "category" => $category->name,
                "pagination" => [
                    "total" => count($products),
                    "page" => 1,
                    "per_page" => count($products)
                ]
            ]);
        } else {
            // GET /api/categories - Get all categories
            $category = new Category($db);
            $stmt = $category->readAll();
            $categories = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
            http_response_code(200);
            echo json_encode([
                "status" => "success",
                "data" => $categories,
                "pagination" => [
                    "total" => count($categories),
                    "page" => 1,
                    "per_page" => count($categories)
                ]
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

