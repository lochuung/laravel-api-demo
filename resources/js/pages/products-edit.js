import {
    showSuccessMessage,
    showErrorMessage,
    showLoadingState,
    hideLoadingState,
    formatCurrency,
    formatDateTime,
    withButtonControl, formatPriceInput
} from '../utils/common.js';
import { getProduct, updateProduct, deleteProduct, getProductFilterOptions } from '../api/products.api.js';
import { uploadImage } from '../api/upload.api.js';

let currentProductId = null;
let originalData = null;
let categories = {};
let hasUnsavedChanges = false;

document.addEventListener('DOMContentLoaded', async () => {
    // Get product ID from window variable or URL
    currentProductId = window.productId || getIdFromUrl('products');

    if (currentProductId && currentProductId > 0) {
        await setupEditProduct();
        setupEventListeners();
    } else {
        showErrorMessage('Product ID not found');
    }
});

function getIdFromUrl(segment) {
    const pathSegments = window.location.pathname.split('/');
    const segmentIndex = pathSegments.indexOf(segment);
    return segmentIndex !== -1 && segmentIndex + 1 < pathSegments.length
        ? parseInt(pathSegments[segmentIndex + 1])
        : null;
}

async function setupEditProduct() {
    try {
        showLoadingState();

        // Load categories and product data in parallel
        const [categoriesResponse, productResponse] = await Promise.all([
            getProductFilterOptions(),
            getProduct(currentProductId)
        ]);

        debugger;

        // Process categories
        if (categoriesResponse.data?.data?.categories) {
            categories = categoriesResponse.data.data.categories;
            populateCategoryDropdown();
        }

        // Process product data
        const product = productResponse.data.data;
        if (!product) {
            showErrorMessage('Product not found.');
            return;
        }

        originalData = product;
        fillDataToForm(product);
        setupStaticElements(product);

    } catch (error) {
        console.error('Error setting up edit product:', error);
        const msg = error.response?.data?.message || 'Failed to load product data';
        showErrorMessage(msg);

        if (error.response?.status === 404) {
            setTimeout(() => window.location.href = '/products', 3000);
        }
    } finally {
        hideLoadingState();
    }
}

function populateCategoryDropdown() {
    const categorySelect = document.getElementById('category_id');
    categorySelect.innerHTML = '<option value="">Select Category</option>';

    Object.entries(categories).forEach(([id, name]) => {
        const option = document.createElement('option');
        option.value = id;
        option.textContent = name;
        categorySelect.appendChild(option);
    });
}

function fillDataToForm(product) {
    // Basic information
    document.getElementById('name').value = product.name || '';
    document.getElementById('description').value = product.description || '';
    document.getElementById('category_id').value = product.category_id || '';

    // Show current product code (read-only)
    document.getElementById('current_code').value = product.code || 'Auto-generated';

    // Dates
    if (product.expiry_date) {
        document.getElementById('expiry_date').value = product.expiry_date;
    }

    // Pricing
    document.getElementById('price').value = product.price ? formatCurrency(product.price) : '';
    document.getElementById('cost_price').value = product.cost || '';

    // Stock
    document.getElementById('stock').value = product.stock || '';
    document.getElementById('min_stock').value = product.min_stock || '';

    // Status checkboxes
    document.getElementById('is_active').checked = product.is_active || false;

    // Fill base unit display information
    fillBaseUnitDisplay(product);

    // Handle product image
    if (product.image) {
        displayCurrentImage(product.image);
    } else {
        displayNoImage();
    }
}

function fillBaseUnitDisplay(product) {
    // Base Unit Information (read-only display)
    document.getElementById('display-base-unit').textContent = product.base_unit || 'N/A';
    document.getElementById('display-base-sku').textContent = product.base_sku || 'N/A';
    document.getElementById('display-base-barcode').textContent = product.base_barcode || 'N/A';

    // Margin percentage with color coding
    const margin = product.margin_percentage || 0;
    const marginClass = margin > 30 ? 'text-green-600' : margin > 15 ? 'text-yellow-600' : 'text-red-600';
    document.getElementById('display-margin-percentage').innerHTML =
        `<span class="${marginClass} font-bold">${margin.toFixed(2)}%</span>`;

    // Stock status with badge
    const stockStatus = product.stock_status || 'unknown';
    const stockStatusClass = getStockStatusBadgeClass(stockStatus);
    document.getElementById('display-stock-status').innerHTML =
        `<span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium ${stockStatusClass}">
            ${stockStatus.replace('_', ' ').toUpperCase()}
        </span>`;

    // Expiry status
    const isExpired = product.is_expired || false;
    const daysUntilExpiry = product.days_until_expiry || 0;
    let expiryText = 'No expiry date';
    let expiryClass = 'text-gray-500';

    if (product.expiry_date) {
        if (isExpired) {
            expiryText = 'Expired';
            expiryClass = 'text-red-600';
        } else if (daysUntilExpiry < 30) {
            expiryText = `Expires in ${daysUntilExpiry} days`;
            expiryClass = 'text-red-600';
        } else if (daysUntilExpiry < 90) {
            expiryText = `Expires in ${daysUntilExpiry} days`;
            expiryClass = 'text-yellow-600';
        } else {
            expiryText = `${daysUntilExpiry} days remaining`;
            expiryClass = 'text-green-600';
        }
    }

    document.getElementById('display-expiry-status').innerHTML =
        `<span class="${expiryClass} font-bold">${expiryText}</span>`;
}

