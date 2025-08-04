import { createProduct, getProductFilterOptions } from '../../api/products.api.js';
import { uploadImage } from '../../api/upload.api.js';

let categories = {};
let selectedImages = [];

$(document).ready(async function () {
    await setupCreateProduct();
    setupEventListeners();
});

async function setupCreateProduct() {
    try {
        showLoadingState();

        // Load categories for dropdown
        try {
            const categoriesResponse = await getProductFilterOptions();
            
            if (categoriesResponse.data?.data?.categories) {
                categories = categoriesResponse.data.data.categories;
            } else if (categoriesResponse.data?.categories) {
                // Fallback if structure is different
                categories = categoriesResponse.data.categories;
            }
        } catch (categoryError) {
            console.warn('Could not load filter options, trying categories endpoint:', categoryError);
            // Fallback: could try a categories endpoint or use hardcoded values
            categories = {
                1: 'Electronics',
                2: 'Clothing', 
                3: 'Books',
                4: 'Home & Garden',
                5: 'Sports'
            };
        }

        populateCategoryDropdown();

    } catch (error) {
        console.error('Error setting up create product:', error);
        showErrorMessage('Failed to load page data. Please refresh and try again.');
    } finally {
        hideLoadingState();
    }
}

function populateCategoryDropdown() {
    const categorySelect = $('#category');
    categorySelect.empty().append('<option value="">Select Category</option>');

    Object.entries(categories).forEach(([id, name]) => {
        categorySelect.append(`<option value="${id}">${name}</option>`);
    });
}

function setupEventListeners() {
    // Form submission
    $('#createProductForm').on('submit', handleCreateProduct);

    // Save as draft button
    $('#save-draft-btn').on('click', () => handleSaveAsDraft());

    // Image upload preview
    $('#images').on('change', handleImagePreview);

    // Auto-generate SKU from product name
    $('#name').on('input', debounce(generateSKUFromName, 300));

    // Generate code from code prefix and name
    $('#code_prefix').on('input', debounce(generateProductCode, 300));
    $('#name').on('input', debounce(generateProductCode, 300));

    // Set minimum date for expiry date (tomorrow)
    const tomorrow = new Date();
    tomorrow.setDate(tomorrow.getDate() + 1);
    $('#expiry_date').attr('min', tomorrow.toISOString().split('T')[0]);
}

async function handleCreateProduct(e) {
    e.preventDefault();

    try {
        const $submitBtn = $('button[type="submit"]');
        const originalText = $submitBtn.html();

        // Disable submit button and show loading
        $submitBtn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> Creating...');

        // Collect form data
        const formData = await collectFormData();

        // Validate required fields
        if (!validateFormData(formData)) {
            return;
        }

        // Create product via API
        const response = await createProduct(formData);

        showSuccessMessage('Product created successfully!');

        // Redirect to product view or list
        const productId = response.data.data.id;
        if (productId) {
            setTimeout(() => {
                window.location.href = `/products/${productId}`;
            }, 1500);
        } else {
            setTimeout(() => {
                window.location.href = '/products';
            }, 1500);
        }

    } catch (error) {
        console.error('Error creating product:', error);

        if (error.response?.data?.errors) {
            displayValidationErrors(error.response.data.errors);
        } else {
            const msg = error.response?.data?.message || 'Failed to create product';
            showErrorMessage(msg);
        }
    } finally {
        // Re-enable submit button
        const $submitBtn = $('button[type="submit"]');
        $submitBtn.prop('disabled', false).html('<i class="fas fa-plus-square"></i> Create Product');
    }
}

async function handleSaveAsDraft() {
    try {
        const $draftBtn = $('#save-draft-btn');
        const originalText = $draftBtn.html();

        $draftBtn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> Saving...');

        // Set is_active to false for draft
        const originalActive = $('#is_active').prop('checked');
        $('#is_active').prop('checked', false);

        // Collect form data
        const formData = await collectFormData();
        formData.is_active = false; // Ensure it's saved as inactive (draft)

        // Validate required fields (relaxed for draft)
        if (!formData.name || !formData.category_id) {
            showErrorMessage('Product name and category are required even for drafts');
            return;
        }

        // Create product as draft
        await createProduct(formData);

        showSuccessMessage('Product saved as draft!');

        // Redirect to products list
        setTimeout(() => {
            window.location.href = '/products';
        }, 1500);

    } catch (error) {
        console.error('Error saving draft:', error);
        const msg = error.response?.data?.message || 'Failed to save draft';
        showErrorMessage(msg);
    } finally {
        const $draftBtn = $('#save-draft-btn');
        $draftBtn.prop('disabled', false).html('<i class="fas fa-save"></i> Save as Draft');
    }
}

