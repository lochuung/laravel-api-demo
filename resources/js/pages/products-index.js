import { 
    showSuccessMessage, 
    showErrorMessage,
    showLoadingState,
    hideLoadingState,
    formatCurrency,
    formatDateTime,
    debounce,
    extractCodePrefix
} from '../utils/common.js';
import { 
    renderPagination,
    updateProductCount,
    getPaginationParams,
    updateURLParams
} from '../utils/pagination.js';
import { getProducts, getProductFilterOptions, deleteProduct } from '../api/products.api.js';

// State management
let currentFilters = {};
let currentSort = { by: 'created_at', order: 'desc' };
let currentView = 'grid';
let products = [];
let categories = {};
let codePrefixes = [];

document.addEventListener('DOMContentLoaded', async () => {
    // Load initial data
    await initializeFilters();
    await loadProducts();
    
    // Setup event listeners
    setupEventListeners();
    
    // Load state from URL
    loadStateFromURL();
});

async function initializeFilters() {
    try {
        const response = await getProductFilterOptions();
        const data = response.data.data;
        
        // Populate categories
        if (data.categories) {
            categories = data.categories;
            populateCategoryFilter();
        }
        
        // Extract code prefixes (this would come from API in real implementation)
        // For now, we'll populate it when products are loaded
        
    } catch (error) {
        console.error('Error loading filter options:', error);
        showErrorMessage('Failed to load filter options');
    }
}

function populateCategoryFilter() {
    const categorySelect = document.getElementById('category-filter');
    categorySelect.innerHTML = '<option value="all">All Categories</option>';
    
    Object.entries(categories).forEach(([id, name]) => {
        const option = document.createElement('option');
        option.value = id;
        option.textContent = name;
        categorySelect.appendChild(option);
    });
}

function setupEventListeners() {
    // Search
    const searchInput = document.getElementById('search-input');
    const searchBtn = document.getElementById('search-btn');
    
    const debouncedSearch = debounce(() => {
        currentFilters.search = searchInput.value.trim();
        loadProducts();
    }, 500);
    
    searchInput.addEventListener('input', debouncedSearch);
    searchBtn.addEventListener('click', () => {
        currentFilters.search = searchInput.value.trim();
        loadProducts();
    });
    
    // Filters
    document.getElementById('category-filter').addEventListener('change', (e) => {
        currentFilters.category_id = e.target.value === 'all' ? null : e.target.value;
        loadProducts();
    });
    
    document.getElementById('status-filter').addEventListener('change', (e) => {
        currentFilters.is_active = e.target.value === 'all' ? null : e.target.value === 'active';
        loadProducts();
    });
    
    document.getElementById('code-prefix-filter').addEventListener('change', (e) => {
        currentFilters.code_prefix = e.target.value === 'all' ? null : e.target.value;
        loadProducts();
    });
    
    // Price filter modal
    document.getElementById('price-filter-btn').addEventListener('click', () => {
        document.getElementById('price-filter-modal').classList.remove('hidden');
    });
    
    document.getElementById('close-price-modal').addEventListener('click', () => {
        document.getElementById('price-filter-modal').classList.add('hidden');
    });
    
    document.getElementById('cancel-price-filter').addEventListener('click', () => {
        document.getElementById('price-filter-modal').classList.add('hidden');
    });
    
    document.getElementById('apply-price-filter').addEventListener('click', () => {
        const minPrice = document.getElementById('min-price').value;
        const maxPrice = document.getElementById('max-price').value;
        
        currentFilters.min_price = minPrice || null;
        currentFilters.max_price = maxPrice || null;
        
        document.getElementById('price-filter-modal').classList.add('hidden');
        loadProducts();
        updateActiveFilters();
    });
    
    document.getElementById('clear-price-filter').addEventListener('click', () => {
        document.getElementById('min-price').value = '';
        document.getElementById('max-price').value = '';
        currentFilters.min_price = null;
        currentFilters.max_price = null;
        
        document.getElementById('price-filter-modal').classList.add('hidden');
        loadProducts();
        updateActiveFilters();
    });
    
    // Sort controls
    document.getElementById('sort-by').addEventListener('change', (e) => {
        currentSort.by = e.target.value;
        loadProducts();
    });
    
    document.getElementById('sort-order').addEventListener('click', (e) => {
        const button = e.target.closest('button');
        const currentOrder = button.dataset.order;
        const newOrder = currentOrder === 'desc' ? 'asc' : 'desc';
        
        button.dataset.order = newOrder;
        currentSort.order = newOrder;
        
        // Update icon
        const icon = button.querySelector('svg');
        icon.innerHTML = newOrder === 'desc' 
            ? '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4h13M3 8h9m-9 4h6m4 0l4-4m0 0l4 4m-4-4v12"/>'
            : '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4h13M3 8h9m-9 4h9m5-4v12m0 0l-4-4m4 4l4-4"/>';
        
        loadProducts();
    });
    
    // View toggle
    document.getElementById('grid-view').addEventListener('click', () => {
        if (currentView !== 'grid') {
            currentView = 'grid';
            updateViewToggle();
            renderProducts();
        }
    });
    
    document.getElementById('list-view').addEventListener('click', () => {
        if (currentView !== 'list') {
            currentView = 'list';
            updateViewToggle();
            renderProducts();
        }
    });
    
    // Clear all filters
    document.getElementById('clear-all-filters').addEventListener('click', () => {
        clearAllFilters();
    });
}

