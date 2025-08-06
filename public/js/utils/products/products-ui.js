/**
 * Product UI rendering utilities
 */

/**
 * Format price for display
 * @param {number} price - Price value
 * @returns {string} Formatted price string
 */
function formatPrice(price) {
    // Use global formatCurrency if available, otherwise fallback to simple formatting
    if (typeof formatCurrency === 'function') {
        return formatCurrency(price);
    }

    // Fallback formatting
    return new Intl.NumberFormat('vi-VN', {
        style: 'currency',
        currency: 'VND'
    }).format(price);
}

/**
 * Render products in grid view
 * @param {Array} products - Array of product objects
 */
export function renderProductsGrid(products) {
    const gridContainer = $('#grid-container');

    if (!products || products.length === 0) {
        gridContainer.html(`
            <div class="col-12">
                <div class="text-center py-5">
                    <i class="fas fa-box-open fa-3x text-muted mb-3"></i>
                    <h5 class="text-muted">No products found</h5>
                    <p class="text-muted">Try adjusting your filters or search criteria.</p>
                </div>
            </div>
        `);
        return;
    }

    const productCards = products.map(product => createProductCard(product)).join('');
    gridContainer.html(productCards);
}

/**
 * Render products in list view
 * @param {Array} products - Array of product objects
 */
export function renderProductsList(products) {
    const tableBody = $('#list-container tbody');

    if (!products || products.length === 0) {
        tableBody.html(`
            <tr>
                <td colspan="8" class="text-center py-5">
                    <i class="fas fa-box-open fa-2x text-muted mb-3"></i>
                    <div class="text-muted">No products found</div>
                </td>
            </tr>
        `);
        return;
    }

    const productRows = products.map(product => createProductRow(product)).join('');
    tableBody.html(productRows);
}

/**
 * Create a product card for grid view
 * @param {Object} product - Product object
 * @returns {string} HTML string for product card
 */
function createProductCard(product) {
    const stockBadge = getStockBadge(product.stock);
    const imageUrl = product.image || 'https://via.placeholder.com/300x200?text=No+Image';
    const price = formatPrice(parseFloat(product.price));

    return `
        <div class="col-md-4 col-lg-3 mb-4">
            <div class="card h-100">
                <img src="${imageUrl}" class="card-img-top" alt="${product.name}"
                     style="height: 200px; object-fit: cover;">
                <div class="card-body d-flex flex-column">
                    <h6 class="card-title">${product.name}</h6>
                    <p class="card-text text-muted small flex-grow-1">
                        ${product.description || 'No description available'}
                    </p>
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <span class="text-primary fw-bold">${price}</span>
                        ${stockBadge}
                    </div>
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <small class="text-muted">Code: ${product.code}</small>
                        <small class="text-muted">Stock: ${product.stock || 0}</small>
                    </div>
                    <div class="btn-group w-100" role="group">
                        <a href="/products/${product.id}" class="btn btn-sm btn-outline-info">
                            <i class="fas fa-eye"></i>
                        </a>
                        <a href="/products/${product.id}/edit" class="btn btn-sm btn-outline-warning">
                            <i class="fas fa-edit"></i>
                        </a>
                        <button type="button" class="btn btn-sm btn-outline-danger delete-product"
                                data-id="${product.id}" data-name="${product.name}">
                            <i class="fas fa-trash"></i>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    `;
}

/**
 * Create a product row for list view
 * @param {Object} product - Product object
 * @returns {string} HTML string for product row
 */
