document.querySelector('.search-input')?.addEventListener('keypress', function(e) {
    if(e.key === 'Enter') {
        e.preventDefault();
        document.getElementById('filterForm').submit();
    }
});





const mainCheckbox = document.getElementById('main-checkbox');
const allCheckboxes = document.querySelectorAll('tbody .form-check-input');
const operationsButton = document.getElementById('operations-button');

function checkCheckboxes() {
    let atLeastOneChecked = false;
    allCheckboxes.forEach(checkbox => {
        if (checkbox.checked) {
            atLeastOneChecked = true;
        }
    });

    if (atLeastOneChecked) {
        operationsButton.classList.remove('btn-white-new');
        operationsButton.classList.add('btn-beta-solid');
        operationsButton.disabled = false;
    } else {
        operationsButton.classList.remove('btn-beta-solid');
        operationsButton.classList.add('btn-white-new');
        operationsButton.disabled = true;
    }
}

mainCheckbox.addEventListener('change', function() {
    const isChecked = this.checked;
    allCheckboxes.forEach(checkbox => {
        checkbox.checked = isChecked;
    });
    checkCheckboxes();
});

allCheckboxes.forEach(checkbox => {
    checkbox.addEventListener('change', checkCheckboxes);
});
document.addEventListener('DOMContentLoaded', checkCheckboxes);




// دکمه حذف فیلترها
const clearFiltersBtn = document.getElementById('clearFiltersBtn');

if (clearFiltersBtn) {
    clearFiltersBtn.addEventListener('click', function() {
        // reset کردن سلکت‌های معمولی (غیر custom)
        const regularSelects = document.querySelectorAll('select:not(.custom-select-input)');
        regularSelects.forEach(select => {
            select.selectedIndex = 0;
        });

        // reset کردن سلکت‌های custom-select-input
        const customSelects = document.querySelectorAll('select.custom-select-input');
        customSelects.forEach(select => {
            // reset کردن مقدار سلکت اصلی
            select.selectedIndex = 0;

            // پیدا کردن instance پلاگین و آپدیت کردن ظاهر
            const wrapper = select.nextElementSibling;
            if (wrapper && wrapper.classList.contains('cs-wrapper')) {
                const triggerText = wrapper.querySelector('.cs-trigger-text');
                const options = wrapper.querySelectorAll('.cs-option');
                const hiddenInput = wrapper.querySelector('input[type="hidden"]');

                // پیدا کردن اولین آپشن (مقدار پیش‌فرض)
                const firstOption = select.options[0];
                if (firstOption) {
                    // آپدیت متن نمایشی
                    if (triggerText) {
                        triggerText.textContent = firstOption.text;
                    }

                    // آپدیت hidden input
                    if (hiddenInput) {
                        hiddenInput.value = firstOption.value;
                    }

                    // آپدیت کلاس selected در آپشن‌ها
                    options.forEach(opt => {
                        const value = opt.getAttribute('data-value');
                        if (value === firstOption.value || (!value && firstOption.value === '')) {
                            opt.classList.add('cs-selected');
                        } else {
                            opt.classList.remove('cs-selected');
                        }
                    });
                }
            }
        });

        const searchInput = document.querySelector('.search-input');
        if (searchInput) {
            searchInput.value = '';
        }

        const form = document.getElementById('filterForm');
        if (form) {
            form.submit();
        }

        updateClearButtonStyle();

    });
}