function displayCurrentImage(imageUrl) {
    const imageContainer = document.getElementById('current-images-container');
    imageContainer.innerHTML = `
        <div class="relative">
            <img src="${imageUrl}" class="w-full h-48 object-cover rounded-lg" alt="Product Image">
            <button type="button"
                    class="absolute top-2 right-2 p-1 bg-red-600 text-white rounded-full hover:bg-red-700 transition-colors"
                    onclick="removeCurrentImage()" title="Remove Image">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>
        <p class="text-gray-500 mt-2 text-sm">Current product image</p>
    `;
}

function displayNoImage() {
    const imageContainer = document.getElementById('current-images-container');
    imageContainer.innerHTML = `
        <div class="text-center py-8 border-2 border-dashed border-gray-300 rounded-lg bg-gray-50">
            <svg class="w-16 h-16 mx-auto text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 002 2v12a2 2 0 002 2z"/>
            </svg>
            <p class="text-gray-500">No image available</p>
        </div>
    `;
}

function setupStaticElements(product) {
    // Update navigation links
    document.getElementById('view-product-link').href = `/products/${currentProductId}`;
    document.getElementById('cancel-edit-link').href = `/products/${currentProductId}`;
    document.getElementById('manage-units-link').href = `/products/${currentProductId}#units`;

    // Update statistics (placeholder for now)
    document.getElementById('total-sales').textContent = '0';
    document.getElementById('page-views').textContent = '-';

    // Update timestamps
    const timestampElements = document.querySelectorAll('.text-sm.text-gray-500');
    if (timestampElements[0]) {
        timestampElements[0].textContent = `Created: ${formatDateTime(product.created_at)}`;
    }
    if (timestampElements[1]) {
        timestampElements[1].textContent = `Last Updated: ${formatDateTime(product.updated_at)}`;
    }
}

function setupEventListeners() {
    // Form submission
    const form = document.getElementById('editProductForm');
    const submitBtn = document.getElementById('submit-btn');

    const handleSubmit = withButtonControl(async () => {
        await handleUpdateProduct();
    }, [submitBtn]);

    form.addEventListener('submit', async (e) => {
        e.preventDefault();
        await handleSubmit();
    });

    // Delete button
    const deleteBtn = document.querySelector('#deleteModal .bg-red-600');
    if (deleteBtn) {
        deleteBtn.addEventListener('click', handleDeleteProduct);
    }

    // Delete modal controls
    const deleteModalBtn = document.getElementById('delete-product-btn');
    const deleteModal = document.getElementById('deleteModal');
    const cancelDeleteBtn = document.getElementById('cancel-delete');
    const closeDeleteBtn = document.getElementById('close-delete-modal');

    if (deleteModalBtn) {
        deleteModalBtn.addEventListener('click', () => {
            deleteModal.classList.remove('hidden');
        });
    }

    if (cancelDeleteBtn) {
        cancelDeleteBtn.addEventListener('click', () => {
            deleteModal.classList.add('hidden');
        });
    }

    if (closeDeleteBtn) {
        closeDeleteBtn.addEventListener('click', () => {
            deleteModal.classList.add('hidden');
        });
    }

    // Image upload preview
    const imageInput = document.getElementById('new_images');
    if (imageInput) {
        imageInput.addEventListener('change', handleImagePreview);
    }

    // Form change detection for unsaved changes warning
    const formInputs = form.querySelectorAll('input, textarea, select');
    formInputs.forEach(input => {
        input.addEventListener('change', () => {
            input.classList.add('changed');
            hasUnsavedChanges = true;
        });
    });

    // Price input formatting
    const priceInput = document.getElementById('price');
    const costPriceInput = document.getElementById('cost_price');

    if (priceInput) {
        priceInput.addEventListener('input', formatPriceInput);
    }
    if (costPriceInput) {
        costPriceInput.addEventListener('input', formatPriceInput);
    }
}

async function handleUpdateProduct() {
    try {
        // Collect form data
        const formData = await collectFormData();

        // Validate required fields
        if (!validateFormData(formData)) {
            return;
        }

        // Update product via API
        const response = await updateProduct(currentProductId, formData);

        showSuccessMessage('Product updated successfully!');

        // Update originalData to prevent unsaved changes warning
        originalData = response.data.data;
        hasUnsavedChanges = false;

        // Remove changed classes
        document.querySelectorAll('.changed').forEach(el => {
            el.classList.remove('changed');
        });

        // Optionally redirect to product view
        setTimeout(() => {
            window.location.href = `/products/${currentProductId}`;
        }, 1500);

    } catch (error) {
        console.error('Error updating product:', error);

        if (error.response?.data?.errors) {
            displayValidationErrors(error.response.data.errors);
        } else {
            const msg = error.response?.data?.message || 'Failed to update product';
            showErrorMessage(msg);
        }
    }
}

