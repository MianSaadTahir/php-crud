-- Product Management System Database Schema
-- Created for REST API Development Assignment

CREATE DATABASE IF NOT EXISTS product_management;
USE product_management;

-- Categories Table
CREATE TABLE IF NOT EXISTS categories (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) UNIQUE NOT NULL,
    description TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Products Table
CREATE TABLE IF NOT EXISTS products (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    description TEXT,
    price DECIMAL(10,2) NOT NULL,
    category VARCHAR(100),
    stock_quantity INT DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Sample Categories Data
INSERT INTO categories (name, description) VALUES
('Electronics', 'Electronic devices and accessories'),
('Books', 'Various books and publications'),
('Clothing', 'Apparel and fashion items')
ON DUPLICATE KEY UPDATE name=name;

-- Sample Products Data
INSERT INTO products (name, description, price, category, stock_quantity) VALUES
('Laptop', 'High-performance laptop with latest processor', 999.99, 'Electronics', 15),
('Smartphone', 'Latest smartphone model with advanced features', 699.99, 'Electronics', 25),
('Programming Book', 'Learn web development with practical examples', 29.99, 'Books', 100),
('T-Shirt', 'Comfortable cotton t-shirt', 19.99, 'Clothing', 50),
('Tablet', 'Portable tablet device', 399.99, 'Electronics', 30),
('Design Patterns Book', 'Advanced software design patterns', 49.99, 'Books', 75),
('Jeans', 'Classic denim jeans', 59.99, 'Clothing', 40)
ON DUPLICATE KEY UPDATE name=name;

