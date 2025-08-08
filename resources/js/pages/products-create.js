import {
    showSuccessMessage,
    showErrorMessage,
    showLoadingState,
    hideLoadingState,
    withButtonControl,
    formatPriceInput,
    showError
} from '../utils/common.js';
import { createProduct, getProductFilterOptions } from '../api/products.api.js';
import { uploadImage } from '../api/upload.api.js';

let categories = {};
let uploadedImageUrl = null;

document.addEventListener('DOMContentLoaded', async () => {
    // Load initial data
    await loadCategories();

    // Setup event listeners
    setupEventListeners();

    // Setup form validation
    setupFormValidation();

    // Initialize image preview
    initializeImagePreview();
});

async function loadCategories() {
    try {
        const response = await getProductFilterOptions();
        const data = response.data.data;
        if (data.categories) {
            categories = data.categories;
            populateCategorySelect();
        }
    } catch (error) {
        console.error('Error loading categories:', error);
        showErrorMessage('Failed to load categories');
    }
}

function populateCategorySelect() {
    const categorySelect = document.getElementById('category_id');
    categorySelect.innerHTML = '<option value="">Select Category</option>';

    Object.entries(categories).forEach(([id, name]) => {
        const option = document.createElement('option');
        option.value = id;
        option.textContent = name;
        categorySelect.appendChild(option);
    });
}

function setupEventListeners() {
    // Form submission
    const form = document.getElementById('product-form');
    const submitBtn = form.querySelector('button[type="submit"]');
    const saveDraftBtn = document.getElementById('save-draft-btn');

    const handleSubmit = withButtonControl(async (isDraft = false) => {
        await submitForm(isDraft);
    }, [submitBtn, saveDraftBtn]);

    form.addEventListener('submit', async (e) => {
        e.preventDefault();
        await handleSubmit(false);
    });

    if (saveDraftBtn) {
        saveDraftBtn.addEventListener('click', async (e) => {
            e.preventDefault();
            await handleSubmit(true);
        });
    }

    // Auto-generate SKU from name
    const nameInput = document.getElementById('name');
    const skuInput = document.getElementById('base_sku');

    nameInput.addEventListener('input', () => {
        if (!skuInput.value) {
            generateProductSKU();
        }
    });

    // Price formatting
    const priceInput = document.getElementById('price');
    priceInput.addEventListener('input', formatPriceInput);

    // Stock validation
    const stockInput = document.getElementById('stock');
    const minStockInput = document.getElementById('min_stock');

    stockInput.addEventListener('input', validateStock);
    minStockInput.addEventListener('input', validateStock);
}

const initializeImagePreview = () => {
    const imageInput = document.getElementById('image');
    const imageDropZone = document.getElementById('image-drop-zone');
    const removeImageBtn = document.getElementById('remove-image');

    // Click to select file
    imageDropZone.addEventListener('click', () => {
        imageInput.click();
    });

    // File input change
    imageInput.addEventListener('change', async (e) => {
        const file = e.target.files[0];
        if (file) {
            await handleImageUpload(file);
        } else {
            clearImagePreview();
        }
    });

    // Drag and drop
    imageDropZone.addEventListener('dragover', (e) => {
        e.preventDefault();
        imageDropZone.classList.add('border-blue-500', 'bg-blue-50');
    });

    imageDropZone.addEventListener('dragleave', (e) => {
        e.preventDefault();
        imageDropZone.classList.remove('border-blue-500', 'bg-blue-50');
    });

    imageDropZone.addEventListener('drop', async (e) => {
        e.preventDefault();
        imageDropZone.classList.remove('border-blue-500', 'bg-blue-50');

        const files = e.dataTransfer.files;
        if (files.length > 0) {
            const file = files[0];
            if (file.type.startsWith('image/')) {
                // Set the file to the input
                const dt = new DataTransfer();
                dt.items.add(file);
                imageInput.files = dt.files;

                await handleImageUpload(file);
            } else {
                showErrorMessage('Please select a valid image file');
            }
        }
    });

    // Remove image
    if (removeImageBtn) {
        removeImageBtn.addEventListener('click', () => {
            clearImagePreview();
        });
    }
};

