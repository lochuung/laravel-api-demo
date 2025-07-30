function withButtonControl(fn, buttonSelector) {
    return async function (...args) {
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
    // Hiển thị message chính nếu có
    if (message) {
        showErrorMessage(message);
    }

    // Hiển thị các lỗi cụ thể từ mảng errors (nếu có)
    for (const key in errors) {
        if (Object.prototype.hasOwnProperty.call(errors, key)) {
            const errorMessages = errors[key];

            if (Array.isArray(errorMessages)) {
                errorMessages.forEach(msg => {
                    showErrorMessage(msg);
                });
            } else if (typeof errorMessages === 'string') {
                showErrorMessage(errorMessages);
            }
        }
    }
}


/**
 * Show loading state
 */
function showLoadingState() {
    const loadingHtml = `
        <div id="loading-overlay" class="d-flex justify-content-center align-items-center position-fixed"
             style="top: 0; left: 0; width: 100%; height: 100%; background: rgba(255,255,255,0.8); z-index: 9999;">
            <div class="text-center">
                <i class="fas fa-spinner fa-spin fa-3x text-primary mb-3"></i>
                <h5>Loading...</h5>
            </div>
        </div>
    `;
    $('body').append(loadingHtml);
}

/**
 * Hide loading state
 */
function hideLoadingState() {
    $('#loading-overlay').remove();
}


function showSuccessMessage(message) {
    // Tạo toast notification với Bootstrap 5
    const toast = $(`
        <div class="alert alert-success alert-dismissible fade show position-fixed"
             style="top: 20px; right: 20px; z-index: 9999; min-width: 300px;">
            <i class="fas fa-check-circle me-2"></i>
            ${message}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    `);

    $('body').append(toast);

    // Tự động ẩn sau 3 giây
    setTimeout(() => {
        toast.fadeOut(() => toast.remove());
    }, 3000);
}

function showErrorMessage(message) {
    const toast = $(`
        <div class="alert alert-danger alert-dismissible fade show position-fixed"
             style="top: 20px; right: 20px; z-index: 9999; min-width: 300px;">
            <i class="fas fa-exclamation-circle me-2"></i>
            ${message}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    `);

    $('body').append(toast);

    // Tự động ẩn sau 5 giây
    setTimeout(() => {
        toast.fadeOut(() => toast.remove());
    }, 5000);
}
