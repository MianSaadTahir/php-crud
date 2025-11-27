<?php
/**
 * Product Model
 * Handles database operations for products using prepared statements
 */

class Product {
    private $conn;
    private $table_name = "products";

    // Product properties
    public $id;
    public $name;
    public $description;
    public $price;
    public $category;
    public $stock_quantity;
    public $created_at;
    public $updated_at;

    /**
     * Constructor
     * @param PDO $db Database connection
     */
    public function __construct($db) {
        $this->conn = $db;
    }

    /**
     * Read all products
     * @return PDOStatement
     */
    public function readAll() {
        $query = "SELECT id, name, description, price, category, stock_quantity, created_at, updated_at 
                  FROM " . $this->table_name . " 
                  ORDER BY created_at DESC";
        
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        
        return $stmt;
    }

    /**
     * Read single product by ID
     * @return array|false
     */
    public function readOne() {
        $query = "SELECT id, name, description, price, category, stock_quantity, created_at, updated_at 
                  FROM " . $this->table_name . " 
                  WHERE id = :id 
                  LIMIT 1";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $this->id, PDO::PARAM_INT);
        $stmt->execute();
        
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if ($row) {
            $this->name = $row['name'];
            $this->description = $row['description'];
            $this->price = $row['price'];
            $this->category = $row['category'];
            $this->stock_quantity = $row['stock_quantity'];
            $this->created_at = $row['created_at'];
            $this->updated_at = $row['updated_at'];
            return $row;
        }
        
        return false;
    }

    /**
     * Read products by category
     * @param string $category Category name
     * @return PDOStatement
     */
    public function readByCategory($category) {
        $query = "SELECT id, name, description, price, category, stock_quantity, created_at, updated_at 
                  FROM " . $this->table_name . " 
                  WHERE category = :category 
                  ORDER BY created_at DESC";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':category', $category, PDO::PARAM_STR);
        $stmt->execute();
        
        return $stmt;
    }

    /**
     * Create new product
     * @return bool
     */
    public function create() {
        $query = "INSERT INTO " . $this->table_name . " 
                  (name, description, price, category, stock_quantity) 
                  VALUES (:name, :description, :price, :category, :stock_quantity)";
        
        $stmt = $this->conn->prepare($query);
        
        // Bind parameters
        $stmt->bindParam(':name', $this->name, PDO::PARAM_STR);
        $stmt->bindParam(':description', $this->description, PDO::PARAM_STR);
        $stmt->bindParam(':price', $this->price, PDO::PARAM_STR);
        $stmt->bindParam(':category', $this->category, PDO::PARAM_STR);
        $stmt->bindParam(':stock_quantity', $this->stock_quantity, PDO::PARAM_INT);
        
        if ($stmt->execute()) {
            $this->id = $this->conn->lastInsertId();
            return true;
        }
        
        return false;
    }

    /**
     * Update existing product
     * @return bool
     */
    public function update() {
        $query = "UPDATE " . $this->table_name . " 
                  SET name = :name, 
                      description = :description, 
                      price = :price, 
                      category = :category, 
                      stock_quantity = :stock_quantity 
                  WHERE id = :id";
        
        $stmt = $this->conn->prepare($query);
        
        // Bind parameters
        $stmt->bindParam(':id', $this->id, PDO::PARAM_INT);
        $stmt->bindParam(':name', $this->name, PDO::PARAM_STR);
        $stmt->bindParam(':description', $this->description, PDO::PARAM_STR);
        $stmt->bindParam(':price', $this->price, PDO::PARAM_STR);
        $stmt->bindParam(':category', $this->category, PDO::PARAM_STR);
        $stmt->bindParam(':stock_quantity', $this->stock_quantity, PDO::PARAM_INT);
        
        return $stmt->execute();
    }

    /**
     * Delete product
     * @return bool
     */
    public function delete() {
        $query = "DELETE FROM " . $this->table_name . " WHERE id = :id";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $this->id, PDO::PARAM_INT);
        
        return $stmt->execute();
    }
}

