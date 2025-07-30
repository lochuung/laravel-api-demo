import {getStatusClass} from "./order-utils.js";

const renderUsers = (users = []) => {
    const container = $('.recent-users');
    container.empty();
    if (!users.length) {
        return container.append('<div class="text-muted p-2">No recent users</div>');
    }

    users.forEach(user => {
        container.append(`
                    <div class="list-group-item d-flex align-items-center">
                        <div class="avatar me-3">
                            <img src="${user.profile_picture}" class="rounded-circle" alt="${user.name}" width="40" height="40">
                        </div>
                        <div>
                            <h6 class="mb-0">${user.name}</h6>
                            <small class="text-muted">${user.email}</small>
                        </div>
                    </div>
                `);
    });
};

const renderOrders = (orders = []) => {
    const container = $('.recent-orders');
    container.empty();
    if (!orders.length) {
        return container.append('<div class="text-muted p-2">No recent orders</div>');
    }

    orders.forEach(order => {
        container.append(`
                    <div class="list-group-item d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="mb-0">Order #${order.order_number}</h6>
                            <small class="text-muted">${order.user_name} - ${parseFloat(order.total).toLocaleString('vi-VN')} ₫</small>
                        </div>
                        <span class="badge bg-${getStatusClass(order.status)} text-capitalize">${order.status}</span>
                    </div>
                `);
    });
};

const renderStats = (data) => {
    $('#total-users').text(data.total_users ?? 0);
    $('#total-products').text(data.total_products ?? 0);
    $('#total-orders').text(data.total_orders ?? 0);
    $('#monthly-revenue').text(formatCurrency(data.monthly_revenue ?? 0));
};

export {
    renderUsers,
    renderOrders,
    renderStats
}
