/**
 * Pagination utility for rendering pagination controls with Tailwind CSS
 * @param {number} currentPage - Current active page
 * @param {number} lastPage - Total number of pages
 * @param {Function} onPageChange - Callback function when page changes
 * @returns {void}
 */
export function renderPagination(currentPage, lastPage, onPageChange = null) {
    const container = document.getElementById('pagination');
    if (!container) return;

    container.innerHTML = '';

    // Don't show pagination for single page
    if (lastPage <= 1) return;

    const maxVisible = 5;
    let startPage = Math.max(currentPage - Math.floor(maxVisible / 2), 1);
    let endPage = startPage + maxVisible - 1;

    if (endPage > lastPage) {
        endPage = lastPage;
        startPage = Math.max(endPage - maxVisible + 1, 1);
    }

    // Previous button
    const prevDisabled = currentPage === 1;
    const prevButton = createPaginationButton('‹ Previous', currentPage - 1, prevDisabled, onPageChange);
    container.appendChild(prevButton);

    // First page + ellipsis if needed
    if (startPage > 1) {
        const firstButton = createPaginationButton('1', 1, false, onPageChange);
        container.appendChild(firstButton);
        
        if (startPage > 2) {
            const ellipsis = createPaginationEllipsis();
            container.appendChild(ellipsis);
        }
    }

    // Page numbers
    for (let page = startPage; page <= endPage; page++) {
        const isActive = page === currentPage;
        const pageButton = createPaginationButton(page.toString(), page, false, onPageChange, isActive);
        container.appendChild(pageButton);
    }

    // Last page + ellipsis if needed
    if (endPage < lastPage) {
        if (endPage < lastPage - 1) {
            const ellipsis = createPaginationEllipsis();
            container.appendChild(ellipsis);
        }
        
        const lastButton = createPaginationButton(lastPage.toString(), lastPage, false, onPageChange);
        container.appendChild(lastButton);
    }

    // Next button
    const nextDisabled = currentPage === lastPage;
    const nextButton = createPaginationButton('Next ›', currentPage + 1, nextDisabled, onPageChange);
    container.appendChild(nextButton);
}

/**
 * Create a pagination button element
 * @param {string} text - Button text
 * @param {number} page - Page number
 * @param {boolean} disabled - Whether button is disabled
 * @param {Function} onPageChange - Click handler
 * @param {boolean} active - Whether button is active
 * @returns {HTMLElement}
 */
function createPaginationButton(text, page, disabled = false, onPageChange = null, active = false) {
    const li = document.createElement('li');
    const link = document.createElement('a');
    
    link.textContent = text;
    link.href = '#';
    link.dataset.page = page;
    
    // Base classes
    let classes = 'relative inline-flex items-center px-3 py-2 text-sm font-medium transition-colors duration-200 ';
    
    if (disabled) {
        classes += 'bg-gray-100 text-gray-400 cursor-not-allowed border border-gray-300';
        link.setAttribute('tabindex', '-1');
    } else if (active) {
        classes += 'bg-blue-600 text-white border border-blue-600 hover:bg-blue-700 z-10';
    } else {
        classes += 'bg-white text-gray-700 border border-gray-300 hover:bg-gray-50 hover:text-gray-900';
    }
    
    link.className = classes;
    
    // Add click handler
    if (!disabled && onPageChange) {
        link.addEventListener('click', (e) => {
            e.preventDefault();
            onPageChange(page);
        });
    }
    
    li.appendChild(link);
    return li;
}

/**
 * Create an ellipsis element for pagination
 * @returns {HTMLElement}
 */
function createPaginationEllipsis() {
    const li = document.createElement('li');
    const span = document.createElement('span');
    
    span.textContent = '...';
    span.className = 'relative inline-flex items-center px-3 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 cursor-default';
    
    li.appendChild(span);
    return li;
}

/**
 * Update user count display
 * @param {number} total - Total number of users
 * @returns {void}
 */
export function updateUserCount(total) {
    const countElement = document.querySelector('.card h3');
    if (countElement && countElement.textContent.includes('Users')) {
        countElement.textContent = `All Users (${total.toLocaleString()})`;
    }
}

/**
 * Get pagination parameters from URL or default values
 * @returns {Object} Pagination parameters
 */
export function getPaginationParams() {
    const urlParams = new URLSearchParams(window.location.search);
    return {
        page: parseInt(urlParams.get('page')) || 1,
        per_page: parseInt(urlParams.get('per_page')) || 15,
        search: urlParams.get('search') || '',
        role: urlParams.get('role') || 'all'
    };
}

/**
 * Update URL with pagination parameters
 * @param {Object} params - Pagination parameters
 * @returns {void}
 */
export function updateURLParams(params) {
    const url = new URL(window.location);
    
    Object.keys(params).forEach(key => {
        if (params[key] && params[key] !== 'all') {
            url.searchParams.set(key, params[key]);
        } else {
            url.searchParams.delete(key);
        }
    });
    
    // Update URL without refreshing page
    window.history.replaceState({}, '', url);
}

/**
 * Update product count display
 */
export function updateProductCount(total) {
    const countElement = document.getElementById('product-count');
    if (countElement) {
        countElement.textContent = total.toLocaleString();
    }

    const titleElement = document.querySelector('.products-title');
    if (titleElement) {
        titleElement.textContent = `Products (${total.toLocaleString()})`;
    }
}
