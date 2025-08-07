import { 
    showLoadingState, 
    hideLoadingState, 
    showErrorMessage,
    showSuccessMessage,
    waitForUser,
    withButtonControl,
    showError
} from '../../utils/common.js';
import { createUser } from '../../api/users.api.js';
import { uploadImage } from '../../api/upload.api.js';

document.addEventListener('DOMContentLoaded', () => {
    // Wait for user data to be available
    waitForUser(() => {
        initializeForm();
        initializeImagePreview();
    });
});

const initializeForm = () => {
    const form = document.getElementById('createUserForm');
    
    form.addEventListener('submit', async (e) => {
        e.preventDefault();
        await handleCreateUser(e);
    });
    
    setupFormValidation();
};

const handleCreateUser = async (event) => {
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
};

const buildUserFormData = async () => {
    const formData = {
        name: document.getElementById('name').value.trim(),
        email: document.getElementById('email').value.trim(),
        password: document.getElementById('password').value,
        password_confirmation: document.getElementById('password_confirmation').value,
        phone_number: document.getElementById('phone').value.trim() || null,
        address: document.getElementById('address').value.trim() || null,
        role: document.getElementById('role').value,
        is_active: document.getElementById('is_active').checked,
        email_verified: document.getElementById('email_verified').checked,
    };

    // Handle avatar upload
    const avatarFile = document.getElementById('avatar').files[0];
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
};

const handleValidationErrors = (error) => {
    if (error.response?.data?.errors) {
        showError(error.response.data);
    } else {
        showErrorMessage(error.response?.data?.message || 'Failed to create user.');
    }
};

const setupFormValidation = () => {
    // Real-time password confirmation validation
    const passwordConfirmation = document.getElementById('password_confirmation');
    if (passwordConfirmation) {
        passwordConfirmation.addEventListener('input', function () {
            const password = document.getElementById('password').value;
            const confirmation = this.value;

            // Remove any existing error styling
            this.classList.remove('border-red-500');
            const existingError = this.parentNode.querySelector('.text-red-500');
            if (existingError) {
                existingError.remove();
            }

            if (confirmation && password !== confirmation) {
                this.classList.add('border-red-500');
                const errorDiv = document.createElement('div');
                errorDiv.className = 'text-red-500 text-sm mt-1';
                errorDiv.textContent = 'Passwords do not match';
                this.parentNode.appendChild(errorDiv);
            }
        });
    }
};

const initializeImagePreview = () => {
    const avatarInput = document.getElementById('avatar');
    
    avatarInput.addEventListener('change', (e) => {
        const file = e.target.files[0];
        if (!file) {
            // Remove preview if no file selected
            const preview = document.getElementById('avatar-preview');
            if (preview) {
                preview.remove();
            }
            return;
        }

        // Validate file type
        if (!file.type.startsWith('image/')) {
            showErrorMessage('Please select a valid image file.');
            avatarInput.value = '';
            return;
        }

        // Validate file size (2MB)
        if (file.size > 2 * 1024 * 1024) {
            showErrorMessage('File size must be less than 2MB.');
            avatarInput.value = '';
            return;
        }

        // Create preview
        const reader = new FileReader();
        reader.onload = (e) => {
            console.log('Image selected:', file.name);
            
            // Create or update preview element
            let preview = document.getElementById('avatar-preview');
            if (!preview) {
                preview = document.createElement('div');
                preview.id = 'preview-container';
                preview.className = 'mt-3';
                preview.innerHTML = `
                    <img id="avatar-preview" src="#" alt="Image Preview"
                         class="w-32 h-32 object-cover rounded-lg border border-gray-300" />
                `;
                avatarInput.parentNode.appendChild(preview);
                preview = document.getElementById('avatar-preview');
            }
            
            preview.src = e.target.result;
            preview.style.display = 'block';
        };
        reader.readAsDataURL(file);
    });
};
