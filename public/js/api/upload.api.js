export async function uploadImage(file) {
    const formData = new FormData();
    formData.append('image', file);

    const response = await api.post('/upload/image', formData, {
        headers: {'Content-Type': 'multipart/form-data'}
    });

    return response?.data?.data?.file_url;
}
