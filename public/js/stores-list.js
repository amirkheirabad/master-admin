function countActiveFilters() {
    let count = 0;

    document.querySelectorAll('#filterMenu select').forEach(select => {
        const value = select.value;
        const firstOptionValue = select.options[0]?.value || '';

        if (value && value !== '' && value !== firstOptionValue) {
            count++;
        }
    });

    document.querySelectorAll('#filterMenu input[type="text"]:not(.search-input)').forEach(input => {
        if (input.value.trim() !== '') {
            count++;
        }
    });

    return count;
}

function updateFilterBadge() {
    const badge = document.getElementById('filterBadge');
    if (!badge) return;

    const activeFiltersCount = countActiveFilters();

    if (activeFiltersCount > 0) {
        badge.textContent = activeFiltersCount;
        badge.style.display = 'inline-block';
    } else {
        badge.style.display = 'none';
    }
}

document.getElementById('clearFiltersBtn')?.addEventListener('click', function (e) {
    e.preventDefault();

    const userSelect = document.querySelector('select[name="user_id"]');
    if (userSelect) {
        userSelect.value = '';
        $(userSelect).trigger('change');
    }

    const provinceInput = document.querySelector('input[name="province"]');
    if (provinceInput) {
        provinceInput.value = '';
    }

    const cityInput = document.querySelector('input[name="city"]');
    if (cityInput) {
        cityInput.value = '';
    }

    const searchInput = document.querySelector('.search-input');
    if (searchInput) {
        searchInput.value = '';
    }

    window.location.href = window.location.pathname;
});

document.addEventListener('DOMContentLoaded', updateFilterBadge);
