export function renderPagination(currentPage, lastPage) {
    const container = $('#pagination');
    container.empty();

    if (lastPage <= 1) return;

    const maxVisible = 5;
    let startPage = Math.max(currentPage - Math.floor(maxVisible / 2), 1);
    let endPage = startPage + maxVisible - 1;

    if (endPage > lastPage) {
        endPage = lastPage;
        startPage = Math.max(endPage - maxVisible + 1, 1);
    }

    // « Previous
    container.append(`
        <li class="page-item ${currentPage === 1 ? 'disabled' : ''}">
            <a class="page-link" href="#" data-page="${currentPage - 1}">«</a>
        </li>
    `);

    if (startPage > 1) {
        container.append(`<li class="page-item"><a class="page-link" data-page="1">1</a></li>`);
        if (startPage > 2) {
            container.append(`<li class="page-item disabled"><span class="page-link">...</span></li>`);
        }
    }

    for (let page = startPage; page <= endPage; page++) {
        const active = page === currentPage ? 'active' : '';
        container.append(`
            <li class="page-item ${active}">
                <a class="page-link" data-page="${page}">${page}</a>
            </li>
        `);
    }

    if (endPage < lastPage) {
        if (endPage < lastPage - 1) {
            container.append(`<li class="page-item disabled"><span class="page-link">...</span></li>`);
        }
        container.append(`<li class="page-item"><a class="page-link" data-page="${lastPage}">${lastPage}</a></li>`);
    }

    // » Next
    container.append(`
        <li class="page-item ${currentPage === lastPage ? 'disabled' : ''}">
            <a class="page-link" data-page="${currentPage + 1}">»</a>
        </li>
    `);
}
