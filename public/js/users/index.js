import {renderUsers} from '../utils/users/users-ui.js';
import {renderPagination} from '../utils/pagination-utils.js';

const PER_PAGE = 8;
let currentFilters = {
    page: 1,
    per_page: PER_PAGE
};

$(document).ready(async function () {
    showLoadingState();
    setupEventListeners();
    await getUsers(currentFilters);
    hideLoadingState();
});

function setupEventListeners() {
    $(document).on('click', '#pagination .page-link', function (e) {
        e.preventDefault();
        const page = $(this).data('page');
        if (page) {
            currentFilters.page = page;
            getUsers(currentFilters);
        }
    });

    $('#search-input').on('input', debounce(() => {
        currentFilters.search = $('#search-input').val().trim() || undefined;
        currentFilters.page = 1;
        getUsers(currentFilters);
    }, 300));

    $('#search-button').on('click', () => {
        currentFilters.search = $('#search-input').val().trim() || undefined;
        currentFilters.page = 1;
        getUsers(currentFilters);
    });

    $('#role-filter').on('change', () => {
        const val = $('#role-filter').val();
        currentFilters.role = val === 'all' ? undefined : val;
        currentFilters.page = 1;
        getUsers(currentFilters);
    });

    $('#clear-filters').on('click', () => {
        $('#search-input').val('');
        $('#role-filter').val('all');
        currentFilters = {page: 1, per_page: PER_PAGE};
        getUsers(currentFilters);
    });

    // Delete user event listener
    $(document).on('click', '.delete-user', async function (e) {
        e.preventDefault();
        const userId = $(this).data('id');
        const userName = $(this).closest('tr').find('h6').text();

        if (confirm(`Bạn có chắc chắn muốn xóa người dùng "${userName}"?\n\nHành động này không thể hoàn tác!`)) {
            await deleteUser(userId);
        }
    });
}

async function getUsers(params = {}) {
    try {
        $('#user-table-body').html(`<tr><td colspan="7" class="text-center"><i class="fas fa-spinner fa-spin"></i> Loading...</td></tr>`);
        const response = await api.get('/users', {params});
        const {data, meta} = response.data;

        renderUsers(data);
        renderPagination(meta.current_page, meta.last_page);
        updateUserCount(meta.total);
    } catch (e) {
        console.error('Failed to load users:', e);
        showErrorMessage(e.response?.data?.message || 'Có lỗi xảy ra khi tải người dùng');
        $('#user-table-body').html('<tr><td colspan="7" class="text-center text-danger">Error loading data.</td></tr>');
    }
}

async function deleteUser(userId) {
    try {
        // Hiển thị loading trên button
        const deleteBtn = $(`.delete-user[data-id="${userId}"]`);
        const originalHtml = deleteBtn.html();
        deleteBtn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i>');

        // Gọi API delete
        await api.delete(`/users/${userId}`);

        // Hiển thị thông báo thành công
        showSuccessMessage('Đã xóa người dùng thành công!');

        // Cập nhật lại danh sách users
        await getUsers(currentFilters);

    } catch (error) {
        console.error('Failed to delete user:', error);

        // Khôi phục button về trạng thái ban đầu (tìm lại element vì DOM có thể đã thay đổi)
        const deleteBtn = $(`.delete-user[data-id="${userId}"]`);
        if (deleteBtn.length) {
            deleteBtn.prop('disabled', false).html('<i class="fas fa-trash"></i>');
        }

        // Hiển thị lỗi cho người dùng
        const errorMessage = error.response?.data?.message || 'Có lỗi xảy ra khi xóa người dùng';
        showErrorMessage(errorMessage);
    }
}
