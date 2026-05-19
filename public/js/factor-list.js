function resetAllFilters() {
    document.getElementById('factor_date').value = '';
    document.getElementById('created_at').value = '';
    document.getElementById('payed_factor_data').value = '';

    const priceStatus = document.querySelector('select[name="price_status"]');
    if (priceStatus) priceStatus.selectedIndex = 0;

    const storeSelect = document.querySelector('select[name="store_id"]');
    if (storeSelect) {
        storeSelect.value = '';
        const event = new Event('change', { bubbles: true });
        storeSelect.dispatchEvent(event);
    }

    $('select.custom-select-input').each(function() {
        $(this).prop('selectedIndex', 0);
        const wrapper = this.nextElementSibling;
        if (wrapper && wrapper.classList.contains('cs-wrapper')) {
            const firstOption = this.options[0];
            if (firstOption) {
                $(wrapper).find('.cs-trigger-text').text(firstOption.text);
                $(wrapper).find('input[type="hidden"]').val(firstOption.value);
                $(wrapper).find('.cs-option').each(function(index) {
                    if (index === 0) $(this).addClass('cs-selected');
                    else $(this).removeClass('cs-selected');
                });
            }
        }
    });

    const categorySelect = document.querySelector('select[name="category_id"]');
    if (categorySelect) {
        categorySelect.value = '';
        const event = new Event('change', { bubbles: true });
        categorySelect.dispatchEvent(event);
    }
}

document.getElementById('clearFiltersBtn')?.addEventListener('click', function(e) {
    e.preventDefault();
    resetAllFilters();
});

function setPaymentData(id, title, amount) {
    let formattedAmount = new Intl.NumberFormat('fa-IR').format(amount);

    let html = `
        <div style="direction: rtl; text-align: right;">
            <div style="background: white; border-radius: 12px; padding: 15px; margin-bottom: 25px; box-shadow: 0 1px 3px rgba(0,0,0,0.05); border-right: 3px solid #0e2d55;">
                <div style="display: flex; justify-content: space-between; margin-bottom: 12px; padding-bottom: 10px; border-bottom: 1px solid #e9ecef;">
                    <span style="color: #64748b; font-size: 13px;">شماره فاکتور</span>
                    <span style="color: #0e2d55; font-weight: 600;">${id}</span>
                </div>
                <div style="display: flex; justify-content: space-between; margin-bottom: 12px; padding-bottom: 10px; border-bottom: 1px solid #e9ecef;">
                    <span style="color: #64748b; font-size: 13px;">عنوان</span>
                    <span style="color: #1e293b;">${title || 'فاکتور فروش'}</span>
                </div>
                <div style="display: flex; justify-content: space-between;">
                    <span style="color: #64748b; font-size: 13px;">مبلغ قابل پرداخت</span>
                    <span class='price-display-class' style="color: #0e2d55; font-weight: 700; font-size: 18px;">${formattedAmount} <span style="font-size: 12px;">تومان</span></span>
                </div>
            </div>

            <p style="margin-bottom: 15px; color: #334155; font-weight: 500; font-size: 14px;">بانک خود را انتخاب کنید:</p>
            <div style="display: flex; gap: 15px; justify-content: center; flex-wrap: wrap;">
                <div class="bank-option" data-bank="mellat" style="cursor: pointer; text-align: center; width: 100px; padding: 12px 8px; background: white; border-radius: 12px; transition: all 0.2s; border: 2px solid #e2e8f0;">
                    <img src="{{ asset('icons/mellat.svg') }}" width="50" style="margin-bottom: 8px;">
                    <div style="font-size: 12px; color: #475569;">بانک ملت</div>
                </div>
                <div class="bank-option" data-bank="saman" style="cursor: pointer; text-align: center; width: 100px; padding: 12px 8px; background: white; border-radius: 12px; transition: all 0.2s; border: 2px solid #e2e8f0;">
                    <img src="{{ asset('icons/saman.svg') }}" width="50" style="margin-bottom: 8px;">
                    <div style="font-size: 12px; color: #475569;">بانک سامان</div>
                </div>
                <div class="bank-option" data-bank="iranzamin" style="cursor: pointer; text-align: center; width: 100px; padding: 12px 8px; background: white; border-radius: 12px; transition: all 0.2s; border: 2px solid #e2e8f0;">
                    <img src="{{ asset('icons/iran-zamin.svg') }}" width="50" style="margin-bottom: 8px;">
                    <div style="font-size: 12px; color: #475569;">بانک ایران زمین</div>
                </div>
            </div>
            <input type="hidden" id="selectedBank" value="">
            <input type="hidden" id="factorId" value="${id}">
        </div>
    `;

    document.getElementById('paymentModalBody').innerHTML = html;

    document.querySelectorAll('.bank-option').forEach(el => {
        el.onclick = function() {
            document.querySelectorAll('.bank-option').forEach(opt => {
                opt.style.border = '2px solid #e2e8f0';
                opt.style.background = 'white';
            });
            this.style.border = '2px solid #0e2d55';
            this.style.background = '#f0f4f8';
            document.getElementById('selectedBank').value = this.getAttribute('data-bank');
        };
    });
}

document.getElementById('confirmPaymentBtn')?.addEventListener('click', function() {
    let bank = document.getElementById('selectedBank').value;
    let factorId = document.getElementById('factorId').value;

    if (!bank) {
        Swal.fire({
            icon: 'warning',
            title: 'توجه',
            text: 'لطفاً یک بانک را انتخاب کنید',
            confirmButtonText: 'باشه',
            confirmButtonColor: '#0e2d55'
        });
        return;
    }

    Swal.fire({
        icon: 'info',
        title: 'در حال پردازش',
        text: 'در حال انتقال به درگاه بانک ' + bank + ' برای فاکتور شماره ' + factorId,
        confirmButtonText: 'ادامه',
        confirmButtonColor: '#0e2d55'
    }).then((result) => {
        if (result.isConfirmed) {
            $('#paymentModal').modal('hide');
        }
    });
    window.location.href = `/factor/pay/${factorId}`;

});
