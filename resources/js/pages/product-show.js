import {
    withButtonControl,
    showSuccessMessage,
    showErrorMessage,
    showLoadingState,
    hideLoadingState,
    getIdFromUrl,
    formatCurrency,
    formatDateTime,
    showError
} from '../utils/common.js';

import {
    getProduct,
    deleteProduct,
    createProductUnit,
    updateProductUnit,
    deleteProductUnit
} from '../api/products.api.js';

let currentProductId = null;
let product = null;
let currentUnitId = null;

document.addEventListener('DOMContentLoaded', async () => {
    // Get product ID from window variable or URL as fallback
    currentProductId = window.productId || getIdFromUrl('products');

    if (currentProductId && currentProductId > 0) {
        await loadProductDetails(currentProductId);
        setupEventListeners();
        setupTabNavigation();
    } else {
        showErrorMessage('Product ID not found');
    }
});

function setupEventListeners() {
    // Edit product link
    const editLink = document.getElementById('edit-product-link');
    if (editLink) {
        editLink.addEventListener('click', (e) => {
            e.preventDefault();
            if (currentProductId) {
                window.location.href = `/products/${currentProductId}/edit`;
            }
        });
    }

    // Delete product button
    const deleteBtn = document.getElementById('delete-product-btn');
    if (deleteBtn) {
        deleteBtn.addEventListener('click', () => handleDeleteProduct(currentProductId));
    }

    // Add unit button
    const addUnitBtn = document.getElementById('add-unit-btn');
    if (addUnitBtn) {
        addUnitBtn.addEventListener('click', () => openUnitModal());
    }

    // Unit form submission
    const unitForm = document.getElementById('unitForm');
    if (unitForm) {
        unitForm.addEventListener('submit', async (e) => {
            e.preventDefault();
            await handleUnitFormSubmit();
        });
    }

    // Modal close buttons
    setupModalEvents();
}

function setupTabNavigation() {
    const tabButtons = document.querySelectorAll('.tab-button');
    const tabPanes = document.querySelectorAll('.tab-pane');

    tabButtons.forEach(button => {
        button.addEventListener('click', () => {
            const targetId = button.getAttribute('data-target');
            
            // Remove active class from all tabs and panes
            tabButtons.forEach(btn => {
                btn.classList.remove('active', 'text-blue-600', 'border-blue-500');
                btn.classList.add('text-gray-500', 'border-transparent');
            });
            
            tabPanes.forEach(pane => {
                pane.classList.add('hidden');
                pane.classList.remove('active');
            });
            
            // Add active class to clicked tab
            button.classList.add('active', 'text-blue-600', 'border-blue-500');
            button.classList.remove('text-gray-500', 'border-transparent');
            
            // Show target pane
            const targetPane = document.getElementById(targetId);
            if (targetPane) {
                targetPane.classList.remove('hidden');
                targetPane.classList.add('active');
            }
        });
    });
}

function setupModalEvents() {
    // Unit modal close events
    const closeUnitModal = document.getElementById('close-unit-modal');
    const cancelUnitBtn = document.getElementById('cancel-unit-btn');
    
    if (closeUnitModal) {
        closeUnitModal.addEventListener('click', () => closeModal('unitModal'));
    }
    
    if (cancelUnitBtn) {
        cancelUnitBtn.addEventListener('click', () => closeModal('unitModal'));
    }

    // Delete unit modal close events
    const closeDeleteModal = document.getElementById('close-delete-unit-modal');
    const cancelDeleteBtn = document.getElementById('cancel-delete-unit-btn');
    
    if (closeDeleteModal) {
        closeDeleteModal.addEventListener('click', () => closeModal('deleteUnitModal'));
    }
    
    if (cancelDeleteBtn) {
        cancelDeleteBtn.addEventListener('click', () => closeModal('deleteUnitModal'));
    }

    // Confirm delete unit
    const confirmDeleteBtn = document.getElementById('confirm-delete-unit-btn');
    if (confirmDeleteBtn) {
        confirmDeleteBtn.addEventListener('click', () => handleConfirmDeleteUnit());
    }

    // Close modals when clicking outside
    document.addEventListener('click', (e) => {
        if (e.target.classList.contains('fixed') && e.target.classList.contains('inset-0')) {
            const modals = ['unitModal', 'deleteUnitModal'];
            modals.forEach(modalId => {
                const modal = document.getElementById(modalId);
                if (modal && !modal.classList.contains('hidden')) {
                    closeModal(modalId);
                }
            });
        }
    });
}

function showModal(modalId) {
    const modal = document.getElementById(modalId);
    if (modal) {
        modal.classList.remove('hidden');
        document.body.style.overflow = 'hidden';
    }
}

