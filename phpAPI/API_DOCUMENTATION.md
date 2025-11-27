# API Documentation - Product Management System

Complete API reference for the Product Management REST API.

## Base URL

```
http://localhost/phpAPI/api
```

## Authentication

Currently, no authentication is required. All endpoints are publicly accessible.

## Response Format

All responses are in JSON format with the following structure:

### Success Response
```json
{
  "status": "success",
  "data": {...},
  "message": "Operation successful"
}
```

### Error Response
```json
{
  "status": "error",
  "message": "Error description",
  "code": 400
}
```

## HTTP Status Codes

| Code | Description |
|------|-------------|
| 200 | OK - Request successful |
| 201 | Created - Resource created successfully |
| 400 | Bad Request - Invalid request data |
| 404 | Not Found - Resource not found |
| 405 | Method Not Allowed - HTTP method not supported |
| 500 | Internal Server Error - Server error |

---

## Product Endpoints

### 1. Get All Products

Retrieve a list of all products.

**Endpoint:** `GET /api/products`

**Request:**
```http
GET /api/products HTTP/1.1
Host: localhost
```

**Response (200 OK):**
```json
{
  "status": "success",
  "data": [
    {
      "id": 1,
      "name": "Laptop",
      "description": "High-performance laptop with latest processor",
      "price": "999.99",
      "category": "Electronics",
      "stock_quantity": 15,
      "created_at": "2024-01-01 10:00:00",
      "updated_at": "2024-01-01 10:00:00"
    },
    {
      "id": 2,
      "name": "Smartphone",
      "description": "Latest smartphone model with advanced features",
      "price": "699.99",
      "category": "Electronics",
      "stock_quantity": 25,
      "created_at": "2024-01-01 10:00:00",
      "updated_at": "2024-01-01 10:00:00"
    }
  ],
  "pagination": {
    "total": 2,
    "page": 1,
    "per_page": 2
  }
}
```

---

### 2. Get Single Product

Retrieve a specific product by ID.

**Endpoint:** `GET /api/products/{id}`

**Parameters:**
- `id` (path, required) - Product ID (integer)

**Request:**
```http
GET /api/products/1 HTTP/1.1
Host: localhost
```

**Response (200 OK):**
```json
{
  "status": "success",
  "data": {
    "id": 1,
    "name": "Laptop",
    "description": "High-performance laptop with latest processor",
    "price": "999.99",
    "category": "Electronics",
    "stock_quantity": 15,
    "created_at": "2024-01-01 10:00:00",
    "updated_at": "2024-01-01 10:00:00"
  },
  "message": "Product retrieved successfully"
}
```

**Response (404 Not Found):**
```json
{
  "status": "error",
  "message": "Product not found",
  "code": 404
}
```

---

### 3. Create Product

Create a new product.

**Endpoint:** `POST /api/products`

**Request Headers:**
```
Content-Type: application/json
```

