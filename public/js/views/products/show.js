import {
    createProductUnit,
    deleteProduct,
    deleteProductUnit,
    getProduct,
    updateProductUnit
} from '../../api/products.api.js';

let currentProductId = null;
let product = null;
let currentUnitId = null;

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

    // Add unit button
    $('#add-unit-btn').on('click', function () {
        openUnitModal();
    });

    // Unit form submission
    $('#unitForm').on('submit', async function (e) {
        e.preventDefault();
        await handleUnitFormSubmit();
    });

    // Unit actions
    $(document).on('click', '.edit-unit-btn', function () {
        const unitId = $(this).data('unit-id');
        const unit = product.units.find(u => u.id === unitId);
        if (unit) {
            openUnitModal(unit);
        }
    });

    $(document).on('click', '.delete-unit-btn', function () {
        const unitId = $(this).data('unit-id');
        const unit = product.units.find(u => u.id === unitId);
        if (unit) {
            openDeleteUnitModal(unit);
        }
    });

    // Delete unit confirmation
    $('#confirm-delete-unit-btn').on('click', async function () {
        if (currentUnitId) {
            await handleDeleteUnit(currentUnitId);
        }
    });
}

async function loadProductDetails(productId) {
    try {
        showLoadingState();

        const response = await getProduct(productId);
        product = response.data.data;

        renderProductDetails(product);
        renderProductStats(product);
        renderUnitsTab(product);
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
        hideLoadingState();
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

    // SKU
    $('#product-sku').text(product.base_sku || 'N/A');

    // Barcode
    $('#product-barcode').text(product.base_barcode || 'N/A');

    // Base Unit
    $('#product-base-unit').text(product.base_unit || 'N/A');

    // Category
    const categoryName = product.category?.name || 'Uncategorized';
    $('#product-category').text(categoryName);

    // Price
    const formattedPrice = formatCurrency(product.price || 0);
    $('#product-price').html(`<h4 class="text-primary mb-0">${formattedPrice}</h4>`);

    // Cost
    const formattedCost = formatCurrency(product.cost || 0);
    $('#product-cost').text(formattedCost);

    // Margin
    const margin = product.margin_percentage || 0;
    const marginClass = margin > 30 ? 'text-success' : margin > 15 ? 'text-warning' : 'text-danger';
    $('#product-margin').html(`<span class="${marginClass} fw-bold">${margin.toFixed(2)}%</span>`);

    // Status
    const statusBadge = product.is_active
        ? '<span class="badge bg-success">Active</span>'
        : '<span class="badge bg-danger">Inactive</span>';
    $('#product-status').html(statusBadge);

    // Description
    $('#product-description').html(product.description || '<em class="text-muted">No description available</em>');

    // Stock
    const stockClass = getStockStatusClass(product.stock, product.min_stock);
    $('#product-stock').html(`<span class="badge ${stockClass}">${product.stock || 0} units</span>`);

    // Min Stock
    $('#product-min-stock').text(product.min_stock || 0);

    // Stock Status
    const stockStatus = product.stock_status || 'unknown';
    const stockStatusClass = getStockStatusBadgeClass(stockStatus);
    $('#product-stock-status').html(`<span class="badge ${stockStatusClass}">${stockStatus.replace('_', ' ').toUpperCase()}</span>`);

    // Expiry date
    const expiryText = product.expiry_date
        ? formatDate(product.expiry_date) + (product.is_expired ? ' <span class="badge bg-danger ms-2">Expired</span>' : '')
        : 'No expiry date';
    $('#product-expiry').html(expiryText);

    // Created date
    $('#product-created').text(formatDateTime(product.created_at));

    // Updated date
    $('#product-updated').text(formatDateTime(product.updated_at));

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
    const margin = product.margin_percentage || 0;
    $('#stat-margin').text(margin.toFixed(1) + '%');

    // Status badge
    const statusClass = product.is_active ? 'bg-success' : 'bg-danger';
    const statusText = product.is_active ? 'Active' : 'Inactive';
    $('#stat-status').removeClass().addClass(`badge ${statusClass}`).text(statusText);
}

/**
 * Render units tab content
 */
function renderUnitsTab(product) {
    const units = product.units || [];

    // Update unit stats
    $('#total-units').text(units.length);
    const baseUnit = units.find(u => u.is_base_unit);
    $('#base-unit-name').text(baseUnit ? baseUnit.unit_name : 'N/A');
    $('#total-unit-variations').text(units.filter(u => !u.is_base_unit).length);

    // Render units table
    const $tbody = $('#units-table-body');
    $tbody.empty();

    if (units.length === 0) {
        $tbody.append(`
            <tr>
                <td colspan="9" class="text-center text-muted py-4">
                    <i class="fas fa-layer-group fa-2x mb-3 d-block"></i>
                    <h6>No Units Found</h6>
                    <p class="mb-0 small">Click "New Unit" to add the first unit for this product.</p>
                </td>
            </tr>
        `);
        return;
    }

    units.forEach(unit => {
        const baseUnitBadge = unit.is_base_unit
            ? '<span class="badge bg-warning"><i class="fas fa-star"></i> Base</span>'
            : '<span class="badge bg-light text-dark">Regular</span>';

        const stockInUnit = unit.stock_in_unit || 0;
        const stockClass = stockInUnit > 0 ? 'text-success' : 'text-danger';

        $tbody.append(`
            <tr>
                <td>${unit.id}</td>
                <td><strong>${unit.unit_name}</strong></td>
                <td><code>${unit.sku || 'N/A'}</code></td>
                <td><small>${unit.barcode || 'N/A'}</small></td>
                <td>
                    <span class="badge bg-info">
                        1 ${baseUnit?.unit_name || 'base'} = ${unit.conversion_rate} ${unit.unit_name}
                    </span>
                </td>
                <td><strong class="text-primary">${formatCurrency(unit.selling_price)}</strong></td>
                <td><span class="${stockClass} fw-bold stock-indicator ${stockClass === 'text-success' ? 'in-stock' : stockClass === 'text-warning' ? 'low-stock' : 'out-of-stock'}">${stockInUnit}</span></td>
                <td>${baseUnitBadge}</td>
                <td>
                    <div class="unit-actions">
                        <button class="btn btn-outline-primary edit-unit-btn" data-unit-id="${unit.id}" title="Edit Unit">
                            <i class="fas fa-edit"></i>
                        </button>
                        ${!unit.is_base_unit ? `
                            <button class="btn btn-outline-danger delete-unit-btn" data-unit-id="${unit.id}" title="Delete Unit">
                                <i class="fas fa-trash"></i>
                            </button>
                        ` : `
                            <button class="btn btn-outline-secondary" disabled title="Cannot delete base unit">
                                <i class="fas fa-lock"></i>
                            </button>
                        `}
                    </div>
                </td>
            </tr>
        `);
    });
}

/**
 * Render inventory tab content
 */
function renderInventoryTab(product) {
    // Current stock
    $('#current-stock').text(product.stock || 0);

    // Stock value
    const stockValue = (product.stock || 0) * (product.cost || 0);
    $('#stock-value').text(formatCurrency(stockValue));

    // Inventory details table
    $('#inventory-base-sku').text(product.base_sku || 'N/A');
    $('#inventory-base-barcode').text(product.base_barcode || 'N/A');
    $('#inventory-base-unit').text(product.base_unit || 'N/A');
    $('#inventory-cost').text(formatCurrency(product.cost || 0));

    const stockStatus = product.stock_status || 'unknown';
    const stockStatusClass = getStockStatusBadgeClass(stockStatus);
    $('#inventory-status').html(`<span class="badge ${stockStatusClass}">${stockStatus.replace('_', ' ').toUpperCase()}</span>`);

    // Margin percentage
    const margin = product.margin_percentage || 0;
    const marginClass = margin > 30 ? 'text-success' : margin > 15 ? 'text-warning' : 'text-danger';
    $('#inventory-margin').html(`<span class="${marginClass} fw-bold">${margin.toFixed(2)}%</span>`);

    // Expiry info
    const expiredBadge = product.is_expired
        ? '<span class="badge bg-danger">Yes</span>'
        : '<span class="badge bg-success">No</span>';
    $('#inventory-expired').html(expiredBadge);

    // Days until expiry
    const daysUntilExpiry = product.days_until_expiry || 0;
    const expiryClass = daysUntilExpiry < 30 ? 'text-danger' : daysUntilExpiry < 90 ? 'text-warning' : 'text-success';
    $('#inventory-days-expiry').html(`<span class="${expiryClass} fw-bold">${daysUntilExpiry} days</span>`);

    $('#inventory-updated').text(formatDateTime(product.updated_at));
}

/**
 * Render sales tab content (placeholder since we don't have sales data in the current API)
 */
function renderSalesTab(product) {
    // For now, show placeholder data since the API doesn't include sales information
    $('#total-sold').text('0');
    $('#total-revenue').text(formatCurrency(0));
    $('#avg-sale-price').text(formatCurrency(0));

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
 * Unit Management Functions
 */
function openUnitModal(unit = null) {
    const isEdit = !!unit;
    currentUnitId = isEdit ? unit.id : null;

    // Update modal title
    $('#unit-modal-title').text(isEdit ? 'Edit Unit' : 'Add New Unit');
    $('#save-unit-text').text(isEdit ? 'Update Unit' : 'Save Unit');

    // Reset form
    $('#unitForm')[0].reset();
    $('#unitForm .is-invalid').removeClass('is-invalid');

    if (isEdit) {
        // Populate form with unit data
        $('#unit-id').val(unit.id);
        $('#unit-name').val(unit.unit_name);
        $('#unit-sku').val(unit.sku);
        $('#unit-barcode').val(unit.barcode);
        $('#unit-conversion-rate').val(unit.conversion_rate);
        $('#unit-selling-price').val(unit.selling_price);
        $('#unit-is-base').prop('checked', unit.is_base_unit);
    } else {
        // Set default values for new unit
        $('#unit-conversion-rate').val('1.0');
        $('#unit-selling-price').val(product.price || '0.00');
    }

    // Show modal
    const modal = new bootstrap.Modal(document.getElementById('unitModal'));
    modal.show();
}

function openDeleteUnitModal(unit) {
    currentUnitId = unit.id;
    $('#delete-unit-name').text(unit.unit_name);

    const modal = new bootstrap.Modal(document.getElementById('deleteUnitModal'));
    modal.show();
}

async function handleUnitFormSubmit() {
    const formData = new FormData($('#unitForm')[0]);
    const isEdit = !!currentUnitId;

    // Convert FormData to object
    const data = {};
    for (let [key, value] of formData.entries()) {
        if (key === 'is_base_unit') {
            data[key] = value === 'on';
        } else if (['conversion_rate', 'selling_price'].includes(key)) {
            data[key] = parseFloat(value) || 0;
        } else {
            data[key] = value;
        }
    }

    try {
        // Disable submit button
        $('#save-unit-btn').prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> Saving...');

        let response;
        if (isEdit) {
            response = await updateProductUnit(currentUnitId, data);
            showSuccessMessage('Unit updated successfully');
        } else {
            response = await createProductUnit(currentProductId, data);
            showSuccessMessage('Unit created successfully');
        }

        // Close modal
        const modal = bootstrap.Modal.getInstance(document.getElementById('unitModal'));
        modal.hide();

        // Reload product details to refresh units
        await loadProductDetails(currentProductId);

    } catch (error) {
        console.error('Failed to save unit:', error);

        if (error.response?.data?.errors) {
            // Handle validation errors
            const errors = error.response.data.errors;
            Object.keys(errors).forEach(field => {
                const input = $(`#unit-${field.replace('_', '-')}`);
                input.addClass('is-invalid');
                input.next('.invalid-feedback').text(errors[field][0]);
            });
        } else {
            showErrorMessage(error.response?.data?.message || 'Failed to save unit');
        }
    } finally {
        $('#save-unit-btn').prop('disabled', false).html(`<i class="fas fa-save"></i> ${currentUnitId ? 'Update Unit' : 'Save Unit'}`);
    }
}

async function handleDeleteUnit(unitId) {
    try {
        await deleteProductUnit(unitId);

        showSuccessMessage('Unit deleted successfully');

        // Close modal
        const modal = bootstrap.Modal.getInstance(document.getElementById('deleteUnitModal'));
        modal.hide();

        // Reload product details to refresh units
        await loadProductDetails(currentProductId);

    } catch (error) {
        console.error('Failed to delete unit:', error);
        showErrorMessage(error.response?.data?.message || 'Failed to delete unit');
    }
}

/**
 * Helper Functions
 */
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

function getStockStatusClass(stock, minStock = 10) {
    if (!stock || stock === 0) return 'bg-danger';
    if (stock <= minStock) return 'bg-warning';
    return 'bg-success';
}

function getStockStatusBadgeClass(status) {
    switch (status) {
        case 'in_stock':
            return 'bg-success';
        case 'low_stock':
            return 'bg-warning';
        case 'out_of_stock':
            return 'bg-danger';
        default:
            return 'bg-secondary';
    }
}
