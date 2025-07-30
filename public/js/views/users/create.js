import { createUser } from '../../api/users.api.js';
import { uploadImage } from '../../api/upload.api.js';

$(document).ready(function () {
    setupCreateUser();
});

function setupCreateUser() {
    $('#createUserForm').on('submit', handleCreateUser);
    setupImagePreview();
    setupFormValidation();
}

function setupImagePreview() {
    $('#avatar').on('change', function () {
        const file = this.files[0];
        if (file && file.type.startsWith('image/')) {
            const reader = new FileReader();
            reader.onload = function (e) {
                // Create preview if doesn't exist
                if (!$('#avatar-preview').length) {
                    $('#avatar').parent().append(`
                        <div id="preview-container" class="mt-3">
                            <img id="avatar-preview" src="#" alt="Image Preview" 
                                 style="max-width: 200px; max-height: 200px;" class="rounded"/>
                        </div>
                    `);
                }
                $('#avatar-preview').attr('src', e.target.result).show();
            };
            reader.readAsDataURL(file);
        } else {
            $('#preview-container').remove();
        }
    });
}

function setupFormValidation() {
    // Real-time password confirmation validation
    $('#password_confirmation').on('input', function () {
        const password = $('#password').val();
        const confirmation = $(this).val();
        
        if (confirmation && password !== confirmation) {
            $(this).addClass('is-invalid');
            if (!$(this).next('.invalid-feedback').length) {
                $(this).after('<div class="invalid-feedback">Passwords do not match</div>');
            }
        } else {
            $(this).removeClass('is-invalid');
            $(this).next('.invalid-feedback').remove();
        }
    });
}

async function handleCreateUser(event) {
    event.preventDefault();
    showLoadingState();

    try {
        const formData = await buildUserFormData();
        const response = await createUser(formData);

        if (response.status === 200 || response.status === 201) {
            showSuccessMessage('User created successfully.');
            // Redirect to user detail page or users list
            const userId = response.data?.data?.id;
            if (userId) {
                window.location.href = `/users/${userId}`;
            } else {
                window.location.href = '/users';
            }
        } else {
            showErrorMessage('Failed to create user.');
        }
    } catch (error) {
        console.error('Create error:', error);
        handleValidationErrors(error);
    } finally {
        hideLoadingState();
    }
}

async function buildUserFormData() {
    const formData = {
        name: $('#name').val().trim(),
        email: $('#email').val().trim(),
        password: $('#password').val(),
        password_confirmation: $('#password_confirmation').val(),
        phone_number: $('#phone').val().trim() || null,
        address: $('#address').val().trim() || null,
        role: $('#role').val(),
        is_active: $('#is_active').is(':checked'),
        email_verified: $('#email_verified').is(':checked'),
    };

    // Handle avatar upload
    const avatarFile = $('#avatar')[0].files[0];
    if (avatarFile) {
        try {
            const avatarUrl = await uploadImage(avatarFile);
            if (avatarUrl) {
                formData.profile_picture = avatarUrl;
            }
        } catch (error) {
            console.error('Avatar upload error:', error);
            throw new Error('Failed to upload profile picture');
        }
    }

    return formData;
}

function handleValidationErrors(error) {
    if (error.response?.data?.errors) {
        showValidationErrors(error.response.data.errors);
    } else {
        showErrorMessage(error.response?.data?.message || 'Failed to create user.');
    }
}

function showValidationErrors(errors) {
    // Clear previous errors
    $('.is-invalid').removeClass('is-invalid');
    $('.invalid-feedback').remove();

    // Show new errors
    Object.keys(errors).forEach(field => {
        const input = $(`#${field}`);
        if (input.length) {
            input.addClass('is-invalid');
            input.after(`<div class="invalid-feedback">${errors[field][0]}</div>`);
        }
    });
}
