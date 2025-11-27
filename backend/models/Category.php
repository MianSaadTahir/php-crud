<?php
/**
 * Category Model
 * Handles database operations for categories using prepared statements
 */

class Category {
    private $conn;
    private $table_name = "categories";

    // Category properties
    public $id;
    public $name;
    public $description;
    public $created_at;

    /**
     * Constructor
     * @param PDO $db Database connection
     */
    public function __construct($db) {
        $this->conn = $db;
    }

    /**
     * Read all categories
     * @return PDOStatement
     */
    public function readAll() {
        $query = "SELECT id, name, description, created_at 
                  FROM " . $this->table_name . " 
                  ORDER BY name ASC";
        
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        
        return $stmt;
    }

    /**
     * Read single category by ID
     * @return array|false
     */
    public function readOne() {
        $query = "SELECT id, name, description, created_at 
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
            $this->created_at = $row['created_at'];
            return $row;
        }
        
        return false;
    }
}