function closeModal(modalId) {
    const modal = document.getElementById(modalId);
    if (modal) {
        modal.classList.add('hidden');
        document.body.style.overflow = '';
        
        // Reset form if it's the unit modal
        if (modalId === 'unitModal') {
            const form = document.getElementById('unitForm');
            if (form) {
                form.reset();
                clearFormErrors();
            }
        }
    }
}

async function loadProductDetails(productId) {
    try {
        showLoadingState();
        
        const response = await getProduct(productId);
        product = response.data;
        
        displayProductDetails(product);
        displayProductStats(product);
        displayProductUnits(product.units || []);
        displayInventoryDetails(product);
        displaySalesData(product);
        
    } catch (error) {
        console.error('Error loading product details:', error);
        showError(error.response?.data || { message: 'Failed to load product details' });
    } finally {
        hideLoadingState();
    }
}

function displayProductDetails(product) {
    // Update main product information
    updateElement('product-name', product.name);
    updateElement('product-sku', product.base_sku || 'Not set');
    updateElement('product-barcode', product.barcode || 'Not set');
    updateElement('product-base-unit', product.base_unit);
    updateElement('product-category', product.category?.name || 'Uncategorized');
    
    // Price display
    const priceElement = document.getElementById('product-price');
    if (priceElement) {
        priceElement.innerHTML = `
            <h4 class="text-2xl font-bold text-blue-600">${formatCurrency(product.price || 0)}</h4>
            <small class="text-gray-500">per ${product.base_unit}</small>
        `;
    }
    
    updateElement('product-cost', formatCurrency(product.cost || 0));
    
    // Calculate and display margin
    const margin = product.price && product.cost ? 
        (((product.price - product.cost) / product.price) * 100).toFixed(2) : 0;
    updateElement('product-margin', `${margin}%`);
    
    // Status badge
    const statusElement = document.getElementById('product-status');
    if (statusElement) {
        const statusClass = product.is_active ? 
            'bg-green-100 text-green-800' : 'bg-red-100 text-red-800';
        statusElement.className = `inline-block px-3 py-1 rounded-full text-sm font-medium ${statusClass}`;
        statusElement.textContent = product.is_active ? 'Active' : 'Inactive';
    }
    
    updateElement('product-description', product.description || 'No description available');
    updateElement('product-stock', product.stock || 0);
    updateElement('product-min-stock', product.min_stock || 0);
    
    // Stock status
    const stockStatusElement = document.getElementById('product-stock-status');
    if (stockStatusElement) {
        const stock = product.stock || 0;
        const minStock = product.min_stock || 0;
        
        let statusText, statusClass;
        if (stock === 0) {
            statusText = 'Out of Stock';
            statusClass = 'bg-red-100 text-red-800';
        } else if (stock <= minStock) {
            statusText = 'Low Stock';
            statusClass = 'bg-yellow-100 text-yellow-800';
        } else {
            statusText = 'In Stock';
            statusClass = 'bg-green-100 text-green-800';
        }
        
        stockStatusElement.className = `inline-block px-3 py-1 rounded-full text-sm font-medium ${statusClass}`;
        stockStatusElement.textContent = statusText;
    }
    
    updateElement('product-expiry', product.expiry_date ? formatDateTime(product.expiry_date) : 'No expiry date');
    updateElement('product-created', formatDateTime(product.created_at));
    updateElement('product-updated', formatDateTime(product.updated_at));
    
    // Update main image
    const mainImage = document.getElementById('main-image');
    if (mainImage && product.image) {
        mainImage.src = product.image;
    }
}

function displayProductStats(product) {
    updateElement('stat-stock', product.stock || 0);
    
    const margin = product.price && product.cost ? 
        (((product.price - product.cost) / product.price) * 100).toFixed(1) : 0;
    updateElement('stat-margin', `${margin}%`);
    
    const statusElement = document.getElementById('stat-status');
    if (statusElement) {
        const statusClass = product.is_active ? 
            'bg-green-100 text-green-800' : 'bg-red-100 text-red-800';
        statusElement.className = `inline-block px-3 py-1 rounded-full text-sm font-medium ${statusClass}`;
        statusElement.textContent = product.is_active ? 'Active' : 'Inactive';
    }
}

