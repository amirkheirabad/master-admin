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
    <title>Gentelella Alela! | قالب مدیریت رایگان </title>
    {{--    <link rel="stylesheet" href="{{ asset('/css/bootstrap.min.css') }}">--}}
    @yield('css')
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="stylesheet" href="{{ asset('/css/custom-select-input.css') }}">
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
            margin-top: 2px;
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
<div class="container body" style="background: #34495e">
    <div class="main_container">
        <div class="col-md-3 left_col hidden-print menu_fixed">
            <div class=" scroll-view" style="width: 230px">

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
<script>
    var CURRENT_URL = window.location.href.split('#')[0].split('?')[0],
        $BODY = $('body'),
        $MENU_TOGGLE = $('#menu_toggle'),
        $SIDEBAR_MENU = $('#sidebar-menu'),
        $SIDEBAR_FOOTER = $('.sidebar-footer'),
        $LEFT_COL = $('.left_col'),
        $RIGHT_COL = $('.right_col'),
        $NAV_MENU = $('.nav_menu'),
        $FOOTER = $('footer');

    // Sidebar - فقط برای دسکتاپ
    function init_sidebar() {
        var setContentHeight = function () {
            $RIGHT_COL.css('min-height', $(window).height());
            var bodyHeight = $BODY.outerHeight(),
                footerHeight = $BODY.hasClass('footer_fixed') ? -10 : $FOOTER.height(),
                leftColHeight = $LEFT_COL.eq(1).height() + $SIDEBAR_FOOTER.height(),
                contentHeight = bodyHeight < leftColHeight ? leftColHeight : bodyHeight;
            contentHeight -= $NAV_MENU.height() + footerHeight;
            $RIGHT_COL.css('min-height', contentHeight);
        };

        // فقط برای دسکتاپ (بزرگتر از 768)
        if (window.innerWidth >= 768) {
            $SIDEBAR_MENU.find('a').on('click', function (ev) {
                var $li = $(this).parent();
                if ($li.is('.active')) {
                    $li.removeClass('active active-sm');
                    $('ul:first', $li).slideUp(function () {
                        setContentHeight();
                    });
                } else {
                    if (!$li.parent().is('.child_menu')) {
                        $SIDEBAR_MENU.find('li').removeClass('active active-sm');
                        $SIDEBAR_MENU.find('li ul').slideUp();
                    } else {
                        if ($BODY.is(".nav-sm")) {
                            $li.parent().find("li").removeClass("active active-sm");
                            $li.parent().find("li ul").slideUp();
                        }
                    }
                    $li.addClass('active');
                    $('ul:first', $li).slideDown(function () {
                        setContentHeight();
                    });
                }
            });
        }

        $MENU_TOGGLE.on('click', function () {
            if ($BODY.hasClass('nav-md')) {
                $SIDEBAR_MENU.find('li.active ul').hide();
                $SIDEBAR_MENU.find('li.active').addClass('active-sm').removeClass('active');
            } else {
                $SIDEBAR_MENU.find('li.active-sm ul').show();
                $SIDEBAR_MENU.find('li.active-sm').addClass('active').removeClass('active-sm');
            }
            $BODY.toggleClass('nav-md nav-sm');
            setContentHeight();
        });

        $SIDEBAR_MENU.find('a[href="' + CURRENT_URL + '"]').parent('li').addClass('current-page');
        $SIDEBAR_MENU.find('a').filter(function () {
            return this.href == CURRENT_URL;
        }).parent('li').addClass('current-page').parents('ul').slideDown(function () {
            setContentHeight();
        }).parent().addClass('active');

        $(window).smartresize(function () {
            setContentHeight();
        });
        setContentHeight();

        if ($.fn.mCustomScrollbar) {
            $('.menu_fixed').mCustomScrollbar({
                autoHideScrollbar: true,
                theme: 'minimal',
                mouseWheel: {preventDefault: true}
            });
        }
    }

    // ================ کد موبایل ================
    function addMobileSubmenuHandler(menu) {
        const parentItems = menu.querySelectorAll('li');
        parentItems.forEach(function(item) {
            const childMenu = item.querySelector('.child_menu');
            if (childMenu) {
                const link = item.querySelector('a:first-child');
                if (link && !link.hasAttribute('data-mobile-handler')) {
                    link.setAttribute('data-mobile-handler', 'true');

                    const newLink = link.cloneNode(true);
                    link.parentNode.replaceChild(newLink, link);

                    newLink.addEventListener('click', function(e) {
                        e.preventDefault();
                        e.stopPropagation();

                        const allChildMenus = menu.querySelectorAll('.child_menu');
                        allChildMenus.forEach(function(cm) {
                            if (cm !== childMenu) {
                                cm.style.display = 'none';
                            }
                        });

                        if (childMenu.style.display === 'block') {
                            childMenu.style.display = 'none';
                        } else {
                            childMenu.style.display = 'block';
                        }
                    });
                }
            }
        });
    }

    function handleMobileMenuHorizontal() {
        const isMobile = window.innerWidth < 768;
        const sidebarMenu = document.getElementById('sidebar-menu');
        const rightCol = document.querySelector('.right_col');

        if (!sidebarMenu) return;

        if (isMobile) {
            if (!sidebarMenu.classList.contains('mobile-horizontal')) {
                // مخفی کردن آیتم‌های more-item در موبایل
                const moreItems = sidebarMenu.querySelectorAll('.desktop-only');
                moreItems.forEach(function(item) {
                    item.style.display = 'none';
                });

                // نمایش آیتم‌های اصلی
                const mainItems = sidebarMenu.querySelectorAll('.mobile-main-item');
                mainItems.forEach(function(item) {
                    item.style.display = 'block';
                });

                // ذخیره جای اصلی
                if (!sidebarMenu.dataset.originalParentId) {
                    const tempDiv = document.createElement('div');
                    tempDiv.style.display = 'none';
                    tempDiv.id = 'original-sidebar-placeholder';
                    const parent = sidebarMenu.parentNode;
                    const next = sidebarMenu.nextSibling;
                    sidebarMenu.dataset.originalParentId = parent.id || 'body';
                    if (next) {
                        sidebarMenu.dataset.originalNextSibling = next.id || 'text';
                    }
                    parent.insertBefore(tempDiv, sidebarMenu);
                    sidebarMenu.dataset.originalPlaceholderId = 'original-sidebar-placeholder';
                }

                // انتقال منو به پایین صفحه
                const parent = sidebarMenu.parentNode;
                if (parent) {
                    parent.removeChild(sidebarMenu);
                }

                sidebarMenu.classList.add('mobile-menu-horizontal');
                sidebarMenu.classList.add('mobile-horizontal');

                if (rightCol && rightCol.parentNode) {
                    rightCol.parentNode.insertBefore(sidebarMenu, rightCol.nextSibling);
                } else {
                    document.body.appendChild(sidebarMenu);
                }

                // مخفی کردن همه ساب‌منوها
                const childMenus = sidebarMenu.querySelectorAll('.child_menu');
                childMenus.forEach(function(menu) {
                    menu.style.display = 'none';
                });

                // اضافه کردن هندلر برای ساب‌منوها
                addMobileSubmenuHandler(sidebarMenu);
            }
        } else {
            if (sidebarMenu.classList.contains('mobile-horizontal')) {
                // برگردوندن به حالت عادی
                const moreItems = sidebarMenu.querySelectorAll('.desktop-only');
                moreItems.forEach(function(item) {
                    item.style.display = '';
                });

                const mainItems = sidebarMenu.querySelectorAll('.mobile-main-item');
                mainItems.forEach(function(item) {
                    item.style.display = '';
                });

                // برگردوندن به جای اصلی
                const placeholder = document.getElementById(sidebarMenu.dataset.originalPlaceholderId);
                if (placeholder) {
                    placeholder.parentNode.insertBefore(sidebarMenu, placeholder);
                    placeholder.remove();
                }
                sidebarMenu.classList.remove('mobile-menu-horizontal', 'mobile-horizontal');
                delete sidebarMenu.dataset.originalPlaceholderId;
                delete sidebarMenu.dataset.originalParentId;
                delete sidebarMenu.dataset.originalNextSibling;

                // ریست کردن ساب‌منوها
                const childMenus = sidebarMenu.querySelectorAll('.child_menu');
                childMenus.forEach(function(menu) {
                    menu.style.display = '';
                });

                sidebarMenu.style.display = '';
            }
        }
    }

    // اجرا در زمان لود
    $(document).ready(function () {
        init_sidebar();
        handleMobileMenuHorizontal();
        setTimeout(handleMobileMenuHorizontal, 50);
    });

    // اجرا در زمان تغییر سایز
    let resizeTimer;
    window.addEventListener('resize', function() {
        clearTimeout(resizeTimer);
        resizeTimer = setTimeout(function() {
            handleMobileMenuHorizontal();
        }, 250);
    });

    // اضافه کردن رویداد برای pageshow
    window.addEventListener('pageshow', function() {
        handleMobileMenuHorizontal();
    });
</script>
<script>
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


@yield('js')
</html>
