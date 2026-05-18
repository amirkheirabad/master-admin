$(document).ready(function() {
    $('.select2').select2();
});
$('.select2').select2({
    placeholder: "انتخاب کنید",
    allowClear: true,
    width: '100%',
    language: {
        noResults: function () {
            return "نتیجه‌ای یافت نشد";
        }
    }
});
$(document).ready(function() {
    $('#store_name').select2();
});
$(document).ready(function() {
    $('#category_name').select2();
});


document.querySelector('form').addEventListener('submit', function() {
    let priceInput = document.getElementById('price');
    if (priceInput) {
        priceInput.value = priceInput.value.replace(/,/g, '');
    }
});


const csrf = document.querySelector('meta[name="csrf-token"]').content;


$('#createFactor').on('submit', function (e) {
    e.preventDefault()

    // گرفتن دکمه سابمیت
    const submitBtn = this.querySelector('button[type="submit"]');

    // فعال کردن حالت لودینگ
    submitBtn.classList.add('btn-loading');
    submitBtn.disabled = true;

    fetch(`/factor-create`,{
        method: 'post',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': csrf,
            'Accept': 'application/json'
        },
        body: JSON.stringify({
            store_id: $('#store_id').val(),
            factor_date: $('#factor_date').val(),
            price: $('#price').val().replace(/,/g, ''),
            category_id: $('#category_id').val(),
            description: $('#description').val(),
            show_status: $('#show_status').val(),
            name: $('#name').val(),
            phone: $('#phone').val(),
            national_kod: $('#national_kod').val(),
        }),
    })
        .then(res => res.json())
        .then(data => {
            if (data.errors) {
                showBackendErrors(data.errors);
                // برگردوندن دکمه به حالت عادی در صورت خطا
                submitBtn.classList.remove('btn-loading');
                submitBtn.disabled = false;
            }

            if (data.redirect) {
                // اگه ریدایرکت داره، نیازی به برگردوندن دکمه نیست
                window.location.href = data.redirect;
            }
        })
        .catch(err => {
            console.log(err);
            alert('خطا در ارتباط با سرور');
            // برگردوندن دکمه به حالت عادی در صورت خطا
            submitBtn.classList.remove('btn-loading');
            submitBtn.disabled = false;
        });
});




$(document).on('click','.delete-factor', function (e) {
    e.preventDefault();

    let id = $(this).data('id');

    Swal.fire({
        title:'حذف دسته بندی',
        text: 'آیا از حذف دسته بندی مطمئن هستید؟',
        icon: 'question',
        showCancelButton: true,
        confirmButtonText: 'بله',
        cancelButtonText: 'خیر',
    }).then((result) => {
        if (result.isConfirmed) {

            fetch(`/delete-factor/${id}`, {
                headers: {
                    "X-CSRF-Token": csrf,
                    'Accept': 'application/json',
                    'Content-Type': 'application/json'
                },
                method: "DELETE",
            })
                .then(function (response) {
                    if(response.ok)
                    {
                        Swal.fire({
                            title: "حذف شد!",
                            text: "دسته بندی با موفقیت حذف شد",
                            icon: "success",
                            showCancelButton: false,
                            confirmButtonText: "بستن",
                        })
                        $(`.item-record${id}`).remove()
                    }
                    else {
                        Swal.fire({
                            title: "خطا!",
                            text: "حذف دسته بندی انجام نشد",
                            icon: "error",
                            showCancelButton: false,
                            confirmButtonText: "بستن",
                        });
                    }
                })
        }
    })
})



document.querySelectorAll('#copyHash').forEach(item => {

    item.addEventListener('click', function () {

        const factorId = this.getAttribute('data-id');

        fetch(`/factor/hash/${factorId}`)

            .then(res => res.json())

            .then(data => {

                navigator.clipboard.writeText(data.hash).then(() => {

                    Swal.fire({
                        icon: 'success',
                        title: 'کپی شد',
                        text: 'هش فاکتور با موفقیت کپی شد',
                        timer: 1500,
                        showConfirmButton: false
                    });

                });

            })

            .catch(err => {

                console.error(err);

                Swal.fire({
                    icon: 'error',
                    title: 'خطا',
                    text: 'مشکلی در کپی هش رخ داد'
                });

            });

    });

});


const accountType = document.getElementById('account_type');
const agencyInputs = document.querySelectorAll('.agency-field');
const storeField = document.getElementById('store_id').closest('.col-md-4');

accountType.addEventListener('change', function () {
    if (this.value === 'agency') {
        agencyInputs.forEach(el => el.style.display = 'block');
        if (storeField) storeField.style.display = 'none';
    } else {
        agencyInputs.forEach(el => el.style.display = 'none');
        if (storeField) storeField.style.display = 'block';
    }
});

if (accountType.value === 'agency') {
    agencyInputs.forEach(el => el.style.display = 'block');
    if (storeField) storeField.style.display = 'none';
} else {
    agencyInputs.forEach(el => el.style.display = 'none');
    if (storeField) storeField.style.display = 'block';
}



function formatPrice(price) {
    let numericPrice = String(price).replace(/[^0-9]/g, '');

    if (numericPrice === '') {
        return '';
    }

    return numericPrice.replace(/\B(?=(\d{3})+(?!\d))/g, ',');
}

function formatPriceOnInput(event) {
    let input = event.target;
    input.value = formatPrice(input.value);
}

function formatDisplayPrice(elementId) {
    let element = document.getElementById(elementId);
    if (element) {
        element.textContent = formatPrice(element.textContent);
    }
}

let priceInput = document.getElementById('price');
if (priceInput) {
    priceInput.addEventListener('input', formatPriceOnInput);
    formatPriceOnInput({ target: priceInput });
}

document.addEventListener('DOMContentLoaded', function() {
    formatDisplayPrice('price-display');
    formatDisplayPrice('price-display2');
});



document.addEventListener('DOMContentLoaded', function() {
    const priceInput = document.getElementById('price');
    const displayPriceToman = document.getElementById('display_price_toman');

    function formatPrice(price) {
        if (isNaN(price)) {
            return '0';
        }
        return parseFloat(price).toLocaleString('en');
    }

    function updatePriceDisplay() {
        const priceValue = priceInput.value;
        const cleanedPrice = priceValue.replace(/,/g, '').replace(/ تومان/g, '');

        if (cleanedPrice === '' || isNaN(cleanedPrice)) {
            displayPriceToman.textContent = '0 تومان';
            return;
        }
        displayPriceToman.textContent = formatPrice(cleanedPrice/10) + ' تومان';

    }

    priceInput.addEventListener('input', updatePriceDisplay);

    updatePriceDisplay();
});



