<!-- top navigation -->
<div class="top_nav hidden-print">
    <div class="nav_menu bg-white">
        <nav>
            @yield('nav')

            <ul class="nav navbar-nav navbar-right hide-on-mobile">
                <li class="">
                    <a href="javascript:;" class="user-profile dropdown-toggle" data-toggle="dropdown"
                       aria-expanded="false">
                        <img src="{{ asset('/images/men.png') }}" style="width: 28px">
                        امیرحسین خیرآبادی
                        <span class=" fa fa-angle-down"></span>
                    </a>
                    <ul class="dropdown-menu dropdown-usermenu pull-right">
                        <li><a href="javascript:;">پروفایل</a></li>
                        <li><a href="javascript:;"><i class="fa fa-sign-out pull-right"></i> خروج</a></li>
                    </ul>
                </li>
            </ul>
        </nav>
    </div>
</div>
<!-- /top navigation -->
