<div id="sidebar-menu" class="main_menu_side hidden-print main_menu">
    <div class="menu_section">
        <ul class="nav side-menu">
            <li>
                <a href="{{ route('dashboard') }}">
                    <i class="fa fa-tachometer text-beta"></i>
                    <span>داشبورد</span>
                </a>
            </li>


            @if(auth()->user()->hasRole('admin'))

            <li>
                <a>
                    <i class="fa fa-user-circle-o text-beta"></i>
                    <span>کاربران</span>
                    <span class="fa fa-chevron-down"></span>
                </a>
                <ul class="nav child_menu">
                    <li><a href="{{ route('user-list') }}">لیست کاربران</a></li>
                    <li><a href="{{ route('user-insert') }}">افزودن کاربر</a></li>
                    <li><a href="{{ route('role-list') }}">لیست نقش ها</a></li>
                    <li><a href="{{ route('role-insert') }}">افزودن نقش</a></li>
                </ul>
            </li>
            @endif
            @if(auth()->user()->hasRole('admin'))

            <li>
                <a>
                    <i class="fa fa-diamond text-beta"></i>
                    <span>باشگاه مشتریان</span>
                    <span class="fa fa-chevron-down"></span>
                </a>
                <ul class="nav child_menu">
                    <li><a href="{{ route('sms-panel') }}">پنل پیامکی</a></li>
                </ul>
            </li>
            @endif
            @if(auth()->user()->hasAnyRole(['admin', 'seller']))

            <li>
                <a>
                    <i class="fa fa-file-text text-beta"></i>
                    <span>فاکتور</span>
                    <span class="fa fa-chevron-down"></span>
                </a>
                <ul class="nav child_menu">
                    <li><a href="{{ route('factor-list') }}">لیست فاکتور</a></li>

            @if(auth()->user()->hasRole('admin'))

                    <li><a href="{{ route('category-list') }}">دسته بندی</a></li>
            @endif
                </ul>
            </li>
            @endif
            @if(auth()->user()->hasRole('admin'))

            <li>
                <a href="{{ route('list_stores') }}">
                    <i class="fa fa-shopping-cart text-beta"></i>
                    <span>فروشگاه ها</span>
                </a>
            </li>
            @endif
            @if(auth()->user()->hasanyRole('admin', 'seller'))

            <li>
                <a>
                    <i class="fa fa-life-ring text-beta"></i>
                    <span>تیکت</span>
                    <span class="fa fa-chevron-down"></span>
                </a>
                <ul class="nav child_menu">
                    <li><a href="{{ route('list_tickets') }}">همه تیکت ها</a></li>
                    <li><a href="{{ route('insert_ticket') }}">تیکت جدید</a></li>
                </ul>
            </li>
            @endif
            @if(auth()->user()->hasRole('admin'))

            <li>
                <a>
                    <i class="fa fa-bell-o text-beta"></i>
                    <span>پیام ها</span>
                    <span class="fa fa-chevron-down"></span>
                </a>
                <ul class="nav child_menu">
                    <li><a href="{{ route('message.list') }}">همه پیام ها</a></li>
                    <li><a href="{{ route('message.insert') }}">پیام جدید</a></li>
                </ul>
            </li>
            @endif
            <li>
                <a>
                    <i class="fa fa-play-circle text-beta"></i>
                    <span>مرکز آموزش </span>
                    <span class="fa fa-chevron-down"></span>
                </a>
                <ul class="nav child_menu">
                    <li><a href="{{ route('education-list') }}"> آموزش ها</a></li>
                    @if(auth()->user()->hasRole('admin'))
                    <li><a href="{{ route('education-insert') }}"> افزودن ویدئو</a></li>
                    <li><a href="{{ route('video-list') }}"> لیست ویدئو ها</a></li>
                    <li><a href="{{ route('video-category-list') }}"> دسته بندی</a></li>
                    @endif
                </ul>
            </li>
            <li>
                <a>
                    <i class="fa fa-play-circle text-beta"></i>
                    <span>سوالات متداول </span>
                    <span class="fa fa-chevron-down"></span>
                </a>
                <ul class="nav child_menu">
                    <li><a href="{{ route('faq_show') }}"> سوالات متداول</a></li>
                    @if(auth()->user()->hasRole('admin'))
                        <li><a href="{{ route('faq_insert') }}"> افزودن سوال</a></li>
                        <li><a href="{{ route('faq_list') }}"> لیست سوالات</a></li>
                    @endif
                </ul>
            </li>
        </ul>
    </div>
</div>
<!-- /sidebar menu -->
