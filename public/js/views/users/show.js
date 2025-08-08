import {deleteUser as deleteUserApi, getUserWithOrders} from '../../api/users.api.js';

let currentUserId = null;
let user = null;
let orders = [];

$(document).ready(async function () {
    currentUserId = getIdFromUrl('users');

    if (currentUserId) {
        await loadUserDetails(currentUserId);
        setupEventListeners();
    } else {
        showErrorMessage('User ID not found in URL');
    }
});

function setupEventListeners() {
    $('#suspend-user-btn').on('click', async () => {
        await handleToggleUserStatus(currentUserId);
    });

    $('#delete-user-btn').on('click', async () => {
        await handleDeleteUser(currentUserId);
    });

    $('#send-message-btn').on('click', () => {
        showSuccessMessage('Message functionality not implemented yet');
    });
}

async function loadUserDetails(userId) {
    try {
        showLoadingState();

        const response = await getUserWithOrders(userId);
        user = response.data.data;
        orders = user?.orders || [];

        renderUserProfile(user);
        renderUserInfo(user);
        renderUserStats(user);
        renderOrders(orders);

    } catch (error) {
        console.error('Failed to load user details:', error);
        const msg = error.response?.data?.message || 'Failed to load user';
        showErrorMessage(msg);

        if (error.response?.status === 404) {
            setTimeout(() => window.location.href = '/users', 3000);
        }
    } finally {
        hideLoadingState();
    }
}

async function handleToggleUserStatus(userId) {
    const isCurrentlyActive = $('.badge').hasClass('bg-success');
    const actionText = isCurrentlyActive ? 'suspend' : 'activate';

    if (!confirm(`Are you sure you want to ${actionText} this user?`)) return;

    const $btn = $('#suspend-user-btn');
    const originalHtml = $btn.html();

    try {
        $btn.prop('disabled', true).html(`<i class="fas fa-spinner fa-spin me-1"></i>${actionText}ing...`);
        showSuccessMessage(`User ${actionText}d successfully`);
        await loadUserDetails(userId);
    } catch (error) {
        console.error(`Failed to ${actionText} user:`, error);
        showErrorMessage(error.response?.data?.message || `Failed to ${actionText} user`);
    } finally {
        $btn.prop('disabled', false).html(originalHtml);
    }
}

async function handleDeleteUser(userId) {
    if (!confirm('Are you sure you want to delete this user? This action cannot be undone!')) return;

    try {
        await deleteUserApi(userId);
        showSuccessMessage('User deleted successfully');
        setTimeout(() => window.location.href = '/users', 2000);
    } catch (error) {
        console.error('Failed to delete user:', error);
        showErrorMessage(error.response?.data?.message || 'Failed to delete user');
    }
}


/**
 * Render user profile section (avatar, name, role, status)
 */
function renderUserProfile(user) {
    const profileSection = $('.card-body.text-center').first();

    // Update avatar
    const avatarUrl = user.profile_picture || 'https://via.placeholder.com/150';

    profileSection.find('img').attr('src', avatarUrl).attr('alt', user.name);

    // Update name and role
    profileSection.find('.card-title').text(user.name);
    profileSection.find('.text-muted').first().text(getRoleDisplayName(user.role));

    // Update status badge
    const statusBadge = user.is_active
        ? '<i class="fas fa-check-circle"></i> Active'
        : '<i class="fas fa-times-circle"></i> Inactive';

    const badgeClass = user.is_active ? 'bg-success' : 'bg-danger';
    profileSection.find('.badge').removeClass('bg-success bg-danger').addClass(badgeClass).html(statusBadge);

    // Update edit button with correct user ID
    $('#edit-user-btn').attr('href', `/users/${user.id}/edit`);
}

/**
 * Render user information in the Personal Info tab
 */
function renderUserInfo(user) {
    const infoTab = $('#info');

    // Update name
    infoTab.find('.col-sm-9').eq(0).text(user.name);

    // Update email with verification status
    const emailHtml = user.email + ' ' + (user.email_verified
        ? '<span class="badge bg-success ms-2"><i class="fas fa-check"></i> Verified</span>'
        : '<span class="badge bg-warning ms-2"><i class="fas fa-clock"></i> Pending</span>');
    infoTab.find('.col-sm-9').eq(1).html(emailHtml);

    // Update phone
    infoTab.find('.col-sm-9').eq(2).text(user.phone_number || 'Not provided');

    // Update joined date
    const joinedDate = new Date(user.created_at).toLocaleDateString('en-US', {
        year: 'numeric',
        month: 'long',
        day: 'numeric'
    });
    infoTab.find('.col-sm-9').eq(3).text(joinedDate);

    // Update address
    const address = user.address || 'No address provided';
    infoTab.find('.col-sm-9').eq(4).html(address.replace(/\n/g, '<br>'));
}

/**
 * Render user statistics
 */
function renderUserStats(user) {
    const statsCard = $('.card .row.text-center').first();

    // Update orders count
    statsCard.find('.text-primary').text(user.orders_count || 0);

    // Update total spent
    const totalSpent = user.total_spent ? formatCurrency(user.total_spent) : '0 ₫';
    statsCard.find('.text-success').text(totalSpent);
}

function renderOrders(orders) {
    const $tbody = $('#orders-body');
    $tbody.empty(); // Clear old content

    orders.forEach(order => {
        // Format date
        const orderDate = new Date(order.ordered_at);
        const formattedDate = orderDate.toLocaleDateString('en-US', {
            year: 'numeric',
            month: 'long',
            day: 'numeric'
        });

        // Dummy item count - bạn có thể thay đổi nếu có dữ liệu chi tiết về items
        const itemCount = Math.floor(Math.random() * 5) + 1;

        // Format total
        const formattedTotal = `$${parseFloat(order.total_amount).toLocaleString(undefined, {minimumFractionDigits: 2})}`;

        // Status badge
        let badgeClass = 'secondary';
        switch (order.status) {
            case 'completed':
                badgeClass = 'success';
                break;
            case 'processing':
                badgeClass = 'warning';
                break;
            case 'pending':
                badgeClass = 'info';
                break;
            case 'cancelled':
                badgeClass = 'danger';
                break;
        }

        const statusBadge = `<span class="badge bg-${badgeClass}">${order.status.charAt(0).toUpperCase() + order.status.slice(1)}</span>`;

        // Create row
        const row = `
            <tr>
                <td><a href="/orders/${order.id}">#${order.order_number}</a></td>
                <td>${formattedDate}</td>
                <td>${itemCount} item${itemCount > 1 ? 's' : ''}</td>
                <td>${formattedTotal}</td>
                <td>${statusBadge}</td>
            </tr>
        `;

        $tbody.append(row);
    });
}

/**
 * Get display name for user role
 */
function getRoleDisplayName(role) {
    const roleMap = {
        'admin': 'Administrator',
        'user': 'User',
        'moderator': 'Moderator',
        'manager': 'Manager'
    };

    return roleMap[role] || role.charAt(0).toUpperCase() + role.slice(1);
}