async function collectFormData() {
    const formData = {
        name: $('#name').val().trim(),
        description: $('#description').val().trim(),
        category_id: parseInt($('#category').val()) || null,
        barcode: $('#barcode').val().trim(),
        sku: $('#sku').val().trim(),
        code_prefix: $('#code_prefix').val().trim(),
        expiry_date: $('#expiry_date').val() || null,
        price: parseFloat($('#price').val()) || 0,
        cost: parseFloat($('#cost_price').val()) || 0,
        stock: parseInt($('#stock').val()) || 0,
        is_active: $('#is_active').is(':checked'),
        is_featured: $('#is_featured').is(':checked')
    };

    // Handle image upload
    const imageInput = $('#images')[0];
    const imageFile = imageInput?.files?.[0]; // Take the first image only

    if (imageFile) {
        try {
            showLoadingState();
            const uploadedImageUrl = await uploadImage(imageFile);
            if (uploadedImageUrl) {
                formData.image = uploadedImageUrl;
            }
        } catch (err) {
            console.error('Image upload failed:', err);
            showErrorMessage('Image upload failed. Product will be created without image.');
        } finally {
            hideLoadingState();
        }
    }

    return formData;
}

function validateFormData(data) {
    let isValid = true;

    // Clear previous validation messages
    clearValidationErrors();

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

    if (data.stock < 0) {
        showFieldError('stock', 'Stock cannot be negative');
        isValid = false;
    }

    if (data.cost && data.cost < 0) {
        showFieldError('cost_price', 'Cost price cannot be negative');
        isValid = false;
    }

    // Validate barcode uniqueness is handled by the API

    return isValid;
}

function clearValidationErrors() {
    $('.invalid-feedback').text('');
    $('.form-control, .form-select').removeClass('is-invalid');
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
            // Handle field name mapping if needed
            const fieldName = mapFieldName(field);
            showFieldError(fieldName, messages[0]);
        }
    });
}

function mapFieldName(apiFieldName) {
    // Map API field names to form field names if they differ
    const fieldMapping = {
        'category_id': 'category',
        'cost': 'cost_price'
    };
    
    return fieldMapping[apiFieldName] || apiFieldName;
}

function generateSKUFromName() {
    const skuField = $('#sku');
    const nameValue = $('#name').val().trim();
    
    if (!skuField.val() && nameValue) {
        const sku = nameValue
            .replace(/[^a-zA-Z0-9\s]/g, '')
            .replace(/\s+/g, '-')
            .toUpperCase()
            .substring(0, 20);
        skuField.val(sku);
    }
}

function generateProductCode() {
    const prefix = $('#code_prefix').val().trim();
    const name = $('#name').val().trim();
    
    if (prefix && name) {
        // Generate a simple sequential number (in real app, this would come from backend)
        const timestamp = Date.now().toString().slice(-4);
        const code = `${prefix}${timestamp}`;
        
        // Store in a hidden field or data attribute for later use
        $('#createProductForm').data('generated-code', code);
    }
}

function handleImagePreview(e) {
    const files = e.target.files;
    const previewContainer = $('#image-preview');
    previewContainer.empty();

    selectedImages = Array.from(files).slice(0, 5); // Limit to 5 images

    selectedImages.forEach((file, index) => {
        if (file.type.startsWith('image/')) {
            const reader = new FileReader();
            reader.onload = function (e) {
                const col = document.createElement('div');
                col.className = 'col-md-4 mb-3';
                col.innerHTML = `
                    <div class="card">
                        <img src="${e.target.result}" class="card-img-top" style="height: 150px; object-fit: cover;">
                        <div class="card-body p-2">
                            <small class="text-muted">${file.name}</small>
                            ${index === 0 ? '<br><small class="text-primary">Main Image</small>' : ''}
                            <button type="button" class="btn btn-sm btn-danger float-end" 
                                    onclick="removeImagePreview(this, ${index})" title="Remove">
                                <i class="fas fa-times"></i>
                            </button>
                        </div>
                    </div>
                `;
                previewContainer.append(col);
            };
            reader.readAsDataURL(file);
        }
    });
}

// Global functions for template usage
window.removeImagePreview = function (button, index) {
    if (confirm('Remove this image?')) {
        $(button).closest('.col-md-4').remove();
        
        // Remove from selectedImages array
        selectedImages.splice(index, 1);
        
        // Update the file input (this is tricky with file inputs, so we'll just clear it)
        // In a production app, you'd want a more sophisticated approach
        if (selectedImages.length === 0) {
            $('#images').val('');
        }
    }
};

// Warn user about unsaved changes
window.addEventListener('beforeunload', function (e) {
    const hasData = $('#name').val() || $('#description').val() || $('#price').val();
    if (hasData && !window.formSubmitted) {
        e.preventDefault();
        e.returnValue = 'You have unsaved changes. Are you sure you want to leave?';
        return e.returnValue;
    }
});

// Set flag when form is submitted to prevent warning
$('#createProductForm').on('submit', function() {
    window.formSubmitted = true;
});
