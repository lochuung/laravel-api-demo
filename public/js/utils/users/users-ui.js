export const renderUsers = (users = []) => {
    const container = $('#user-table-body');
    container.empty();

    if (users.length === 0) {
        return container.append(`
            <tr>
                <td colspan="7" class="text-center text-muted py-4">
                    <i class="fas fa-users fa-3x mb-3 text-muted"></i>
                    <h5>No users found</h5>
                    <p>Try adjusting your search or filter criteria.</p>
                </td>
            </tr>
        `);
    }

    users.forEach(user => {
        const joined = new Date(user.created_at).toLocaleDateString('vi-VN');
        const roleBadge = getRoleBadge(user.role);

        container.append(`
            <tr>
                <td>#${user.id}</td>
                <td><img src="${user.profile_picture || 'https://via.placeholder.com/40'}"
                         class="rounded-circle" alt="${user.name}" width="40" height="40"></td>
                <td>
                    <div>
                        <h6 class="mb-0">${user.name}</h6>
                        <small class="text-muted text-capitalize">${user.role}</small>
                    </div>
                </td>
                <td>${user.email}</td>
                <td>${joined}</td>
                <td><span class="badge ${roleBadge} text-capitalize">${user.role}</span></td>
                <td>
                    <div class="btn-group" role="group">
                        <a href="/users/${user.id}" class="btn btn-sm btn-outline-info" title="View">
                            <i class="fas fa-eye"></i>
                        </a>
                        <a href="/users/${user.id}/edit" class="btn btn-sm btn-outline-warning" title="Edit">
                            <i class="fas fa-edit"></i>
                        </a>
                        <button class="btn btn-sm btn-outline-danger delete-user" title="Delete" data-id="${user.id}">
                            <i class="fas fa-trash"></i>
                        </button>
                    </div>
                </td>
            </tr>
        `);
    });
};

const getRoleBadge = (role) => {
    switch (role?.toLowerCase()) {
        case 'admin': return 'bg-success';
        case 'moderator': return 'bg-warning';
        case 'user': return 'bg-info';
        default: return 'bg-secondary';
    }
};
