
document.getElementById('clearFiltersBtn')?.addEventListener('click', function (e) {
    e.preventDefault();

    document.getElementById('created_at').value = '';

    const provinceInput = document.querySelector('input[name="operation"]');
    if (provinceInput) {
        provinceInput.value = '';
    }

    const searchInput = document.querySelector('.search-input');
    if (searchInput) {
        searchInput.value = '';
    }

    window.location.href = window.location.pathname;
});