function updateViewToggle() {
    const gridBtn = document.getElementById('grid-view');
    const listBtn = document.getElementById('list-view');
    const gridContainer = document.getElementById('grid-container');
    const listContainer = document.getElementById('list-container');
    
    if (currentView === 'grid') {
        gridBtn.classList.add('bg-white', 'text-blue-600', 'shadow-sm');
        gridBtn.classList.remove('text-gray-600', 'hover:text-gray-900');
        listBtn.classList.remove('bg-white', 'text-blue-600', 'shadow-sm');
        listBtn.classList.add('text-gray-600', 'hover:text-gray-900');
        
        gridContainer.classList.remove('hidden');
        listContainer.classList.add('hidden');
    } else {
        listBtn.classList.add('bg-white', 'text-blue-600', 'shadow-sm');
        listBtn.classList.remove('text-gray-600', 'hover:text-gray-900');
        gridBtn.classList.remove('bg-white', 'text-blue-600', 'shadow-sm');
        gridBtn.classList.add('text-gray-600', 'hover:text-gray-900');
        
        listContainer.classList.remove('hidden');
        gridContainer.classList.add('hidden');
    }
}

async function loadProducts() {
    try {
        showLoadingState();
        
        const params = {
            ...currentFilters,
            sort_by: currentSort.by,
            sort_order: currentSort.order,
            page: currentFilters.page || 1,
            per_page: 12
        };
        
        // Clean up null/undefined params
        Object.keys(params).forEach(key => {
            if (params[key] === null || params[key] === undefined || params[key] === '') {
                delete params[key];
            }
        });
        
        const response = await getProducts(params);
        products = response.data.data;
        
        // Update product count
        updateProductCount(response.data.meta?.total || products.length);
        
        // Extract code prefixes for filter
        extractCodePrefixes();
        
        // Render products
        renderProducts();
        
        // Render pagination
        if (response.data.meta) {
            renderPagination(
                response.data.meta.current_page,
                response.data.meta.last_page,
                (page) => {
                    currentFilters.page = page;
                    loadProducts();
                }
            );
        }
        
        // Update active filters display
        updateActiveFilters();
        
        // Update URL
        updateURLParams(params);
        
    } catch (error) {
        console.error('Error loading products:', error);
        showErrorMessage('Failed to load products');
    } finally {
        hideLoadingState();
    }
}

function extractCodePrefixes() {
    const prefixes = new Set();
    products.forEach(product => {
        if (product.code || product.base_sku) {
            const prefix = extractCodePrefix(product.code || product.base_sku);
            if (prefix) prefixes.add(prefix);
        }
    });
    
    codePrefixes = Array.from(prefixes);
    populateCodePrefixFilter();
}

function populateCodePrefixFilter() {
    const prefixSelect = document.getElementById('code-prefix-filter');
    prefixSelect.innerHTML = '<option value="all">All Prefixes</option>';
    
    codePrefixes.forEach(prefix => {
        const option = document.createElement('option');
        option.value = prefix;
        option.textContent = prefix;
        prefixSelect.appendChild(option);
    });
}

function renderProducts() {
    if (currentView === 'grid') {
        renderGridView();
    } else {
        renderListView();
    }
}

