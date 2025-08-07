import api from './api.js';

export async function login({ email, password }) {
    const response = await api.post('/auth/login', { email, password });
    return response.data;
}

export async function register({ name, email, password, password_confirmation }) {
    const response = await api.post('/auth/register', {
        name,
        email,
        password,
        password_confirmation
    });
    return response.data;
}

export async function logout() {
    try {
        await api.post('/auth/logout');
    } catch {
        // Ignored: logout can fail silently on server
    }
}

export async function handleLogout() {
    try {
        await logout();
    } finally {
        // Clear local storage and user data regardless of API response
        localStorage.removeItem('access_token');
        window.user = null;
    }
}

export async function getCurrentUser() {
    const response = await api.get('/auth/me');
    return response.data?.data || null;
}

export async function forgotPassword({ email }) {
    const response = await api.post('/auth/forgot-password', { email });
    return response.data;
}

export async function resetPassword({ token, email, password, password_confirmation }) {
    const response = await api.post('/auth/reset-password', {
        token,
        email,
        password,
        password_confirmation
    });
    return response.data;
}

export async function verifyEmail({ token, email }) {
    const response = await api.post('/auth/verify-email', { token, email });
    return response.data;
}

export async function resendVerificationEmail({ email }) {
    const response = await api.post('/auth/resend-verification-email', { email });
    return response.data;
}
