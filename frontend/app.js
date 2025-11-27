/**
 * Product Management System - Frontend Application
 * Handles all API consumption using jQuery and AJAX
 */

// API Base URL - Adjust based on your server configuration
const API_BASE_URL = '/phpAPI/backend/api';

// Global variables
let allProducts = [];
let allCategories = [];
let productModal;
let currentEditId = null;

// Initialize application when DOM is ready
$(document).ready(function() {
    // Initialize Bootstrap modal
    productModal = new bootstrap.Modal(document.getElementById('productModal'));
    
    // Load initial data
    loadProducts();
    loadCategories();
    
    // Event listeners
    setupEventListeners();
});

/**
 * Setup all event listeners
 */
function setupEventListeners() {
    // Search functionality
    $('#searchInput').on('input', debounce(function() {
        filterProducts();
    }, 300));
    
    // Category filter
    $('#categoryFilter').on('change', function() {
        filterProducts();
    });
    
    // Sort functionality
    $('#sortSelect').on('change', function() {
        filterProducts();
    });
    
    // Add product button
    $('#addProductBtn').on('click', function() {
        openProductModal();
    });
    
    // Save product button
    $('#saveProductBtn').on('click', function() {
        saveProduct();
    });
    
    // Form validation
    $('#productForm').on('submit', function(e) {
        e.preventDefault();
        saveProduct();
    });
}

/**
 * Load all products from API
 */
function loadProducts() {
    showLoading(true);
    hideMessages();
    
    $.ajax({
        url: API_BASE_URL + '/products',
        method: 'GET',
        dataType: 'json',
        success: function(response) {
            if (response.status === 'success') {
                allProducts = response.data || [];
                displayProducts(allProducts);
                updateProductCount(allProducts.length);
                showSuccess('Products loaded successfully');
            } else {
                showError('Failed to load products: ' + (response.message || 'Unknown error'));
            }
        },
        error: function(xhr, status, error) {
            console.error('Error loading products:', error);
            showError('Failed to load products. Please check your API connection.');
            displayProducts([]);
        },
        complete: function() {
            showLoading(false);
        }
    });
}

/**
 * Load all categories from API
 */
function loadCategories() {
    $.ajax({
        url: API_BASE_URL + '/categories',
        method: 'GET',
        dataType: 'json',
        success: function(response) {
            if (response.status === 'success') {
                allCategories = response.data || [];
                populateCategoryDropdowns();
            }
        },
        error: function(xhr, status, error) {
            console.error('Error loading categories:', error);
        }
    });
}

/**
 * Populate category dropdowns with categories from API
 */
function populateCategoryDropdowns() {
    const categoryFilter = $('#categoryFilter');
    const productCategory = $('#productCategory');
    
    // Clear existing options (except "All Categories" and "Select Category")
    categoryFilter.find('option:not(:first)').remove();
    productCategory.find('option:not(:first)').remove();
    
    // Add categories
    allCategories.forEach(function(category) {
        categoryFilter.append(
            $('<option></option>')
                .attr('value', category.name)
                .text(category.name)
        );
        
        productCategory.append(
            $('<option></option>')
                .attr('value', category.name)
                .text(category.name)
        );
    });
}

/**
 * Display products in the table
 * @param {Array} products - Array of product objects
 */
function displayProducts(products) {
    const tbody = $('#productsTableBody');
    tbody.empty();
    
    if (products.length === 0) {
        $('#noProducts').show();
        $('#productsTable').hide();
        return;
    }
    
    $('#noProducts').hide();
    $('#productsTable').show();
    
    products.forEach(function(product) {
        const row = createProductRow(product);
        tbody.append(row);
    });
}

/**
 * Create a table row for a product
 * @param {Object} product - Product object
 * @returns {jQuery} Table row element
 */
