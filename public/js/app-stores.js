
if ($('#token').length > 0) {

    $("#btn-generate-token").on('click', function () {
        $("#token").val(randomAlphaNumeric10())
    });

    function randomAlphaNumeric10() {
        let chars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';
        let result = '';

        for (let i = 0; i < 20; i++) {
            result += chars.charAt(Math.floor(Math.random() * chars.length));
        }

        return result;
    }
}

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
    $('#user_id').select2();
});

const csrf = document.querySelector('meta[name="csrf-token"]').content;

$('#editForm').on('submit', function (e) {

    e.preventDefault();

    clearAllBackendErrors();

    const id = $(this).data('id');

    fetch(`/update/${id}`, {

        method: 'PUT',

        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': csrf,
            'Accept': 'application/json'
        },

        body: JSON.stringify({

            store_name: $('#store_name').val(),
            user_id: $('#user_id').val(),
            link: $('#link').val(),
            slogan: $('#slogan').val(),
            phone: $('#phone').val(),
            province: $('#province').val(),
            city: $('#city').val(),
            location: $('#location').val(),
            code_posty: $('#code_posty').val(),
            latitude: $('#latitude').val(),
            longitude: $('#longitude').val(),
            about: $('#about').val(),
            token: $('#token').val(),

        }),

    })

    .then(async (res) => {

        const data = await res.json();

        if (!res.ok) {

            if (data.errors) {
                showBackendErrors(data.errors);
            }

            return;
        }

        if (data.redirect) {
            window.location.href = data.redirect;
        }

    })

    .catch(err => {
        console.log(err);
    });

});

function setError(fieldId, message) {

    const errorElement = document.getElementById(fieldId + "_error");

    if (errorElement) {
        errorElement.textContent = message;
    }
}

function showBackendErrors(errors) {

    clearAllBackendErrors();

    Object.keys(errors).forEach(field => {

        const message = Array.isArray(errors[field])
            ? errors[field][0]
            : errors[field];

        setError(field, message);

    });
}

function clearAllBackendErrors() {

    const errorElements = document.querySelectorAll('[id$="_error"]');

    errorElements.forEach(element => {
        element.textContent = '';
    });
}