function createProductRow(product) {
    const stockBadge = getStockBadge(product.stock);
    const imageUrl = product.image || 'https://via.placeholder.com/50?text=No+Image';
    const price = formatPrice(parseFloat(product.price));
    const createdDate = formatDate(product.created_at);

    return `
        <tr>
            <td>
                <img src="${imageUrl}" class="rounded" alt="${product.name}" width="50" height="50"
                     style="object-fit: cover;">
            </td>
            <td>
                <div>
                    <h6 class="mb-1">${product.name}</h6>
                    <small class="text-muted">${truncateText(product.description || 'No description', 60)}</small>
                </div>
            </td>
            <td>
                <code class="text-dark">${product.code}</code>
            </td>
            <td class="fw-bold text-primary">${price}</td>
            <td>
                <span class="badge ${getStockBadgeClass(product.stock)}">${product.stock || 0}</span>
            </td>
            <td>${stockBadge}</td>
            <td>
                <small class="text-muted">${createdDate}</small>
            </td>
            <td>
                <div class="btn-group" role="group">
                    <a href="/products/${product.id}" class="btn btn-sm btn-outline-info" title="View">
                        <i class="fas fa-eye"></i>
                    </a>
                    <a href="/products/${product.id}/edit" class="btn btn-sm btn-outline-warning" title="Edit">
                        <i class="fas fa-edit"></i>
                    </a>
                    <button type="button" class="btn btn-sm btn-outline-danger delete-product"
                            data-id="${product.id}" data-name="${product.name}" title="Delete">
                        <i class="fas fa-trash"></i>
                    </button>
                </div>
            </td>
        </tr>
    `;
}

/**
 * Get stock status badge
 * @param {number} stock - Stock quantity
 * @returns {string} HTML string for stock badge
 */
function getStockBadge(stock) {
    const stockNum = parseInt(stock) || 0;

    if (stockNum === 0) {
        return '<span class="badge bg-danger">Out of Stock</span>';
    } else if (stockNum <= 5) {
        return '<span class="badge bg-warning">Low Stock</span>';
    } else {
        return '<span class="badge bg-success">In Stock</span>';
    }
}

/**
 * Update product count display
 * @param {number} total - Total number of products
 */
export function updateProductCount(total) {
    const countElement = $('.product-count');
    if (countElement.length) {
        countElement.text(`All Products (${total.toLocaleString()})`);
    } else {
        // If no specific element found, update the h5 with product count
        $('h5:contains("All Products")').text(`All Products (${total.toLocaleString()})`);
    }
}

/**
 * Populate category filter dropdown
 * @param {Object} categories - Categories object with id:name pairs
 */
export function populateCategoryFilter(categories) {
    const categorySelect = $('#category-filter');
    if (!categorySelect.length) return;

    // Clear existing options except "All Categories"
    categorySelect.find('option:not(:first)').remove();

    // Add category options
    Object.entries(categories).forEach(([id, name]) => {
        categorySelect.append(`<option value="${id}">${name}</option>`);
    });
}

/**
 * Populate code prefix filter dropdown
 * @param {Array} codePrefixes - Array of code prefixes
 */
export function populateCodePrefixFilter(codePrefixes) {
    const prefixSelect = $('#code-prefix-filter');
    if (!prefixSelect.length || !Array.isArray(codePrefixes)) return;

    // Clear existing options except "All Prefixes"
    prefixSelect.find('option:not(:first)').remove();

    // Add prefix options
    codePrefixes.forEach(prefix => {
        prefixSelect.append(`<option value="${prefix}">${prefix}</option>`);
    });
}

/**
 * Set price range information and input limits
 * @param {Object} priceRange - Price range object with min/max values
 */
export function setPriceRangeInfo(priceRange) {
    if (!priceRange || typeof priceRange.min === 'undefined' || typeof priceRange.max === 'undefined') return;

    // Update price range info display
    const priceInfo = $('#price-range-info');
    const minFormatted = formatPrice(priceRange.min);
    const maxFormatted = formatPrice(priceRange.max);
    priceInfo.text(`Price range: ${minFormatted} - ${maxFormatted}`);

    // Set input placeholders and limits
    $('#min-price').attr({
        'placeholder': priceRange.min,
        'min': priceRange.min,
        'max': priceRange.max
    });

    $('#max-price').attr({
        'placeholder': priceRange.max,
        'min': priceRange.min,
        'max': priceRange.max
    });
}

/**
 * Update active filter tags display
 * @param {Object} filters - Current active filters
 * @param {Object} filterOptions - Available filter options for display names
 */
