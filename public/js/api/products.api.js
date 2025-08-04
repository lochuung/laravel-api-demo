/**
 * Product API functions using the configured Axios instance
 */

/**
 * Get all products with optional filtering and pagination
 * @param {Object} params - Query parameters
 * @returns {Promise} Axios response promise
 */
export async function getProducts(params = {}) {
    return await api.get('/products', { params });
}

/**
 * Get filter options for products (categories, etc.)
 * This is a fallback endpoint - if not available, we'll use categories endpoint
 * @returns {Promise} Axios response promise
 */
export async function getProductFilterOptions() {
    try {
        return await api.get('/products/filter-options');
    } catch (error) {
        // Fallback: try to get categories separately
        return await api.get('/categories');
    }
}

/**
 * Get a single product by ID
 * @param {number} id - Product ID
 * @returns {Promise} Axios response promise
 */
export async function getProduct(id) {
    return await api.get(`/products/${id}`);
}

/**
 * Create a new product
 * @param {Object} productData - Product data
 * @returns {Promise} Axios response promise
 */
export async function createProduct(productData) {
    return await api.post('/products', productData);
}

/**
 * Update an existing product
 * @param {number} id - Product ID
 * @param {Object} productData - Updated product data
 * @returns {Promise} Axios response promise
 */
export async function updateProduct(id, productData) {
    return await api.put(`/products/${id}`, productData);
}

/**
 * Delete a product
 * @param {number} id - Product ID
 * @returns {Promise} Axios response promise
 */
export async function deleteProduct(id) {
    return await api.delete(`/products/${id}`);
}
