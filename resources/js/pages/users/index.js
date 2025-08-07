import { 
    showLoadingState, 
    hideLoadingState, 
    showErrorMessage,
    showSuccessMessage,
    waitForUser,
    debounce
} from '../../utils/common.js';
import { renderPagination, updateUserCount } from '../../utils/pagination.js';
import api from '../../api/api.js';

const PER_PAGE = 5;
let currentFilters = {
    page: 1,
    per_page: PER_PAGE
};

document.addEventListener('DOMContentLoaded', () => {
    // Wait for user data to be available
    waitForUser(async () => {
        await loadUsers(currentFilters);
        initializeSearchAndFilters();
    });
});

const loadUsers = async (params = {}) => {
    try {
        showLoadingState();
        
        // Show loading in table
        const tbody = document.getElementById('user-table-body');
        tbody.innerHTML = `
            <tr>
                <td colspan="7" class="px-6 py-8 text-center text-gray-500">
                    <i class="fas fa-spinner fa-spin text-2xl mb-2"></i>
                    <p>Loading users...</p>
                </td>
            </tr>
        `;
        
        const response = await api.get('/users', { params });
        
        if (response.status === 200) {
            const { data, meta } = response.data;
            renderUsers(data || []);
            
            // Render pagination if meta data exists
            if (meta) {
                renderPagination(meta.current_page, meta.last_page, handlePageChange);
                updateUserCount(meta.total);
            }
        } else {
            showErrorMessage('Failed to load users');
        }
    } catch (error) {
        showErrorMessage(error.response?.data?.message || 'Failed to load users');
        console.error('Error loading users:', error);
        
        // Show error in table
        const tbody = document.getElementById('user-table-body');
        tbody.innerHTML = `
            <tr>
                <td colspan="7" class="px-6 py-8 text-center text-red-500">
                    <i class="fas fa-exclamation-triangle text-2xl mb-2"></i>
                    <p>Error loading users. Please try again.</p>
                </td>
            </tr>
        `;
    } finally {
        hideLoadingState();
    }
};

const renderUsers = (users) => {
    const tbody = document.getElementById('user-table-body');
    
    if (!users.length) {
        tbody.innerHTML = `
            <tr>
                <td colspan="7" class="px-6 py-8 text-center text-gray-500">No users found.</td>
            </tr>
        `;
        return;
    }

    tbody.innerHTML = users.map(user => `
        <tr class="hover:bg-gray-50 transition-colors">
            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">${user.id}</td>
            <td class="px-6 py-4 whitespace-nowrap">
                <img src="${user.profile_picture || 'https://via.placeholder.com/40'}" 
                     alt="${user.name}" 
                     class="w-10 h-10 rounded-full border-2 border-gray-200">
            </td>
            <td class="px-6 py-4 whitespace-nowrap">
                <div class="text-sm font-medium text-gray-900">${user.name}</div>
                <div class="text-sm text-gray-500">${user.role || 'User'}</div>
            </td>
            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">${user.email}</td>
            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                ${new Date(user.created_at).toLocaleDateString()}
            </td>
            <td class="px-6 py-4 whitespace-nowrap">
                <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full ${
                    user.is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800'
                }">
                    ${user.is_active ? 'Active' : 'Inactive'}
                </span>
            </td>
            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium space-x-2">
                <a href="/users/${user.id}" 
                   class="text-blue-600 hover:text-blue-900 transition-colors">
                    <i class="fas fa-eye"></i>
                </a>
                <a href="/users/${user.id}/edit" 
                   class="text-amber-600 hover:text-amber-900 transition-colors">
                    <i class="fas fa-edit"></i>
                </a>
                <button onclick="deleteUser(${user.id})" 
                        class="text-red-600 hover:text-red-900 transition-colors">
                    <i class="fas fa-trash"></i>
                </button>
            </td>
        </tr>
    `).join('');
};

const initializeSearchAndFilters = () => {
    const searchInput = document.getElementById('search-input');
    const searchButton = document.getElementById('search-button');
    const roleFilter = document.getElementById('role-filter');
    const clearFilters = document.getElementById('clear-filters');

    // Debounced search functionality
    const debouncedSearch = debounce(() => {
        performSearch();
    }, 300);

    const performSearch = () => {
        const searchTerm = searchInput.value.trim();
        const role = roleFilter.value;
        
        // Update current filters
        currentFilters = {
            page: 1, // Reset to first page
            per_page: PER_PAGE,
            ...(searchTerm && { search: searchTerm }),
            ...(role && role !== 'all' && { role })
        };
        
        loadUsers(currentFilters);
    };

    // Search event listeners
    searchButton.addEventListener('click', performSearch);
    searchInput.addEventListener('keypress', (e) => {
        if (e.key === 'Enter') {
            performSearch();
        }
    });
    searchInput.addEventListener('input', debouncedSearch);

    roleFilter.addEventListener('change', performSearch);

    clearFilters.addEventListener('click', () => {
        searchInput.value = '';
        roleFilter.value = 'all';
        currentFilters = { page: 1, per_page: PER_PAGE };
        loadUsers(currentFilters);
    });
};

/**
 * Handle pagination page change
 * @param {number} page - Target page number
 */
const handlePageChange = (page) => {
    if (page < 1) return;
    
    currentFilters.page = page;
    loadUsers(currentFilters);
    
    // Scroll to top of table
    document.querySelector('.bg-white.rounded-xl')?.scrollIntoView({ 
        behavior: 'smooth', 
        block: 'start' 
    });
};

// Global function for delete action
window.deleteUser = async (userId) => {
    if (!confirm('Are you sure you want to delete this user? This action cannot be undone.')) {
        return;
    }

    try {
        showLoadingState();
        const response = await api.delete(`/users/${userId}`);
        
        if (response.status === 200) {
            showSuccessMessage('User deleted successfully');
            
            // Reload current page
            await loadUsers(currentFilters);
        }
    } catch (error) {
        showErrorMessage(error.response?.data?.message || 'Failed to delete user');
        console.error('Error deleting user:', error);
    } finally {
        hideLoadingState();
    }
};