function displayProductUnits(units) {
    updateElement('total-units', units.length);
    
    const baseUnit = units.find(unit => unit.is_base_unit);
    updateElement('base-unit-name', baseUnit?.unit_name || 'N/A');
    
    const variations = units.filter(unit => !unit.is_base_unit).length;
    updateElement('total-unit-variations', variations);
    
    const tbody = document.getElementById('units-table-body');
    if (!tbody) return;
    
    if (units.length === 0) {
        tbody.innerHTML = `
            <tr>
                <td colspan="9" class="px-6 py-8 text-center text-gray-500">
                    No units found
                </td>
            </tr>
        `;
        return;
    }
    
    tbody.innerHTML = units.map(unit => `
        <tr class="hover:bg-gray-50">
            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">${unit.id}</td>
            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">${unit.unit_name}</td>
            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">${unit.sku || 'N/A'}</td>
            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">${unit.barcode || 'N/A'}</td>
            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">${unit.conversion_rate}</td>
            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">${formatCurrency(unit.selling_price)}</td>
            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">${calculateUnitStock(unit)}</td>
            <td class="px-6 py-4 whitespace-nowrap">
                ${unit.is_base_unit ? 
                    '<span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800"><i class="fas fa-star mr-1"></i>Base</span>' : 
                    '<span class="text-gray-400">-</span>'
                }
            </td>
            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                <div class="flex space-x-2">
                    <button onclick="editUnit(${unit.id})" 
                            class="text-blue-600 hover:text-blue-900" 
                            title="Edit Unit">
                        <i class="fas fa-edit"></i>
                    </button>
                    ${!unit.is_base_unit ? `
                        <button onclick="deleteUnit(${unit.id}, '${unit.unit_name}')" 
                                class="text-red-600 hover:text-red-900" 
                                title="Delete Unit">
                            <i class="fas fa-trash"></i>
                        </button>
                    ` : ''}
                </div>
            </td>
        </tr>
    `).join('');
}

function calculateUnitStock(unit) {
    if (!product || !product.stock) return 0;
    
    const baseStock = product.stock;
    const unitStock = Math.floor(baseStock / unit.conversion_rate);
    return unitStock;
}

function displayInventoryDetails(product) {
    updateElement('current-stock', product.stock || 0);
    
    const stockValue = (product.stock || 0) * (product.cost || 0);
    updateElement('stock-value', formatCurrency(stockValue));
    
    // Inventory table details
    updateElement('inventory-base-sku', product.base_sku || 'N/A');
    updateElement('inventory-base-barcode', product.barcode || 'N/A');
    updateElement('inventory-base-unit', product.base_unit || 'N/A');
    updateElement('inventory-cost', formatCurrency(product.cost || 0));
    
    const inventoryStatusElement = document.getElementById('inventory-status');
    if (inventoryStatusElement) {
        const stock = product.stock || 0;
        const minStock = product.min_stock || 0;
        
        let statusText, statusClass;
        if (stock === 0) {
            statusText = 'Out of Stock';
            statusClass = 'text-red-600';
        } else if (stock <= minStock) {
            statusText = 'Low Stock';
            statusClass = 'text-yellow-600';
        } else {
            statusText = 'In Stock';
            statusClass = 'text-green-600';
        }
        
        inventoryStatusElement.className = `px-6 py-4 whitespace-nowrap ${statusClass}`;
        inventoryStatusElement.textContent = statusText;
    }
    
    const margin = product.price && product.cost ? 
        (((product.price - product.cost) / product.price) * 100).toFixed(2) : 0;
    updateElement('inventory-margin', `${margin}%`);
    
    const expiredElement = document.getElementById('inventory-expired');
    if (expiredElement) {
        const isExpired = product.expiry_date && new Date(product.expiry_date) < new Date();
        expiredElement.className = `px-6 py-4 whitespace-nowrap ${isExpired ? 'text-red-600' : 'text-green-600'}`;
        expiredElement.textContent = isExpired ? 'Yes' : 'No';
    }
    
    updateElement('inventory-days-expiry', calculateDaysUntilExpiry(product.expiry_date));
    updateElement('inventory-updated', formatDateTime(product.updated_at));
}

function calculateDaysUntilExpiry(expiryDate) {
    if (!expiryDate) return 'N/A';
    
    const expiry = new Date(expiryDate);
    const today = new Date();
    const diffTime = expiry - today;
    const diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24));
    
    if (diffDays < 0) return 'Expired';
    if (diffDays === 0) return 'Expires today';
    return `${diffDays} days`;
}

function displaySalesData(product) {
    // Placeholder - these would come from actual sales data
    updateElement('total-sold', '0');
    updateElement('total-revenue', '$0.00');
    updateElement('avg-sale-price', '$0.00');
    
    const salesTable = document.getElementById('recent-sales');
    if (salesTable) {
        salesTable.innerHTML = `
            <tr>
                <td colspan="6" class="px-6 py-8 text-center text-gray-500">
                    No recent sales found
                </td>
            </tr>
        `;
    }
}

function updateElement(elementId, value) {
    const element = document.getElementById(elementId);
    if (element) {
        element.textContent = value;
    }
}

