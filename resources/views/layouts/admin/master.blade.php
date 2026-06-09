<!DOCTYPE html>
<html lang="fa" dir="rtl">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <!-- Meta, title, CSS, favicons, etc. -->
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="fontiran.com:license" content="Y68A9">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>پنل master-admin </title>
    {{--    <link rel="stylesheet" href="{{ asset('/css/bootstrap.min.css') }}">--}}
    @yield('css')
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="stylesheet" href="{{ asset('/css/custom-select-input.css') }}">
    <link rel="stylesheet" href="{{ asset('/css/sidebar.css') }}">
    <style>
        .select2-container--default .select2-selection--single {
            min-height: 34px !important;
        }
        .select2-container--default .select2-selection--single .select2-selection__rendered {
            color: #73879c;
            font-size: 14px;
        }
        textarea:focus {
            outline: none;
            box-shadow: none;
        }
        .select2-container--default .select2-selection__clear {
            margin-left: 5px;
            font-size: 17px;
        }


    </style>
    <style>
        /* استایل دستی برای dropdown سفارشی */
        .custom-dropdown .dropdown-menu {
            display: none;
            opacity: 0;
            transition: opacity 0.15s linear;
        }

        .custom-dropdown .dropdown-menu.show {
            display: block;
            opacity: 1;
        }

    </style>
</head>
<!-- /header content -->
<body class="nav-md" style="background-color: #F8F7FA">
<div class="container body">
    <div class="main_container">
        <div class="col-md-3 left_col hidden-print menu_fixed">
            <div class=" scroll-view bg-white" style="width: 230px">

                <div class="clearfix"></div>

                <br/>
                @include('layouts.admin.sections.sidebar')
            </div>
        </div>

        @include('layouts.admin.sections.navbar')
        <!-- /header content -->
        <div class="right_col" role="main" style="background-color: #F8F7FA">

        @yield('content')
        </div>

    </div>
</div>
<div id="lock_screen">
    <table>
        <tr>
            <td>
                <div class="clock"></div>
                <span class="unlock">
                    <span class="fa-stack fa-5x">
                      <i class="fa fa-square-o fa-stack-2x fa-inverse"></i>
                      <i id="icon_lock" class="fa fa-lock fa-stack-1x fa-inverse"></i>
                    </span>
                </span>
            </td>
        </tr>
    </table>
</div>
</body>
<script src="{{ asset('/js/jquery.js') }}"></script>
<script src="{{ asset('/js/custom-select-input.js') }}"></script>
<script src="{{ asset('/js/sidebar.js') }}"></script>
<script>
    function setError(field, message) {
        // اگر فیلد با attachments. شروع شد، به attachments_error بریز
        if (field.startsWith('attachments.')) {
            const errorSpan = $('#attachments_error');
            const existingError = errorSpan.html();
            const formattedMessage = `فایل : ${message}`;

            if (existingError) {
                errorSpan.html(existingError + '<br>' + formattedMessage);
            } else {
                errorSpan.html(formattedMessage);
            }
        } else {
            // فیلدهای معمولی
            const errorElement = $(`#${field}_error`);
            if (errorElement.length) {
                errorElement.html(message);
            } else {
                // اگه المان خطا وجود نداشت، میتونی کنار input یه span ایجاد کنی
                $(`[name="${field}"]`).after(`<span class="text-danger error-message" id="${field}_error">${message}</span>`);
            }
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
</script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        var dropdownMenus = document.querySelectorAll('.dropdown-menu');
        dropdownMenus.forEach(function(menu) {
            menu.addEventListener('click', function(e) {
                e.stopPropagation();
            });
        });
    });
</script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const dropdownBtn = document.getElementById('filterDropdown');
        const dropdownMenu = document.getElementById('filterMenu');
        let isOpen = false;

        // تابع باز کردن dropdown
        function openDropdown() {
            dropdownMenu.classList.add('show');
            dropdownBtn.setAttribute('aria-expanded', 'true');
            isOpen = true;
        }

        // تابع بستن dropdown
        function closeDropdown() {
            dropdownMenu.classList.remove('show');
            dropdownBtn.setAttribute('aria-expanded', 'false');
            isOpen = false;
        }

        // فقط با کلیک روی دکمه باز و بسته بشه
        dropdownBtn.addEventListener('click', function(e) {
            e.stopPropagation();
            if (isOpen) {
                closeDropdown();
            } else {
                openDropdown();
            }
        });

        // جلوگیری از بسته شدن هنگام کلیک داخل منو
        dropdownMenu.addEventListener('click', function(e) {
            e.stopPropagation();
        });

        // جلوگیری از بسته شدن توسط بوت استرپ (override)
        document.body.addEventListener('click', function(e) {
            // اگه کلیک خارج از dropdown بود و dropdown باز بود، هیچ کاری نکن (نبسته بشه)
            if (!dropdownBtn.contains(e.target) && !dropdownMenu.contains(e.target) && isOpen) {
                // اینجا می‌تونی تصمیم بگیری بسته بشه یا نه
                // طبق خواسته تو، هیچ کاری نمی‌کنیم تا فقط با دکمه بسته بشه
                // اگه خواستی با کلیک بیرون بسته بشه، خط زیر رو uncomment کن:
                // closeDropdown();
            }
        });

        // دکمه انصراف
        const cancelBtn = document.getElementById('cancelFilterBtn');
        if (cancelBtn) {
            cancelBtn.addEventListener('click', function() {
                closeDropdown();
            });
        }

        // جلوگیری از propagation برای select2 و datepicker
        $(document).on('click', '.select2-selection', function(e) {
            e.stopPropagation();
        });

        $(document).on('click', '.select2-dropdown', function(e) {
            e.stopPropagation();
        });

        // برای کتابخانه جلایلی - اگه از jalaali-datepicker استفاده می‌کنی
        $(document).on('click', '.jdp-container', function(e) {
            e.stopPropagation();
        });

        $(document).on('click', '.jdp-cell', function(e) {
            e.stopPropagation();
        });
    });
</script>
<script>
    document.querySelectorAll('.fa-number').forEach(el => {
        el.textContent = el.textContent.replace(/[0-9]/g, d => '۰۱۲۳۴۵۶۷۸۹'[d]);
    });
</script>


@yield('js')
</html>