function countActiveFilters() {
    let count = 0;

    const selects = document.querySelectorAll('select');
    selects.forEach(select => {
        const value = select.value;
        const firstOptionValue = select.options[0]?.value || '';

        if (value && value !== '' && value !== firstOptionValue) {
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

function applyFiltersAndUpdateBadge() {
    // آپدیت بج
    updateFilterBadge();

    // submit کردن فرم
    const form = document.getElementById('filterForm');
    if (form) {
        form.submit();
    }
}

function clearFiltersAndUpdateBadge() {
    // reset کردن سلکت‌های معمولی
    const selects = document.querySelectorAll('select');
    selects.forEach(select => {
        select.selectedIndex = 0;
    });

    // reset کردن سلکت‌های custom
    const customSelects = document.querySelectorAll('select.custom-select-input');
    customSelects.forEach(select => {
        select.selectedIndex = 0;

        const wrapper = select.nextElementSibling;
        if (wrapper && wrapper.classList.contains('cs-wrapper')) {
            const triggerText = wrapper.querySelector('.cs-trigger-text');
            const options = wrapper.querySelectorAll('.cs-option');
            const hiddenInput = wrapper.querySelector('input[type="hidden"]');

            const firstOption = select.options[0];
            if (firstOption) {
                if (triggerText) triggerText.textContent = firstOption.text;
                if (hiddenInput) hiddenInput.value = firstOption.value;

                options.forEach(opt => {
                    const value = opt.getAttribute('data-value');
                    if (value === firstOption.value || (!value && firstOption.value === '')) {
                        opt.classList.add('cs-selected');
                    } else {
                        opt.classList.remove('cs-selected');
                    }
                });
            }
        }
    });

    // آپدیت بج
    updateFilterBadge();

    // submit کردن فرم
    const form = document.getElementById('filterForm');
    if (form) {
        form.submit();
    }
}

document.addEventListener('DOMContentLoaded', function() {
    // آپدیت اولیه بج (اگه فیلتری از قبل انتخاب شده باشه)
    updateFilterBadge();

    // تغییر دکمه اعمال
    const submitBtn = document.querySelector('#filterForm button[type="submit"]');
    if (submitBtn) {
        submitBtn.addEventListener('click', function(e) {
            e.preventDefault();
            applyFiltersAndUpdateBadge();
        });
    }

    // تغییر دکمه حذف فیلترها
    const clearBtn = document.getElementById('clearFiltersBtn');
    if (clearBtn) {
        clearBtn.addEventListener('click', function(e) {
            e.preventDefault();
            clearFiltersAndUpdateBadge();
        });
    }
});


// تابع کمکی برای اضافه/حذف کلاس
function updateRowBackground(checkbox) {
    const row = checkbox.closest('tr');
    if (row && row.classList.contains('responsive-table-row')) {
        checkbox.checked ? row.classList.add('bg-default') : row.classList.remove('bg-default');
    }
}

// آپدیت event listenerهای چک‌باکس
document.querySelectorAll('tbody .form-check-input').forEach(checkbox => {
    // حذف listener قدیمی (اگه باشه)
    checkbox.removeEventListener('change', checkCheckboxes);
    // اضافه کردن listener جدید
    checkbox.addEventListener('change', function() {
        updateRowBackground(this);
        checkCheckboxes();
    });
});

// آپدیت main checkbox
if (mainCheckbox) {
    mainCheckbox.addEventListener('change', function() {
        const isChecked = this.checked;
        document.querySelectorAll('tbody .form-check-input').forEach(checkbox => {
            checkbox.checked = isChecked;
            updateRowBackground(checkbox);
        });
        checkCheckboxes();
    });
}

// اجرا اولیه برای هماهنگی
document.addEventListener('DOMContentLoaded', function() {
    document.querySelectorAll('tbody .form-check-input').forEach(checkbox => {
        updateRowBackground(checkbox);
    });
});

// تابع برای آپدیت استایل دکمه حذف فیلترها
function updateClearButtonStyle() {
    const clearBtn = document.getElementById('clearFiltersBtn');
    if (!clearBtn) return;

    // چک کردن وجود فیلتر فعال
    let hasFilter = false;

    // چک کردن همه سلکت‌ها
    document.querySelectorAll('select').forEach(select => {
        const firstValue = select.options[0]?.value || '';
        if (select.value && select.value !== '' && select.value !== firstValue) {
            hasFilter = true;
        }
    });

    // چک کردن search input
    const searchInput = document.querySelector('.search-input');
    if (searchInput && searchInput.value && searchInput.value.trim() !== '') {
        hasFilter = true;
    }

    // اعمال کلاس‌ها (این بخش رو اصلاح کن)
    if (hasFilter) {
        clearBtn.classList.add('text-beta', 'pointer');
        clearBtn.classList.remove('text-default');
    } else {
        clearBtn.classList.remove('text-beta', 'pointer');
        clearBtn.classList.add('text-default');
    }
}

// اجرا هنگام لود صفحه
document.addEventListener('DOMContentLoaded', function() {
    updateClearButtonStyle();

    //监听 تغییرات سلکت‌ها
    document.querySelectorAll('select').forEach(select => {
        select.addEventListener('change', updateClearButtonStyle);
    });

    //听 تغییرات search input
    const searchInput = document.querySelector('.search-input');
    if (searchInput) {
        searchInput.addEventListener('input', updateClearButtonStyle);
    }
});

document.addEventListener('DOMContentLoaded', function() {

    // ================ خواندن دیتاها از HTML ================
    function loadFiltersFromHTML() {
        const filterGroups = document.querySelectorAll('.filters-data .filter-group');
        const filterData = {};

        filterGroups.forEach(group => {
            const type = group.getAttribute('data-type');
            const title = group.getAttribute('data-title');
            const options = [];

            group.querySelectorAll('span[data-value]').forEach(span => {
                const value = span.getAttribute('data-value');
                const label = span.textContent.trim();

                options.push({ value: value, label: label });
            });

            filterData[type] = { title, options };
        });

        return filterData;
    }

    function loadSelectedFromHTML() {
        const selectedContainer = document.querySelector('.selected-filters-data');
        if (!selectedContainer) return {
            store: null,
            status: null,
            contact_name: null,
            priority: null,
            sort: 'latest'
        };

        const selectedStoreSpan = selectedContainer.querySelector('[data-selected-store]');
        const selectedStatusSpan = selectedContainer.querySelector('[data-selected-status]');
        const selectedContactNameSpan = selectedContainer.querySelector('[data-selected-contact-name]');
        const selectedSortSpan = selectedContainer.querySelector('[data-selected-sort]');
        const selectedPrioritySpan = selectedContainer.querySelector('[data-selected-priority]');


        let storeValue = selectedStoreSpan ? selectedStoreSpan.getAttribute('data-selected-store') || '' : '';
        let statusValue = selectedStatusSpan ? selectedStatusSpan.getAttribute('data-selected-status') || '' : '';
        let contactNameValue = selectedContactNameSpan ? selectedContactNameSpan.getAttribute('data-selected-contact-name') || '' : '';
        let priorityValue = selectedPrioritySpan ? selectedPrioritySpan.getAttribute('data-selected-priority') || '' : '';


        return {
            store: storeValue === '' ? null : storeValue,
            status: statusValue === '' ? null : statusValue,
            contact_name: contactNameValue === '' ? null : contactNameValue,
            priority: priorityValue === '' ? null : priorityValue,
            sort: selectedSortSpan ? selectedSortSpan.getAttribute('data-selected-sort') || 'latest' : 'latest'
        };
    }

    // دیتاهای فیلترها از HTML
    const filterData = loadFiltersFromHTML();

    // مقدارهای انتخاب شده
    let selectedFilters = loadSelectedFromHTML();

    // ================ گرفتن المنت‌ها ================
    const modal = document.getElementById('mobileFilterModal');
    const filterBtn = document.getElementById('filterDropdown');
    const overlay = document.querySelector('#mobileFilterModal .mobile-filter-overlay');
    const closeBtn = document.querySelector('#mobileFilterModal .mobile-filter-close');
    const clearBtn = document.getElementById('mobileClearFilters');
    const applyBtn = document.getElementById('mobileApplyFilters');
    const mainPage = document.querySelector('.filter-main-page');
    const subPages = document.querySelectorAll('.filter-sub-page');
    const filterTitle = document.querySelector('#mobileFilterModal .mobile-filter-title');

    // دسکتاپ dropdown
    const desktopMenu = document.querySelector('.desktop-filter-menu');
    let isDesktopMenuOpen = false;

    // بررسی موبایل
    function isMobile() {
        return window.innerWidth < 768;
    }

    // تابع کمکی برای گرفتن متن از روی value از filter-group مربوطه
    function getTextFromFilterGroup(type, value) {
        if (!value || value === '') return 'همه';

        const filterGroup = document.querySelector(`.filters-data .filter-group[data-type="${type}"]`);
        if (!filterGroup) return value;

        const targetSpan = filterGroup.querySelector(`span[data-value="${value}"]`);
        return targetSpan ? targetSpan.textContent.trim() : value;
    }

    function getActiveFiltersCount() {
        let count = 0;
        if (selectedFilters.store && selectedFilters.store !== null) count++;
        if (selectedFilters.status && selectedFilters.status !== null) count++;
        if (selectedFilters.contact_name && selectedFilters.contact_name !== null) count++;
        if (selectedFilters.priority && selectedFilters.priority !== null) count++;
        if (selectedFilters.sort && selectedFilters.sort !== 'latest') count++;
    
        return count;
    }

    function hasActiveFilters() {
        return getActiveFiltersCount() > 0;
    }

    function updateClearButtonState() {
        if (!clearBtn) return;

        const activeCount = getActiveFiltersCount();

        if (hasActiveFilters()) {
            clearBtn.removeAttribute('disabled');
            clearBtn.innerHTML = 'حذف فیلترها (' + activeCount + ')';
        } else {
            clearBtn.setAttribute('disabled', 'disabled');
            clearBtn.innerHTML = 'حذف فیلترها';
        }
    }

    function updateFilterBadge() {
        const badge = document.getElementById('filterBadge');
        if (!badge) return;

        const filterCount = getActiveFiltersCount();

        if (filterCount > 0) {
            badge.textContent = filterCount;
            badge.style.display = 'inline-block';
        } else {
            badge.style.display = 'none';
        }
    }

    function updateSelectedValuesDisplay() {
        // update store
        const storeContainer = document.getElementById('selected-store-value');
        if (storeContainer) {
            storeContainer.innerHTML = '';
            if (selectedFilters.store && selectedFilters.store !== null) {
                const storeName = getTextFromFilterGroup('store', selectedFilters.store);
                const badge = document.createElement('span');
                badge.className = 'filter-badge filter-badge-active';
                badge.textContent = storeName;
                storeContainer.appendChild(badge);
            } else {
                const defaultSpan = document.createElement('span');
                defaultSpan.className = 'filter-default-text';
                defaultSpan.textContent = 'همه';
                storeContainer.appendChild(defaultSpan);
            }
        }

        // update status
        const statusContainer = document.getElementById('selected-status-value');
        if (statusContainer) {
            statusContainer.innerHTML = '';
            if (selectedFilters.status && selectedFilters.status !== null) {
                const statusText = getTextFromFilterGroup('status', selectedFilters.status);
                const badge = document.createElement('span');
                badge.className = 'filter-badge filter-badge-active';
                badge.textContent = statusText;
                statusContainer.appendChild(badge);
            } else {
                const defaultSpan = document.createElement('span');
                defaultSpan.className = 'filter-default-text';
                defaultSpan.textContent = 'همه';
                statusContainer.appendChild(defaultSpan);
            }
        }

        // update contact_name (team)
        const teamContainer = document.getElementById('selected-team-value');
        if (teamContainer) {
            teamContainer.innerHTML = '';
            if (selectedFilters.contact_name && selectedFilters.contact_name !== null) {
                const teamText = getTextFromFilterGroup('team', selectedFilters.contact_name);
                const badge = document.createElement('span');
                badge.className = 'filter-badge filter-badge-active';
                badge.textContent = teamText;
                teamContainer.appendChild(badge);
            } else {
                const defaultSpan = document.createElement('span');
                defaultSpan.className = 'filter-default-text';
                defaultSpan.textContent = 'همه';
                teamContainer.appendChild(defaultSpan);
            }
        }
        //update priority
            const priorityContainer = document.getElementById('selected-priority-value');
            if (priorityContainer) {
                priorityContainer.innerHTML = '';
                if (selectedFilters.priority && selectedFilters.priority !== null) {
                    const priorityText = getTextFromFilterGroup('priority', selectedFilters.priority);
                    const badge = document.createElement('span');
                    badge.className = 'filter-badge filter-badge-active';
                    badge.textContent = priorityText;
                    priorityContainer.appendChild(badge);
                } else {
                    const defaultSpan = document.createElement('span');
                    defaultSpan.className = 'filter-default-text';
                    defaultSpan.textContent = 'همه';
                    priorityContainer.appendChild(defaultSpan);
                }
            }

        updateFilterBadge();
        updateClearButtonState();
    }

    // ================ توابع مودال ================
    function openMobileModal() {
        modal.classList.add('active');
        document.body.style.overflow = 'hidden';
        showMainPage();
    }

    function closeMobileModal() {
        modal.classList.remove('active');
        document.body.style.overflow = '';
        setTimeout(() => {
            showMainPage();
        }, 300);
    }

    function showMainPage() {
        if (mainPage) mainPage.style.display = 'block';
        subPages.forEach(page => {
            page.style.display = 'none';
        });
        if (filterTitle) filterTitle.textContent = 'فیلترها';

        const backBtn = document.querySelector('#mobileFilterModal .mobile-filter-back');
        if (backBtn) backBtn.style.display = 'none';

        updateSelectedValuesDisplay();
    }

    function showSubPage(pageType) {
        if (mainPage) mainPage.style.display = 'none';
        subPages.forEach(page => {
            if (page.getAttribute('data-filter-page') === pageType) {
                page.style.display = 'block';
            } else {
                page.style.display = 'none';
            }
        });

        let title = '';
        if (pageType === 'store') title = 'نام فروشگاه';
        else if (pageType === 'status') title = 'وضعیت';
        else if (pageType === 'team') title = 'تیم مخاطب';
        else if (pageType === 'priority') title = 'اولویت';

        if (filterTitle) filterTitle.textContent = title;

        const backBtn = document.querySelector('#mobileFilterModal .mobile-filter-back');
        if (backBtn) backBtn.style.display = 'block';

        populateSubPage(pageType);
    }

    function populateSubPage(pageType) {
        const data = filterData[pageType];
        const subPage = document.querySelector(`.filter-sub-page[data-filter-page="${pageType}"]`);
        const container = subPage?.querySelector('.filter-sub-content');

        if (!container || !data) return;
        container.innerHTML = '';

        // اضافه کردن گزینه "همه" در ابتدا
        const allOption = document.createElement('div');
        let isAllActive = false;

        if (pageType === 'store') {
            isAllActive = (!selectedFilters.store || selectedFilters.store === null);
        } else if (pageType === 'status') {
            isAllActive = (!selectedFilters.status || selectedFilters.status === null);
        } else if (pageType === 'team') {
            isAllActive = (!selectedFilters.contact_name || selectedFilters.contact_name === null);
        } else if (pageType === 'priority') {
            isAllActive = (!selectedFilters.priority || selectedFilters.priority === null);
        }

        allOption.className = 'filter-sub-item' + (isAllActive ? ' active' : '');
        allOption.setAttribute('data-value', '');
        allOption.innerHTML = `
            <span>همه</span>
            <i class="fa fa-check check-icon"></i>
        `;
        allOption.addEventListener('click', function() {
            container.querySelectorAll('.filter-sub-item').forEach(item => {
                item.classList.remove('active');
            });
            this.classList.add('active');

            if (pageType === 'store') {
                selectedFilters.store = null;
            } else if (pageType === 'status') {
                selectedFilters.status = null;
            } else if (pageType === 'team') {
                selectedFilters.contact_name = null;
            } else if (pageType === 'priority') {
                selectedFilters.priority = null;
            }

            setTimeout(() => {
                showMainPage();
            }, 150);
        });
        container.appendChild(allOption);

        data.options.forEach(opt => {
            //跳过 value 为空的那个选项 (همه)
            if (opt.value === '') return;

            let isActive = false;

            if (pageType === 'store') {
                isActive = (selectedFilters.store == opt.value);
            } else if (pageType === 'status') {
                isActive = (selectedFilters.status == opt.value);
            } else if (pageType === 'team') {
                isActive = (selectedFilters.contact_name == opt.value);
            } else if (pageType === 'priority') {
                isActive = (selectedFilters.priority == opt.value);
            }

            const div = document.createElement('div');
            div.className = 'filter-sub-item' + (isActive ? ' active' : '');
            div.setAttribute('data-value', opt.value);
            div.innerHTML = `
                <span>${opt.label}</span>
                <i class="fa fa-check check-icon"></i>
            `;

            div.addEventListener('click', function() {
                container.querySelectorAll('.filter-sub-item').forEach(item => {
                    item.classList.remove('active');
                });
                this.classList.add('active');

                const selectedValue = this.getAttribute('data-value');

                if (pageType === 'store') {
                    selectedFilters.store = selectedValue;
                } else if (pageType === 'status') {
                    selectedFilters.status = selectedValue;
                } else if (pageType === 'team') {
                    selectedFilters.contact_name = selectedValue;
                }else if (pageType === 'priority') {
                    selectedFilters.priority = selectedValue;
                }

                setTimeout(() => {
                    showMainPage();
                }, 150);
            });

            container.appendChild(div);
        });
    }

    function clearFilters() {
        selectedFilters = {
            store: null,
            status: null,
            contact_name: null,
            priority: null,
            sort: selectedFilters.sort
        };
        updateSelectedValuesDisplay();
    }

    // ===================== تابع applyFiltersAndSubmit اصلاح شده =====================
    function applyFiltersAndSubmit() {
        const form = document.getElementById('filterForm');
        if (!form) {
            console.error('فرم filterForm پیدا نشد!');
            return;
        }

        // ===== حذف همه hidden input های قبلی =====
        const oldHidden = form.querySelectorAll('input[name="store_id"], input[name="status"], input[name="contact_name"], input[name="sort"], input[name="priority"]');
        oldHidden.forEach(input => input.remove());

        // ===== هماهنگ کردن select های داخل فرم با مقادیر جدید =====
        // برای store_id
        const storeSelect = form.querySelector('select[name="store_id"]');
        if (storeSelect) {
            storeSelect.value = selectedFilters.store || '';
        } else if (selectedFilters.store && selectedFilters.store !== null) {
            const input = document.createElement('input');
            input.type = 'hidden';
            input.name = 'store_id';
            input.value = selectedFilters.store;
            form.appendChild(input);
        }

        // برای status
        const statusSelect = form.querySelector('select[name="status"]');
        if (statusSelect) {
            statusSelect.value = selectedFilters.status || '';
        } else if (selectedFilters.status && selectedFilters.status !== null) {
            const input = document.createElement('input');
            input.type = 'hidden';
            input.name = 'status';
            input.value = selectedFilters.status;
            form.appendChild(input);
        }

        // برای contact_name
        const contactSelect = form.querySelector('select[name="contact_name"]');
        if (contactSelect) {
            contactSelect.value = selectedFilters.contact_name || '';
        } else if (selectedFilters.contact_name && selectedFilters.contact_name !== null) {
            const input = document.createElement('input');
            input.type = 'hidden';
            input.name = 'contact_name';
            input.value = selectedFilters.contact_name;
            form.appendChild(input);
        }
        //برای priority
        const prioritySelect = form.querySelector('select[name="priority"]');
        if (prioritySelect) {
            prioritySelect.value = selectedFilters.priority || '';
        } else if (selectedFilters.priority && selectedFilters.priority !== null) {
            const input = document.createElement('input');
            input.type = 'hidden';
            input.name = 'priority';
            input.value = selectedFilters.priority;
            form.appendChild(input);
        }

        // برای sort
        const sortSelect = form.querySelector('select[name="sort"]');
        if (sortSelect) {
            sortSelect.value = selectedFilters.sort || 'latest';
        } else if (selectedFilters.sort) {
            const input = document.createElement('input');
            input.type = 'hidden';
            input.name = 'sort';
            input.value = selectedFilters.sort;
            form.appendChild(input);
        }

        form.submit();
    }
    // ===================== پایان اصلاح =====================

    // ================ رویدادها ================
    if (filterBtn && desktopMenu) {
        filterBtn.addEventListener('click', function(e) {
            if (isMobile()) {
                e.preventDefault();
                openMobileModal();
            } else {
                e.preventDefault();
                e.stopPropagation();
                isDesktopMenuOpen = !isDesktopMenuOpen;
                desktopMenu.style.display = isDesktopMenuOpen ? 'block' : 'none';
            }
        });

        document.addEventListener('click', function(e) {
            if (!isMobile() && isDesktopMenuOpen && !filterBtn.contains(e.target) && !desktopMenu.contains(e.target)) {
                desktopMenu.style.display = 'none';
                isDesktopMenuOpen = false;
            }
        });
    }

    const backBtn = document.querySelector('#mobileFilterModal .mobile-filter-back');
    if (backBtn) {
        backBtn.addEventListener('click', showMainPage);
    }

    if (overlay) overlay.addEventListener('click', closeMobileModal);
    if (closeBtn) closeBtn.addEventListener('click', closeMobileModal);

    // اصلاح دکمه حذف فیلترها در مودال
    if (clearBtn) {
        clearBtn.addEventListener('click', function() {
            clearFilters();
            const form = document.getElementById('filterForm');
            if (form) {
                // ریست کردن select های فرم
                const selects = form.querySelectorAll('select');
                selects.forEach(select => {
                    select.selectedIndex = 0;
                });

                // حذف hidden input ها
                const paramsToRemove = ['store_id', 'status', 'contact_name', 'sort', 'priority'];
                paramsToRemove.forEach(param => {
                    const inputs = form.querySelectorAll(`input[name="${param}"]`);
                    inputs.forEach(input => input.remove());
                });

                form.submit();
            }
            closeMobileModal();
        });
    }

    if (applyBtn) applyBtn.addEventListener('click', function() {
        applyFiltersAndSubmit();
        closeMobileModal();
    });

    const filterOptions = document.querySelectorAll('#mobileFilterModal .filter-option-item');
    filterOptions.forEach(option => {
        option.addEventListener('click', function() {
            const filterType = this.getAttribute('data-filter-type');
            showSubPage(filterType);
        });
    });

    // ================ کد مرتب‌سازی ================
    (function() {
        let selectedSort = 'latest';
        let tempSelectedSort = 'latest';

        const selectedContainerSort = document.querySelector('.selected-filters-data');
        if (selectedContainerSort) {
            const selectedSortSpan = selectedContainerSort.querySelector('[data-selected-sort]');
            if (selectedSortSpan) {
                selectedSort = selectedSortSpan.getAttribute('data-selected-sort') || 'latest';
                tempSelectedSort = selectedSort;
                selectedFilters.sort = selectedSort;
            }
        }

        const sortModal = document.getElementById('mobileSortModal');
        const sortBtn = document.getElementById('mobileSortBtn');
        const sortOverlay = document.querySelector('#mobileSortModal .mobile-filter-overlay');
        const sortCloseBtn = document.querySelector('#mobileSortModal .mobile-sort-close');
        const sortMainPage = document.querySelector('#mobileSortModal .filter-sort-main-page');

        function updateSortModalUI() {
            if (!sortMainPage) return;
            const items = sortMainPage.querySelectorAll('.filter-option-item');
            items.forEach(item => {
                const sortType = item.getAttribute('data-sort-type');
                const checkIcon = item.querySelector('.check-icon');
                if (sortType === tempSelectedSort) {
                    item.classList.add('active');
                    if (checkIcon) checkIcon.style.display = 'inline-block';
                } else {
                    item.classList.remove('active');
                    if (checkIcon) checkIcon.style.display = 'none';
                }
            });
        }

        function openSortModal() {
            if (!sortModal) return;
            tempSelectedSort = selectedSort;
            updateSortModalUI();
            sortModal.classList.add('active');
            document.body.style.overflow = 'hidden';
        }

        function closeSortModal() {
            if (!sortModal) return;
            sortModal.classList.remove('active');
            document.body.style.overflow = '';
        }

        // ===================== تابع applySortAndClose اصلاح شده =====================
        function applySortAndClose(sortType) {
            selectedSort = sortType;
            tempSelectedSort = sortType;
            selectedFilters.sort = selectedSort;

            const form = document.getElementById('filterForm');
            if (form) {
                // حذف hidden input های قبلی sort
                const oldSortInputs = form.querySelectorAll('input[name="sort"]');
                oldSortInputs.forEach(input => input.remove());

                // آپدیت select sort اگر وجود داره
                const sortSelect = form.querySelector('select[name="sort"]');
                if (sortSelect) {
                    sortSelect.value = selectedSort;
                } else {
                    const input = document.createElement('input');
                    input.type = 'hidden';
                    input.name = 'sort';
                    input.value = selectedSort;
                    form.appendChild(input);
                }

                form.submit();
            }
            closeSortModal();
        }
        // ===================== پایان اصلاح =====================

        if (sortBtn) {
            sortBtn.addEventListener('click', function(e) {
                e.preventDefault();
                openSortModal();
            });
        }

        if (sortOverlay) sortOverlay.addEventListener('click', closeSortModal);
        if (sortCloseBtn) sortCloseBtn.addEventListener('click', closeSortModal);

        if (sortMainPage) {
            const items = sortMainPage.querySelectorAll('.filter-option-item');
            items.forEach(item => {
                item.addEventListener('click', function() {
                    const sortType = this.getAttribute('data-sort-type');
                    if (sortType) {
                        applySortAndClose(sortType);
                    }
                });
            });
        }
    })();

    // ================ مقداردهی اولیه ================
    updateSelectedValuesDisplay();
});
