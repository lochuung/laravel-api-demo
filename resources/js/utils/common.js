import {Notyf} from 'notyf';
import 'notyf/notyf.min.css';
import debounce from 'lodash/debounce';

// Init toast system
const notyf = new Notyf({
    duration: 4000,
    position: {x: 'right', y: 'top'}
});

// ==================== Button Wrapper ====================
export function withButtonControl(fn, buttonSelector) {
    return async function (...args) {
        const button = document.querySelector(buttonSelector);
        if (button) button.disabled = true;

        try {
            return await fn(...args);
        } finally {
            if (button) button.disabled = false;
        }
    };
}

// ==================== Wait for window.user ====================
export function waitForUser(fn, interval = 100) {
    const timer = setInterval(() => {
        if (window.user?.name) {
            fn(window.user);
            clearInterval(timer);
        }
    }, interval);
}

// ==================== Format currency (VND) ====================
export const formatCurrency = (value) =>
    new Intl.NumberFormat('vi-VN', {
        style: 'currency',
        currency: 'VND'
    }).format(value);

// ==================== Get ID from URL ====================
export function getIdFromUrl(resource = 'users') {
    const path = window.location.pathname;
    const regex = new RegExp(`/${resource}/(\\d+)`);
    const match = path.match(regex);
    return match ? parseInt(match[1], 10) : -1;
}

// ==================== Debounce (lodash) ====================
export {debounce};

// ==================== Update user count (vanilla) ====================
export function updateUserCount(total) {
    const titleEl = document.querySelector('.card-title');
    if (titleEl) {
        titleEl.textContent = `All Users (${total.toLocaleString()})`;
    }
}

// ==================== Show error from response ====================
export function showError({message = '', errors = []}) {
    if (message) showErrorMessage(message);
    for (const key in errors) {
        if (Object.prototype.hasOwnProperty.call(errors, key)) {
            const errorMessages = errors[key];
            if (Array.isArray(errorMessages)) {
                errorMessages.forEach(showErrorMessage);
            } else if (typeof errorMessages === 'string') {
                showErrorMessage(errorMessages);
            }
        }
    }
}

// ==================== Loading Overlay ====================
export function showLoadingState() {
    document.getElementById('loading-overlay')?.classList.remove('d-none');
}

export function hideLoadingState() {
    document.getElementById('loading-overlay')?.classList.add('d-none');
}

// ==================== Toast Message (Notyf) ====================
export function showSuccessMessage(message) {
    notyf.success(message);
}

export function showErrorMessage(message) {
    notyf.error(message);
}

// ==================== Extract prefix from code ====================
export function extractCodePrefix(code) {
    if (!code) return '';
    const match = code.match(/^([A-Z]+)/);
    return match ? match[1] : '';
}

// ==================== Format datetime ====================
export function formatDateTime(dateString) {
    if (!dateString) return 'N/A';
    return new Date(dateString).toLocaleString('vi-VN', {
        year: 'numeric',
        month: 'short',
        day: 'numeric',
        hour: '2-digit',
        minute: '2-digit'
    });
}
