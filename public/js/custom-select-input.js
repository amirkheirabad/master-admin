/**
 * CustomSelect Plugin - نسخه نهایی
 * ویژگی‌ها:
 * - آیکون chevron-down
 * - بدون انیمیشن
 * - تیک سمت چپ فقط برای گزینه انتخاب شده
 * - اولین آپشن به صورت پیش‌فرض انتخاب شده
 * - فونت یکسان برای title و متن
 */
(function(global) {

    class CustomSelect {
        constructor(selectElement, options = {}) {
            this.selectElement = selectElement;
            this.options = {
                title: selectElement.getAttribute('data-title') || '',
                ...options
            };

            this.selectedValue = '';
            this.selectedText = '';
            this.name = selectElement.getAttribute('name');
            this.wrapper = null;
            this.hiddenInput = null;

            this.init();
        }

        init() {
            // مخفی کردن سلکت اصلی
            this.selectElement.style.display = 'none';

            // ساختار HTML پلاگین
            this.wrapper = document.createElement('div');
            this.wrapper.className = 'cs-wrapper';

            // آیکون chevron-down (SVG)
            const chevronSvg = `<svg class="cs-chevron" viewBox="0 0 24 24" width="16" height="16" fill="none" stroke="#666" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="6 9 12 15 18 9"></polyline></svg>`;

            const titleHtml = this.options.title ? `<span class="cs-trigger-label">${this.options.title}</span>` : '';

            this.wrapper.innerHTML = `
                <div class="cs-trigger">
                    <div class="cs-trigger-content">
                        ${titleHtml}
                        <span class="cs-trigger-text"></span>
                    </div>
                    ${chevronSvg}
                </div>
                <div class="cs-options">
                    ${this.getOptionsHtml()}
                </div>
            `;

            // اضافه کردن به DOM
            this.selectElement.parentNode.insertBefore(this.wrapper, this.selectElement.nextSibling);

            // ساخت input مخفی برای ارسال به فرم
            this.hiddenInput = document.createElement('input');
            this.hiddenInput.type = 'hidden';
            this.hiddenInput.name = this.name;
            this.wrapper.appendChild(this.hiddenInput);

            // ذخیره رفرنس‌ها
            this.trigger = this.wrapper.querySelector('.cs-trigger');
            this.triggerText = this.wrapper.querySelector('.cs-trigger-text');
            this.optionsList = this.wrapper.querySelector('.cs-options');
            this.optionItems = this.wrapper.querySelectorAll('.cs-option');

            // رویدادها
            this.bindEvents();

            // مقدار اولیه - اولین آپشن رو انتخاب کن
            this.setInitialValue();
        }

        getOptionsHtml() {
            let html = '';
            for (let i = 0; i < this.selectElement.options.length; i++) {
                const option = this.selectElement.options[i];
                const selectedClass = (i === 0 && !this.selectElement.value) ? 'cs-selected' : ''; // اولین آپشن پیش‌فرض
                html += `
                    <div class="cs-option ${selectedClass}" data-value="${option.value}" data-index="${i}">
                        <span class="cs-option-check"></span>
                        <span class="cs-option-text">${option.text}</span>
                    </div>
                `;
            }
            return html;
        }

        bindEvents() {
            // کلیک روی تریگر
            this.trigger.addEventListener('click', (e) => {
                e.stopPropagation();
                this.toggleDropdown();
            });

            // کلیک روی آپشن‌ها
            this.optionItems.forEach(option => {
                option.addEventListener('click', (e) => {
                    e.stopPropagation();
                    const value = option.getAttribute('data-value');
                    const textSpan = option.querySelector('.cs-option-text');
                    const text = textSpan ? textSpan.textContent : option.textContent;
                    this.selectOption(value, text);
                    this.closeDropdown();
                });
            });

            // بستن با کلیک خارج از المان
            document.addEventListener('click', (e) => {
                if (!this.wrapper.contains(e.target)) {
                    this.closeDropdown();
                }
            });
        }

        setInitialValue() {
            // اگر سلکت اصلی مقدار داشت از اون استفاده کن، وگرنه اولین آپشن
            let selectedOption = null;

            if (this.selectElement.value && this.selectElement.value !== '') {
                // اگه مقدار از قبل تعیین شده بود
                for (let i = 0; i < this.selectElement.options.length; i++) {
                    if (this.selectElement.options[i].value === this.selectElement.value) {
                        selectedOption = this.selectElement.options[i];
                        break;
                    }
                }
            }

            // اگه مقداری نبود، اولین آپشن رو انتخاب کن
            if (!selectedOption && this.selectElement.options.length > 0) {
                selectedOption = this.selectElement.options[0];
            }

            if (selectedOption) {
                this.selectOption(selectedOption.value, selectedOption.text, false);

                // بروزرسانی سلکت اصلی
                this.selectElement.value = selectedOption.value;
            }
        }

        toggleDropdown() {
            // بستن بقیه سلکت‌ها
            document.querySelectorAll('.cs-wrapper').forEach(wrapper => {
                if (wrapper !== this.wrapper) {
                    const trigger = wrapper.querySelector('.cs-trigger');
                    const options = wrapper.querySelector('.cs-options');
                    if (trigger) trigger.classList.remove('cs-open');
                    if (options) options.classList.remove('cs-show');
                }
            });

            this.trigger.classList.toggle('cs-open');
            this.optionsList.classList.toggle('cs-show');
        }

        closeDropdown() {
            this.trigger.classList.remove('cs-open');
            this.optionsList.classList.remove('cs-show');
        }

        selectOption(value, text, triggerChange = true) {
            this.selectedValue = value;
            this.selectedText = text;

            // نمایش متن انتخاب شده
            this.triggerText.textContent = text;

            // بروزرسانی input مخفی
            this.hiddenInput.value = value;

            // بروزرسانی سلکت اصلی
            this.selectElement.value = value;

            // بروزرسانی کلاس selected و تیک در آپشن‌ها
            this.optionItems.forEach(opt => {
                if (opt.getAttribute('data-value') === value) {
                    opt.classList.add('cs-selected');
                } else {
                    opt.classList.remove('cs-selected');
                }
            });

            // تریگر رویداد change
            if (triggerChange) {
                const changeEvent = new Event('change', { bubbles: true });
                this.selectElement.dispatchEvent(changeEvent);
            }
        }

        getValue() {
            return this.selectedValue;
        }

        getText() {
            return this.selectedText;
        }

        destroy() {
            this.selectElement.style.display = '';
            if (this.wrapper) {
                this.wrapper.remove();
            }
        }
    }

    // کلاس اصلی پلاگین
    class CustomSelectPlugin {
        constructor() {
            this.instances = [];
            this.init();
        }

        init() {
            const selects = document.querySelectorAll('select.custom-select-input');
            selects.forEach(select => {
                if (!select.hasAttribute('data-custom-initialized')) {
                    const instance = new CustomSelect(select);
                    this.instances.push(instance);
                    select.setAttribute('data-custom-initialized', 'true');
                }
            });
        }

        addSelect(selectElement) {
            if (selectElement.tagName === 'SELECT' && !selectElement.hasAttribute('data-custom-initialized')) {
                const instance = new CustomSelect(selectElement);
                this.instances.push(instance);
                selectElement.setAttribute('data-custom-initialized', 'true');
                return instance;
            }
            return null;
        }

        refresh() {
            this.init();
        }

        getAllInstances() {
            return this.instances;
        }

        getInstance(selectElement) {
            return this.instances.find(inst => inst.selectElement === selectElement);
        }
    }

    // ایجاد نمونه گلوبال
    const customSelectPlugin = new CustomSelectPlugin();

    global.CustomSelectPlugin = customSelectPlugin;
    global.CustomSelect = CustomSelect;

})(window);

// هندل کردن فرم
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('weatherForm');
    const resultDiv = document.getElementById('result');

    if (form) {
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            const formData = new FormData(this);
            const data = {};
            for (let [key, value] of formData.entries()) {
                data[key] = value;
            }

            resultDiv.innerHTML = `<strong>✅ اطلاعات ارسال شد:</strong><br>${JSON.stringify(data, null, 2)}`;
            resultDiv.style.display = 'block';
        });
    }

});

