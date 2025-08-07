import { withButtonControl } from '../utils/common.js';
import { showSuccessMessage, showErrorMessage, showError } from '../utils/common.js';
import {resetPassword} from "@/api/auth.api.js";

document.addEventListener('DOMContentLoaded', () => {
    const form = document.getElementById('reset-password-form');
    const submitBtn = document.getElementById('submit');

    // Get token and email from URL parameters
    const urlParams = new URLSearchParams(window.location.search);
    const token = urlParams.get('token');
    const email = urlParams.get('email');

    if (email) {
        document.getElementById('email').value = email;
    }

    if (!token) {
        showError({message: 'Invalid reset token. Please request a new password reset.'});
        submitBtn.disabled = true;
    }

    const wrappedHandleResetPassword = withButtonControl(async (data) => {
        if (!token) {
            showErrorMessage('Invalid reset token.');
            return;
        }

        try {
            await resetPassword({
                ...data,
                token,
                email: document.getElementById('email').value
            });

            showSuccessMessage('Password reset successful! You can now log in with your new password.');

            // Small delay for user feedback, then redirect
            setTimeout(() => {
                window.location.href = '/login';
            }, 2000);

        } catch (error) {
            console.error('Reset password error:', error);
            if (error.response?.data) {
                showError(error.response.data);
            } else {
                showErrorMessage('Password reset failed. Please try again.');
            }
        }
    }, '#submit');

    // Handle form submission
    form.addEventListener('submit', (e) => {
        e.preventDefault();

        const data = {
            password: document.getElementById('password').value,
            password_confirmation: document.getElementById('password_confirmation').value
        };

        wrappedHandleResetPassword(data);
    });
});

// Toggle password visibility
window.togglePassword = function(fieldId) {
    const field = document.getElementById(fieldId);
    const iconId = fieldId === 'password' ? 'toggleIconPassword' : 'toggleIconConfirm';
    const icon = document.getElementById(iconId);

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

// Password strength checker
window.checkPasswordStrength = function() {
    const password = document.getElementById('password').value;
    const strengthDiv = document.getElementById('passwordStrength');
    const progressBar = strengthDiv.querySelector('.progress-bar');
    const strengthText = document.getElementById('strengthText');

    if (password.length === 0) {
        strengthDiv.style.display = 'none';
        return;
    }

    strengthDiv.style.display = 'block';

    let strength = 0;
    let text = '';
    let colorClass = '';

    if (password.length >= 8) strength++;
    if (/[A-Z]/.test(password)) strength++;
    if (/[a-z]/.test(password)) strength++;
    if (/[0-9]/.test(password)) strength++;
    if (/[^A-Za-z0-9]/.test(password)) strength++;

    switch (strength) {
        case 0:
        case 1:
            text = 'Very Weak';
            colorClass = 'bg-red-500';
            break;
        case 2:
            text = 'Weak';
            colorClass = 'bg-orange-500';
            break;
        case 3:
            text = 'Fair';
            colorClass = 'bg-yellow-500';
            break;
        case 4:
            text = 'Good';
            colorClass = 'bg-blue-500';
            break;
        case 5:
            text = 'Strong';
            colorClass = 'bg-green-500';
            break;
    }

    progressBar.style.width = (strength * 20) + '%';
    progressBar.className = `h-1 rounded transition-all duration-300 ${colorClass}`;
    strengthText.textContent = text;
    strengthText.className = `text-sm mt-1 font-medium ${colorClass.replace('bg-', 'text-')}`;
};

// Password match checker
window.checkPasswordMatch = function() {
    const password = document.getElementById('password').value;
    const confirmPassword = document.getElementById('password_confirmation').value;
    const matchDiv = document.getElementById('passwordMatch');
    const matchText = document.getElementById('matchText');

    if (confirmPassword.length === 0) {
        matchDiv.style.display = 'none';
        return;
    }

    matchDiv.style.display = 'block';

    if (password === confirmPassword) {
        matchText.textContent = '✓ Passwords match';
        matchText.className = 'text-sm text-green-600 font-medium';
    } else {
        matchText.textContent = '✗ Passwords do not match';
        matchText.className = 'text-sm text-red-600 font-medium';
    }
};
