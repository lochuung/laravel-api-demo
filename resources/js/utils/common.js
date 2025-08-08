import {Notyf} from 'notyf';
import 'notyf/notyf.min.css';
import debounce from 'lodash/debounce';

// Init toast system
const notyf = new Notyf({
    duration: 4000,
    position: {x: 'right', y: 'top'}
});

// ==================== Button Wrapper ====================
export function withButtonControl(fn, buttons = []) {
    return async function (...args) {
        // Handle both single button selector string and array of button elements
        const buttonElements = Array.isArray(buttons)
            ? buttons
            : [typeof buttons === 'string' ? document.querySelector(buttons) : buttons];

        const originalStates = [];

        // Store original states and disable buttons
        buttonElements.forEach((button, index) => {
            if (button) {
                originalStates[index] = {
                    disabled: button.disabled,
                    innerHTML: button.innerHTML
                };

                button.disabled = true;
                button.innerHTML = `
                    <span class="flex items-center justify-center gap-2">
                        <svg class="animate-spin h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none"
                             viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10"
                                    stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor"
                                  d="M4 12a8 8 0 018-8v4a4 4 0 00-4 4H4z"></path>
                        </svg>
                        Processing...
                    </span>
                `;
            }
        });

        try {
            return await fn(...args);
        } finally {
            // Restore original states
            buttonElements.forEach((button, index) => {
                if (button && originalStates[index]) {
                    button.disabled = originalStates[index].disabled;
                    button.innerHTML = originalStates[index].innerHTML;
                }
            });
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
    if (message && !errors) showErrorMessage(message);
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
    const loadingOverlay = document.getElementById('loading-overlay');
    loadingOverlay.classList.remove('hidden');
    loadingOverlay.classList.add('flex');
}

export function hideLoadingState() {
    const loadingOverlay = document.getElementById('loading-overlay');
    loadingOverlay.classList.add('hidden');
    loadingOverlay.classList.remove('flex');
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

export function formatPriceInput(e) {
    let value = e.target.value.replace(/[^\d.]/g, '');

    // Ensure only one decimal point
    const parts = value.split('.');
    if (parts.length > 2) {
        value = parts[0] + '.' + parts.slice(1).join('');
    }

    // Limit decimal places to 2
    if (parts[1] && parts[1].length > 2) {
        value = parts[0] + '.' + parts[1].substring(0, 2);
    }

    e.target.value = value;
}
