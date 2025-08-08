import {
    populateCategoryFilter,
    populateCodePrefixFilter,
    renderProductsGrid,
    renderProductsList,
    setPriceRangeInfo,
    updateFilterTags,
    updateProductCount,
    updateSortIndicators
} from '../../utils/products/products-ui.js';
import {renderPagination} from '../../utils/pagination-utils.js';
import {deleteProduct as deleteProductApi, getProductFilterOptions, getProducts} from '../../api/products.api.js';

const PER_PAGE = 12;
let currentView = 'grid'; // 'grid' or 'list'
let currentFilters = {
    page: 1,
    per_page: PER_PAGE,
    sort_by: 'created_at',
    sort_order: 'desc'
};
let filterOptions = {}; // Store available filter options

$(document).ready(async function () {
    showLoadingState();
    setupEventListeners();
    await loadFilterOptions();
    await loadProducts(currentFilters);
    hideLoadingState();
});

function setupEventListeners() {
    // View toggle (Grid/List)
    $('input[name="view"]').on('change', function () {
        currentView = $(this).val();
        toggleView();
    });

    // Pagination
    $(document).on('click', '#pagination .page-link', function (e) {
        e.preventDefault();
        const page = $(this).data('page');
        if (page) {
            currentFilters.page = page;
            loadProducts(currentFilters);
        }
    });

    // Search input
    $('.input-group input[type="text"]').on('input', debounce(() => {
        const searchValue = $('.input-group input[type="text"]').val().trim();
        currentFilters.search = searchValue || undefined;
        currentFilters.page = 1;
        updateFiltersAndLoad();
    }, 300));

    // Search button
    $('.input-group button').on('click', () => {
        const searchValue = $('.input-group input[type="text"]').val().trim();
        currentFilters.search = searchValue || undefined;
        currentFilters.page = 1;
        updateFiltersAndLoad();
    });

    // Category filter
    $('#category-filter').on('change', () => {
        const val = $('#category-filter').val();
        currentFilters.category_id = val === 'all' ? undefined : parseInt(val);
        currentFilters.page = 1;
        updateFiltersAndLoad();
    });

    // Status filter
    $('#status-filter').on('change', () => {
        const val = $('#status-filter').val();
        delete currentFilters.is_active;
        delete currentFilters.is_expired;

        if (val === 'active') {
            currentFilters.is_active = true;
        } else if (val === 'inactive') {
            currentFilters.is_active = false;
        } else if (val === 'out_of_stock') {
            // This could be handled differently - for now, we'll use a stock filter
            currentFilters.max_stock = 0;
        } else {
            delete currentFilters.max_stock;
        }

        currentFilters.page = 1;
        updateFiltersAndLoad();
    });

    // Code prefix filter
    $('#code-prefix-filter').on('change', () => {
        const val = $('#code-prefix-filter').val();
        currentFilters.code_prefix = val === 'all' ? undefined : val;
        currentFilters.page = 1;
        updateFiltersAndLoad();
    });

    // Price filter modal
    $('#apply-price-filter').on('click', () => {
        const minPrice = parseFloat($('#min-price').val()) || undefined;
        const maxPrice = parseFloat($('#max-price').val()) || undefined;

        currentFilters.min_price = minPrice;
        currentFilters.max_price = maxPrice;
        currentFilters.page = 1;

        // Close modal using jQuery and Bootstrap
        $('#priceFilterModal').modal('hide');

        updateFiltersAndLoad();
    });

    // Clear price filter
    $('#clear-price-filter').on('click', () => {
        $('#min-price').val('');
        $('#max-price').val('');
        delete currentFilters.min_price;
        delete currentFilters.max_price;
        currentFilters.page = 1;

        // Close modal using jQuery and Bootstrap
        $('#priceFilterModal').modal('hide');

        updateFiltersAndLoad();
    });

    // Remove individual filter tags
    $(document).on('click', '[data-filter-remove]', function () {
        const filterType = $(this).data('filter-remove');
        removeFilter(filterType);
    });

    // Clear all filters
    $('#clear-all-filters').on('click', clearAllFilters);

    // Delete product
    $(document).on('click', '.delete-product', async function (e) {
        e.preventDefault();
        const productId = $(this).data('id');
        const productName = $(this).data('name');

        if (confirm(`Are you sure you want to delete product "${productName}"?\n\nThis action cannot be undone!`)) {
            await handleDeleteProduct(productId);
        }
    });

    // Sort controls
    $('#sort-by').on('change', () => {
        currentFilters.sort_by = $('#sort-by').val();
        currentFilters.page = 1;
        updateFiltersAndLoad();
    });

    $('#sort-order').on('click', function () {
        const currentOrder = $(this).data('order');
        const newOrder = currentOrder === 'asc' ? 'desc' : 'asc';

        $(this).data('order', newOrder);
        currentFilters.sort_order = newOrder;
        currentFilters.page = 1;

        // Update button icon
        const icon = $(this).find('i');
        if (newOrder === 'asc') {
            icon.removeClass('fa-sort-amount-down').addClass('fa-sort-amount-up');
            $(this).attr('title', 'Sort Ascending');
        } else {
            icon.removeClass('fa-sort-amount-up').addClass('fa-sort-amount-down');
            $(this).attr('title', 'Sort Descending');
        }

        updateFiltersAndLoad();
    });

    // Table header sorting (for list view)
    $(document).on('click', '.sortable', function () {
        const sortField = $(this).data('sort');

        // If clicking the same field, toggle order
        if (currentFilters.sort_by === sortField) {
            currentFilters.sort_order = currentFilters.sort_order === 'asc' ? 'desc' : 'asc';
        } else {
            // New field, default to ascending
            currentFilters.sort_by = sortField;
            currentFilters.sort_order = 'asc';
        }

        currentFilters.page = 1;

        // Update the sort dropdown to match
        $('#sort-by').val(sortField);
        const sortButton = $('#sort-order');
        sortButton.data('order', currentFilters.sort_order);

        const icon = sortButton.find('i');
        if (currentFilters.sort_order === 'asc') {
            icon.removeClass('fa-sort-amount-down').addClass('fa-sort-amount-up');
            sortButton.attr('title', 'Sort Ascending');
        } else {
            icon.removeClass('fa-sort-amount-up').addClass('fa-sort-amount-down');
            sortButton.attr('title', 'Sort Descending');
        }

        updateFiltersAndLoad();
    });
}

