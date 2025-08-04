import {getProduct, updateProduct, deleteProduct, getProductFilterOptions} from '../../api/products.api.js';
import {uploadImage} from "../../api/upload.api.js";

let currentProductId = null;
let originalData = null;
let categories = {};

$(document).ready(async function () {
    // Get product ID from window variable or URL as fallback
    currentProductId = window.productId || getIdFromUrl('products');

    if (currentProductId && currentProductId > 0) {
        await setupEditProduct();
        setupEventListeners();
    } else {
        showErrorMessage('Product ID not found');
    }
});

async function setupEditProduct() {
    try {
        $('#loading-overlay').removeClass('d-none');

        // Load categories and product data in parallel
        const [categoriesResponse, productResponse] = await Promise.all([
            getProductFilterOptions(),
            getProduct(currentProductId)
        ]);

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
        $('#loading-overlay').addClass('d-none');
    }
}

function populateCategoryDropdown() {
    const categorySelect = $('#category');
    categorySelect.empty().append('<option value="">Select Category</option>');

    Object.entries(categories).forEach(([id, name]) => {
        categorySelect.append(`<option value="${id}">${name}</option>`);
    });
}

function fillDataToForm(product) {
    // Basic information
    $('#name').val(product.name || '');
    $('#description').val(product.description || '');
    $('#category').val(product.category_id || '');
    $('#barcode').val(product.barcode || '');

    // Extract code prefix from product code (e.g., "PRD001" -> "PRD")
    const codePrefix = extractCodePrefix(product.code);
    $('#code_prefix').val(codePrefix || '');
    $('#current_code').val(product.code || '');

    // Dates
    if (product.expiry_date) {
        $('#expiry_date').val(product.expiry_date);
    }

    // Pricing
    $('#price').val(product.price || '');
    $('#cost_price').val(product.cost || '');

    // Stock
    $('#stock').val(product.stock || '');

    // Status checkboxes
    $('#is_active').prop('checked', product.is_active || false);
    $('#is_featured').prop('checked', product.is_featured || false);

    // Handle product image
    if (product.image) {
        displayCurrentImage(product.image);
    } else {
        displayNoImage();
    }
}

function displayCurrentImage(imageUrl) {
    const imageContainer = $('#current-images-container');
    imageContainer.html(`
        <div class="col-md-6">
            <div class="position-relative">
                <img src="${imageUrl}" class="img-fluid rounded" alt="Product Image" style="max-height: 200px;">
                <button type="button" class="btn btn-sm btn-danger position-absolute top-0 end-0 m-1"
                        onclick="removeCurrentImage()" title="Remove Image">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <p class="text-muted mt-2 small">Current product image</p>
        </div>
    `);
}

function displayNoImage() {
    const imageContainer = $('#current-images-container');
    imageContainer.html(`
        <div class="col-12">
            <div class="text-center py-4 border rounded bg-light">
                <i class="fas fa-image fa-3x text-muted mb-2"></i>
                <p class="text-muted">No image available</p>
            </div>
        </div>
    `);
}

function setupStaticElements(product) {
    // Update navigation links
    $('#view-product-link').attr('href', `/products/${currentProductId}`);
    $('#cancel-edit-link').attr('href', `/products/${currentProductId}`);

    // Update statistics (placeholder for now)
    $('#total-sales').text('0');
    $('#page-views').text('-');

    // Update timestamps
    $('.card-body .row .col-12 small').first().text(`Created: ${formatDateTime(product.created_at)}`);
    $('.card-body .row .col-12 small').last().text(`Last Updated: ${formatDateTime(product.updated_at)}`);
}

function setupEventListeners() {
    // Form submission
    $('#editProductForm').on('submit', handleUpdateProduct);

    // Delete button
    $('#deleteModal .btn-danger').on('click', handleDeleteProduct);

    // Image upload preview
    $('#new_images').on('change', handleImagePreview);

    // Form change detection for unsaved changes warning
    $('#editProductForm input, #editProductForm textarea, #editProductForm select').on('change', function () {
        $(this).addClass('changed');
    });
}

async function handleUpdateProduct(e) {
    e.preventDefault();

    try {
        const $submitBtn = $('button[type="submit"]');
        const originalText = $submitBtn.html();

        // Disable submit button and show loading
        $submitBtn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> Updating...');

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
        $('.changed').removeClass('changed');

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
    } finally {
        // Re-enable submit button
        const $submitBtn = $('button[type="submit"]');
        $submitBtn.prop('disabled', false).html('<i class="fas fa-save"></i> Update Product');
    }
}

async function collectFormData() {
    const formData = {
        name: $('#name').val().trim(),
        description: $('#description').val().trim(),
        category_id: parseInt($('#category').val()) || null,
        barcode: $('#barcode').val().trim(),
        expiry_date: $('#expiry_date').val() || null,
        price: parseFloat($('#price').val()) || 0,
        cost: parseFloat($('#cost_price').val()) || 0,
        stock: parseInt($('#stock').val()) || 0,
        code_prefix: $('#code_prefix').val().trim(),
        is_active: $('#is_active').is(':checked'),
        is_featured: $('#is_featured').is(':checked')
    };
    // Handle new image upload
    const imageInput = $('#new_images')[0];
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
    $('.invalid-feedback').text('');
    $('.form-control, .form-select').removeClass('is-invalid');

    // Validate required fields
    if (!data.name) {
        showFieldError('name', 'Product name is required');
        isValid = false;
    }

    if (!data.category_id) {
        showFieldError('category', 'Category is required');
        isValid = false;
    }

    if (!data.price || data.price <= 0) {
        showFieldError('price', 'Price must be greater than 0');
        isValid = false;
    }

    if (!data.stock && data.stock !== 0) {
        showFieldError('stock', 'Stock quantity is required');
        isValid = false;
    }

    if (data.stock < 0) {
        showFieldError('stock', 'Stock cannot be negative');
        isValid = false;
    }

    return isValid;
}

function showFieldError(fieldName, message) {
    const field = $(`#${fieldName}`);
    field.addClass('is-invalid');
    field.siblings('.invalid-feedback').text(message);
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
    const previewContainer = $('#new-image-preview');
    previewContainer.empty();

    Array.from(files).forEach((file, index) => {
        if (file.type.startsWith('image/')) {
            const reader = new FileReader();
            reader.onload = function (e) {
                previewContainer.append(`
                    <div class="col-md-3 mb-3">
                        <div class="position-relative">
                            <img src="${e.target.result}" class="img-fluid rounded"
                                 style="max-height: 150px; width: 100%; object-fit: cover;">
                            <button type="button" class="btn btn-sm btn-danger position-absolute top-0 end-0 m-1"
                                    onclick="removeImagePreview(this)" title="Remove">
                                <i class="fas fa-times"></i>
                            </button>
                        </div>
                        <small class="text-muted">New image ${index + 1}</small>
                    </div>
                `);
            };
            reader.readAsDataURL(file);
        }
    });
}

// Global functions for template usage
window.removeCurrentImage = function () {
    if (confirm('Are you sure you want to remove the current image?')) {
        displayNoImage();
        // Mark as changed
        $('#editProductForm').addClass('changed');
    }
};

window.removeImagePreview = function (button) {
    $(button).closest('.col-md-3').remove();
};

// Warn user about unsaved changes
window.addEventListener('beforeunload', function (e) {
    if ($('.changed').length > 0) {
        e.preventDefault();
        e.returnValue = 'You have unsaved changes. Are you sure you want to leave?';
        return e.returnValue;
    }
});