export function updateFilterTags(filters, filterOptions = {}) {
    const filterTagsContainer = $('#filter-tags');
    const activeFiltersRow = $('#active-filters');

    const tags = [];

    // Search filter
    if (filters.search) {
        tags.push(createFilterTag('search', `Search: "${filters.search}"`, 'search'));
    }

    // Category filter
    if (filters.category_id && filterOptions.categories) {
        const categoryName = filterOptions.categories[filters.category_id];
        tags.push(createFilterTag('category_id', `Category: ${categoryName}`, 'category_id'));
    }

    // Status filters
    if (filters.is_active === true) {
        tags.push(createFilterTag('is_active', 'Status: Active', 'is_active'));
    } else if (filters.is_active === false) {
        tags.push(createFilterTag('is_active', 'Status: Inactive', 'is_active'));
    }

    // Code prefix filter
    if (filters.code_prefix) {
        tags.push(createFilterTag('code_prefix', `Prefix: ${filters.code_prefix}`, 'code_prefix'));
    }

    // Price range filter
    if (filters.min_price || filters.max_price) {
        let priceText = 'Price: ';
        if (filters.min_price && filters.max_price) {
            priceText += `${formatPrice(filters.min_price)} - ${formatPrice(filters.max_price)}`;
        } else if (filters.min_price) {
            priceText += `≥ ${formatPrice(filters.min_price)}`;
        } else if (filters.max_price) {
            priceText += `≤ ${formatPrice(filters.max_price)}`;
        }
        tags.push(createFilterTag('price_range', priceText, 'price_range'));
    }

    // Update display
    filterTagsContainer.html(tags.join(''));

    if (tags.length > 0) {
        activeFiltersRow.show();
    } else {
        activeFiltersRow.hide();
    }
}

/**
 * Create a filter tag element
 * @param {string} type - Filter type
 * @param {string} text - Display text
 * @param {string} removeAction - Action to trigger when removing
 * @returns {string} HTML string for filter tag
 */
function createFilterTag(type, text, removeAction) {
    return `
        <span class="badge bg-primary me-1 mb-1">
            ${text}
            <button type="button" class="btn-close btn-close-white btn-sm ms-1"
                    data-filter-remove="${removeAction}" aria-label="Remove filter"></button>
        </span>
    `;
}

/**
 * Get stock badge class for styling
 * @param {number} stock - Stock quantity
 * @returns {string} Bootstrap badge class
 */
function getStockBadgeClass(stock) {
    const stockNum = parseInt(stock) || 0;

    if (stockNum === 0) {
        return 'bg-light text-dark';
    } else if (stockNum <= 5) {
        return 'bg-warning';
    } else {
        return 'bg-light text-dark';
    }
}

/**
 * Format date for display
 * @param {string} dateString - ISO date string
 * @returns {string} Formatted date
 */
function formatDate(dateString) {
    if (!dateString) return 'N/A';

    try {
        const date = new Date(dateString);
        return date.toLocaleDateString('en-US', {
            year: 'numeric',
            month: 'short',
            day: 'numeric'
        });
    } catch (error) {
        return 'N/A';
    }
}

/**
 * Truncate text to specified length
 * @param {string} text - Text to truncate
 * @param {number} maxLength - Maximum length
 * @returns {string} Truncated text
 */
function truncateText(text, maxLength) {
    if (!text || text.length <= maxLength) return text;
    return text.substring(0, maxLength) + '...';
}

/**
 * Update sort indicators in table headers
 * @param {string} sortBy - Current sort field
 * @param {string} sortOrder - Current sort order
 */
export function updateSortIndicators(sortBy, sortOrder) {
    // Reset all sort indicators
    $('.sortable i').removeClass('fa-sort-up fa-sort-down').addClass('fa-sort text-muted');

    // Update active sort indicator
    const activeHeader = $(`.sortable[data-sort="${sortBy}"] i`);
    activeHeader.removeClass('fa-sort text-muted');

    if (sortOrder === 'asc') {
        activeHeader.addClass('fa-sort-up text-primary');
    } else {
        activeHeader.addClass('fa-sort-down text-primary');
    }
}
