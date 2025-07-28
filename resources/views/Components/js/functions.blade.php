<script>

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

    function showError({message = '', errors = []}) {
        const errorsDiv = $('#errors');
        if (!errorsDiv) {
            console.error('Error: #errors element not found.');
            return;
        }
        // Clear any existing messages and append the new one
        errorsDiv.empty();
        if (message) {
            errorsDiv.append(`<div class="alert alert-danger">${message}</div>`);
        }
        for (const key in errors) {
            if (errors.hasOwnProperty(key)) {
                const errorMessages = errors[key];
                if (Array.isArray(errorMessages)) {
                    errorMessages.forEach(msg => {
                        errorsDiv.append(`<div class="alert alert-danger">${msg}</div>`);
                    });
                } else {
                    errorsDiv.append(`<div class="alert alert-danger">${errorMessages}</div>`);
                }
            }
        }
    }

    function clearErrors() {
        const errorsDiv = $('#errors');
        if (errorsDiv) {
            errorsDiv.empty();
        } else {
            console.error('Error: #errors element not found.');
        }
    }
</script>