**Request Body:**
```json
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
- `price` (number) - Product price (must be >= 0)

**Optional Fields:**
- `description` (string) - Product description
- `category` (string) - Product category
- `stock_quantity` (integer) - Stock quantity (default: 0)

**Request Example:**
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

**Response (201 Created):**
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

**Response (400 Bad Request):**
```json
{
  "status": "error",
  "message": "Name and price are required fields",
  "code": 400
}
```

---

### 4. Update Product

Update an existing product.

**Endpoint:** `PUT /api/products/{id}`

**Parameters:**
- `id` (path, required) - Product ID (integer)

**Request Headers:**
```
Content-Type: application/json
```

**Request Body:**
All fields are optional. Only provided fields will be updated.
```json
{
  "name": "Updated Product Name",
  "description": "Updated description",
  "price": 59.99,
  "category": "Books",
  "stock_quantity": 20
}
```

**Request Example:**
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

**Response (200 OK):**
```json
{
  "status": "success",
  "data": {
    "id": 1,
    "name": "Updated Product Name",
    "description": "High-performance laptop with latest processor",
    "price": "59.99",
    "category": "Electronics",
    "stock_quantity": 20
  },
  "message": "Product updated successfully"
}
```

**Response (404 Not Found):**
```json
{
  "status": "error",
  "message": "Product not found",
  "code": 404
}
```

---

### 5. Delete Product

Delete a product by ID.

**Endpoint:** `DELETE /api/products/{id}`

**Parameters:**
- `id` (path, required) - Product ID (integer)

**Request:**
```http
DELETE /api/products/1 HTTP/1.1
Host: localhost
```

**Response (200 OK):**
```json
{
  "status": "success",
  "message": "Product deleted successfully"
}
```

**Response (404 Not Found):**
```json
{
  "status": "error",
  "message": "Product not found",
  "code": 404
}
```

---

## Category Endpoints

### 1. Get All Categories

Retrieve a list of all categories.

**Endpoint:** `GET /api/categories`

**Request:**
```http
GET /api/categories HTTP/1.1
Host: localhost
```

**Response (200 OK):**
```json
{
  "status": "success",
  "data": [
    {
      "id": 1,
      "name": "Electronics",
      "description": "Electronic devices and accessories",
      "created_at": "2024-01-01 10:00:00"
    },
    {
      "id": 2,
      "name": "Books",
      "description": "Various books and publications",
      "created_at": "2024-01-01 10:00:00"
    },
    {
      "id": 3,
      "name": "Clothing",
      "description": "Apparel and fashion items",
      "created_at": "2024-01-01 10:00:00"
    }
  ],
  "pagination": {
    "total": 3,
    "page": 1,
    "per_page": 3
  }
}
```

---

### 2. Get Products by Category

Retrieve all products in a specific category.

**Endpoint:** `GET /api/categories/{id}/products`

**Parameters:**
- `id` (path, required) - Category ID (integer)

**Request:**
```http
GET /api/categories/1/products HTTP/1.1
Host: localhost
```

**Response (200 OK):**
```json
{
  "status": "success",
  "data": [
    {
      "id": 1,
      "name": "Laptop",
      "description": "High-performance laptop with latest processor",
      "price": "999.99",
      "category": "Electronics",
      "stock_quantity": 15,
      "created_at": "2024-01-01 10:00:00",
      "updated_at": "2024-01-01 10:00:00"
    },
    {
      "id": 2,
      "name": "Smartphone",
      "description": "Latest smartphone model with advanced features",
      "price": "699.99",
      "category": "Electronics",
      "stock_quantity": 25,
      "created_at": "2024-01-01 10:00:00",
      "updated_at": "2024-01-01 10:00:00"
    }
  ],
  "category": "Electronics",
  "pagination": {
    "total": 2,
    "page": 1,
    "per_page": 2
  }
}
```

**Response (404 Not Found):**
```json
{
  "status": "error",
  "message": "Category not found",
  "code": 404
}
```

---

## Example Requests

### Using cURL

#### Get All Products
```bash
curl -X GET http://localhost/phpAPI/api/products
```

#### Get Single Product
```bash
curl -X GET http://localhost/phpAPI/api/products/1
```

#### Create Product
```bash
curl -X POST http://localhost/phpAPI/api/products \
  -H "Content-Type: application/json" \
  -d '{
    "name": "New Product",
    "description": "Product description",
    "price": 49.99,
    "category": "Electronics",
    "stock_quantity": 10
  }'
```

#### Update Product
```bash
curl -X PUT http://localhost/phpAPI/api/products/1 \
  -H "Content-Type: application/json" \
  -d '{
    "name": "Updated Product",
    "price": 59.99
  }'
```

#### Delete Product
```bash
curl -X DELETE http://localhost/phpAPI/api/products/1
```

#### Get All Categories
```bash
curl -X GET http://localhost/phpAPI/api/categories
```

#### Get Products by Category
```bash
curl -X GET http://localhost/phpAPI/api/categories/1/products
```

### Using JavaScript (jQuery)

#### Get All Products
```javascript
$.ajax({
  url: '/phpAPI/api/products',
  method: 'GET',
  dataType: 'json',
  success: function(response) {
    console.log(response.data);
  }
});
```

#### Create Product
```javascript
$.ajax({
  url: '/phpAPI/api/products',
  method: 'POST',
  contentType: 'application/json',
  data: JSON.stringify({
    name: 'New Product',
    price: 49.99,
    category: 'Electronics'
  }),
  success: function(response) {
    console.log('Product created:', response.data);
  }
});
```

#### Update Product
```javascript
$.ajax({
  url: '/phpAPI/api/products/1',
  method: 'PUT',
  contentType: 'application/json',
  data: JSON.stringify({
    name: 'Updated Product',
    price: 59.99
  }),
  success: function(response) {
    console.log('Product updated:', response.data);
  }
});
```

#### Delete Product
```javascript
$.ajax({
  url: '/phpAPI/api/products/1',
  method: 'DELETE',
  success: function(response) {
    console.log('Product deleted');
  }
});
```

---

## Error Handling

All endpoints return appropriate HTTP status codes and error messages. Always check the `status` field in the response:

```javascript
$.ajax({
  url: '/phpAPI/api/products',
  method: 'GET',
  dataType: 'json',
  success: function(response) {
    if (response.status === 'success') {
      // Handle success
      console.log(response.data);
    } else {
      // Handle error
      console.error(response.message);
    }
  },
  error: function(xhr, status, error) {
    // Handle HTTP error
    console.error('Request failed:', error);
  }
});
```

---

## Rate Limiting

Currently, there are no rate limits implemented. For production use, consider implementing rate limiting to prevent abuse.

---

## Versioning

Current API version: **v1**

No versioning is currently implemented. For future versions, consider using URL versioning:
- `/api/v1/products`
- `/api/v2/products`

---

## Support

For issues or questions, please refer to the main README.md file or check the code comments in the source files.

