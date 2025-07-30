export const getDashboardData = async () => {
    try {
        const response = await api.get('/dashboard');
        if (response.status === 200) {
            return response.data?.data ?? {};
        } else {
            console.error('Failed to fetch dashboard data:', response.statusText);
            return null;
        }
    } catch (error) {
        console.error('Error fetching dashboard data:', error);
        throw new Error(error.response?.data?.message || 'An error occurred while fetching dashboard data.');
    }
};
