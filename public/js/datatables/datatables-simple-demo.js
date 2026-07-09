window.addEventListener('DOMContentLoaded', function () {
    const table = document.getElementById('datatablesSimple');

    if (!table || typeof DataTable === 'undefined') {
        return;
    }

    new DataTable(table, {
        responsive: true,
        autoWidth: false,
        order: [],
        columnDefs: [
            { responsivePriority: 1, targets: 0 },
            { responsivePriority: 2, targets: 1 },
            { responsivePriority: 3, targets: -1 },
        ],
    });
});