function renderGridView() {
    const container = document.getElementById('grid-container');
    
    if (!products || products.length === 0) {
        container.innerHTML = `
            <div class="col-span-full text-center py-12">
                <svg class="w-16 h-16 mx-auto text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                </svg>
                <h3 class="text-lg font-medium text-gray-900 mb-2">No products found</h3>
                <p class="text-gray-500">Try adjusting your search or filter criteria.</p>
            </div>
        `;
        return;
    }
    
    const productsHTML = products.map(product => `
        <div class="bg-white rounded-lg border border-gray-200 overflow-hidden hover:shadow-lg transition-shadow">
            <div class="aspect-square bg-gray-100 overflow-hidden">
                ${product.image 
                    ? `<img src="${product.image}" alt="${product.name}" class="w-full h-full object-cover">`
                    : `<div class="w-full h-full flex items-center justify-center">
                         <svg class="w-16 h-16 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                             <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                         </svg>
                       </div>`
                }
            </div>
            <div class="p-4">
                <div class="flex justify-between items-start mb-2">
                    <h3 class="font-semibold text-gray-900 truncate">${product.name}</h3>
                    ${getStatusBadge(product)}
                </div>
                <p class="text-sm text-gray-600 mb-2 line-clamp-2">${product.description || 'No description'}</p>
                <div class="flex justify-between items-center mb-3">
                    <span class="text-lg font-bold text-green-600">${formatCurrency(product.price)}</span>
                    <span class="text-sm text-gray-500">Stock: ${product.stock}</span>
                </div>
                <div class="flex gap-2">
                    <a href="/products/${product.id}" class="flex-1 text-center px-3 py-2 bg-blue-600 text-white text-sm rounded-lg hover:bg-blue-700 transition-colors">
                        View
                    </a>
                    <a href="/products/${product.id}/edit" class="flex-1 text-center px-3 py-2 border border-gray-300 text-gray-700 text-sm rounded-lg hover:bg-gray-50 transition-colors">
                        Edit
                    </a>
                </div>
            </div>
        </div>
    `).join('');
    
    container.innerHTML = `<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">${productsHTML}</div>`;
}

function renderListView() {
    const container = document.getElementById('list-container');
    const tbody = container.querySelector('tbody');
    
    if (!products || products.length === 0) {
        tbody.innerHTML = `
            <tr>
                <td colspan="8" class="px-6 py-12 text-center">
                    <svg class="w-16 h-16 mx-auto text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                    </svg>
                    <h3 class="text-lg font-medium text-gray-900 mb-2">No products found</h3>
                    <p class="text-gray-500">Try adjusting your search or filter criteria.</p>
                </td>
            </tr>
        `;
        return;
    }
    
    const rowsHTML = products.map(product => `
        <tr class="hover:bg-gray-50">
            <td class="px-6 py-4">
                <div class="w-16 h-16 bg-gray-100 rounded-lg overflow-hidden">
                    ${product.image 
                        ? `<img src="${product.image}" alt="${product.name}" class="w-full h-full object-cover">`
                        : `<div class="w-full h-full flex items-center justify-center">
                             <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                 <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                             </svg>
                           </div>`
                    }
                </div>
            </td>
            <td class="px-6 py-4">
                <div class="text-sm font-medium text-gray-900">${product.name}</div>
                <div class="text-sm text-gray-500 truncate max-w-xs">${product.description || 'No description'}</div>
            </td>
            <td class="px-6 py-4 text-sm text-gray-900">${product.code || product.base_sku || '-'}</td>
            <td class="px-6 py-4 text-sm font-medium text-green-600">${formatCurrency(product.price)}</td>
            <td class="px-6 py-4 text-sm text-gray-900">${product.stock}</td>
            <td class="px-6 py-4">${getStatusBadge(product)}</td>
            <td class="px-6 py-4 text-sm text-gray-500">${formatDateTime(product.created_at)}</td>
            <td class="px-6 py-4 text-sm font-medium">
                <div class="flex gap-2">
                    <a href="/products/${product.id}" class="text-blue-600 hover:text-blue-900">View</a>
                    <a href="/products/${product.id}/edit" class="text-gray-600 hover:text-gray-900">Edit</a>
                </div>
            </td>
        </tr>
    `).join('');
    
    tbody.innerHTML = rowsHTML;
}

