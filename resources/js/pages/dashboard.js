import { 
    showLoadingState, 
    hideLoadingState, 
    showErrorMessage, 
    waitForUser, 
    formatCurrency 
} from '../utils/common.js';
import api from '../api/api.js';

document.addEventListener('DOMContentLoaded', async () => {
    // Wait for user data to be available
    waitForUser(async (user) => {
        // Update user name in the welcome message
        const userNameElement = document.querySelector('.user-name');
        if (userNameElement) {
            userNameElement.textContent = user.name;
        }
        
        // Load dashboard data
        await getDashboardData();
    });
});

const getDashboardData = async () => {
    try {
        showLoadingState();
        const response = await api.get('/dashboard');
        
        if (response.status === 200) {
            const data = response.data?.data ?? {};
            renderStats(data);
            renderUsers(data.recent_users ?? []);
            renderOrders(data.recent_orders ?? []);
        } else {
            console.error('Failed to fetch dashboard data:', response.statusText);
            showErrorMessage('Failed to load dashboard data');
        }
    } catch (error) {
        showErrorMessage(error.response?.data?.message || 'An error occurred while fetching dashboard data.');
        console.error('Error fetching dashboard data:', error);
    } finally {
        hideLoadingState();
    }
};

const renderStats = (data) => {
    // Update stat cards
    const totalUsersEl = document.getElementById('total-users');
    const totalProductsEl = document.getElementById('total-products');
    const totalOrdersEl = document.getElementById('total-orders');
    const monthlyRevenueEl = document.getElementById('monthly-revenue');

    if (totalUsersEl) totalUsersEl.textContent = data.total_users ?? 0;
    if (totalProductsEl) totalProductsEl.textContent = data.total_products ?? 0;
    if (totalOrdersEl) totalOrdersEl.textContent = data.total_orders ?? 0;
    if (monthlyRevenueEl) monthlyRevenueEl.textContent = formatCurrency(data.monthly_revenue ?? 0);
};

const renderUsers = (users = []) => {
    const container = document.querySelector('.recent-users');
    if (!container) return;
    
    container.innerHTML = '';
    
    if (!users.length) {
        container.innerHTML = '<div class="p-4 text-center text-gray-500">No recent users</div>';
        return;
    }

    users.forEach(user => {
        const userElement = document.createElement('div');
        userElement.className = 'flex items-center p-4 hover:bg-gray-50 transition-colors';
        userElement.innerHTML = `
            <div class="flex-shrink-0 w-10 h-10 mr-3">
                <img src="${user.profile_picture || 'https://via.placeholder.com/40'}" 
                     class="w-10 h-10 rounded-full border border-gray-200" 
                     alt="${user.name}">
            </div>
            <div class="flex-1 min-w-0">
                <h6 class="text-sm font-medium text-gray-900 truncate">${user.name}</h6>
                <p class="text-sm text-gray-500 truncate">${user.email}</p>
            </div>
        `;
        container.appendChild(userElement);
    });
};

const renderOrders = (orders = []) => {
    const container = document.querySelector('.recent-orders');
    if (!container) return;
    
    container.innerHTML = '';
    
    if (!orders.length) {
        container.innerHTML = '<div class="p-4 text-center text-gray-500">No recent orders</div>';
        return;
    }

    orders.forEach(order => {
        const orderElement = document.createElement('div');
        orderElement.className = 'flex items-center justify-between p-4 hover:bg-gray-50 transition-colors';
        
        const statusClass = getStatusClass(order.status);
        const statusColors = {
            'success': 'bg-green-100 text-green-800',
            'warning': 'bg-amber-100 text-amber-800',
            'info': 'bg-blue-100 text-blue-800',
            'danger': 'bg-red-100 text-red-800',
            'secondary': 'bg-gray-100 text-gray-800'
        };
        
        orderElement.innerHTML = `
            <div class="flex-1">
                <h6 class="text-sm font-medium text-gray-900">Order #${order.order_number}</h6>
                <p class="text-sm text-gray-500">
                    ${order.user_name} - ${parseFloat(order.total).toLocaleString('vi-VN')} ₫
                </p>
            </div>
            <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full capitalize ${statusColors[statusClass] || statusColors.secondary}">
                ${order.status}
            </span>
        `;
        container.appendChild(orderElement);
    });
};

const getStatusClass = (status) => {
    const statusMap = {
        'completed': 'success',
        'delivered': 'success', 
        'shipped': 'info',
        'processing': 'warning',
        'pending': 'warning',
        'cancelled': 'danger',
        'refunded': 'danger'
    };
    
    return statusMap[status?.toLowerCase()] || 'secondary';
};
