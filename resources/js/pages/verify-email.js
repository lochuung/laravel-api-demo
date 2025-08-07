import { withButtonControl } from '../utils/common.js';
import { showSuccessMessage, showErrorMessage, showError } from '../utils/common.js';
import {resendVerificationEmail, verifyEmail} from "@/api/auth.api.js";

document.addEventListener('DOMContentLoaded', async () => {
    const resendForm = document.getElementById('resendForm');
    const resendBtn = document.getElementById('resend');

    // Get token from URL parameters for auto-verification
    const urlParams = new URLSearchParams(window.location.search);
    const token = urlParams.get('token');
    const email = urlParams.get('email');

    if (token && email) {
        // Auto-verify if token is present
        await handleVerifyEmailWithToken(token, email);
    }

    const wrappedHandleResendVerification = withButtonControl(async (data) => {
        try {
            await resendVerificationEmail(data);
            showSuccessMessage('Verification email sent! Please check your inbox.');

        } catch (error) {
            console.error('Resend verification error:', error);
            if (error.response?.data) {
                showError(error.response.data);
            } else {
                showErrorMessage('Failed to send verification email. Please try again.');
            }
        }
    }, '#resend');

    // Handle resend form submission
    resendForm.addEventListener('submit', (e) => {
        e.preventDefault();

        const email = document.getElementById('email').value.trim();
        if (!email) {
            showErrorMessage('Please enter your email address.');
            return;
        }

        wrappedHandleResendVerification({ email });
    });
});

// Auto-verify function
async function handleVerifyEmailWithToken(token, email) {
    try {
        await verifyEmail({ token, email });
        showSuccessMessage('Email verified successfully! You can now log in.');

        // Small delay for user feedback, then redirect
        setTimeout(() => {
            window.location.href = '/login';
        }, 2000);

    } catch (error) {
        console.error('Email verification error:', error);
        if (error.response?.data) {
            showError(error.response.data);
        } else {
            showErrorMessage('Email verification failed. Please try again or request a new verification email.');
        }
    }
}
