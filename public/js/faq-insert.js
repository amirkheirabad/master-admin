CKEDITOR.disableAutoInline = true;
CKEDITOR.config.versionCheck = false;

CKEDITOR.replace('answer', {
    width: '100%',
    language: 'fa',
    contentsLangDirection: 'rtl',
    customConfig: '',
    allowedContent: true
});



const csrf = document.querySelector('meta[name="csrf-token"]').content;


$('#FAQForm').on('submit', function (e) {
    e.preventDefault()

    const submitBtn = this.querySelector('button[type="submit"]');

    submitBtn.classList.add('btn-loading');
    submitBtn.disabled = true;

    for (var instance in CKEDITOR.instances) {
        CKEDITOR.instances[instance].updateElement();
    }

    fetch(`/faq_create`,{
        method: 'post',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': csrf,
            'Accept': 'application/json'
        },
        body: JSON.stringify({
            question: $('#question').val(),
            answer: $('#answer').val(),

        }),
    })
        .then(res => {
            if (res.status === 500) {
                throw new Error(res.status);
            }
            return res.json();
        })
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
            showServerConnectionError();
            submitBtn.classList.remove('btn-loading');
            submitBtn.disabled = false;
        });
});
