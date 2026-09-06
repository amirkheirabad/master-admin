document.querySelectorAll('.team-delete-form').forEach(form => {
    form.addEventListener('submit', event => {
        event.preventDefault();

        return Swal.fire({
            title: 'حذف تیم',
            text: `آیا از حذف تیم «${form.dataset.teamName}» مطمئن هستید؟`,
            icon: 'question',
            showCancelButton: true,
            confirmButtonText: 'بله، حذف شود',
            cancelButtonText: 'خیر',
        }).then(result => {
            if (result.isConfirmed) {
                form.submit();
            }
        });
    });
});
