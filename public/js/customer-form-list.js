function updateCustomerFormFilterBadge() {
    const badge = document.getElementById('filterBadge');
    if (!badge) return;

    const count = [...document.querySelectorAll('#filterMenu select')].filter(select => select.value).length;
    badge.textContent = count;
    badge.style.display = count ? 'inline-block' : 'none';
}

document.addEventListener('DOMContentLoaded', updateCustomerFormFilterBadge);

document.getElementById('clearFiltersBtn')?.addEventListener('click', function (event) {
    event.preventDefault();
    window.location.href = window.location.pathname;
});
