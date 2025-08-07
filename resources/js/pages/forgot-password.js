import {withButtonControl} from '../utils/common.js';
import {showSuccessMessage, showErrorMessage, showError} from '../utils/common.js';
import {forgotPassword} from "@/api/auth.api.js";

document.addEventListener('DOMContentLoaded', () => {
    const form = document.getElementById('forgot-password-form');
    const submitBtn = document.getElementById('submit');
    const emailInput = document.getElementById('email');

    const wrappedHandleForgotPassword = withButtonControl(async (data) => {
        try {
            await forgotPassword(data);
            showSuccessMessage('Password reset instructions sent to your email!');

            // Small delay for user feedback
            setTimeout(() => {
                window.location.href = '/login';
            }, 3000);

        } catch (error) {
            console.error('Forgot password error:', error);
            if (error.response?.data) {
                showError(error.response.data);
            } else {
                showErrorMessage('Failed to send reset instructions. Please try again.');
            }
        }
    }, '#submit');

    // Handle form submission
    form.addEventListener('submit', (e) => {
        e.preventDefault();

        const email = emailInput.value.trim();
        if (!email) {
            showErrorMessage('Please enter your email address.');
            return;
        }

        wrappedHandleForgotPassword({email});
    });

    // Handle Enter key on email field
    emailInput.addEventListener('keypress', (e) => {
        if (e.key === 'Enter') {
            e.preventDefault();
            form.dispatchEvent(new Event('submit'));
        }
    });
});