function createProductRow(product) {
    const stockClass = getStockClass(product.stock_quantity);
    const formattedPrice = formatPrice(product.price);
    
    const row = $('<tr></tr>').addClass('fade-in');
    
    row.html(`
        <td>${product.id}</td>
        <td><strong>${escapeHtml(product.name)}</strong></td>
        <td>${escapeHtml(product.description || '-')}</td>
        <td class="price">${formattedPrice}</td>
        <td><span class="badge bg-info">${escapeHtml(product.category || '-')}</span></td>
        <td><span class="${stockClass}">${product.stock_quantity}</span></td>
        <td class="action-buttons">
            <button class="btn btn-sm btn-primary edit-product" data-id="${product.id}" title="Edit">
                <i class="bi bi-pencil"></i>
            </button>
            <button class="btn btn-sm btn-danger delete-product" data-id="${product.id}" title="Delete">
                <i class="bi bi-trash"></i>
            </button>
        </td>
    `);
    
    // Attach event listeners
    row.find('.edit-product').on('click', function() {
        editProduct($(this).data('id'));
    });
    
    row.find('.delete-product').on('click', function() {
        deleteProduct($(this).data('id'));
    });
    
    return row;
}

/**
 * Get CSS class based on stock quantity
 * @param {number} stock - Stock quantity
 * @returns {string} CSS class name
 */
function getStockClass(stock) {
    if (stock === 0) return 'stock-low';
    if (stock < 10) return 'stock-low';
    if (stock < 25) return 'stock-medium';
    return 'stock-high';
}

/**
 * Format price with currency symbol
 * @param {number} price - Price value
 * @returns {string} Formatted price
 */
function formatPrice(price) {
    return '$' + parseFloat(price).toFixed(2);
}

/**
 * Escape HTML to prevent XSS
 * @param {string} text - Text to escape
 * @returns {string} Escaped text
 */
