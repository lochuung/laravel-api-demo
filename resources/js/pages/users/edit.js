import { 
    showLoadingState, 
    hideLoadingState, 
    showErrorMessage,
    showSuccessMessage,
    waitForUser,
    withButtonControl,
    showError,
    getIdFromUrl
} from '../../utils/common.js';
import { getUser, updateUser, deleteUser } from '../../api/users.api.js';
import { uploadImage } from '../../api/upload.api.js';

let currentUserId = null;

document.addEventListener('DOMContentLoaded', () => {
    // Get user ID from URL
    currentUserId = getIdFromUrl('users');
    
    if (!currentUserId) {
        showErrorMessage('Invalid user ID');
        return;
    }

    // Wait for user data to be available
    waitForUser(async () => {
        await loadUserData();
        initializeForm();
        initializeImagePreview();
        initializeModal();
    });
});

const loadUserData = async () => {
    try {
        showLoadingState();
        const response = await getUser(currentUserId);
        
        if (response.status === 200) {
            const user = response.data.data;
            populateForm(user);
        }
    } catch (error) {
        showErrorMessage('Failed to load user data');
        console.error('Error loading user:', error);
    } finally {
        hideLoadingState();
    }
};

const populateForm = (user) => {
    // Populate form fields
    document.getElementById('name').value = user.name || '';
    document.getElementById('email').value = user.email || '';
    document.getElementById('phone').value = user.phone_number || '';
    document.getElementById('address').value = user.address || '';
    document.getElementById('role').value = user.role || 'User';
    document.getElementById('is_active').checked = user.is_active || false;
    document.getElementById('email_verified').checked = user.email_verified_at !== null;
    
    // Set profile picture
    const profilePicture = document.getElementById('profile_picture');
    if (user.profile_picture) {
        profilePicture.src = user.profile_picture;
    }
    
    // Set created date
    const createdAt = document.getElementById('created_at');
    if (user.created_at) {
        createdAt.textContent = new Date(user.created_at).toLocaleDateString();
    }
};

const initializeForm = () => {
    const form = document.getElementById('editUserForm');
    
    form.addEventListener('submit', async (e) => {
        e.preventDefault();
        await handleUpdateUser(e);
    });

    // View button
    const viewBtn = document.getElementById('view-btn');
    if (viewBtn) {
        viewBtn.addEventListener('click', (e) => {
            e.preventDefault();
            window.location.href = `/users/${currentUserId}`;
        });
    }
};

const handleUpdateUser = async (event) => {
    event.preventDefault();
    showLoadingState();

    try {
        const formData = await buildUserFormData();
        const response = await updateUser(currentUserId, formData);

        if (response.status === 200) {
            showSuccessMessage('User updated successfully.');
            window.location.href = `/users/${currentUserId}`;
        } else {
            showErrorMessage('Update failed.');
        }
    } catch (error) {
        console.error('Update error:', error);
        handleValidationErrors(error);
    } finally {
        hideLoadingState();
    }
};

const buildUserFormData = async () => {
    const formData = {
        name: document.getElementById('name').value.trim(),
        email: document.getElementById('email').value.trim(),
        phone_number: document.getElementById('phone').value.trim() || null,
        address: document.getElementById('address').value.trim() || null,
        email_verified: document.getElementById('email_verified').checked,
        role: document.getElementById('role').value,
        is_active: document.getElementById('is_active').checked,
    };

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

    const password = document.getElementById('password').value.trim();
    if (password) {
        formData.password = password;
        formData.password_confirmation = document.getElementById('password_confirmation').value;
    }

    return formData;
};

const handleValidationErrors = (error) => {
    if (error.response?.data?.errors) {
        showError(error.response.data);
    } else {
        showErrorMessage(error.response?.data?.message || 'Failed to update user.');
    }
};

const initializeImagePreview = () => {
    const avatarInput = document.getElementById('avatar');
    const preview = document.getElementById('avatar-preview');
    
    avatarInput.addEventListener('change', (e) => {
        const file = e.target.files[0];
        if (!file) {
            preview.style.display = 'none';
            return;
        }

        // Validate file type
        if (!file.type.startsWith('image/')) {
            showErrorMessage('Please select a valid image file.');
            avatarInput.value = '';
            preview.style.display = 'none';
            return;
        }

        // Validate file size (2MB)
        if (file.size > 2 * 1024 * 1024) {
            showErrorMessage('File size must be less than 2MB.');
            avatarInput.value = '';
            preview.style.display = 'none';
            return;
        }

        // Create preview
        const reader = new FileReader();
        reader.onload = (e) => {
            preview.src = e.target.result;
            preview.classList.remove('hidden');
            
            // Also update the main profile picture
            const profilePicture = document.getElementById('profile_picture');
            profilePicture.src = e.target.result;
        };
        reader.readAsDataURL(file);
    });
};

const initializeModal = () => {
    const deleteModalTrigger = document.getElementById('delete-modal-trigger');
    const deleteModal = document.getElementById('deleteModal');
    const cancelDelete = document.getElementById('cancelDelete');
    const confirmDeleteBtn = document.getElementById('confirmDeleteBtn');

    // Show modal
    deleteModalTrigger.addEventListener('click', () => {
        deleteModal.classList.remove('hidden');
    });

    // Hide modal
    const hideModal = () => {
        deleteModal.classList.add('hidden');
    };

    cancelDelete.addEventListener('click', hideModal);
    
    // Click outside to close
    deleteModal.addEventListener('click', (e) => {
        if (e.target === deleteModal) {
            hideModal();
        }
    });

    // Confirm delete
    confirmDeleteBtn.addEventListener('click', async () => {
        hideModal();
        await handleDeleteUser();
    });
};

const handleDeleteUser = async () => {
    try {
        showLoadingState();
        const response = await deleteUser(currentUserId);
        
        if (response.status === 200) {
            showSuccessMessage('User deleted successfully');
            
            // Redirect to users list after a short delay
            setTimeout(() => {
                window.location.href = '/users';
            }, 1500);
        }
    } catch (error) {
        showErrorMessage(error.response?.data?.message || 'Failed to delete user');
        console.error('Error deleting user:', error);
    } finally {
        hideLoadingState();
    }
};
