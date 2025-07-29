function withButtonControl(fn, buttonSelector) {
    return async function (...args) {
        clearErrors();
        const $button = $(buttonSelector);
        $button.prop('disabled', true);
        try {
            return await fn(...args);
        } finally {
            $button.prop('disabled', false);
        }
    }
}

function waitForUser(fn) {
    const waitForUser = setInterval(function () {
        if (window.user && window.user.name) {
            fn(window.user);
            clearInterval(waitForUser); // dừng lại sau khi đã có user
        }
    }, 100); // kiểm tra mỗi 100ms
}

const formatCurrency = (value) => {
    return new Intl.NumberFormat('vi-VN', {
        style: 'currency',
        currency: 'VND'
    }).format(value);
};

const debounce = (func, delay) => {
    let timeoutId;
    return function (...args) {
        clearTimeout(timeoutId);
        timeoutId = setTimeout(() => func.apply(this, args), delay);
    };
};

const updateUserCount = (total) => {
    $('.card-title').text(`All Users (${total.toLocaleString()})`);
};

function showError({message = '', errors = []}) {
    const errorsDiv = $('#errors');
    if (!errorsDiv.length) {
        console.error('Error: #errors element not found.');
        return;
    }
    // Clear any existing messages and append the new one
    errorsDiv.empty();
    if (message) {
        errorsDiv.append(`<div class="alert alert-danger alert-dismissible fade show" role="alert">
                ${message}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>`);
    }
    for (const key in errors) {
        if (errors.hasOwnProperty(key)) {
            const errorMessages = errors[key];
            if (Array.isArray(errorMessages)) {
                errorMessages.forEach(msg => {
                    errorsDiv.append(`<div class="alert alert-danger alert-dismissible fade show" role="alert">
                            ${msg}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>`);
                });
            } else {
                errorsDiv.append(`<div class="alert alert-danger alert-dismissible fade show" role="alert">
                        ${errorMessages}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>`);
            }
        }
    }
}

function showSuccess(message) {
    const errorsDiv = $('#errors');
    if (!errorsDiv.length) {
        console.error('Error: #errors element not found.');
        return;
    }
    // Clear any existing messages and append the new one
    errorsDiv.empty();
    errorsDiv.append(`<div class="alert alert-success alert-dismissible fade show" role="alert">
            ${message}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>`);
}

function clearErrors() {
    const errorsDiv = $('#errors');
    if (errorsDiv.length) {
        errorsDiv.empty();
    } else {
        console.error('Error: #errors element not found.');
    }
}
