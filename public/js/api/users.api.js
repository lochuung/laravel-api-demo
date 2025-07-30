export async function getUser(id) {
    return await api.get(`/users/${id}`);
}

export async function getUsers(params = {}) {
    return await api.get('/users', {params});
}

export async function getUserWithOrders(id) {
    return await api.get(`/users/${id}/orders`);
}

export async function updateUser(id, data) {
    return await api.put(`/users/${id}`, data);
}

export async function deleteUser(id) {
    return await api.delete(`/users/${id}`);
}

export async function createUser(userData) {
    return await api.post('/users', userData);
}
