const getStatusClass = (status) => {
    const map = {
        completed: 'success',
        processing: 'info',
        pending: 'warning',
        canceled: 'danger'
    };
    return map[status?.toLowerCase()] ?? 'secondary';
};

export {getStatusClass};
