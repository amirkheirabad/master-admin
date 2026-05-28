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
    $('#category_id').select2();
});



const csrf = document.querySelector('meta[name="csrf-token"]').content;

$('#editVideoForm').on('submit', function (e) {
    e.preventDefault()

    const id = document.getElementById('video_id').value

    const submitBtn = this.querySelector('button[type="submit"]');

    submitBtn.classList.add('btn-loading');
    submitBtn.disabled = true;

    fetch(`/update-video/${id}`,{
        method: 'post',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': csrf,
            'Accept': 'application/json'
        },
        body: JSON.stringify({
            category_id: $('#category_id').val(),
            description: $('#description').val(),
            title: $('#title').val(),

        }),
    })
        .then(res => res.json())
        .then(data => {
            if (data.errors) {
                showBackendErrors(data.errors);
                submitBtn.classList.remove('btn-loading');
                submitBtn.disabled = false;
            }

            if (data.redirect) {
                window.location.href = data.redirect;
            }
        })
        .catch(err => {
            console.log(err);
            submitBtn.classList.remove('btn-loading');
            submitBtn.disabled = false;
        });
});
