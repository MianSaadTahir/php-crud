# Product Management System - REST API

A complete RESTful API system built with PHP and MySQL, featuring a dynamic web interface that consumes the APIs using JavaScript and jQuery.

## 📋 Table of Contents

- [Overview](#overview)
- [Features](#features)
- [Technologies Used](#technologies-used)
- [Project Structure](#project-structure)
- [Installation & Setup](#installation--setup)
- [API Documentation](#api-documentation)
- [Frontend Usage](#frontend-usage)
- [Testing](#testing)
- [Code Structure](#code-structure)

## 🎯 Overview

This project demonstrates full-stack API development from server-side creation to client-side consumption. The system provides a complete Product Management solution with:

- **Backend**: RESTful API built with PHP and MySQL
- **Frontend**: Single-page application using HTML, CSS, JavaScript, and jQuery
- **Features**: Full CRUD operations, search, filter, sorting, and real-time updates

## ✨ Features

### Backend Features
- RESTful API endpoints for products and categories
- Prepared statements for SQL security
- Proper error handling and HTTP status codes
- CORS support for cross-origin requests
- JSON-based data interchange
- Input validation and sanitization

### Frontend Features
- Dynamic product listing with real-time updates
- Search functionality (by name and description)
- Category filtering
- Multiple sorting options (name, price, stock)
- Add/Edit product modal form
- Delete with confirmation
- Loading indicators and error handling
- Responsive design

## 🛠 Technologies Used

### Backend
- **PHP 7.4+** - Server-side API development
- **MySQL 5.7+** - Database management
- **PDO** - Database access with prepared statements
- **JSON** - Data interchange format

### Frontend
- **HTML5** - Structure
- **CSS3** - Styling with Bootstrap 5
- **JavaScript (ES6+)** - Client-side logic
- **jQuery 3.7** - AJAX calls and DOM manipulation
- **Bootstrap 5** - UI framework
- **Bootstrap Icons** - Icon library

## 📁 Project Structure

```
phpAPI/
├── backend/
│   ├── api/
│   │   ├── products/
│   │   │   └── index.php          # Product endpoints
│   │   └── categories/
│   │       └── index.php          # Category endpoints
│   ├── config/
│   │   ├── database.php            # Database connection
│   │   └── cors.php                # CORS configuration
│   └── models/
│       ├── Product.php             # Product model
│       └── Category.php            # Category model
├── database/
│   └── schema.sql                  # Database schema and sample data
├── frontend/
│   ├── index.html                  # Main HTML file
│   ├── styles.css                  # Custom CSS
│   └── app.js                      # JavaScript/jQuery code
├── .htaccess                       # URL rewriting rules
└── README.md                       # This file
```

## 🚀 Installation & Setup

### Prerequisites

- PHP 7.4 or higher
- MySQL 5.7 or higher (or MariaDB)
- Apache web server with mod_rewrite enabled
- Web browser (Chrome, Firefox, Safari, Edge)

### Step 1: Database Setup

1. **Create the database:**
   ```bash
   mysql -u root -p < database/schema.sql
   ```
   
   Or manually:
   - Open MySQL command line or phpMyAdmin
   - Import `database/schema.sql`
   - This will create the database, tables, and sample data

2. **Verify database:**
   ```sql
   USE product_management;
   SELECT * FROM products;
   SELECT * FROM categories;
   ```

### Step 2: Configure Database Connection

1. Open `backend/config/database.php`
2. Update database credentials if needed:
   ```php
   private $host = 'localhost';
   private $db_name = 'product_management';
   private $username = 'root';      // Change if needed
   private $password = '';          // Change if needed
   ```

### Step 3: Web Server Configuration

#### Option A: Using XAMPP/WAMP/MAMP

1. Copy the `phpAPI` folder to your web server directory:
   - **XAMPP**: `C:\xampp\htdocs\phpAPI` (Windows) or `/Applications/XAMPP/htdocs/phpAPI` (Mac)
   - **WAMP**: `C:\wamp64\www\phpAPI`
   - **MAMP**: `/Applications/MAMP/htdocs/phpAPI`

2. Ensure Apache mod_rewrite is enabled:
   - Check `httpd.conf` for `LoadModule rewrite_module modules/mod_rewrite.so`
   - In XAMPP, it's usually enabled by default

3. Access the application:
   - Frontend: `http://localhost/phpAPI/frontend/index.html`
   - API: `http://localhost/phpAPI/api/products`

#### Option B: Using PHP Built-in Server

1. Navigate to project directory:
   ```bash
   cd /path/to/phpAPI
   ```

2. Start PHP server:
   ```bash
   php -S localhost:8000
   ```

3. Access the application:
   - Frontend: `http://localhost:8000/frontend/index.html`
   - API: `http://localhost:8000/api/products`

**Note:** For PHP built-in server, you may need to adjust the `.htaccess` routing or use a different routing approach.

### Step 4: Update API Base URL (if needed)

If your project is not in the root directory, update the API base URL in `frontend/app.js`:

```javascript
// Change this line if your API path is different
const API_BASE_URL = '/phpAPI/api';  // Adjust based on your setup
```

### Step 5: Test the Application

1. Open `http://localhost/phpAPI/frontend/index.html` in your browser
2. You should see the product list with sample data
3. Try adding, editing, and deleting products

## 📚 API Documentation

### Base URL

```
http://localhost/phpAPI/api
```

### Product Endpoints

#### 1. Get All Products

**Endpoint:** `GET /api/products`

**Description:** Retrieve all products from the database.

**Request:**
```http
GET /api/products HTTP/1.1
Host: localhost
```

**Response (Success - 200):**
```json
{
  "status": "success",
  "data": [
    {
      "id": 1,
      "name": "Laptop",
      "description": "High-performance laptop",
      "price": "999.99",
      "category": "Electronics",
      "stock_quantity": 15,
      "created_at": "2024-01-01 10:00:00",
      "updated_at": "2024-01-01 10:00:00"
    }
  ],
  "pagination": {
    "total": 1,
    "page": 1,
    "per_page": 1
  }
}
```

#### 2. Get Single Product

**Endpoint:** `GET /api/products/{id}`

**Description:** Retrieve a specific product by ID.

**Request:**
```http
GET /api/products/1 HTTP/1.1
Host: localhost
```

**Response (Success - 200):**
```json
{
  "status": "success",
  "data": {
    "id": 1,
    "name": "Laptop",
    "description": "High-performance laptop",
    "price": "999.99",
    "category": "Electronics",
    "stock_quantity": 15,
    "created_at": "2024-01-01 10:00:00",
    "updated_at": "2024-01-01 10:00:00"
  },
  "message": "Product retrieved successfully"
}
```

**Response (Error - 404):**
```json
{
  "status": "error",
  "message": "Product not found",
  "code": 404
}
```

#### 3. Create Product

**Endpoint:** `POST /api/products`

**Description:** Create a new product.

**Request:**
```http
POST /api/products HTTP/1.1
Host: localhost
Content-Type: application/json

{
  "name": "New Product",
  "description": "Product description",
  "price": 49.99,
  "category": "Electronics",
  "stock_quantity": 10
}
```

**Required Fields:**
- `name` (string) - Product name
- `price` (number) - Product price

**Optional Fields:**
- `description` (string) - Product description
- `category` (string) - Product category
- `stock_quantity` (integer) - Stock quantity (default: 0)

**Response (Success - 201):**
```json
{
  "status": "success",
  "data": {
    "id": 4,
    "name": "New Product",
    "description": "Product description",
    "price": 49.99,
    "category": "Electronics",
    "stock_quantity": 10
  },
  "message": "Product created successfully"
}
```

**Response (Error - 400):**
```json
{
  "status": "error",
  "message": "Name and price are required fields",
  "code": 400
}
```

#### 4. Update Product

**Endpoint:** `PUT /api/products/{id}`

**Description:** Update an existing product.

**Request:**
```http
PUT /api/products/1 HTTP/1.1
Host: localhost
Content-Type: application/json

{
  "name": "Updated Product Name",
  "price": 59.99,
  "stock_quantity": 20
}
```

**Note:** All fields are optional. Only provided fields will be updated.

**Response (Success - 200):**
```json
{
  "status": "success",
  "data": {
    "id": 1,
    "name": "Updated Product Name",
    "description": "High-performance laptop",
    "price": "59.99",
    "category": "Electronics",
    "stock_quantity": 20
  },
  "message": "Product updated successfully"
}
```

#### 5. Delete Product

**Endpoint:** `DELETE /api/products/{id}`

**Description:** Delete a product by ID.

**Request:**
```http
DELETE /api/products/1 HTTP/1.1
Host: localhost
```

**Response (Success - 200):**
```json
{
  "status": "success",
  "message": "Product deleted successfully"
}
```

**Response (Error - 404):**
```json
{
  "status": "error",
  "message": "Product not found",
  "code": 404
}
```

### Category Endpoints

#### 1. Get All Categories

**Endpoint:** `GET /api/categories`

**Description:** Retrieve all categories.

**Request:**
```http
GET /api/categories HTTP/1.1
Host: localhost
```

**Response (Success - 200):**
```json
{
  "status": "success",
  "data": [
    {
      "id": 1,
      "name": "Electronics",
      "description": "Electronic devices and accessories",
      "created_at": "2024-01-01 10:00:00"
    }
  ],
  "pagination": {
    "total": 1,
    "page": 1,
    "per_page": 1
  }
}
```

#### 2. Get Products by Category

**Endpoint:** `GET /api/categories/{id}/products`

**Description:** Retrieve all products in a specific category.

**Request:**
```http
GET /api/categories/1/products HTTP/1.1
Host: localhost
```

**Response (Success - 200):**
```json
{
  "status": "success",
  "data": [
    {
      "id": 1,
      "name": "Laptop",
      "price": "999.99",
      "category": "Electronics",
      "stock_quantity": 15
    }
  ],
  "category": "Electronics",
  "pagination": {
    "total": 1,
    "page": 1,
    "per_page": 1
  }
}
```

### Error Responses

All endpoints return standardized error responses:

```json
{
  "status": "error",
  "message": "Error description",
  "code": 400
}
```

**Common HTTP Status Codes:**
- `200` - Success
- `201` - Created
- `400` - Bad Request
- `404` - Not Found
- `405` - Method Not Allowed
- `500` - Internal Server Error

## 💻 Frontend Usage

### Accessing the Interface

1. Open `frontend/index.html` in your web browser
2. The interface will automatically load products from the API

### Features

#### Product Listing
- View all products in a table format
- See product details: ID, Name, Description, Price, Category, Stock

#### Search
- Type in the search box to filter products by name or description
- Search is performed in real-time as you type

#### Filter by Category
- Select a category from the dropdown to show only products in that category
- Select "All Categories" to show all products

#### Sort Products
- Sort by:
  - Name (A-Z or Z-A)
  - Price (Low to High or High to Low)
  - Stock (Low to High or High to Low)

#### Add Product
1. Click the "Add Product" button
2. Fill in the product form:
   - **Name** (required)
   - **Description** (optional)
   - **Price** (required)
   - **Category** (optional)
   - **Stock Quantity** (optional, default: 0)
3. Click "Save Product"
4. The product will be added and the list will refresh

#### Edit Product
1. Click the edit icon (pencil) next to a product
2. The modal will open with pre-filled product data
3. Modify the fields as needed
4. Click "Save Product"
5. The product will be updated and the list will refresh

#### Delete Product
1. Click the delete icon (trash) next to a product
2. Confirm the deletion in the popup
3. The product will be deleted and the list will refresh

### Visual Indicators

- **Stock Levels:**
  - Red: Stock < 10 or 0
  - Yellow: Stock 10-24
  - Green: Stock ≥ 25

- **Loading States:**
  - Spinner appears during API calls
  - Buttons are disabled during operations

- **Messages:**
  - Success messages (green) appear for successful operations
  - Error messages (red) appear for failed operations
  - Messages auto-dismiss after a few seconds

## 🧪 Testing

### Testing with Postman

1. **Import Postman Collection:**
   - Create a new collection in Postman
   - Add requests for each endpoint
   - Set base URL: `http://localhost/phpAPI/api`

2. **Test GET All Products:**
   ```
   GET http://localhost/phpAPI/api/products
   ```

3. **Test GET Single Product:**
   ```
   GET http://localhost/phpAPI/api/products/1
   ```

4. **Test POST Create Product:**
   ```
   POST http://localhost/phpAPI/api/products
   Body (raw JSON):
   {
     "name": "Test Product",
     "description": "Test Description",
     "price": 29.99,
     "category": "Electronics",
     "stock_quantity": 5
   }
   ```

5. **Test PUT Update Product:**
   ```
   PUT http://localhost/phpAPI/api/products/1
   Body (raw JSON):
   {
     "name": "Updated Name",
     "price": 39.99
   }
   ```

6. **Test DELETE Product:**
   ```
   DELETE http://localhost/phpAPI/api/products/1
   ```

### Testing with cURL

```bash
# Get all products
curl -X GET http://localhost/phpAPI/api/products

# Get single product
curl -X GET http://localhost/phpAPI/api/products/1

# Create product
curl -X POST http://localhost/phpAPI/api/products \
  -H "Content-Type: application/json" \
  -d '{"name":"New Product","price":49.99,"category":"Electronics"}'

# Update product
curl -X PUT http://localhost/phpAPI/api/products/1 \
  -H "Content-Type: application/json" \
  -d '{"name":"Updated Product","price":59.99}'

# Delete product
curl -X DELETE http://localhost/phpAPI/api/products/1
```

### Browser Testing

1. Open browser developer tools (F12)
2. Navigate to the Network tab
3. Perform operations in the frontend
4. Monitor API requests and responses
5. Check for errors in the Console tab

## 📝 Code Structure

### Backend Architecture

#### Models (`backend/models/`)
- **Product.php**: Handles all database operations for products
  - Uses prepared statements for security
  - Methods: `readAll()`, `readOne()`, `readByCategory()`, `create()`, `update()`, `delete()`

- **Category.php**: Handles category database operations
  - Methods: `readAll()`, `readOne()`

#### API Endpoints (`backend/api/`)
- **products/index.php**: Routes product requests based on HTTP method
  - GET: Retrieve products
  - POST: Create product
  - PUT: Update product
  - DELETE: Delete product

- **categories/index.php**: Routes category requests
  - GET: Retrieve categories or products by category

#### Configuration (`backend/config/`)
- **database.php**: Database connection class using PDO
- **cors.php**: CORS headers configuration

### Frontend Architecture

#### JavaScript (`frontend/app.js`)
- **API Functions:**
  - `loadProducts()`: Fetch all products
  - `loadCategories()`: Fetch all categories
  - `createProduct()`: Create new product (via `saveProduct()`)
  - `editProduct(id)`: Load and edit product
  - `deleteProduct(id)`: Delete product with confirmation
  - `saveProduct()`: Save product (create or update)

- **UI Functions:**
  - `displayProducts(products)`: Render products in table
  - `filterProducts()`: Filter and sort products
  - `showLoading()`, `showError()`, `showSuccess()`: UI feedback

- **Utility Functions:**
  - `debounce()`: Limit function calls
  - `escapeHtml()`: Prevent XSS
  - `formatPrice()`: Format currency
  - `getStockClass()`: Get CSS class for stock level

## 🔒 Security Features

1. **Prepared Statements**: All SQL queries use PDO prepared statements to prevent SQL injection
2. **Input Sanitization**: All user inputs are sanitized using `htmlspecialchars()` and `strip_tags()`
3. **Input Validation**: Required fields are validated before processing
4. **CORS Configuration**: Proper CORS headers for cross-origin requests
5. **XSS Prevention**: HTML escaping in frontend display functions

## 🐛 Troubleshooting

### Common Issues

1. **API returns 404:**
   - Check `.htaccess` is in the root directory
   - Ensure mod_rewrite is enabled in Apache
   - Verify URL routing is correct

2. **Database connection fails:**
   - Check database credentials in `backend/config/database.php`
   - Ensure MySQL service is running
   - Verify database exists: `SHOW DATABASES;`

3. **CORS errors:**
   - Check `backend/config/cors.php` is included
   - Verify CORS headers are being sent

4. **Products not loading:**
   - Check browser console for errors
   - Verify API base URL in `frontend/app.js`
   - Test API endpoints directly in browser or Postman

5. **Modal not working:**
   - Ensure Bootstrap JS is loaded
   - Check for JavaScript errors in console

## 📄 License

This project is created for educational purposes as part of a REST API development assignment.

## 👨‍💻 Author

Created for Assignment #5 - REST API Development and Consumption

## 📅 Date

November 2024

---

**Note:** This is a learning project demonstrating REST API development and consumption. For production use, additional security measures, authentication, and error handling should be implemented.

