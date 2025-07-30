import {getUser, updateUser, deleteUser} from '../../api/users.api.js';
import {uploadImage} from "../../api/upload.api.js";

let currentUserId = null;

$(document).ready(async function () {
    showLoadingState();
    currentUserId = getIdFromUrl('users');
    await setupEditUser();
    hideLoadingState();
});

async function setupEditUser() {
    try {
        const response = await getUser(currentUserId);
        const user = response?.data?.data;

        if (!user) {
            showErrorMessage('User not found.');
            return;
        }

        fillDataToForm(user);
        setUpButtons(user);
    } catch (error) {
        console.error('Error fetching user data:', error);
        showErrorMessage('Failed to load user data.');
    }
}

function fillDataToForm(user) {
    $('#profile_picture').prop('src', user.profile_picture || '/images/default-profile.png');
    $('#name').val(user.name);
    $('#email').val(user.email);
    $('#phone').val(user.phone_number);
    $('#address').val(user.address);
    $('#is_active').prop('checked', user.is_active);
    $('#email_verified').prop('checked', user.email_verified);
    $('#role').val(user.role);
    $('#created_at').val(user.created_at);
}

function setUpButtons(user) {
    $('#view-btn').prop('href', `/users/${currentUserId}`);
    $('#confirmDeleteBtn').on('click', handleDeleteUser);
    $('#editUserForm').on('submit', handleUpdateUser);
}

async function handleDeleteUser() {
    try {
        const response = await deleteUser(currentUserId);
        if (response.status === 200) {
            showSuccessMessage('User deleted successfully.');
            window.location.href = '/users';
        } else {
            showErrorMessage('Failed to delete user.');
        }
    } catch (error) {
        console.error('Delete error:', error);
        showErrorMessage(error.response?.data?.message || 'Delete failed.');
    }
}

async function handleUpdateUser(event) {
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
        showError({errors: error.response?.data?.errors || {}});
    } finally {
        hideLoadingState();
    }
}

async function buildUserFormData() {
    const formData = {
        name: $('#name').val(),
        email: $('#email').val(),
        phone_number: $('#phone').val(),
        address: $('#address').val(),
        email_verified: $('#email_verified').is(':checked'),
        role: $('#role').val(),
        is_active: $('#is_active').is(':checked'),
    };

    const avatarFile = $('#avatar')[0].files[0];
    if (avatarFile) {
        const avatarUrl = await uploadImage(avatarFile);
        if (avatarUrl) {
            formData.profile_picture = avatarUrl;
        }
    }

    const password = $('#password').val().trim();
    if (password) {
        formData.password = password;
        formData.password_confirmation = $('#password_confirmation').val();
    }

    return formData;
}
