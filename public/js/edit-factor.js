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


const showStatusRadios = document.querySelectorAll('input[name="show_status"]');
let selectedShowStatus = null;
showStatusRadios.forEach(radio => {
    if (radio.checked) {
        selectedShowStatus = radio.value;
    }
});


$('#editFactor').on('submit', function (e) {
    e.preventDefault()

    const id = document.getElementById('factor_id').value;

    const csrf = document.querySelector('meta[name="csrf-token"]').content;

    let formData = new FormData()

    formData.append('category_id', document.getElementById('category_id').value);
    formData.append('price', document.getElementById('price').value.replace(/,/g, ''));
    formData.append('price_status', document.getElementById('price_status').value);
    formData.append('show_status', document.getElementById('show_status').value);
    formData.append('description', document.getElementById('description').value);
    formData.append('paid_factor_date', document.getElementById('paid_factor_date').value);
    formData.append('factor_date', document.getElementById('factor_date').value);

    const imageInput = document.getElementById('imageInput');

    if (imageInput.files && imageInput.files.length > 0) {
        formData.append('image', imageInput.files[0]);
    }

    const image = document.getElementById('imageInput').files[0];


    fetch(`/factor-update/${id}`,{
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': csrf,
            'Accept': 'application/json',
        },
        body: formData
        })
        .then(res => res.json())
        .then(data => {
            if (data.errors) {
                showBackendErrors(data.errors);
            }

            if (data.redirect) {
                window.location.href = data.redirect;
            }
        })
        .catch(err => {
            console.log(err);
            showServerConnectionError();
        });
})

document.querySelectorAll('label.card-option').forEach(label => {
    label.addEventListener('click', function () {
        document.querySelectorAll('label.card-option').forEach(l => l.classList.remove('active'));

        this.classList.add('active');
    });
});

document.addEventListener('DOMContentLoaded', () => {
    const checked = document.querySelector('input[name="payment_status"]:checked');
    if (checked) checked.closest('label.card-option').classList.add('active');
});

const priceStatusSelect = document.getElementById('price_status');
const dateInputWrapper = document.querySelector('.col-md-4.mt-4[style*="display: none"]');

function toggleDateField() {
    if (priceStatusSelect.value === '3') {
        dateInputWrapper.style.display = 'block';
    } else {
        dateInputWrapper.style.display = 'none';
    }
}

priceStatusSelect.addEventListener('change', toggleDateField);

toggleDateField();

function formatPrice(price) {
    // اول فارسی به انگلیسی تبدیل کن
    let converted = String(price)
        .replace(/[۰-۹]/g, d => '۰۱۲۳۴۵۶۷۸۹'.indexOf(d))
        .replace(/[٠-٩]/g, d => '٠١٢٣٤٥٦٧٨٩'.indexOf(d));

    let numericPrice = converted.replace(/[^0-9]/g, '');

    if (numericPrice === '') return '';

    return numericPrice.replace(/\B(?=(\d{3})+(?!\d))/g, ',');
}

function formatPriceOnInput(event) {
    let input = event.target;
    input.value = formatPrice(input.value);
}

function formatDisplayPriceByClass(className) {
    let elements = document.getElementsByClassName(className);
    for (let i = 0; i < elements.length; i++) {
        elements[i].textContent = formatPrice(elements[i].textContent);
    }
}

let priceInputs = document.getElementsByClassName('price-input-class');
if (priceInputs.length > 0) {
    for (let i = 0; i < priceInputs.length; i++) {
        priceInputs[i].addEventListener('input', formatPriceOnInput);
        formatPriceOnInput({ target: priceInputs[i] });
    }
}

document.addEventListener('DOMContentLoaded', function() {
    formatDisplayPriceByClass('price-display-class');
});



