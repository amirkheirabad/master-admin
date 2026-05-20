<!-- top navigation -->
<div class="top_nav hidden-print">
    <div class="nav_menu bg-white">
        <nav>
            <!-- تو بخش navbar، قبل از بقیه المان‌ها یا بعدش -->
            <button class="mobile-menu-toggle" id="mobile-menu-toggle" style="display: none;">
                <i class="fa fa-bars"></i>
            </button>

            @yield('nav')

                     <ul class="nav navbar-nav navbar-right">
                        <li class="dropdown">
                            <a href="javascript:;" class="dropdown-toggle user-profile" data-toggle="dropdown" aria-expanded="false"
                            style="display: flex; align-items: center; gap: 8px; padding: 8px 12px; border-radius: 30px; background: #f8fafc; transition: all 0.2s;">
                                <img src="{{ asset('/images/men.png') }}" style="width: 32px; height: 32px; border-radius: 50%; object-fit: cover;">
                                <span style="color: #133c6d; font-weight: 500;">{{ Auth::user()->name }}</span>
                                <i class="fa fa-chevron-down" style="font-size: 12px; color: #133c6d;"></i>
                            </a>
                            <ul class="dropdown-menu dropdown-usermenu pull-right" style="border-radius: 12px; border: none; box-shadow: 0 10px 25px rgba(0,0,0,0.08); margin-top: 8px; min-width: 200px;">
                                <li style="border-bottom: 1px solid #edf2f7;">
                                    <a href="javascript:;" style="padding: 12px 16px; color: #4a5568; transition: all 0.2s;">
                                        <i class="fa fa-user-circle-o" style="width: 20px; color: #133c6d;"></i> 
                                        <span style="margin-right: 10px;">پروفایل</span>
                                    </a>
                                </li>
                                <li>
                                    <a href="javascript:;" onclick="event.preventDefault(); document.getElementById('logout-form').submit();"
                                    style="padding: 12px 16px; color: #e53e3e; transition: all 0.2s;">
                                        <i class="fa fa-sign-out" style="width: 20px;"></i>
                                        <span style="margin-right: 10px;">خروج</span>
                                    </a>
                                    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                        @csrf
                                    </form>
                                </li>
                            </ul>
                        </li>
                    </ul>
                </li>
            </ul>
        </nav>
    </div>
</div>
<!-- /top navigation -->