function escapeHtml(text) {
    const map = {
        '&': '&amp;',
        '<': '&lt;',
        '>': '&gt;',
        '"': '&quot;',
        "'": '&#039;'
    };
    return String(text).replace(/[&<>"']/g, function(m) { return map[m]; });
}

/**
 * Filter and sort products based on search, category, and sort options
 */
function filterProducts() {
    let filtered = [...allProducts];
    
    // Search filter
    const searchTerm = $('#searchInput').val().toLowerCase();
    if (searchTerm) {
        filtered = filtered.filter(function(product) {
            return product.name.toLowerCase().includes(searchTerm) ||
                   (product.description && product.description.toLowerCase().includes(searchTerm));
        });
    }
    
    // Category filter
    const category = $('#categoryFilter').val();
    if (category) {
        filtered = filtered.filter(function(product) {
            return product.category === category;
        });
    }
    
    // Sort
    const sortOption = $('#sortSelect').val();
    filtered.sort(function(a, b) {
        switch(sortOption) {
            case 'name-asc':
                return a.name.localeCompare(b.name);
            case 'name-desc':
                return b.name.localeCompare(a.name);
            case 'price-asc':
                return parseFloat(a.price) - parseFloat(b.price);
            case 'price-desc':
                return parseFloat(b.price) - parseFloat(a.price);
            case 'stock-asc':
                return a.stock_quantity - b.stock_quantity;
            case 'stock-desc':
                return b.stock_quantity - a.stock_quantity;
            default:
                return 0;
        }
    });
    
    displayProducts(filtered);
    updateProductCount(filtered.length);
}

/**
 * Update product count badge
 * @param {number} count - Number of products
 */
function updateProductCount(count) {
    $('#productCount').text(count);
}

/**
 * Open product modal for adding new product
 */
function openProductModal() {
    currentEditId = null;
    $('#productModalLabel').html('<i class="bi bi-plus-circle"></i> Add New Product');
    $('#productForm')[0].reset();
    $('#productId').val('');
    productModal.show();
}

/**
 * Edit product - Load product data and open modal
 * @param {number} id - Product ID
 */
function editProduct(id) {
    currentEditId = id;
    showLoading(true);
    
    $.ajax({
        url: API_BASE_URL + '/products/' + id,
        method: 'GET',
        dataType: 'json',
        success: function(response) {
            if (response.status === 'success' && response.data) {
                const product = response.data;
                $('#productId').val(product.id);
                $('#productName').val(product.name);
                $('#productDescription').val(product.description || '');
                $('#productPrice').val(product.price);
                $('#productCategory').val(product.category || '');
                $('#productStock').val(product.stock_quantity || 0);
                
                $('#productModalLabel').html('<i class="bi bi-pencil"></i> Edit Product');
                productModal.show();
            } else {
                showError('Product not found');
            }
        },
        error: function(xhr, status, error) {
            console.error('Error loading product:', error);
            showError('Failed to load product details');
        },
        complete: function() {
            showLoading(false);
        }
    });
}

/**
 * Save product (Create or Update)
 */
function saveProduct() {
    // Validate form
    if (!validateProductForm()) {
        return;
    }
    
    const productData = {
        name: $('#productName').val().trim(),
        description: $('#productDescription').val().trim(),
        price: parseFloat($('#productPrice').val()),
        category: $('#productCategory').val(),
        stock_quantity: parseInt($('#productStock').val()) || 0
    };
    
    const isEdit = currentEditId !== null;
    const url = isEdit ? API_BASE_URL + '/products/' + currentEditId : API_BASE_URL + '/products';
    const method = isEdit ? 'PUT' : 'POST';
    
    showLoading(true);
    hideMessages();
    
    $.ajax({
        url: url,
        method: method,
        contentType: 'application/json',
        data: JSON.stringify(productData),
        dataType: 'json',
        success: function(response) {
            if (response.status === 'success') {
                productModal.hide();
                showSuccess(isEdit ? 'Product updated successfully!' : 'Product created successfully!');
                loadProducts(); // Reload products list
            } else {
                showError('Failed to save product: ' + (response.message || 'Unknown error'));
            }
        },
        error: function(xhr, status, error) {
            console.error('Error saving product:', error);
            let errorMsg = 'Failed to save product.';
            if (xhr.responseJSON && xhr.responseJSON.message) {
                errorMsg = xhr.responseJSON.message;
            }
            showError(errorMsg);
        },
        complete: function() {
            showLoading(false);
        }
    });
}

/**
 * Delete product with confirmation
 * @param {number} id - Product ID
 */
function deleteProduct(id) {
    // Find product name for confirmation
    const product = allProducts.find(p => p.id == id);
    const productName = product ? product.name : 'this product';
    
    if (!confirm(`Are you sure you want to delete "${productName}"? This action cannot be undone.`)) {
        return;
    }
    
    showLoading(true);
    hideMessages();
    
    $.ajax({
        url: API_BASE_URL + '/products/' + id,
        method: 'DELETE',
        dataType: 'json',
        success: function(response) {
            if (response.status === 'success') {
                showSuccess('Product deleted successfully!');
                loadProducts(); // Reload products list
            } else {
                showError('Failed to delete product: ' + (response.message || 'Unknown error'));
            }
        },
        error: function(xhr, status, error) {
            console.error('Error deleting product:', error);
            let errorMsg = 'Failed to delete product.';
            if (xhr.responseJSON && xhr.responseJSON.message) {
                errorMsg = xhr.responseJSON.message;
            }
            showError(errorMsg);
        },
        complete: function() {
            showLoading(false);
        }
    });
}

/**
 * Validate product form
 * @returns {boolean} True if valid, false otherwise
 */
function validateProductForm() {
    let isValid = true;
    
    // Reset validation
    $('.form-control').removeClass('is-invalid');
    
    // Validate name
    if (!$('#productName').val().trim()) {
        $('#productName').addClass('is-invalid');
        isValid = false;
    }
    
    // Validate price
    const price = parseFloat($('#productPrice').val());
    if (isNaN(price) || price < 0) {
        $('#productPrice').addClass('is-invalid');
        isValid = false;
    }
    
    return isValid;
}

/**
 * Show loading indicator
 * @param {boolean} show - Show or hide
 */
function showLoading(show) {
    if (show) {
        $('#loadingIndicator').show();
    } else {
        $('#loadingIndicator').hide();
    }
}

/**
 * Show error message
 * @param {string} message - Error message
 */
function showError(message) {
    $('#errorMessage').text(message).fadeIn();
    setTimeout(function() {
        $('#errorMessage').fadeOut();
    }, 5000);
}

/**
 * Show success message
 * @param {string} message - Success message
 */
function showSuccess(message) {
    $('#successMessage').text(message).fadeIn();
    setTimeout(function() {
        $('#successMessage').fadeOut();
    }, 3000);
}

/**
 * Hide all messages
 */
function hideMessages() {
    $('#errorMessage').hide();
    $('#successMessage').hide();
}

/**
 * Debounce function to limit function calls
 * @param {Function} func - Function to debounce
 * @param {number} wait - Wait time in milliseconds
 * @returns {Function} Debounced function
 */
function debounce(func, wait) {
    let timeout;
    return function executedFunction(...args) {
        const later = () => {
            clearTimeout(timeout);
            func(...args);
        };
        clearTimeout(timeout);
        timeout = setTimeout(later, wait);
    };
}