const handleImageUpload = async (file) => {
    // Validate file type
    if (!file.type.startsWith('image/')) {
        showErrorMessage('Please select a valid image file');
        return;
    }

    // Validate file size (2MB limit)
    const maxSize = 2 * 1024 * 1024; // 2MB
    if (file.size > maxSize) {
        showErrorMessage('Image size must be less than 2MB');
        return;
    }

    showLoadingState();

    try {
        // Upload image
        const imageUrl = await uploadImage(file);
        uploadedImageUrl = imageUrl;

        showImagePreview(imageUrl);
        showSuccessMessage('Image uploaded successfully!');

    } catch (error) {
        console.error('Image upload error:', error);
        showErrorMessage(error.message || 'Failed to upload image');

        // Clear the file input
        const imageInput = document.getElementById('image');
        imageInput.value = '';
    } finally {
        hideLoadingState();
    }
};

const showImagePreview = (imageUrl) => {
    const dropZone = document.getElementById('image-drop-zone');
    const preview = document.getElementById('image-preview');
    const previewImage = document.getElementById('preview-image');

    previewImage.src = imageUrl;
    dropZone.classList.add('hidden');
    preview.classList.remove('hidden');
};

const clearImagePreview = () => {
    const dropZone = document.getElementById('image-drop-zone');
    const preview = document.getElementById('image-preview');
    const imageInput = document.getElementById('image');

    uploadedImageUrl = null;
    imageInput.value = '';

    preview.classList.add('hidden');
    dropZone.classList.remove('hidden');
};

function generateProductSKU() {
    const name = document.getElementById('name').value.trim();
    if (!name) return;

    // Generate SKU from name (simple implementation)
    const words = name.split(' ').filter(word => word.length > 0);
    let sku;

    if (words.length === 1) {
        sku = words[0].substring(0, 3).toUpperCase();
    } else if (words.length === 2) {
        sku = words[0].charAt(0).toUpperCase() + words[1].substring(0, 2).toUpperCase();
    } else {
        sku = words.slice(0, 3).map(word => word.charAt(0).toUpperCase()).join('');
    }

    // Add random numbers
    const randomNum = Math.floor(Math.random() * 1000).toString().padStart(3, '0');
    sku += '-' + randomNum;

    document.getElementById('base_sku').value = sku;
}

function validateStock() {
    const stock = parseInt(document.getElementById('stock').value) || 0;
    const minStock = parseInt(document.getElementById('min_stock').value) || 0;

    const stockInput = document.getElementById('stock');
    const minStockInput = document.getElementById('min_stock');

    // Clear previous validation styles
    stockInput.classList.remove('border-red-500');
    minStockInput.classList.remove('border-red-500');

    // Remove existing error messages
    const existingError = stockInput.parentNode.querySelector('.text-red-500');
    if (existingError) {
        existingError.remove();
    }

    if (minStock > stock) {
        minStockInput.classList.add('border-red-500');

        const errorMsg = document.createElement('p');
        errorMsg.className = 'text-red-500 text-sm mt-1';
        errorMsg.textContent = 'Minimum stock cannot be greater than current stock';
        minStockInput.parentNode.appendChild(errorMsg);
    }
}

function setupFormValidation() {
    const form = document.getElementById('product-form');
    const inputs = form.querySelectorAll('input[required], select[required], textarea[required]');

    inputs.forEach(input => {
        input.addEventListener('blur', () => validateField(input));
        input.addEventListener('input', () => clearFieldError(input));
    });
}

function validateField(field) {
    const value = field.value.trim();
    const isValid = field.checkValidity();

    clearFieldError(field);

    if (!isValid || !value) {
        showFieldError(field, getFieldErrorMessage(field));
        return false;
    }

    // Custom validations
    if (field.id === 'price' && parseFloat(value) <= 0) {
        showFieldError(field, 'Price must be greater than 0');
        return false;
    }

    if (field.id === 'stock' && parseInt(value) < 0) {
        showFieldError(field, 'Stock cannot be negative');
        return false;
    }

    return true;
}

