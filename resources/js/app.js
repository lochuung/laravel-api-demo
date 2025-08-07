import './bootstrap';
import './api/api.js';
import {Notyf} from 'notyf';
import 'notyf/notyf.min.css';
import Alpine from 'alpinejs';

// Start Alpine
window.Alpine = Alpine;
Alpine.start();

// Import utilities and API functions
import {
    showSuccessMessage,
    showErrorMessage,
    showError,
    withButtonControl,
    showLoadingState,
    hideLoadingState,
    waitForUser
} from './utils/common.js';
import {getCurrentUser} from "@/api/auth.api.js";

// Make functions globally available
window.showSuccessMessage = showSuccessMessage;
window.showErrorMessage = showErrorMessage;
window.showError = showError;
window.withButtonControl = withButtonControl;
window.showLoadingState = showLoadingState;
window.hideLoadingState = hideLoadingState;
window.waitForUser = waitForUser;

// Initialize Notyf globally
window.notyf = new Notyf({
    duration: 4000,
    position: {x: 'right', y: 'top'},
    ripple: true,
    dismissible: true
});

// Auto-load current user on page load and handle authentication
document.addEventListener('DOMContentLoaded', async () => {
    const currentPath = window.location.pathname;
    const isAuthPage = ['/login', '/register', '/forgot-password', '/reset-password', '/verify-email'].includes(currentPath);
    
    if (!isAuthPage && localStorage.getItem('access_token')) {
        try {
            window.user = await getCurrentUser();
            if (!window.user) {
                // Redirect to login if user data cannot be loaded
                window.location.href = '/login';
            }
        } catch (error) {
            console.error('Failed to load current user:', error);
            // Clear invalid token and redirect to login
            localStorage.removeItem('access_token');
            window.location.href = '/login';
        }
    } else if (!isAuthPage && !localStorage.getItem('access_token')) {
        // Redirect to login if no token exists and not on auth page
        window.location.href = '/login';
    }
});
