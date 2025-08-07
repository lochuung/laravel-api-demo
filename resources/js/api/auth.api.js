import { showSuccessMessage, showError } from '../utils/common.js';
import api from '../axios.js';

export async function handleLogin({ email, password }) {
    try {
        const response = await api.post('/auth/login', { email, password });
        const { access_token, refresh_token } = response.data?.data || {};

        if (access_token) {
            localStorage.setItem('access_token', access_token);
            localStorage.setItem('refresh_token', refresh_token);

            showSuccessMessage('Login successful!');
            setTimeout(() => {
                window.location.href = '/dashboard';
            }, 500);
        } else {
            showError({ message: response.data?.message || 'Login failed. Please try again.' });
        }
    } catch (error) {
        const { data } = error.response || {};
        showError({ errors: data?.errors, message: data?.message || 'An unexpected error occurred.' });
    }
}

export async function handleRegister({ name, email, password, password_confirmation }) {
    try {
        const response = await api.post('/auth/register', { name, email, password, password_confirmation });
        if (response.data?.success) {
            showSuccessMessage('Registration successful! Please check your email to verify your account.');
            setTimeout(() => {
                window.location.href = '/login';
            }, 500);
        } else {
            showError({ message: response.data?.message || 'Registration failed. Please try again.' });
        }
    } catch (error) {
        const { data } = error.response || {};
        showError({ errors: data?.errors, message: data?.message || 'An unexpected error occurred.' });
    }
}

export async function handleLogout() {
    try {
        await api.post('/auth/logout');
    } catch (error) {
        console.warn('Server logout failed, proceeding to clear local storage.');
    } finally {
        localStorage.removeItem('access_token');
        localStorage.removeItem('refresh_token');
        window.user = null;
        window.location.href = '/login';
    }
}

export async function getCurrentUser() {
    try {
        const response = await api.get('/auth/me');
        if (response.data?.data) {
            window.user = response.data.data;
            return response.data.data;
        }
        return null;
    } catch (error) {
        console.error('Error fetching current user:', error);
        return null;
    }
}

export async function handleForgotPassword({ email }) {
    try {
        const response = await api.post('/auth/forgot-password', { email });
        if (response.data?.success) {
            showSuccessMessage('Password reset email sent successfully. Please check your email.');
        } else {
            showError({ message: response.data?.message || 'Failed to send reset email.' });
        }
    } catch (error) {
        const { data } = error.response || {};
        showError({ errors: data?.errors, message: data?.message || 'An unexpected error occurred.' });
    }
}

export async function handleResetPassword({ token, email, password, password_confirmation }) {
    try {
        const response = await api.post('/auth/reset-password', {
            token, email, password, password_confirmation
        });
        if (response.data?.success) {
            showSuccessMessage('Password reset successful! You can now login.');
            setTimeout(() => {
                window.location.href = '/login';
            }, 500);
        } else {
            showError({ message: response.data?.message || 'Password reset failed. Please try again.' });
        }
    } catch (error) {
        const { data } = error.response || {};
        showError({ errors: data?.errors, message: data?.message || 'An unexpected error occurred.' });
    }
}

export async function handleVerifyEmail({ token, email }) {
    try {
        const response = await api.post('/auth/verify-email', { token, email });
        if (response.data?.success) {
            showSuccessMessage('Email verified successfully!');
            setTimeout(() => {
                window.location.href = '/login';
            }, 500);
        } else {
            showError({ message: response.data?.message || 'Email verification failed. Please try again.' });
        }
    } catch (error) {
        const { data } = error.response || {};
        showError({ errors: data?.errors, message: data?.message || 'An unexpected error occurred.' });
    }
}

export async function handleResendVerificationEmail({ email }) {
    try {
        const response = await api.post('/auth/resend-verification-email', { email });
        if (response.data?.success) {
            showSuccessMessage('Verification email sent. Please check your inbox.');
        } else {
            showError({ message: response.data?.message || 'Failed to send verification email.' });
        }
    } catch (error) {
        const { data } = error.response || {};
        showError({ errors: data?.errors, message: data?.message || 'An unexpected error occurred.' });
    }
}
