import { 
    getProduct, 
    getProductUnits, 
    createProductUnit, 
    updateProductUnit, 
    deleteProductUnit,
    deleteProduct as deleteProductApi
} from '../api/products.api.js';
import { 
    showSuccessMessage, 
    showErrorMessage, 
    formatCurrency as formatCurrencyUtil,
    formatDateTime
} from '../utils/common.js';

/**
 * Alpine.js component for product show page
 */
export default function productShow() {
    return {
        // Data properties
        loading: true,
        product: {},
        units: [],
        activeTab: 'details',
        
        // Modal states
        showUnitModal: false,
        showDeleteUnitModal: false,
        
        // Unit form
        unitForm: {
            unit_name: '',
            sku: '',
            barcode: '',
            conversion_rate: 1,
            selling_price: 0,
            is_base_unit: false
        },
        unitErrors: {},
        editingUnit: null,
        savingUnit: false,
        
        // Delete unit
        unitToDelete: null,
        deletingUnit: false,
        
        // Sales data
        salesStats: {
            totalSold: 0,
            totalRevenue: 0,
            avgSalePrice: 0
        },
        recentSales: [],

        // Computed properties
        get editProductLink() {
            return this.product.id ? `/products/${this.product.id}/edit` : '#';
        },

        get marginPercentage() {
            if (!this.product.price || !this.product.cost) return 0;
            const margin = ((this.product.price - this.product.cost) / this.product.price) * 100;
            return Math.round(margin * 100) / 100;
        },

        get statusClass() {
            return this.product.is_active 
                ? 'bg-green-100 text-green-800' 
                : 'bg-red-100 text-red-800';
        },

        get statusText() {
            return this.product.is_active ? 'Active' : 'Inactive';
        },

        get stockStatusClass() {
            if (!this.product.stock || !this.product.min_stock) return 'bg-gray-100 text-gray-800';
            
            if (this.product.stock <= 0) return 'bg-red-100 text-red-800';
            if (this.product.stock <= this.product.min_stock) return 'bg-yellow-100 text-yellow-800';
            return 'bg-green-100 text-green-800';
        },

        get stockStatusText() {
            if (!this.product.stock || !this.product.min_stock) return 'Unknown';
            
            if (this.product.stock <= 0) return 'Out of Stock';
            if (this.product.stock <= this.product.min_stock) return 'Low Stock';
            return 'In Stock';
        },

        get stockValue() {
            return (this.product.stock || 0) * (this.product.cost || 0);
        },

        get isExpired() {
            if (!this.product.expiry_date) return false;
            return new Date(this.product.expiry_date) < new Date();
        },

        get daysUntilExpiry() {
            if (!this.product.expiry_date) return null;
            const expiryDate = new Date(this.product.expiry_date);
            const today = new Date();
            const diffTime = expiryDate - today;
            const diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24));
            return diffDays;
        },

        // Initialize component
        async init() {
            if (!window.productId) {
                showErrorMessage('Product ID not found');
                this.loading = false;
                return;
            }

            try {
                await this.loadProduct();
                await this.loadUnits();
                await this.loadSalesData();
            } catch (error) {
                console.error('Error initializing product show:', error);
                showErrorMessage('Failed to load product data');
            } finally {
                this.loading = false;
            }
        },

        // Load product data
        async loadProduct() {
            try {
                const response = await getProduct(window.productId);
                this.product = response.data.data;
            } catch (error) {
                console.error('Error loading product:', error);
                showErrorMessage('Failed to load product details');
                throw error;
            }
        },

        // Load units data
        async loadUnits() {
            try {
                const response = await getProductUnits(window.productId);
                this.units = response.data.data || [];
            } catch (error) {
                console.error('Error loading units:', error);
                showErrorMessage('Failed to load product units');
            }
        },

        // Load sales data (mock for now)
        async loadSalesData() {
            try {
                // This would be replaced with actual API calls
                this.salesStats = {
                    totalSold: 125,
                    totalRevenue: 15750,
                    avgSalePrice: 126
                };
                this.recentSales = [];
            } catch (error) {
                console.error('Error loading sales data:', error);
            }
        },

        // Unit modal methods
        openUnitModal(unit = null) {
            this.editingUnit = unit;
            if (unit) {
                this.unitForm = { ...unit };
            } else {
                this.resetUnitForm();
            }
            this.unitErrors = {};
            this.showUnitModal = true;
        },

        closeUnitModal() {
            this.showUnitModal = false;
            this.editingUnit = null;
            this.resetUnitForm();
            this.unitErrors = {};
        },

        resetUnitForm() {
            this.unitForm = {
                unit_name: '',
                sku: '',
                barcode: '',
                conversion_rate: 1,
                selling_price: 0,
                is_base_unit: false
            };
        },

        // Edit unit
        editUnit(unit) {
            this.openUnitModal(unit);
        },

        // Save unit
        async saveUnit() {
            this.savingUnit = true;
            this.unitErrors = {};

            try {
                let response;
                if (this.editingUnit) {
                    response = await updateProductUnit(this.editingUnit.id, this.unitForm);
                    showSuccessMessage('Unit updated successfully');
                } else {
                    response = await createProductUnit(window.productId, this.unitForm);
                    showSuccessMessage('Unit created successfully');
                }

                await this.loadUnits();
                this.closeUnitModal();
            } catch (error) {
                console.error('Error saving unit:', error);
                
                if (error.response?.data?.errors) {
                    this.unitErrors = error.response.data.errors;
                } else {
                    showErrorMessage(error.response?.data?.message || 'Failed to save unit');
                }
            } finally {
                this.savingUnit = false;
            }
        },

        // Delete unit methods
        confirmDeleteUnit(unit) {
            this.unitToDelete = unit;
            this.showDeleteUnitModal = true;
        },

        closeDeleteUnitModal() {
            this.showDeleteUnitModal = false;
            this.unitToDelete = null;
        },

        async executeDeleteUnit() {
            if (!this.unitToDelete) return;

            this.deletingUnit = true;
            try {
                await deleteProductUnit(this.unitToDelete.id);
                showSuccessMessage('Unit deleted successfully');
                await this.loadUnits();
                this.closeDeleteUnitModal();
            } catch (error) {
                console.error('Error deleting unit:', error);
                showErrorMessage(error.response?.data?.message || 'Failed to delete unit');
            } finally {
                this.deletingUnit = false;
            }
        },

        // Delete product
        async deleteProduct() {
            if (!confirm('Are you sure you want to delete this product? This action cannot be undone.')) {
                return;
            }

            try {
                await deleteProductApi(window.productId);
                showSuccessMessage('Product deleted successfully');
                window.location.href = '/products';
            } catch (error) {
                console.error('Error deleting product:', error);
                showErrorMessage(error.response?.data?.message || 'Failed to delete product');
            }
        },

        // Utility methods
        formatCurrency(value) {
            return formatCurrencyUtil(value);
        },

        formatDateTime(dateString) {
            return formatDateTime(dateString);
        },

        formatDate(dateString) {
            if (!dateString) return 'N/A';
            return new Date(dateString).toLocaleDateString('vi-VN');
        }
    };
}

// Register Alpine component
document.addEventListener('alpine:init', () => {
    Alpine.data('productShow', productShow);
});
