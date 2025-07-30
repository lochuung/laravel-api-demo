import {renderOrders, renderStats, renderUsers} from "../utils/dashboard-utils.js";

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
        }
    } catch (error) {
        showErrorMessage(error.response?.data?.message || 'An error occurred while fetching dashboard data.');
        console.error('Error fetching dashboard data:', error);
    } finally {
        hideLoadingState();
    }
};

$(document).ready(async () => {
    await getDashboardData();
});
