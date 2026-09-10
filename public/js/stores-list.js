function countActiveFilters() {
    let count = 0;

    document.querySelectorAll('#filterMenu select').forEach(select => {
        const value = select.value;
        const firstOptionValue = select.options[0]?.value || '';

        if (value && value !== firstOptionValue) count++;
    });

    document.querySelectorAll('#filterMenu input[type="text"]:not(.search-input)').forEach(input => {
        if (input.value.trim()) count++;
    });

    return count;
}

function updateFilterBadge() {
    const badge = document.getElementById('filterBadge');
    if (!badge) return;

    const count = countActiveFilters();
    badge.textContent = count;
    badge.style.display = count ? 'inline-block' : 'none';
}

document.getElementById('clearFiltersBtn')?.addEventListener('click', function (event) {
    event.preventDefault();

    const userSelect = document.querySelector('select[name="user_id"]');
    if (userSelect) {
        userSelect.value = '';
        $(userSelect).trigger('change');
    }

    ['province', 'city'].forEach(name => {
        const input = document.querySelector(`input[name="${name}"]`);
        if (input) input.value = '';
    });

    const searchInput = document.querySelector('.search-input');
    if (searchInput) searchInput.value = '';

    window.location.href = window.location.pathname;
});

document.addEventListener('DOMContentLoaded', function () {
    updateFilterBadge();

    const modal = document.getElementById('myModal');
    const form = document.getElementById('checkListForm');
    if (!modal || !form) return;

    const rows = [...modal.querySelectorAll('[data-checklist-row]')];
    const status = document.getElementById('checklistStatus');
    const saveButton = document.getElementById('checklistSaveButton');

    function syncRow(row) {
        const checked = row.querySelector('.checklist-checkbox').checked;
        row.classList.toggle('is-selected', checked);
    }

    function setCommentOpen(button, open) {
        const panel = document.getElementById(button.getAttribute('aria-controls'));
        button.classList.toggle('is-open', open);
        button.setAttribute('aria-expanded', open ? 'true' : 'false');
        panel?.classList.toggle('is-open', open);
    }

    function resetModal() {
        document.getElementById('report').value = '';
        rows.forEach(row => {
            row.querySelector('.checklist-checkbox').checked = false;
            const textarea = row.querySelector('[data-comment-id]');
            const button = row.querySelector('.checklist-comment-toggle');
            textarea.value = '';
            button.classList.remove('has-comment');
            setCommentOpen(button, false);
            syncRow(row);
        });

        status.hidden = true;
        status.classList.remove('is-error');
    }

    rows.forEach(row => {
        const checkbox = row.querySelector('.checklist-checkbox');
        const button = row.querySelector('.checklist-comment-toggle');
        const textarea = row.querySelector('[data-comment-id]');

        row.addEventListener('click', function (event) {
            if (event.target.closest('button, input, textarea, label, .checklist-comment-panel')) return;
            checkbox.checked = !checkbox.checked;
            syncRow(row);
        });

        row.addEventListener('keydown', function (event) {
            if (event.target !== row || !['Enter', ' '].includes(event.key)) return;
            event.preventDefault();
            checkbox.checked = !checkbox.checked;
            syncRow(row);
        });

        checkbox.addEventListener('change', () => syncRow(row));

        button.addEventListener('click', function (event) {
            event.stopPropagation();
            const open = button.getAttribute('aria-expanded') !== 'true';
            setCommentOpen(button, open);
            if (open) textarea.focus();
        });

        textarea.addEventListener('click', event => event.stopPropagation());
        textarea.addEventListener('input', () => button.classList.toggle('has-comment', Boolean(textarea.value.trim())));
        setCommentOpen(button, button.getAttribute('aria-expanded') === 'true');
        syncRow(row);
    });

    document.querySelectorAll('.open-checklist-modal').forEach(button => {
        button.addEventListener('click', async function () {
            resetModal();
            document.getElementById('store_id').value = this.dataset.id;
            document.getElementById('store_name').textContent = this.dataset.name;
            form.classList.add('is-loading');
            saveButton.disabled = true;
            status.textContent = 'در حال دریافت اطلاعات چک‌لیست…';
            status.hidden = false;

            try {
                const response = await fetch(this.dataset.url, { headers: { Accept: 'application/json' } });
                if (!response.ok) throw new Error('Checklist request failed');

                const data = await response.json();
                const selected = new Set((data.check_lists || []).map(String));
                document.getElementById('report').value = data.report || '';

                rows.forEach(row => {
                    const checkbox = row.querySelector('.checklist-checkbox');
                    const textarea = row.querySelector('[data-comment-id]');
                    const comment = data.comments?.[textarea.dataset.commentId] || '';
                    checkbox.checked = selected.has(checkbox.value);
                    textarea.value = comment;
                    row.querySelector('.checklist-comment-toggle').classList.toggle('has-comment', Boolean(comment));
                    syncRow(row);
                });

                status.hidden = true;
                saveButton.disabled = false;
            } catch (error) {
                status.textContent = 'دریافت اطلاعات انجام نشد. لطفاً دوباره تلاش کنید.';
                status.classList.add('is-error');
            } finally {
                form.classList.remove('is-loading');
            }
        });
    });

    form.addEventListener('submit', function () {
        saveButton.disabled = true;
        saveButton.querySelector('span').textContent = 'در حال ذخیره…';
    });

    if (form.dataset.hasErrors === 'true') {
        rows.forEach(syncRow);
        $('#myModal').modal('show');
    }
});