async function loadFilterOptions() {
    try {
        const response = await getProductFilterOptions();

        // Handle different response structures
        let categories = {};
        let priceRange = {min: 0, max: 1000};
        let codePrefixes = [];

        if (response.data.data) {
            const data = response.data.data;

            // Categories
            if (data.categories) {
                if (typeof data.categories === 'object' && !Array.isArray(data.categories)) {
                    categories = data.categories;
                } else if (Array.isArray(data.categories)) {
                    categories = data.categories.reduce((acc, cat) => {
                        acc[cat.id] = cat.name;
                        return acc;
                    }, {});
                }
            }

            // Price range
            if (data.price_range) {
                priceRange = {
                    min: data.price_range.min || 0,
                    max: data.price_range.max || 1000
                };
            }

            // Code prefixes
            if (Array.isArray(data.code_prefixes)) {
                codePrefixes = data.code_prefixes;
            }
        }

        // Store filter options for later use
        filterOptions = {
            categories,
            price_range: priceRange,
            code_prefixes: codePrefixes
        };

        // Populate UI elements
        if (Object.keys(categories).length > 0) {
            populateCategoryFilter(categories);
        }

        if (codePrefixes.length > 0) {
            populateCodePrefixFilter(codePrefixes);
        }

        setPriceRangeInfo(priceRange);

    } catch (error) {
        console.error('Failed to load filter options:', error);
        // Continue without filter options if this fails
    }
}

async function loadProducts(params = {}) {
    try {
        // Show loading in the current view
        showProductsLoading();

        const response = await getProducts(params);
        const {data, meta} = response.data;

        // Render products based on current view
        if (currentView === 'grid') {
            renderProductsGrid(data);
        } else {
            renderProductsList(data);
        }

        // Update pagination and count
        renderPagination(meta.current_page, meta.last_page);
        updateProductCount(meta.total);

        // Update sort indicators in list view
        updateSortIndicators(currentFilters.sort_by, currentFilters.sort_order);

    } catch (error) {
        console.error('Failed to load products:', error);
        showErrorMessage(error.response?.data?.message || 'Failed to load products');
        showProductsError();
    }
}