function showFieldError(field, message) {
    field.classList.add('border-red-500');

    const errorDiv = document.createElement('div');
    errorDiv.className = 'field-error text-red-500 text-sm mt-1';
    errorDiv.textContent = message;

    field.parentNode.appendChild(errorDiv);
}

function clearFieldError(field) {
    field.classList.remove('border-red-500');

    const errorDiv = field.parentNode.querySelector('.field-error');
    if (errorDiv) {
        errorDiv.remove();
    }
}

function getFieldErrorMessage(field) {
    if (!field.value.trim()) {
        return `${field.previousElementSibling.textContent.replace('*', '').trim()} is required`;
    }

    if (field.type === 'email') {
        return 'Please enter a valid email address';
    }

    if (field.type === 'url') {
        return 'Please enter a valid URL';
    }

    return 'This field is invalid';
}

const buildProductFormData = () => {
    const formData = {
        name: document.getElementById('name').value.trim(),
        description: document.getElementById('description').value.trim(),
        base_sku: document.getElementById('base_sku').value.trim(),
        category_id: parseInt(document.getElementById('category_id').value),
        price: parseFloat(document.getElementById('price').value),
        stock: parseInt(document.getElementById('stock').value),
        min_stock: parseInt(document.getElementById('min_stock').value),
        base_unit: document.getElementById('base_unit').value.trim(),
        is_active: document.getElementById('is_active').checked,
    };

    // Add optional fields
    const barcode = document.getElementById('barcode').value.trim();
    if (barcode) formData.barcode = barcode;

    const cost = document.getElementById('cost').value;
    if (cost) formData.cost = parseFloat(cost);

    const expiryDate = document.getElementById('expiry_date').value;
    if (expiryDate) formData.expiry_date = expiryDate;

    // Add uploaded image URL
    if (uploadedImageUrl) {
        formData.image = uploadedImageUrl;
    }

    return formData;
};

async function submitForm(isDraft = false) {
    try {
        showLoadingState();

        // Validate all required fields only if not saving as draft
        if (!isDraft) {
            const form = document.getElementById('product-form');
            const inputs = form.querySelectorAll('input[required], select[required], textarea[required]');
            let isValid = true;

            inputs.forEach(input => {
                if (!validateField(input)) {
                    isValid = false;
                }
            });

            if (!isValid) {
                showErrorMessage('Please fix the validation errors');
                return;
            }

            // Additional validations
            const stock = parseInt(document.getElementById('stock').value) || 0;
            const minStock = parseInt(document.getElementById('min_stock').value) || 0;

            if (minStock > stock) {
                showErrorMessage('Minimum stock cannot be greater than current stock');
                return;
            }
        }

        // Build form data
        const formData = buildProductFormData();

        // Add draft status
        if (isDraft) {
            formData.is_draft = true;
        }

        // Submit to API
        const response = await createProduct(formData);

        if (isDraft) {
            showSuccessMessage('Product saved as draft successfully!');
        } else {
            showSuccessMessage('Product created successfully!');
        }

        // Redirect to product list or detail
        const productId = response.data.data.id;
        setTimeout(() => {
            window.location.href = `/products/${productId}`;
        }, 1500);

    } catch (error) {
        console.error('Error creating product:', error);

        if (error.response?.status === 422) {
            // Validation errors
            handleValidationErrors(error);
        } else {
            showErrorMessage(error.response?.data?.message || 'Failed to create product');
        }
    } finally {
        hideLoadingState();
    }
}

function handleValidationErrors(error) {
    if (error.response?.data?.errors) {
        showError(error.response.data);

        // Also show field-specific errors
        const errors = error.response.data.errors;
        Object.entries(errors).forEach(([field, messages]) => {
            const input = document.getElementById(field);
            if (input) {
                showFieldError(input, messages[0]);
            }
        });
    } else {
        showErrorMessage(error.response?.data?.message || 'Failed to create product.');
    }
}
