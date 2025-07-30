import { renderUsers } from '../../utils/users/users-ui.js';
import { renderPagination } from '../../utils/pagination-utils.js';
import { getUsers as fetchUsers, deleteUser as deleteUserApi } from '../../api/users.api.js';

const PER_PAGE = 8;
let currentFilters = {
    page: 1,
    per_page: PER_PAGE
};

$(document).ready(async function () {
    showLoadingState();
    setupEventListeners();
    await loadUsers(currentFilters);
    hideLoadingState();
});

function setupEventListeners() {
    // Pagination
    $(document).on('click', '#pagination .page-link', function (e) {
        e.preventDefault();
        const page = $(this).data('page');
        if (page) {
            currentFilters.page = page;
            loadUsers(currentFilters);
        }
    });

    // Search input
    $('#search-input').on('input', debounce(() => {
        currentFilters.search = $('#search-input').val().trim() || undefined;
        currentFilters.page = 1;
        loadUsers(currentFilters);
    }, 300));

    $('#search-button').on('click', () => {
        currentFilters.search = $('#search-input').val().trim() || undefined;
        currentFilters.page = 1;
        loadUsers(currentFilters);
    });

    // Role filter
    $('#role-filter').on('change', () => {
        const val = $('#role-filter').val();
        currentFilters.role = val === 'all' ? undefined : val;
        currentFilters.page = 1;
        loadUsers(currentFilters);
    });

    // Clear filters
    $('#clear-filters').on('click', () => {
        $('#search-input').val('');
        $('#role-filter').val('all');
        currentFilters = { page: 1, per_page: PER_PAGE };
        loadUsers(currentFilters);
    });

    // Delete user
    $(document).on('click', '.delete-user', async function (e) {
        e.preventDefault();
        const userId = $(this).data('id');
        const userName = $(this).closest('tr').find('h6').text();

        if (confirm(`Bạn có chắc chắn muốn xóa người dùng "${userName}"?\n\nHành động này không thể hoàn tác!`)) {
            await handleDeleteUser(userId);
        }
    });
}

async function loadUsers(params = {}) {
    try {
        $('#user-table-body').html(`<tr><td colspan="7" class="text-center"><i class="fas fa-spinner fa-spin"></i> Loading...</td></tr>`);
        const response = await fetchUsers(params);
        const { data, meta } = response.data;

        renderUsers(data);
        renderPagination(meta.current_page, meta.last_page);
        updateUserCount(meta.total);

    } catch (error) {
        console.error('Failed to load users:', error);
        showErrorMessage(error.response?.data?.message || 'Có lỗi xảy ra khi tải người dùng');
        $('#user-table-body').html('<tr><td colspan="7" class="text-center text-danger">Error loading data.</td></tr>');
    }
}

async function handleDeleteUser(userId) {
    const deleteBtn = $(`.delete-user[data-id="${userId}"]`);
    const originalHtml = deleteBtn.html();

    try {
        deleteBtn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i>');
        await deleteUserApi(userId);
        showSuccessMessage('Đã xóa người dùng thành công!');
        await loadUsers(currentFilters);

    } catch (error) {
        console.error('Failed to delete user:', error);
        const errorMessage = error.response?.data?.message || 'Có lỗi xảy ra khi xóa người dùng';
        showErrorMessage(errorMessage);
        deleteBtn.prop('disabled', false).html(originalHtml);
    }
}
