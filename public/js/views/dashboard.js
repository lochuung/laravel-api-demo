import {renderOrders, renderStats, renderUsers} from "../utils/dashboard-utils.js";
import {getDashboardData} from "../api/dashboard.api.js";

const loadDashboard = async () => {
    try {
        showLoadingState();
        const data = await getDashboardData();
        if (data) {
            renderStats(data);
            renderUsers(data.recent_users ?? []);
            renderOrders(data.recent_orders ?? []);
        }
    } catch (error) {
        showErrorMessage(error.message);
    } finally {
        hideLoadingState();
    }
};

$(document).ready(async () => {
    await loadDashboard();
});