function getStatusBadge(product) {
    if (!product.is_active) {
        return '<span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">Inactive</span>';
    }
    
    if (product.stock <= 0) {
        return '<span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">Out of Stock</span>';
    }
    
    if (product.stock <= (product.min_stock || 0)) {
        return '<span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">Low Stock</span>';
    }
    
    return '<span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">In Stock</span>';
}

function updateActiveFilters() {
    const activeFiltersContainer = document.getElementById('active-filters');
    const filterTags = document.getElementById('filter-tags');
    
    const activeFilters = [];
    
    // Build filter tags
    if (currentFilters.search) {
        activeFilters.push({ key: 'search', label: `Search: ${currentFilters.search}`, value: currentFilters.search });
    }
    
    if (currentFilters.category_id && categories[currentFilters.category_id]) {
        activeFilters.push({ key: 'category_id', label: `Category: ${categories[currentFilters.category_id]}`, value: currentFilters.category_id });
    }
    
    if (currentFilters.is_active !== null && currentFilters.is_active !== undefined) {
        activeFilters.push({ key: 'is_active', label: `Status: ${currentFilters.is_active ? 'Active' : 'Inactive'}`, value: currentFilters.is_active });
    }
    
    if (currentFilters.code_prefix) {
        activeFilters.push({ key: 'code_prefix', label: `Prefix: ${currentFilters.code_prefix}`, value: currentFilters.code_prefix });
    }
    
    if (currentFilters.min_price || currentFilters.max_price) {
        const priceLabel = `Price: ${currentFilters.min_price || '0'} - ${currentFilters.max_price || '∞'}`;
        activeFilters.push({ key: 'price', label: priceLabel, value: 'price' });
    }
    
    if (activeFilters.length === 0) {
        activeFiltersContainer.classList.add('hidden');
        return;
    }
    
    const tagsHTML = activeFilters.map(filter => `
        <span class="inline-flex items-center gap-1 px-3 py-1 bg-blue-100 text-blue-800 text-sm rounded-full">
            ${filter.label}
            <button type="button" class="ml-1 text-blue-600 hover:text-blue-900" onclick="removeFilter('${filter.key}')">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </span>
    `).join('');
    
    filterTags.innerHTML = tagsHTML;
    activeFiltersContainer.classList.remove('hidden');
}

// Global function for removing filters
window.removeFilter = function(filterKey) {
    switch (filterKey) {
        case 'search':
            currentFilters.search = null;
            document.getElementById('search-input').value = '';
            break;
        case 'category_id':
            currentFilters.category_id = null;
            document.getElementById('category-filter').value = 'all';
            break;
        case 'is_active':
            currentFilters.is_active = null;
            document.getElementById('status-filter').value = 'all';
            break;
        case 'code_prefix':
            currentFilters.code_prefix = null;
            document.getElementById('code-prefix-filter').value = 'all';
            break;
        case 'price':
            currentFilters.min_price = null;
            currentFilters.max_price = null;
            document.getElementById('min-price').value = '';
            document.getElementById('max-price').value = '';
            break;
    }
    
    loadProducts();
};

function clearAllFilters() {
    currentFilters = {};
    
    document.getElementById('search-input').value = '';
    document.getElementById('category-filter').value = 'all';
    document.getElementById('status-filter').value = 'all';
    document.getElementById('code-prefix-filter').value = 'all';
    document.getElementById('min-price').value = '';
    document.getElementById('max-price').value = '';
    
    loadProducts();
}

function loadStateFromURL() {
    const params = getPaginationParams();
    currentFilters = { ...params };
    
    // Update form fields
    if (params.search) document.getElementById('search-input').value = params.search;
    if (params.category_id) document.getElementById('category-filter').value = params.category_id;
    if (params.is_active !== undefined) document.getElementById('status-filter').value = params.is_active ? 'active' : 'inactive';
    if (params.code_prefix) document.getElementById('code-prefix-filter').value = params.code_prefix;
    if (params.min_price) document.getElementById('min-price').value = params.min_price;
    if (params.max_price) document.getElementById('max-price').value = params.max_price;
    if (params.sort_by) {
        document.getElementById('sort-by').value = params.sort_by;
        currentSort.by = params.sort_by;
    }
    if (params.sort_order) {
        document.getElementById('sort-order').dataset.order = params.sort_order;
        currentSort.order = params.sort_order;
    }
}