async function collectFormData() {
    const formData = {
        name: document.getElementById('name').value.trim(),
        description: document.getElementById('description').value.trim(),
        category_id: parseInt(document.getElementById('category_id').value) || null,
        expiry_date: document.getElementById('expiry_date').value || null,
        cost: parseFloat(document.getElementById('cost_price').value.replace(/,/g, '')) || 0,
        stock: parseInt(document.getElementById('stock').value) || 0,
        min_stock: parseInt(document.getElementById('min_stock').value) || 0,
        is_active: document.getElementById('is_active').checked
    };

    // Handle new image upload
    const imageInput = document.getElementById('new_images');
    const imageFile = imageInput?.files?.[0];

    if (imageFile) {
        try {
            const uploadedImageUrl = await uploadImage(imageFile);
            if (uploadedImageUrl) {
                formData.image = uploadedImageUrl;
            }
        } catch (err) {
            console.error('Image upload failed:', err);
            showErrorMessage('Image upload failed. Please try again.');
        }
    }

    return formData;
}

function validateFormData(data) {
    let isValid = true;

    // Clear previous validation messages
    document.querySelectorAll('.field-error').forEach(error => error.remove());
    document.querySelectorAll('.border-red-500').forEach(field => {
        field.classList.remove('border-red-500');
    });

    // Validate required fields
    if (!data.name) {
        showFieldError('name', 'Product name is required');
        isValid = false;
    }

    if (!data.category_id) {
        showFieldError('category', 'Category is required');
        isValid = false;
    }

    if (!data.cost || data.cost <= 0) {
        showFieldError('cost_price', 'Cost price must be greater than 0');
        isValid = false;
    }

    if (data.stock < 0) {
        showFieldError('stock', 'Stock cannot be negative');
        isValid = false;
    }

    if (data.min_stock < 0) {
        showFieldError('min_stock', 'Minimum stock cannot be negative');
        isValid = false;
    }

    if (data.min_stock > data.stock) {
        showFieldError('min_stock', 'Minimum stock cannot be greater than current stock');
        isValid = false;
    }

    if (!isValid) {
        showErrorMessage('Please fix the validation errors');
    }

    return isValid;
}

function showFieldError(fieldName, message) {
    const field = document.getElementById(fieldName);
    field.classList.add('border-red-500');

    const errorDiv = document.createElement('div');
    errorDiv.className = 'field-error text-red-500 text-sm mt-1';
    errorDiv.textContent = message;

    field.parentNode.appendChild(errorDiv);
}

function displayValidationErrors(errors) {
    Object.keys(errors).forEach(field => {
        const messages = errors[field];
        if (messages && messages.length > 0) {
            showFieldError(field, messages[0]);
        }
    });
}

async function handleDeleteProduct() {
    try {
        await deleteProduct(currentProductId);
        showSuccessMessage('Product deleted successfully');
        setTimeout(() => window.location.href = '/products', 2000);
    } catch (error) {
        console.error('Failed to delete product:', error);
        const msg = error.response?.data?.message || 'Failed to delete product';
        showErrorMessage(msg);
    }
}

function handleImagePreview(e) {
    const files = e.target.files;
    const previewContainer = document.getElementById('new-image-preview');
    previewContainer.innerHTML = '';

    Array.from(files).forEach((file, index) => {
        if (file.type.startsWith('image/')) {
            const reader = new FileReader();
            reader.onload = function (e) {
                const previewDiv = document.createElement('div');
                previewDiv.className = 'relative';
                previewDiv.innerHTML = `
                    <img src="${e.target.result}" class="w-full h-32 object-cover rounded-lg">
                    <button type="button"
                            class="absolute top-1 right-1 p-1 bg-red-600 text-white rounded-full hover:bg-red-700 transition-colors"
                            onclick="removeImagePreview(this)" title="Remove">
                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                    <small class="block text-gray-500 mt-1">New image ${index + 1}</small>
                `;
                previewContainer.appendChild(previewDiv);
            };
            reader.readAsDataURL(file);
        }
    });
}

// Global functions for template usage
window.removeCurrentImage = function () {
    if (confirm('Are you sure you want to remove the current image?')) {
        displayNoImage();
        hasUnsavedChanges = true;
    }
};

window.removeImagePreview = function (button) {
    button.closest('.relative').remove();
};

// Warn user about unsaved changes
window.addEventListener('beforeunload', function (e) {
    if (hasUnsavedChanges) {
        e.preventDefault();
        e.returnValue = 'You have unsaved changes. Are you sure you want to leave?';
        return e.returnValue;
    }
});

// Helper function for stock status badge classes
function getStockStatusBadgeClass(status) {
    switch (status) {
        case 'in_stock':
            return 'bg-green-100 text-green-800';
        case 'low_stock':
            return 'bg-yellow-100 text-yellow-800';
        case 'out_of_stock':
            return 'bg-red-100 text-red-800';
        default:
            return 'bg-gray-100 text-gray-800';
    }
}
