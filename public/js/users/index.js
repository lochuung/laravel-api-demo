import {renderUsers} from '../utils/users/users-ui.js';
import {renderPagination} from '../utils/pagination-utils.js';

const PER_PAGE = 8;
let currentFilters = {
    page: 1,
    per_page: PER_PAGE
};

$(document).ready(async function () {
    setupEventListeners();
    await getUsers(currentFilters);
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
        $('#user-table-body').html('<tr><td colspan="7" class="text-center text-danger">Error loading data.</td></tr>');
    }
}
