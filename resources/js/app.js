import './bootstrap';
import './axios';
import $ from 'jquery';
import { Notyf } from 'notyf';
import 'notyf/notyf.min.css';

// Import utilities and API functions
import {
    showSuccessMessage,
    showErrorMessage,
    showError,
    withButtonControl,
    showLoadingState,
    hideLoadingState
} from './utils/common.js';

import {
    handleLogin,
    handleRegister,
    handleLogout,
    getCurrentUser,
    handleForgotPassword,
    handleResetPassword,
    handleVerifyEmail,
    handleResendVerificationEmail
} from './api/auth.api.js';

// Make functions globally available
window.$ = $;
window.showSuccessMessage = showSuccessMessage;
window.showErrorMessage = showErrorMessage;
window.showError = showError;
window.withButtonControl = withButtonControl;
window.showLoadingState = showLoadingState;
window.hideLoadingState = hideLoadingState;

// Auth functions
window.handleLogin = handleLogin;
window.handleRegister = handleRegister;
window.handleLogout = handleLogout;
window.getCurrentUser = getCurrentUser;
window.handleForgotPassword = handleForgotPassword;
window.handleResetPassword = handleResetPassword;
window.handleVerifyEmail = handleVerifyEmail;
window.handleResendVerificationEmail = handleResendVerificationEmail;

// Initialize Notyf globally
window.notyf = new Notyf({
    duration: 4000,
    position: { x: 'right', y: 'top' },
    ripple: true,
    dismissible: true
});

// Auto-load current user on page load
document.addEventListener('DOMContentLoaded', async () => {
    if (localStorage.getItem('access_token')) {
        try {
            await getCurrentUser();
        } catch (error) {
            console.error('Failed to load current user:', error);
        }
    }
});
