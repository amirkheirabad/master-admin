<!-- top navigation -->
<div class="top_nav hidden-print">
    <div class="nav_menu bg-white">
        <nav>
            <!-- تو بخش navbar، قبل از بقیه المان‌ها یا بعدش -->
            <button class="mobile-menu-toggle" id="mobile-menu-toggle" style="display: none;">
                <i class="fa fa-bars"></i>
            </button>

            @yield('nav')

            <ul class="nav navbar-nav navbar-right hide-on-mobile">
                <li class="">
                    <a href="javascript:;" class="user-profile dropdown-toggle" data-toggle="dropdown"
                        aria-expanded="false">
                        <img src="{{ asset('/images/men.png') }}" style="width: 28px">
                        {{ Auth::user()->name }}
                        <span class=" fa fa-angle-down"></span>
                    </a>
                    <ul class="dropdown-menu dropdown-usermenu pull-right">
                        <li><a href="javascript:;">پروفایل</a></li>
                        <li><a href="javascript:;"
                                onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                <i class="fa fa-sign-out pull-right"></i> خروج
                            </a>
                            <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                @csrf
                            </form>
                        </li>
                    </ul>
                </li>
            </ul>
        </nav>
    </div>
</div>
<!-- /top navigation -->
