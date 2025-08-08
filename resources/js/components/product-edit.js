import { 
    getProduct, 
    updateProduct as updateProductApi, 
    deleteProduct as deleteProductApi,
    createProduct,
    getProductFilterOptions
} from '../api/products.api.js';
import { uploadImage } from '../api/upload.api.js';
import { 
    showSuccessMessage, 
    showErrorMessage, 
    formatCurrency as formatCurrencyUtil,
    formatDateTime
} from '../utils/common.js';

/**
 * Alpine.js component for product edit page
 */
export default function productEdit() {
    return {
        // Data properties
        loading: true,
        saving: false,
        deleting: false,
        product: {},
        categories: {},
        
        // Form data
        form: {
            name: '',
            description: '',
            category_id: '',
            expiry_date: '',
            cost: '',
            price: '',
            stock: '',
            min_stock: '',
            is_active: true,
            image: ''
        },
        
        // UI state
        errors: {},
        showDeleteModal: false,
        newImagePreview: '',
        
        // Statistics
        stats: {
            totalSales: 0,
            pageViews: 0
        },

        // Computed properties
        get viewProductLink() {
            return this.product.id ? `/products/${this.product.id}` : '#';
        },

        get manageUnitsLink() {
            return this.product.id ? `/products/${this.product.id}#units` : '#';
        },

        get cancelLink() {
            return this.product.id ? `/products/${this.product.id}` : '/products';
        },

        get marginPercentage() {
            if (!this.form.price || !this.form.cost) return 0;
            const margin = ((this.form.price - this.form.cost) / this.form.price) * 100;
            return Math.round(margin * 100) / 100;
        },

        get marginClass() {
            const margin = this.marginPercentage;
            if (margin > 30) return 'text-green-600 font-bold';
            if (margin > 15) return 'text-yellow-600 font-bold';
            return 'text-red-600 font-bold';
        },

        get stockStatusClass() {
            if (!this.form.stock || !this.form.min_stock) return 'bg-gray-100 text-gray-800';
            
            if (this.form.stock <= 0) return 'bg-red-100 text-red-800';
            if (this.form.stock <= this.form.min_stock) return 'bg-yellow-100 text-yellow-800';
            return 'bg-green-100 text-green-800';
        },

        get stockStatusText() {
            if (!this.form.stock || !this.form.min_stock) return 'Unknown';
            
            if (this.form.stock <= 0) return 'Out of Stock';
            if (this.form.stock <= this.form.min_stock) return 'Low Stock';
            return 'In Stock';
        },

        // Initialize component
        async init() {
            if (!window.productId) {
                showErrorMessage('Product ID not found');
                this.loading = false;
                return;
            }

            try {
                await this.loadCategories();
                await this.loadProduct();
            } catch (error) {
                console.error('Error initializing product edit:', error);
                showErrorMessage('Failed to load product data');
            } finally {
                this.loading = false;
            }
        },

        // Load categories
        async loadCategories() {
            try {
                const response = await getProductFilterOptions();
                if (response.data?.data?.categories) {
                    this.categories = response.data.data.categories.reduce((acc, cat) => {
                        acc[cat.id] = cat.name;
                        return acc;
                    }, {});
                }
            } catch (error) {
                console.error('Error loading categories:', error);
                showErrorMessage('Failed to load categories');
            }
        },

        // Load product data
        async loadProduct() {
            try {
                const response = await getProduct(window.productId);
                this.product = response.data.data;
                this.fillFormData(this.product);
            } catch (error) {
                console.error('Error loading product:', error);
                showErrorMessage('Failed to load product details');
                throw error;
            }
        },

        // Fill form with product data
        fillFormData(product) {
            this.form = {
                name: product.name || '',
                description: product.description || '',
                category_id: product.category_id || '',
                expiry_date: product.expiry_date ? product.expiry_date.split(' ')[0] : '',
                cost: product.cost || '',
                price: product.price || '',
                stock: product.stock || '',
                min_stock: product.min_stock || '',
                is_active: product.is_active || false,
                image: product.image || ''
            };
        },

        // Handle image upload
        async handleImageUpload(event) {
            const file = event.target.files[0];
            if (!file) return;

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

            try {
                // Show preview
                const reader = new FileReader();
                reader.onload = (e) => {
                    this.newImagePreview = e.target.result;
                };
                reader.readAsDataURL(file);

                // Upload image
                const imageUrl = await uploadImage(file);
                this.form.image = imageUrl;
                showSuccessMessage('Image uploaded successfully!');
            } catch (error) {
                console.error('Image upload error:', error);
                showErrorMessage(error.message || 'Failed to upload image');
                this.newImagePreview = '';
                event.target.value = '';
            }
        },

        // Remove current image
        removeCurrentImage() {
            if (confirm('Are you sure you want to remove the current image?')) {
                this.form.image = '';
                this.product.image = '';
                this.newImagePreview = '';
            }
        },

        // Validate form
        validateForm() {
            this.errors = {};

            if (!this.form.name?.trim()) {
                this.errors.name = 'Product name is required';
            }

            if (!this.form.category_id) {
                this.errors.category_id = 'Category is required';
            }

            if (!this.form.cost || parseFloat(this.form.cost) <= 0) {
                this.errors.cost = 'Cost price must be greater than 0';
            }

            if (!this.form.price || parseFloat(this.form.price) <= 0) {
                this.errors.price = 'Unit price must be greater than 0';
            }

            if (this.form.stock < 0) {
                this.errors.stock = 'Stock cannot be negative';
            }

            if (this.form.min_stock < 0) {
                this.errors.min_stock = 'Min stock cannot be negative';
            }

            if (parseFloat(this.form.min_stock) > parseFloat(this.form.stock)) {
                this.errors.min_stock = 'Min stock cannot be greater than current stock';
            }

            return Object.keys(this.errors).length === 0;
        },

        // Update product
        async updateProduct() {
            if (!this.validateForm()) {
                showErrorMessage('Please fix the validation errors');
                return;
            }

            this.saving = true;

            try {
                const formData = {
                    name: this.form.name.trim(),
                    description: this.form.description?.trim() || '',
                    category_id: parseInt(this.form.category_id),
                    expiry_date: this.form.expiry_date || null,
                    cost: parseFloat(this.form.cost),
                    price: parseFloat(this.form.price),
                    stock: parseInt(this.form.stock),
                    min_stock: parseInt(this.form.min_stock),
                    is_active: this.form.is_active
                };

                if (this.form.image) {
                    formData.image = this.form.image;
                }

                const response = await updateProductApi(window.productId, formData);
                
                showSuccessMessage('Product updated successfully');
                
                // Update product data
                this.product = response.data.data;
                
                // Redirect to product view after delay
                setTimeout(() => {
                    window.location.href = `/products/${window.productId}`;
                }, 1500);

            } catch (error) {
                console.error('Error updating product:', error);
                
                if (error.response?.status === 422) {
                    this.errors = error.response.data.errors || {};
                    showErrorMessage('Please fix the validation errors');
                } else {
                    showErrorMessage(error.response?.data?.message || 'Failed to update product');
                }
            } finally {
                this.saving = false;
            }
        },

        // Delete product
        async deleteProduct() {
            this.deleting = true;

            try {
                await deleteProductApi(window.productId);
                showSuccessMessage('Product deleted successfully');
                
                setTimeout(() => {
                    window.location.href = '/products';
                }, 1500);

            } catch (error) {
                console.error('Error deleting product:', error);
                showErrorMessage(error.response?.data?.message || 'Failed to delete product');
            } finally {
                this.deleting = false;
                this.showDeleteModal = false;
            }
        },

        // Duplicate product
        async duplicateProduct() {
            try {
                const duplicateData = {
                    name: `${this.form.name} (Copy)`,
                    description: this.form.description,
                    category_id: parseInt(this.form.category_id),
                    expiry_date: this.form.expiry_date || null,
                    cost: parseFloat(this.form.cost),
                    price: parseFloat(this.form.price),
                    stock: parseInt(this.form.stock),
                    min_stock: parseInt(this.form.min_stock),
                    base_unit: this.product.base_unit,
                    is_active: false // Set as inactive by default
                };

                if (this.form.image) {
                    duplicateData.image = this.form.image;
                }

                const response = await createProduct(duplicateData);
                
                showSuccessMessage('Product duplicated successfully');
                
                setTimeout(() => {
                    window.location.href = `/products/${response.data.data.id}/edit`;
                }, 1500);

            } catch (error) {
                console.error('Error duplicating product:', error);
                showErrorMessage(error.response?.data?.message || 'Failed to duplicate product');
            }
        },

        // Utility methods
        formatCurrency(value) {
            return formatCurrencyUtil(value);
        },

        formatDateTime(dateString) {
            return formatDateTime(dateString);
        }
    };
}

// Register Alpine component
document.addEventListener('alpine:init', () => {
    Alpine.data('productEdit', productEdit);
});
