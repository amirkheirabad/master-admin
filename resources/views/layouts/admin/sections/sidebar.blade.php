<style>
    @media (max-width: 767px) {
        /* مخفی کردن آیتم‌های دسکتاپ در موبایل */
        .desktop-only {
            display: none !important;
        }

        /* نمایش آیتم‌های اصلی */
        .mobile-main-item {
            display: block !important;
        }

        /* منوی افقی - پایین صفحه */
        #sidebar-menu.mobile-menu-horizontal {
            height: 67px;
            width: 100% !important;
            background: white !important;
            overflow-x: auto !important;
            overflow-y: hidden !important;
            position: fixed !important;
            bottom: 0 !important;
            left: 0 !important;
            right: 0 !important;
            z-index: 999 !important;
            box-shadow: 0 -2px 10px rgba(0,0,0,0.1) !important;
            border-radius: 0 !important;
            margin: 0 !important;
            padding: 0 !important;
        }

        #sidebar-menu.mobile-menu-horizontal .nav.side-menu {
            display: flex !important;
            flex-direction: row !important;
            flex-wrap: nowrap !important;
            margin: 0 !important;
            justify-content: space-around !important;
        }

        #sidebar-menu.mobile-menu-horizontal .nav.side-menu > li {
            flex: 1 !important;
            list-style: none !important;
            text-align: center !important;
        }

        #sidebar-menu.mobile-menu-horizontal .nav.side-menu > li > a {
            white-space: nowrap !important;
            color: #5a5c69 !important;
            padding: 10px 8px !important;
            font-size: 12px !important;
            display: flex !important;
            align-items: center !important;
            justify-content: center !important;
            gap: 6px !important;
            flex-direction: column !important;
        }

        #sidebar-menu.mobile-menu-horizontal .nav.side-menu > li > a i {
            font-size: 20px !important;
            margin-bottom: 4px !important;
        }

        #sidebar-menu.mobile-menu-horizontal .nav.side-menu > li > a .fa-chevron-down {
            display: none !important;
        }

        /* ساب‌منو به صورت شیت از پایین */
        #sidebar-menu.mobile-menu-horizontal .child_menu {
            position: fixed !important;
            bottom: 67px !important;
            left: 0 !important;
            right: 0 !important;
            background: white !important;
            border-radius: 15px 15px 0 0 !important;
            box-shadow: 0 -2px 20px rgba(0,0,0,0.15) !important;
            z-index: 1000 !important;
            padding: 10px 0 !important;
            margin: 0 !important;
            max-height: 50vh !important;
            overflow-y: auto !important;
            display: none !important;
        }

        #sidebar-menu.mobile-menu-horizontal .child_menu li {
            display: block !important;
            list-style: none !important;
        }

        #sidebar-menu.mobile-menu-horizontal .child_menu li a {
            display: block !important;
            padding: 12px 20px !important;
            color: #333 !important;
            text-decoration: none !important;
            border-bottom: 1px solid #eee !important;
            text-align: center !important;
        }

        #sidebar-menu.mobile-menu-horizontal .child_menu li a:hover {
            background: #f5f5f5 !important;
        }

        #sidebar-menu.mobile-menu-horizontal .child_menu li:last-child a {
            border-bottom: none !important;
        }

        /* فضای خالی برای فوتر منو */
        .right_col {
            padding-bottom: 70px !important;
        }

        /* مخفی کردن دکمه منو */
        #menu_toggle {
            display: none !important;
        }

        /* تنظیمات اضافی */
        body {
            padding-bottom: 55px !important;
        }
    }
    /* استایل جدید سایدبار سفید */
    .main_menu_side {
        background: #ffffff !important;
    }

    .nav.side-menu > li {
        position: relative;
        display: block;
        cursor: pointer;
    }

    .nav.side-menu > li > a {
        margin-bottom: 6px;
        color: #5a5c69 !important;
        font-weight: 500;
        position: relative;
        display: block;
        padding: 13px 15px 12px;
    }

    .nav.side-menu > li > a:hover {
        color: #2c3e50 !important;
        text-decoration: none;
        background: #f8f7fa !important;
    }

    /* حذف رنگ سبز از border-left */
    .nav.side-menu > li.current-page {
        border-left: none !important;
    }

    .nav.side-menu > li.active {
        border-left: none !important;
    }

    /* رنگ پس زمینه برای آیتم فعال و هاور */
    .nav.side-menu > li.active > a {
        background: #f8f7fa !important;
        color: #2c3e50 !important;
        text-shadow: none !important;
        box-shadow: none !important;
    }

    .nav.side-menu > li > a:focus {
        text-decoration: none;
        background: transparent;
        background-color: transparent;
    }

    /* استایل منوی فرزند - باز شدن به سمت پایین */
    ul.nav.child_menu {
        position: relative !important;
        right: auto !important;
        top: auto !important;
        width: 100% !important;
        z-index: 4000;
        background: #ffffff !important;
        display: none;
        box-shadow: none !important;
        border-radius: 0 !important;
        padding-right: 20px !important;
    }

    ul.nav.child_menu li {
        padding: 0 10px;
        position: relative;
    }

    ul.nav.child_menu li a {
        text-align: right !important;
        color: #5a5c69 !important;
        font-weight: 500;
        font-size: 12px;
        padding: 9px 15px !important;
        display: block;
        position: relative;
    }

    ul.nav.child_menu li a:hover {
        color: #2c3e50 !important;
        background: #f8f7fa !important;
    }

    /* فقط خط سمت راست - بدون توپ */
    /* فقط خط سمت راست - بدون توپ */
    ul.nav.child_menu li:before {
        display: none !important;
    }

    ul.nav.child_menu li:after {
        content: "" !important;
        position: absolute !important;
        right: 15px !important;
        top: 0 !important;
        bottom: 0 !important;
        width: 1px !important;
        height: auto !important;
        background: #e0e0e0 !important;
        border: none !important;
    }



    /* استایل برای آیتم فعال در منوی فرزند */
    ul.nav.child_menu li.active {
        background: #f8f7fa !important;
    }

    ul.nav.child_menu li.active a {
        color: #2c3e50 !important;
    }

    /* استایل برای current-page */
    .nav li.current-page {
        background: #f8f7fa !important;
    }

    .nav li li.current-page {
        background: #f8f7fa !important;
    }

    .nav li li.current-page a {
        color: #2c3e50 !important;
    }

    /* استایل منوی کودک در حالت باز */
    .nav.child_menu {
        display: none;
    }

    .nav.child_menu li {
        padding-right: 36px;
    }

    .nav.child_menu li:hover {
        background-color: #f8f7fa !important;
    }

    .nav.child_menu li.active {
        background-color: #f8f7fa !important;
    }

    .nav.child_menu li li:hover {
        background: none;
    }

    .nav.child_menu li li.active {
        background: none;
    }

    .nav.child_menu li li a:hover,
    .nav.child_menu li li a.active {
        color: #2c3e50 !important;
    }

    .nav.child_menu > li > a {
        color: #5a5c69 !important;
        font-weight: 500;
        color: rgba(90,92,105,0.75) !important;
        font-size: 12px;
        padding: 9px;
    }

    /* استایل برای آیتم active-sm (حالت کوچک) */
    .nav.side-menu > li.active-sm > a {
        color: #2c3e50 !important;
        background: #f8f7fa !important;
    }

    .nav.side-menu > li.active-sm {
        border-left: none !important;
    }

    /* حذف گرادیان و شادو های اضافی */
    .nav.side-menu > li.active > a {
        background: linear-gradient(#f8f7fa, #f8f7fa), #f8f7fa !important;
        box-shadow: none !important;
    }

    /* منوی ثابت */
    .menu_fixed {
        background: #ffffff;
    }

    /* حالت کوچک سایدبار */
    body.nav-sm .main_menu_side {
        background: #ffffff !important;
    }

    body.nav-sm .nav.side-menu > li.active-sm {
        border-left: none !important;
    }

    body.nav-sm .nav.side-menu > li.active-sm > a {
        background: #f8f7fa !important;
        color: #2c3e50 !important;
    }

    body.nav-sm ul.nav.child_menu {
        position: absolute !important;
        right: 100% !important;
        top: 0 !important;
        width: 210px !important;
        background: #ffffff !important;
        box-shadow: 0 2px 6px rgba(0,0,0,0.1) !important;
        padding-right: 0 !important;
    }

    body.nav-sm ul.nav.child_menu li:after {
        display: none !important;
    }
</style>
<style>
    @media (max-width: 767px) {
        /* استایل منوی افقی - پایین صفحه */
        #sidebar-menu.mobile-menu-horizontal {
            height: 67px;
            width: 100% !important;
            background: white !important;
            overflow-x: auto !important;
            overflow-y: hidden !important;
            position: fixed !important;
            bottom: 0 !important;
            left: 0 !important;
            right: 0 !important;
            z-index: 999 !important;
            box-shadow: 0 -2px 10px rgba(0,0,0,0.1) !important;
            border-radius: 0 !important;
            margin: 0 !important;
            padding: 0 !important;
        }

        #sidebar-menu.mobile-menu-horizontal .nav.side-menu {
            display: flex !important;
            flex-direction: row !important;
            flex-wrap: nowrap !important;
            margin: 0 !important;
        }

        #sidebar-menu.mobile-menu-horizontal .nav.side-menu > li {
            flex: 0 0 auto !important;
            list-style: none !important;
        }

        #sidebar-menu.mobile-menu-horizontal .nav.side-menu > li > a {
            white-space: nowrap !important;
            color: black !important;
            padding: 10px 16px !important;
            font-size: 13px !important;
            display: flex !important;
            align-items: center !important;
            gap: 6px !important;
        }

        #sidebar-menu.mobile-menu-horizontal .nav.side-menu > li > a i {
            font-size: 16px !important;
        }

        /* ساب‌منو به صورت شیت از پایین */
        #sidebar-menu.mobile-menu-horizontal .child_menu {
            position: fixed !important;
            bottom: 50px !important;
            left: 0 !important;
            right: 0 !important;
            background: white !important;
            border-radius: 15px 15px 0 0 !important;
            box-shadow: 0 -2px 20px rgba(0,0,0,0.15) !important;
            z-index: 1000 !important;
            padding: 10px 0 !important;
            margin: 0 !important;
            max-height: 50vh !important;
            overflow-y: auto !important;
            display: none !important;
        }

        #sidebar-menu.mobile-menu-horizontal .child_menu li {
            display: block !important;
            list-style: none !important;
        }

        #sidebar-menu.mobile-menu-horizontal .child_menu li a {
            display: block !important;
            padding: 12px 20px !important;
            color: #333 !important;
            text-decoration: none !important;
            border-bottom: 1px solid #eee !important;
        }

        #sidebar-menu.mobile-menu-horizontal .child_menu li a:hover {
            background: #f5f5f5 !important;
        }

        #sidebar-menu.mobile-menu-horizontal .child_menu li:last-child a {
            border-bottom: none !important;
        }

        /* فضای خالی برای فوتر منو */
        .right_col {
            padding-bottom: 70px !important;
        }

        /* مخفی کردن دکمه منو */
        #menu_toggle {
            display: none !important;
        }

        /* تنظیمات اضافی برای نمایش بهتر */
        body {
            padding-bottom: 55px !important;
        }

        /* استایل پیش‌فرض برای دسکتاپ */
        .main_menu_side .nav.side-menu > li > a {
            display: flex;
            flex-direction: row;
            align-items: center;
            gap: 10px;
        }

        .main_menu_side .nav.side-menu > li > a i {
            font-size: 18px;
        }

        .main_menu_side .nav.side-menu > li > a {
            flex-direction: column !important;
            justify-content: center !important;
            text-align: center !important;
            padding: 15px 10px !important;
            gap: 5px !important;
        }

        .main_menu_side .nav.side-menu > li > a i {
            font-size: 24px !important;
            margin-bottom: 5px !important;
        }

        .main_menu_side .nav.side-menu > li > a .fa-chevron-down {
            display: none; /* مخفی کردن آیکون زیرمنو در موبایل */
        }

        /* تنظیم زیرمنوها در حالت موبایل */
        .main_menu_side .nav.side-menu .child_menu {
            text-align: center;
        }

        .main_menu_side .nav.side-menu .child_menu li a {
            display: block;
            padding: 8px 5px;
            text-align: center;
        }
    }
