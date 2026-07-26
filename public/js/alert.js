document.addEventListener('DOMContentLoaded', function () {
    Swal.fire({
        icon: 'info',
        title: 'راهنمای ثبت تیکت',
        html: `
            قبل از ثبت تیکت، لطفاً راهنمای ثبت تیکت را مطالعه کنید تا درخواست شما سریع‌تر بررسی شود.
        `,
        showCancelButton: true,
        confirmButtonText: 'مشاهده راهنما',
        cancelButtonText: 'بستن'
    }).then((result) => {
        if (result.isConfirmed) {
            window.location.href = "https://support.datamtech.net/%D8%A2%D9%85%D9%88%D8%B2%D8%B4_%D8%AA%DB%8C%DA%A9%D8%AA.pdf";
        }
    });
});
