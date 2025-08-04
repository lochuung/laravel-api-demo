import {getProduct, deleteProduct} from '../../api/products.api.js';

let currentProductId = null;
let product = null;

$(document).ready(async function () {
    // Get product ID from window variable or URL as fallback
    currentProductId = window.productId || getIdFromUrl('products');

    if (currentProductId && currentProductId > 0) {
        await loadProductDetails(currentProductId);
        setupEventListeners();
    } else {
        showErrorMessage('Product ID not found');
    }
});

function setupEventListeners() {
    // Edit product link
    $('#edit-product-link').on('click', function (e) {
        e.preventDefault();
        if (currentProductId) {
            window.location.href = `/products/${currentProductId}/edit`;
        }
    });

    // Delete product button
    $('#delete-product-btn').on('click', async () => {
        await handleDeleteProduct(currentProductId);
    });
}

async function loadProductDetails(productId) {
    try {
        // Show loading overlay
        $('#loading-overlay').removeClass('d-none');

        const response = await getProduct(productId);
        product = response.data.data;

        renderProductDetails(product);
        renderProductStats(product);
        renderInventoryTab(product);
        renderSalesTab(product);

    } catch (error) {
        console.error('Failed to load product details:', error);
        const msg = error.response?.data?.message || 'Failed to load product details';
        showErrorMessage(msg);

        if (error.response?.status === 404) {
            setTimeout(() => window.location.href = '/products', 3000);
        }
    } finally {
        $('#loading-overlay').addClass('d-none');
    }
}

async function handleDeleteProduct(productId) {
    if (!confirm('Are you sure you want to delete this product? This action cannot be undone!')) return;

    try {
        await deleteProduct(productId);
        showSuccessMessage('Product deleted successfully');
        setTimeout(() => window.location.href = '/products', 2000);
    } catch (error) {
        console.error('Failed to delete product:', error);
        showErrorMessage(error.response?.data?.message || 'Failed to delete product');
    }
}

/**
 * Render product details in the Details tab
 */
function renderProductDetails(product) {
    // Product name
    $('#product-name').text(product.name || 'N/A');

    $('#product-code').text(product.code || 'N/A');

    // Category
    const categoryName = product.category?.name || 'Uncategorized';
    $('#product-category').text(categoryName);

    // Price
    const formattedPrice = formatCurrencyLocal(product.price);
    const formattedCost = product.cost ? formatCurrencyLocal(product.cost) : 'N/A';
    $('#product-price').html(`
        <h4 class="text-primary mb-0">${formattedPrice}</h4>
        <small class="text-muted">Cost: ${formattedCost}</small>
    `);

    // Status
    const statusBadge = product.is_active
        ? '<span class="badge bg-success">Active</span>'
        : '<span class="badge bg-danger">Inactive</span>';
    $('#product-status').html(statusBadge);

    // Description
    $('#product-description').html(product.description || '<em class="text-muted">No description available</em>');

    // Stock
    const stockClass = getStockStatusClass(product.stock);
    $('#product-stock').html(`<span class="badge ${stockClass}">${product.stock || 0} units</span>`);

    // Barcode
    $('#product-barcode').text(product.barcode || 'N/A');

    // Expiry date
    const expiryText = product.expiry_date
        ? formatDate(product.expiry_date) + (isExpired(product.expiry_date) ? ' <span class="badge bg-danger ms-2">Expired</span>' : '')
        : 'N/A';
    $('#product-expiry').html(expiryText);

    // Created date
    $('#product-created').text(formatDateTime(product.created_at));

    // Product image
    const imageUrl = product.image || 'https://via.placeholder.com/400x300?text=No+Image';
    const $mainImage = $('#main-image');
    $mainImage.attr('src', imageUrl).attr('alt', product.name || 'Product Image');

    // Handle image load error
    $mainImage.off('error').on('error', function () {
        $(this).attr('src', 'https://via.placeholder.com/400x300?text=Image+Not+Found');
    });
}

/**
 * Render product statistics in the sidebar
 */
function renderProductStats(product) {
    // Stock count
    $('#stat-stock').text(product.stock || 0);

    // Profit margin
    const margin = calculateMargin(product.price, product.cost);
    $('#stat-margin').text(margin + '%');

    // Status badge
    const statusClass = product.is_active ? 'bg-success' : 'bg-danger';
    const statusText = product.is_active ? 'Active' : 'Inactive';
    $('#stat-status').removeClass().addClass(`badge ${statusClass}`).text(statusText);
}

/**
 * Render inventory tab content
 */
function renderInventoryTab(product) {
    // Current stock
    $('#current-stock').text(product.stock || 0);

    // Stock value
    const stockValue = (product.stock || 0) * (product.cost || 0);
    $('#stock-value').text(formatCurrencyLocal(stockValue));

    // Inventory details table
    $('#inventory-barcode').text(product.barcode || 'N/A');
    $('#inventory-cost').text(product.cost ? formatCurrencyLocal(product.cost) : 'N/A');

    const stockStatus = getStockStatusText(product.stock);
    const stockStatusClass = getStockStatusClass(product.stock);
    $('#inventory-status').html(`<span class="badge ${stockStatusClass}">${stockStatus}</span>`);

    $('#inventory-updated').text(formatDateTime(product.updated_at));
}

/**
 * Render sales tab content (placeholder since we don't have sales data in the current API)
 */
function renderSalesTab(product) {
    // For now, show placeholder data since the API doesn't include sales information
    $('#total-sold').text('0');
    $('#total-revenue').text(formatCurrencyLocal(0));
    $('#avg-sale-price').text(formatCurrencyLocal(0));

    // Show message in recent sales table
    $('#recent-sales').html(`
        <tr>
            <td colspan="6" class="text-center text-muted py-4">
                <i class="fas fa-chart-line fa-2x mb-3 d-block"></i>
                <h6>Sales Analytics Coming Soon</h6>
                <p class="mb-0 small">Sales data and analytics will be available once order tracking is integrated.</p>
            </td>
        </tr>
    `);
}

/**
 * Helper Functions
 */

function formatCurrencyLocal(amount) {
    if (!amount || isNaN(amount)) return formatCurrency(0);
    return formatCurrency(amount);
}

function formatDate(dateString) {
    if (!dateString) return 'N/A';
    return new Date(dateString).toLocaleDateString('en-US', {
        year: 'numeric',
        month: 'short',
        day: 'numeric'
    });
}

function formatDateTime(dateString) {
    if (!dateString) return 'N/A';
    return new Date(dateString).toLocaleString('en-US', {
        year: 'numeric',
        month: 'short',
        day: 'numeric',
        hour: '2-digit',
        minute: '2-digit'
    });
}

function isExpired(dateString) {
    if (!dateString) return false;
    return new Date(dateString) < new Date();
}

function calculateMargin(price, cost) {
    if (!price || !cost || price <= 0 || cost <= 0) return 0;
    return Math.round(((price - cost) / price) * 100);
}

function getStockStatusClass(stock) {
    if (!stock || stock === 0) return 'bg-danger';
    if (stock < 10) return 'bg-warning';
    return 'bg-success';
}

function getStockStatusText(stock) {
    if (!stock || stock === 0) return 'Out of Stock';
    if (stock < 10) return 'Low Stock';
    return 'In Stock';
}