</style>
<div id="sidebar-menu" class="main_menu_side hidden-print main_menu">
    <div class="menu_section">
        <ul class="nav side-menu">
            <li>
                <a href="/">
                    <i class="fa fa-home text-beta"></i>
                    <span>داشبورد</span>
                </a>
            </li>

{{--            @canany(['user-list', 'user-insert', 'role-insert', 'role-list'])--}}
            <li>
                <a>
                    <i class="fa fa-file-text text-beta"></i>
                    <span>کاربران</span>
                    <span class="fa fa-chevron-down"></span>
                </a>
                <ul class="nav child_menu">
{{--                    @can('user-list')--}}
                    <li><a href="{{ route('user-list') }}">لیست کاربران</a></li>
{{--                    @endcan--}}
{{--                    @can('user-insert')--}}
                    <li><a href="{{ route('user-insert') }}">افزودن کاربر</a></li>
{{--                    @endcan--}}
{{--                    @can('role-list')--}}
                    <li><a href="{{ route('role-list') }}">لیست نقش ها</a></li>
{{--                    @endcan--}}
{{--                    @can('role-insert')--}}
                    <li><a href="{{ route('role-insert') }}">افزودن نقش</a></li>
{{--                    @endcan--}}
                </ul>
            </li>
{{--            @endcanany--}}
{{--            @can('sms-panel')--}}
            <li>
                <a>
                    <i class="fa fa-home text-beta"></i>
                    <span>باشگاه مشتریان</span>
                    <span class="fa fa-chevron-down"></span>
                </a>
                <ul class="nav child_menu">
                    <li><a href="{{ route('sms-panel') }}">پنل پیامکی</a></li>
                </ul>
            </li>
{{--            @endcan--}}
{{--            @canany(['factor-list', 'factor-insert', 'category-list'])--}}
            <li>
                <a>
                    <i class="fa fa-file-text text-beta"></i>
                    <span>فاکتور</span>
                    <span class="fa fa-chevron-down"></span>
                </a>
                <ul class="nav child_menu">
{{--                    @can('factor-list')--}}
                    <li><a href="{{ route('factor-list') }}">لیست فاکتور</a></li>
{{--                    @endcan--}}
{{--                    @can('category-list')--}}
                    <li><a href="{{ route('category-list') }}">دسته بندی</a></li>
{{--                    @endcan--}}
                </ul>
            </li>
{{--            @endcanany--}}
{{--            @canany('list_stores')--}}
            <li>
                <a href="{{ route('list_stores') }}">
                    <i class="fa fa-shopping-cart text-beta"></i>
                    <span>فروشگاه ها</span>
                </a>
            </li>
{{--            @endcanany--}}
{{--            @canany(['list_tickets','insert_ticket'])--}}
            <li>
                <a>
                    <i class="fa fa-file-text text-beta"></i>
                    <span>تیکت</span>
                    <span class="fa fa-chevron-down"></span>
                </a>
                <ul class="nav child_menu">
{{--                    @can('list_tickets')--}}
                    <li><a href="{{ route('list_tickets') }}">همه تیکت ها</a></li>
{{--                    @endcan--}}
{{--                    @can('insert_tickets')--}}
                    <li><a href="{{ route('insert_ticket') }}">تیکت جدید</a></li>
{{--                    @endcan--}}
                </ul>
            </li>
{{--            @endcanany--}}
        </ul>
    </div>
</div>
<!-- /sidebar menu -->
