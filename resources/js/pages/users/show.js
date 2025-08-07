import {
    showLoadingState,
    hideLoadingState,
    showErrorMessage,
    showSuccessMessage,
    waitForUser, formatCurrency
} from '../../utils/common.js';
import api from '../../api/api.js';

let currentUserId = null;

document.addEventListener('DOMContentLoaded', () => {
    // Get user ID from URL
    const pathParts = window.location.pathname.split('/');
    currentUserId = pathParts[pathParts.length - 1]; // /users/{id}

    if (!currentUserId) {
        showErrorMessage('Invalid user ID');
        return;
    }

    // Wait for user data to be available
    waitForUser(async () => {
        await loadUserData();
        initializeButtons();
    });
});

const loadUserData = async () => {
    try {
        showLoadingState();
        const response = await api.get(`/users/${currentUserId}`);

        if (response.status === 200) {
            const user = response.data.data;
            populateUserData(user);
        }
    } catch (error) {
        showErrorMessage('Failed to load user data');
        console.error('Error loading user:', error);
    } finally {
        hideLoadingState();
    }
};

const populateUserData = (user) => {
    // Update avatar
    const avatar = document.getElementById('user-avatar');
    if (avatar) {
        avatar.src = user.profile_picture || 'https://via.placeholder.com/150';
        avatar.alt = user.name;
    }

    // Update name and email
    const nameEl = document.getElementById('user-name');
    const emailEl = document.getElementById('user-email');
    const statusEl = document.getElementById('user-status');

    if (nameEl) nameEl.textContent = user.name;
    if (emailEl) emailEl.textContent = user.email;

    if (statusEl) {
        statusEl.innerHTML = user.is_active
            ? '<i class="fas fa-check-circle mr-2 text-green-600"></i>Active'
            : '<i class="fas fa-times-circle mr-2 text-red-600"></i>Inactive';
        statusEl.className = `inline-flex items-center px-3 py-1 rounded-full text-sm font-medium ${
            user.is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800'
        }`;
    }

    // update the quick stats
    const totalOrdersEl = document.getElementById('total-orders');
    const totalSpentEl = document.getElementById('total-spent');

    if (totalOrdersEl) totalOrdersEl.textContent = user.orders_count || '0';
    if (totalSpentEl) totalSpentEl.textContent = user.total_spent ? formatCurrency(user.total_spent) : '0.00 đ';

    // Update personal info in the info tab
    const infoTab = document.getElementById('info');
    if (infoTab) {
        const infoItems = infoTab.querySelectorAll('.grid');

        // Update the displayed data in each grid item
        if (infoItems[0]) {
            const nameSpan = infoItems[0].querySelector('.sm\\:col-span-2 span');
            if (nameSpan) nameSpan.textContent = user.name;
        }

        if (infoItems[1]) {
            const emailSpan = infoItems[1].querySelector('.sm\\:col-span-2 span');
            const statusBadge = infoItems[1].querySelector('.inline-flex');
            if (emailSpan) emailSpan.textContent = user.email;
            if (statusBadge) {
                statusBadge.innerHTML = user.email_verified
                    ? '<i class="fas fa-check-circle mr-1"></i>Verified'
                    : '<i class="fas fa-clock mr-1"></i>Pending';
                statusBadge.className = `inline-flex items-center px-2 py-1 rounded-full text-xs font-medium ${
                    user.email_verified ? 'bg-green-100 text-green-600' : 'bg-amber-100 text-amber-600'
                }`;
            }
        }

        if (infoItems[2]) {
            const phoneSpan = infoItems[2].querySelector('.sm\\:col-span-2 span');
            if (phoneSpan) phoneSpan.textContent = user.phone_number || 'Not provided';
        }

        if (infoItems[3]) {
            const joinedSpan = infoItems[3].querySelector('.sm\\:col-span-2 span');
            if (joinedSpan) joinedSpan.textContent = new Date(user.created_at).toLocaleDateString();
        }

        if (infoItems[4]) {
            const addressSpan = infoItems[4].querySelector('.sm\\:col-span-2 span');
            if (addressSpan) addressSpan.textContent = user.address || 'Not provided';
        }
    }
};

const initializeButtons = () => {
    const editBtn = document.getElementById('edit-user-btn');
    const deleteBtn = document.getElementById('delete-user-btn');
    const sendMessageBtn = document.getElementById('send-message-btn');
    const suspendBtn = document.getElementById('suspend-user-btn');

    if (editBtn) {
        editBtn.addEventListener('click', (e) => {
            e.preventDefault();
            window.location.href = `/users/${currentUserId}/edit`;
        });
    }

    if (deleteBtn) {
        deleteBtn.addEventListener('click', async () => {
            if (!confirm('Are you sure you want to delete this user? This action cannot be undone.')) {
                return;
            }

            try {
                showLoadingState();
                const response = await api.delete(`/users/${currentUserId}`);

                if (response.status === 200) {
                    showSuccessMessage('User deleted successfully');

                    setTimeout(() => {
                        window.location.href = '/users';
                    }, 1500);
                }
            } catch (error) {
                showErrorMessage(error.response?.data?.message || 'Failed to delete user');
                console.error('Error deleting user:', error);
            } finally {
                hideLoadingState();
            }
        });
    }

    if (sendMessageBtn) {
        sendMessageBtn.addEventListener('click', () => {
            // Implement send message functionality
            showErrorMessage('Send message functionality not implemented yet');
        });
    }

    if (suspendBtn) {
        suspendBtn.addEventListener('click', () => {
            // Implement suspend user functionality
            showErrorMessage('Suspend user functionality not implemented yet');
        });
    }
};
