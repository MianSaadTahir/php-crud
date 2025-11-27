<?php
/**
 * Simple API Test Page
 * Use this to verify API endpoints are working
 * Access: http://localhost/phpAPI/test_api.php
 */

// Test database connection
require_once 'backend/config/database.php';

$database = new Database();
$db = $database->getConnection();

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>API Test Page</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            max-width: 1200px;
            margin: 50px auto;
            padding: 20px;
            background: #f5f5f5;
        }
        .test-section {
            background: white;
            padding: 20px;
            margin: 20px 0;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        .status {
            padding: 10px;
            margin: 10px 0;
            border-radius: 4px;
        }
        .success {
            background: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }
        .error {
            background: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }
        .info {
            background: #d1ecf1;
            color: #0c5460;
            border: 1px solid #bee5eb;
        }
        pre {
            background: #f8f9fa;
            padding: 15px;
            border-radius: 4px;
            overflow-x: auto;
        }
        a {
            color: #007bff;
            text-decoration: none;
        }
        a:hover {
            text-decoration: underline;
        }
        .endpoint {
            margin: 10px 0;
            padding: 10px;
            background: #e9ecef;
            border-left: 4px solid #007bff;
        }
    </style>
</head>
<body>
    <h1>🔧 API Test Page</h1>
    <p>Use this page to verify your API setup is working correctly.</p>

    <div class="test-section">
        <h2>1. Database Connection Test</h2>
        <?php if ($db): ?>
            <div class="status success">
                ✅ <strong>Success:</strong> Database connection established!
            </div>
            <?php
            try {
                $stmt = $db->query("SELECT COUNT(*) as count FROM products");
                $result = $stmt->fetch(PDO::FETCH_ASSOC);
                echo '<div class="status info">';
                echo "📊 Products in database: <strong>" . $result['count'] . "</strong>";
                echo '</div>';
                
                $stmt = $db->query("SELECT COUNT(*) as count FROM categories");
                $result = $stmt->fetch(PDO::FETCH_ASSOC);
                echo '<div class="status info">';
                echo "📁 Categories in database: <strong>" . $result['count'] . "</strong>";
                echo '</div>';
            } catch (PDOException $e) {
                echo '<div class="status error">';
                echo "❌ Error querying database: " . $e->getMessage();
                echo '</div>';
            }
            ?>
        <?php else: ?>
            <div class="status error">
                ❌ <strong>Error:</strong> Database connection failed!
                <br>Please check your database configuration in <code>backend/config/database.php</code>
            </div>
        <?php endif; ?>
    </div>

    <div class="test-section">
        <h2>2. API Endpoints</h2>
        <p>Test these endpoints in your browser or Postman:</p>
        
        <div class="endpoint">
            <strong>GET All Products:</strong><br>
            <a href="api/products" target="_blank"><?php echo $_SERVER['HTTP_HOST'] . dirname($_SERVER['PHP_SELF']) . '/api/products'; ?></a>
        </div>
        
        <div class="endpoint">
            <strong>GET Single Product:</strong><br>
            <a href="api/products/1" target="_blank"><?php echo $_SERVER['HTTP_HOST'] . dirname($_SERVER['PHP_SELF']) . '/api/products/1'; ?></a>
        </div>
        
        <div class="endpoint">
            <strong>GET All Categories:</strong><br>
            <a href="api/categories" target="_blank"><?php echo $_SERVER['HTTP_HOST'] . dirname($_SERVER['PHP_SELF']) . '/api/categories'; ?></a>
        </div>
        
        <div class="endpoint">
            <strong>GET Products by Category:</strong><br>
            <a href="api/categories/1/products" target="_blank"><?php echo $_SERVER['HTTP_HOST'] . dirname($_SERVER['PHP_SELF']) . '/api/categories/1/products'; ?></a>
        </div>
    </div>

    <div class="test-section">
        <h2>3. Test API Response</h2>
        <p>Click the link below to test the API response:</p>
        <p><a href="api/products" target="_blank">Test: Get All Products</a></p>
        
        <?php
        // Test API endpoint
        if (isset($_GET['test'])) {
            echo '<h3>API Response:</h3>';
            $url = 'http://' . $_SERVER['HTTP_HOST'] . dirname($_SERVER['PHP_SELF']) . '/api/products';
            $response = @file_get_contents($url);
            
            if ($response) {
                $json = json_decode($response, true);
                if ($json) {
                    echo '<div class="status success">✅ Valid JSON response received!</div>';
                    echo '<pre>' . json_encode($json, JSON_PRETTY_PRINT) . '</pre>';
                } else {
                    echo '<div class="status error">❌ Invalid JSON response</div>';
                    echo '<pre>' . htmlspecialchars($response) . '</pre>';
                }
            } else {
                echo '<div class="status error">❌ Could not fetch API response</div>';
            }
        }
        ?>
    </div>

    <div class="test-section">
        <h2>4. Frontend Application</h2>
        <p>Access the main application:</p>
        <p><a href="frontend/index.html" target="_blank">Open Product Management Interface</a></p>
    </div>

    <div class="test-section">
        <h2>5. Configuration Check</h2>
        <ul>
            <li><strong>PHP Version:</strong> <?php echo phpversion(); ?></li>
            <li><strong>PDO Available:</strong> <?php echo extension_loaded('pdo') ? '✅ Yes' : '❌ No'; ?></li>
            <li><strong>MySQL PDO:</strong> <?php echo extension_loaded('pdo_mysql') ? '✅ Yes' : '❌ No'; ?></li>
            <li><strong>mod_rewrite:</strong> Check Apache configuration</li>
        </ul>
    </div>

    <div class="test-section">
        <h2>6. Next Steps</h2>
        <ol>
            <li>✅ Verify database connection (above)</li>
            <li>✅ Test API endpoints (click links above)</li>
            <li>✅ Open frontend application</li>
            <li>✅ Import Postman collection: <code>Postman_Collection.json</code></li>
            <li>✅ Read documentation: <code>README.md</code></li>
        </ol>
    </div>

    <div class="test-section">
        <h2>📚 Documentation</h2>
        <ul>
            <li><a href="README.md">README.md</a> - Complete documentation</li>
            <li><a href="API_DOCUMENTATION.md">API_DOCUMENTATION.md</a> - API reference</li>
            <li><a href="SETUP_GUIDE.md">SETUP_GUIDE.md</a> - Quick setup guide</li>
        </ul>
    </div>
</body>
</html>