// Global functions for unit management
window.editUnit = function(unitId) {
    const unit = product?.units?.find(u => u.id === unitId);
    if (!unit) return;
    
    currentUnitId = unitId;
    
    // Fill form with unit data
    document.getElementById('unit-id').value = unit.id;
    document.getElementById('unit-name').value = unit.unit_name;
    document.getElementById('unit-sku').value = unit.sku || '';
    document.getElementById('unit-barcode').value = unit.barcode || '';
    document.getElementById('unit-conversion-rate').value = unit.conversion_rate;
    document.getElementById('unit-selling-price').value = unit.selling_price;
    document.getElementById('unit-is-base').checked = unit.is_base_unit;
    
    // Update modal title
    document.getElementById('unit-modal-title').textContent = 'Edit Unit';
    document.getElementById('save-unit-text').textContent = 'Update Unit';
    
    showModal('unitModal');
};

window.deleteUnit = function(unitId, unitName) {
    currentUnitId = unitId;
    document.getElementById('delete-unit-name').textContent = unitName;
    showModal('deleteUnitModal');
};

function openUnitModal() {
    currentUnitId = null;
    
    // Reset form
    const form = document.getElementById('unitForm');
    if (form) {
        form.reset();
        clearFormErrors();
    }
    
    // Update modal title
    document.getElementById('unit-modal-title').textContent = 'Add New Unit';
    document.getElementById('save-unit-text').textContent = 'Save Unit';
    
    showModal('unitModal');
}

async function handleUnitFormSubmit() {
    clearFormErrors();
    
    const formData = new FormData(document.getElementById('unitForm'));
    const unitData = {
        unit_name: formData.get('unit_name'),
        sku: formData.get('sku'),
        barcode: formData.get('barcode'),
        conversion_rate: parseFloat(formData.get('conversion_rate')),
        selling_price: parseFloat(formData.get('selling_price')),
        is_base_unit: formData.get('is_base_unit') === 'on'
    };
    
    const submitHandler = withButtonControl(async () => {
        try {
            if (currentUnitId) {
                await updateProductUnit(currentUnitId, unitData);
                showSuccessMessage('Unit updated successfully');
            } else {
                await createProductUnit(currentProductId, unitData);
                showSuccessMessage('Unit created successfully');
            }
            
            closeModal('unitModal');
            await loadProductDetails(currentProductId);
            
        } catch (error) {
            console.error('Error saving unit:', error);
            handleFormErrors(error.response?.data);
        }
    }, document.getElementById('save-unit-btn'));
    
    await submitHandler();
}

async function handleConfirmDeleteUnit() {
    if (!currentUnitId) return;
    
    const deleteHandler = withButtonControl(async () => {
        try {
            await deleteProductUnit(currentUnitId);
            showSuccessMessage('Unit deleted successfully');
            
            closeModal('deleteUnitModal');
            await loadProductDetails(currentProductId);
            
        } catch (error) {
            console.error('Error deleting unit:', error);
            showError(error.response?.data || { message: 'Failed to delete unit' });
        }
    }, document.getElementById('confirm-delete-unit-btn'));
    
    await deleteHandler();
}

async function handleDeleteProduct(productId) {
    if (!confirm('Are you sure you want to delete this product? This action cannot be undone.')) {
        return;
    }
    
    const deleteHandler = withButtonControl(async () => {
        try {
            await deleteProduct(productId);
            showSuccessMessage('Product deleted successfully');
            window.location.href = '/products';
            
        } catch (error) {
            console.error('Error deleting product:', error);
            showError(error.response?.data || { message: 'Failed to delete product' });
        }
    }, document.getElementById('delete-product-btn'));
    
    await deleteHandler();
}

function handleFormErrors(errorData) {
    if (errorData?.errors) {
        Object.entries(errorData.errors).forEach(([field, messages]) => {
            const errorElement = document.getElementById(`${field.replace('_', '-')}-error`);
            if (errorElement) {
                errorElement.textContent = messages[0];
                errorElement.classList.remove('hidden');
                
                const inputElement = document.getElementById(field.replace('_', '-'));
                if (inputElement) {
                    inputElement.classList.add('border-red-500');
                }
            }
        });
    } else {
        showError(errorData || { message: 'Failed to save unit' });
    }
}

function clearFormErrors() {
    const errorElements = document.querySelectorAll('[id$="-error"]');
    errorElements.forEach(element => {
        element.textContent = '';
        element.classList.add('hidden');
    });
    
    const inputElements = document.querySelectorAll('input.border-red-500');
    inputElements.forEach(element => {
        element.classList.remove('border-red-500');
    });
}
