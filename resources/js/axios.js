import axios from 'axios';

const api = axios.create({
    baseURL: '/api/v1',
    withCredentials: true,
});

// request
api.interceptors.request.use(config => {
    const accessToken = localStorage.getItem('access_token');
    if (accessToken) {
        config.headers.Authorization = `Bearer ${accessToken}`;
    }
    return config;
});

// Response interceptor
api.interceptors.response.use(
    response => response,
    async error => {
        const originalRequest = error.config;

        if (error.response?.status === 401 && !originalRequest._retry) {
            originalRequest._retry = true;

            try {
                const res = await axios.post('/api/v1/auth/refresh', {
                    refresh_token: localStorage.getItem('refresh_token') || ''
                }, {withCredentials: true});

                const newAccessToken = res.data?.data?.access_token;
                if (newAccessToken) {
                    localStorage.setItem('access_token', newAccessToken);
                    if (res.data?.data?.refresh_token) {
                        localStorage.setItem('refresh_token', res.data.data.refresh_token);
                    }

                    originalRequest.headers['Authorization'] = `Bearer ${newAccessToken}`;
                    return api(originalRequest); // Retry original request
                } else {
                    throw new Error('No access token received');
                }
            } catch (refreshError) {
                console.error('Refresh failed:', refreshError);
                window.location.href = '/login';
                return Promise.reject(refreshError);
            }
        }

        return Promise.reject(error);
    }
);

window.api = api;
