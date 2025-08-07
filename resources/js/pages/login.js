import { withButtonControl } from '../utils/common.js';
import { showSuccessMessage, showErrorMessage, showError } from '../utils/common.js';
import {login} from '../api/auth.api.js';

document.addEventListener('DOMContentLoaded', () => {
    const form = document.getElementById('login-form');
    const emailInput = document.getElementById('email');
    const passwordInput = document.getElementById('password');

    const wrappedHandleLogin = withButtonControl(async (credentials) => {
        try {
            await login(credentials);
            showSuccessMessage('Login successful! Redirecting...');

            // Small delay for user feedback, then redirect
            setTimeout(() => {
                window.location.href = '/dashboard';
            }, 1000);

        } catch (error) {
            console.error('Login error:', error);
            if (error.response?.data) {
                showError(error.response.data);
            } else {
                showErrorMessage('Login failed. Please try again.');
            }
        }
    }, '#submit');

    // Handle form submission
    form.addEventListener('submit', async (e) => {
        e.preventDefault();

        const credentials = {
            email: emailInput.value.trim(),
            password: passwordInput.value
        };

        await wrappedHandleLogin(credentials);
    });

    // Handle Enter key on password field
    passwordInput.addEventListener('keypress', (e) => {
        if (e.key === 'Enter') {
            e.preventDefault();
            form.dispatchEvent(new Event('submit'));
        }
    });
});

// Toggle password visibility
window.togglePassword = function(fieldId) {
    const field = document.getElementById(fieldId);
    const icon = document.getElementById('toggleIcon');

    if (field.type === 'password') {
        field.type = 'text';
        icon.classList.remove('fa-eye');
        icon.classList.add('fa-eye-slash');
    } else {
        field.type = 'password';
        icon.classList.remove('fa-eye-slash');
        icon.classList.add('fa-eye');
    }
};
