# Quick Setup Guide

## Prerequisites Checklist

- [ ] PHP 7.4+ installed
- [ ] MySQL 5.7+ installed and running
- [ ] Apache web server with mod_rewrite enabled
- [ ] Web browser

## Step-by-Step Setup

### 1. Database Setup (5 minutes)

**Option A: Using Command Line**
```bash
# Navigate to project directory
cd /path/to/phpAPI

# Import database schema
mysql -u root -p < database/schema.sql
```

**Option B: Using phpMyAdmin**
1. Open phpMyAdmin (usually at `http://localhost/phpmyadmin`)
2. Click "Import" tab
3. Choose file: `database/schema.sql`
4. Click "Go"

**Verify:**
```sql
USE product_management;
SELECT COUNT(*) FROM products;  -- Should return 7
SELECT COUNT(*) FROM categories;  -- Should return 3
```

### 2. Configure Database Connection (2 minutes)

Edit `backend/config/database.php`:

```php
private $host = 'localhost';        // Usually 'localhost'
private $db_name = 'product_management';  // Database name
private $username = 'root';         // Your MySQL username
private $password = '';             // Your MySQL password
```

### 3. Web Server Setup (3 minutes)

**For XAMPP (Windows/Mac/Linux):**
1. Copy `phpAPI` folder to:
   - Windows: `C:\xampp\htdocs\phpAPI`
   - Mac: `/Applications/XAMPP/htdocs/phpAPI`
   - Linux: `/opt/lampp/htdocs/phpAPI`

2. Start Apache and MySQL from XAMPP Control Panel

3. Access: `http://localhost/phpAPI/frontend/index.html`

**For WAMP (Windows):**
1. Copy `phpAPI` folder to `C:\wamp64\www\phpAPI`
2. Start WAMP services
3. Access: `http://localhost/phpAPI/frontend/index.html`

**For MAMP (Mac):**
1. Copy `phpAPI` folder to `/Applications/MAMP/htdocs/phpAPI`
2. Start MAMP servers
3. Access: `http://localhost:8888/phpAPI/frontend/index.html`

**For PHP Built-in Server:**
```bash
cd /path/to/phpAPI
php -S localhost:8000
# Access: http://localhost:8000/frontend/index.html
```

### 4. Test the Application (2 minutes)

1. **Open Frontend:**
   - Navigate to `http://localhost/phpAPI/frontend/index.html`
   - You should see 7 products loaded

2. **Test API Directly:**
   - Open: `http://localhost/phpAPI/api/products`
   - You should see JSON response with products

3. **Test Operations:**
   - Click "Add Product" - Fill form - Save
   - Click edit icon (pencil) - Modify - Save
   - Click delete icon (trash) - Confirm
   - Try search, filter, and sort

## Troubleshooting

### Problem: "Database connection failed"
**Solution:**
- Check MySQL is running
- Verify credentials in `backend/config/database.php`
- Test connection: `mysql -u root -p`

### Problem: "404 Not Found" for API
**Solution:**
- Check `.htaccess` file exists in root
- Enable mod_rewrite in Apache
- Check Apache error logs

### Problem: "CORS error" in browser console
**Solution:**
- Verify `backend/config/cors.php` is included
- Check file paths are correct

### Problem: Products not loading
**Solution:**
- Open browser console (F12)
- Check for JavaScript errors
- Verify API URL in `frontend/app.js`:
  ```javascript
  const API_BASE_URL = '/phpAPI/api';  // Adjust if needed
  ```

### Problem: Modal not opening
**Solution:**
- Check Bootstrap JS is loaded
- Verify jQuery is loaded before app.js
- Check browser console for errors

## Quick Test Commands

### Test Database
```bash
mysql -u root -p -e "USE product_management; SELECT * FROM products;"
```

### Test API with cURL
```bash
# Get all products
curl http://localhost/phpAPI/api/products

# Get single product
curl http://localhost/phpAPI/api/products/1

# Create product
curl -X POST http://localhost/phpAPI/api/products \
  -H "Content-Type: application/json" \
  -d '{"name":"Test","price":10.99}'
```

### Check PHP Version
```bash
php -v  # Should be 7.4 or higher
```

### Check Apache mod_rewrite
```bash
# On Linux/Mac
apache2ctl -M | grep rewrite

# Or check httpd.conf for: LoadModule rewrite_module
```

## File Permissions (Linux/Mac)

If you get permission errors:
```bash
chmod -R 755 /path/to/phpAPI
chown -R www-data:www-data /path/to/phpAPI  # Adjust user/group
```

## Next Steps

1. ✅ Database imported
2. ✅ Database configured
3. ✅ Web server running
4. ✅ Frontend accessible
5. ✅ API responding
6. ✅ CRUD operations working

**You're all set!** 🎉

For detailed documentation, see `README.md` and `API_DOCUMENTATION.md`.