function updateFiltersAndLoad() {
    updateFilterTags(currentFilters, filterOptions);
    loadProducts(currentFilters);
}

function removeFilter(filterType) {
    switch (filterType) {
        case 'search':
            delete currentFilters.search;
            $('.input-group input[type="text"]').val('');
            break;
        case 'category_id':
            delete currentFilters.category_id;
            $('#category-filter').val('all');
            break;
        case 'is_active':
            delete currentFilters.is_active;
            $('#status-filter').val('all');
            break;
        case 'code_prefix':
            delete currentFilters.code_prefix;
            $('#code-prefix-filter').val('all');
            break;
        case 'price_range':
            delete currentFilters.min_price;
            delete currentFilters.max_price;
            $('#min-price').val('');
            $('#max-price').val('');
            break;
    }

    currentFilters.page = 1;
    updateFiltersAndLoad();
}

function clearAllFilters() {
    // Reset form controls
    $('.input-group input[type="text"]').val('');
    $('#category-filter').val('all');
    $('#status-filter').val('all');
    $('#code-prefix-filter').val('all');
    $('#min-price').val('');
    $('#max-price').val('');

    // Reset filters object
    currentFilters = {
        page: 1,
        per_page: PER_PAGE,
        sort_by: 'created_at',
        sort_order: 'desc'
    };

    // Reset sort controls
    $('#sort-by').val('created_at');
    const sortButton = $('#sort-order');
    sortButton.data('order', 'desc');
    sortButton.find('i').removeClass('fa-sort-amount-up').addClass('fa-sort-amount-down');
    sortButton.attr('title', 'Sort Descending');

    updateFiltersAndLoad();
}

function showProductsLoading() {
    if (currentView === 'grid') {
        $('#grid-container').html(`
            <div class="col-12">
                <div class="text-center py-5">
                    <i class="fas fa-spinner fa-spin fa-3x text-primary mb-3"></i>
                    <h5>Loading products...</h5>
                </div>
            </div>
        `);
    } else {
        $('#list-container tbody').html(`
            <tr>
                <td colspan="8" class="text-center py-4">
                    <i class="fas fa-spinner fa-spin fa-2x text-primary mb-2"></i>
                    <div>Loading products...</div>
                </td>
            </tr>
        `);
    }
}

function showProductsError() {
    if (currentView === 'grid') {
        $('#grid-container').html(`
            <div class="col-12">
                <div class="text-center py-5">
                    <i class="fas fa-exclamation-triangle fa-3x text-danger mb-3"></i>
                    <h5 class="text-danger">Error loading products</h5>
                    <button class="btn btn-primary" onclick="loadProducts(currentFilters)">
                        <i class="fas fa-redo"></i> Try Again
                    </button>
                </div>
            </div>
        `);
    } else {
        $('#list-container tbody').html(`
            <tr>
                <td colspan="8" class="text-center py-4">
                    <i class="fas fa-exclamation-triangle fa-2x text-danger mb-2"></i>
                    <div class="text-danger">Error loading products</div>
                    <button class="btn btn-sm btn-primary mt-2" onclick="loadProducts(currentFilters)">
                        <i class="fas fa-redo"></i> Try Again
                    </button>
                </td>
            </tr>
        `);
    }
}

function toggleView() {
    if (currentView === 'grid') {
        $('#grid-container').removeClass('d-none');
        $('#list-container').addClass('d-none');
        $('#grid-view').prop('checked', true);
    } else {
        $('#grid-container').addClass('d-none');
        $('#list-container').removeClass('d-none');
        $('#list-view').prop('checked', true);
    }
}

async function handleDeleteProduct(productId) {
    const deleteBtn = $(`.delete-product[data-id="${productId}"]`);
    const originalHtml = deleteBtn.html();

    try {
        deleteBtn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i>');
        await deleteProductApi(productId);
        showSuccessMessage('Product deleted successfully!');
        await loadProducts(currentFilters);

    } catch (error) {
        console.error('Failed to delete product:', error);
        const errorMessage = error.response?.data?.message || 'Failed to delete product';
        showErrorMessage(errorMessage);

        deleteBtn.prop('disabled', false).html(originalHtml);
    }
}

// Make loadProducts available globally for error retry buttons
window.loadProducts = loadProducts;
