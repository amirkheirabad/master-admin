var CURRENT_URL = window.location.href.split('#')[0].split('?')[0],
    $BODY = $('body'),
    $MENU_TOGGLE = $('#menu_toggle'),
    $SIDEBAR_MENU = $('#sidebar-menu'),
    $SIDEBAR_FOOTER = $('.sidebar-footer'),
    $LEFT_COL = $('.left_col'),
    $RIGHT_COL = $('.right_col'),
    $NAV_MENU = $('.nav_menu'),
    $FOOTER = $('footer');

// تابع تنظیم ارتفاع
function setContentHeight() {
    $RIGHT_COL.css('min-height', $(window).height());
    var bodyHeight = $BODY.outerHeight(),
        footerHeight = $BODY.hasClass('footer_fixed') ? -10 : $FOOTER.height(),
        leftColHeight = $LEFT_COL.eq(1).height() + $SIDEBAR_FOOTER.height(),
        contentHeight = bodyHeight < leftColHeight ? leftColHeight : bodyHeight;
    contentHeight -= $NAV_MENU.height() + footerHeight;
    $RIGHT_COL.css('min-height', contentHeight);
}

// تابع باز کردن آیتم فعال در سایدبار
function openActiveMenuItem() {
    var currentPath = window.location.pathname; // مثلاً "/user-list"
    if (currentPath === '') currentPath = '/';

    $SIDEBAR_MENU.find('a').each(function() {
        var $this = $(this);
        var href = $this.attr('href');
        if (!href || href === '#' || href === 'javascript:;') return;

        // تبدیل href به مسیر نسبی (بدون پروتکل و دامنه)
        var linkPath = href.split('?')[0].split('#')[0];
        // اگر href مطلق بود (مثل http://...) فقط قسمت path را می‌گیریم
        if (linkPath.indexOf('http') === 0) {
            var a = document.createElement('a');
            a.href = href;
            linkPath = a.pathname;
        }

        // مقایسه دقیق مسیرها
        if (linkPath === currentPath) {
            var $parentLi = $this.parent('li');
            var $parentUl = $parentLi.parents('ul.child_menu');

            if ($parentUl.length) {
                $parentUl.show();
                $parentUl.parent('li').addClass('active');
            }
            $parentLi.addClass('active current-page');
            return false; // فقط اولین تطابق را فعال کن
        }
    });
}

// سایدبار دسکتاپ
function init_sidebar() {
    setContentHeight();

    if (window.innerWidth >= 990 && $BODY.hasClass('nav-md')) {
        $SIDEBAR_MENU.find('li > a').on('click.sidebar', function(ev) {
            var $li = $(this).parent();

            if ($(this).next().is('.child_menu')) {
                ev.preventDefault();

                if ($li.hasClass('active')) {
                    $li.removeClass('active');
                    $li.find('ul.child_menu:first').slideUp();
                } else {
                    $SIDEBAR_MENU.find('li.active').each(function() {
                        $(this).removeClass('active');
                        $(this).find('ul.child_menu:first').slideUp();
                    });
                    $li.addClass('active');
                    $li.find('ul.child_menu:first').slideDown();
                }
                setContentHeight();
            }
        });
    }

    $MENU_TOGGLE.on('click', function() {
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

    openActiveMenuItem();

    $(window).smartresize(function() {
        setContentHeight();
    });

    if ($.fn.mCustomScrollbar) {
        $('.menu_fixed').mCustomScrollbar({
            autoHideScrollbar: true,
            theme: 'minimal',
            mouseWheel: {preventDefault: true}
        });
    }
}

// ================ سیستم موبایل ================
let isMobileMenuOpen = false;

function createMobileSidebar() {
    if ($('.mobile-sidebar').length) {
        return $('.mobile-sidebar');
    }

    const originalSidebar = $('#sidebar-menu');
    const clonedSidebar = originalSidebar.clone(true);

    clonedSidebar.addClass('mobile-sidebar');
    clonedSidebar.removeClass('main_menu_side');
    clonedSidebar.attr('id', 'mobile-sidebar-menu');
    clonedSidebar.removeAttr('style');
    clonedSidebar.find('a').off('click');

    $('body').append(clonedSidebar);

    if ($('.mobile-menu-overlay').length === 0) {
        $('body').append('<div class="mobile-menu-overlay"></div>');
    }

    return clonedSidebar;
}

function toggleMobileMenu() {
    const sidebar = $('.mobile-sidebar');
    const overlay = $('.mobile-menu-overlay');

    if (!sidebar.length) return;

    if (isMobileMenuOpen) {
        sidebar.removeClass('mobile-open');
        overlay.removeClass('active');
        isMobileMenuOpen = false;
        $('body').css('overflow', '');
    } else {
        sidebar.addClass('mobile-open');
        overlay.addClass('active');
        isMobileMenuOpen = true;
        $('body').css('overflow', 'hidden');
    }
}

function closeMobileMenu() {
    if (isMobileMenuOpen) {
        toggleMobileMenu();
    }
}
function setupMobileEvents() {
    // دکمه منو
    $('.mobile-menu-toggle').off('click').on('click', function(e) {
        e.preventDefault();
        e.stopPropagation();
        toggleMobileMenu();
    });

    // اوورلی
    $('.mobile-menu-overlay').off('click').on('click', function() {
        closeMobileMenu();
    });

    // رویداد کلیک روی آیتم‌های منوی موبایل
    $(document).off('click.mobile', '.mobile-sidebar .nav.side-menu > li > a').on('click.mobile', '.mobile-sidebar .nav.side-menu > li > a', function(e) {
        const $this = $(this);
        const $parentLi = $this.parent('li');
        const $childMenu = $parentLi.children('.child_menu');

        // سناریو ۱: آیتم کلیک شده زیرمنو دارد
        if ($childMenu.length) {
            e.preventDefault();

            // بستن سایر منوهای اصلی (به جز منویی که کلیک شده یا فرزندانش جاری هستند)
            $('.mobile-sidebar .nav.side-menu > li').not($parentLi).each(function() {
                // اگر این منو حاوی صفحه فعلی نیست، کلاس active را بردار و ببندش
                if (!$(this).find('li.current-page').length) {
                    $(this).removeClass('active');
                    $(this).children('.child_menu').slideUp('fast');
                }
            });

            // باز و بسته کردن کشویی منوی فعلی
            if ($childMenu.is(':visible')) {
                $childMenu.slideUp('fast');
                $parentLi.removeClass('active');
            } else {
                $childMenu.slideDown('fast');
                $parentLi.addClass('active');
            }
        }
        // سناریو ۲: آیتم کلیک شده یک لینک مستقیم است (زیرمنو ندارد)
        else {
            // حذف کلاس active و current-page از تمام صفحات قبلی
            $('.mobile-sidebar li').removeClass('active current-page');

            // فعال کردن آیتم فعلی و والد آن
            $parentLi.addClass('active current-page');
            $parentLi.parents('li').addClass('active');

            setTimeout(function() {
                closeMobileMenu();
            }, 150);
        }
    });

    // روشن نگه داشتن آیتم فعال بر اساس آدرس URL فعلی در زمان لود صفحه
    fixMobileActiveState();
}

// تابع کمکی برای پیدا کردن و فعال نگه داشتن صفحه فعلی در موبایل
function fixMobileActiveState() {
    var currentPath = window.location.pathname;
    if (currentPath === '') currentPath = '/';

    $('.mobile-sidebar .nav.side-menu a').each(function() {
        var href = $(this).attr('href');
        if (!href || href === '#' || href === 'javascript:;') return;

        var linkPath = href.split('?')[0].split('#')[0];
        if (linkPath.indexOf('http') === 0) {
            var a = document.createElement('a');
            a.href = href;
            linkPath = a.pathname;
        }

        if (linkPath === currentPath) {
            // پاک کردن حالت‌های قبلی موبایل
            $('.mobile-sidebar li').removeClass('active current-page');

            var $parentLi = $(this).parent('li');
            $parentLi.addClass('active current-page');

            // باز کردن و فعال کردن منوی مادر
            var $parentUl = $parentLi.parents('ul.child_menu');
            if ($parentUl.length) {
                $parentUl.show();
                $parentUl.parent('li').addClass('active');
            }
            return false;
        }
    });
}

function handleMobileMode() {
    const isMobile = window.innerWidth < 990;

    if (isMobile) {
        if (!$BODY.hasClass('nav-md')) {
            $BODY.addClass('nav-md');
        }
        $BODY.removeClass('nav-sm');

        $SIDEBAR_MENU.find('a').off('click.sidebar');

        createMobileSidebar();
        setupMobileEvents();

    } else {
        if (isMobileMenuOpen) {
            closeMobileMenu();
        }

        $('.mobile-sidebar').remove();
        $('.mobile-menu-overlay').remove();

        if (!$BODY.hasClass('nav-md')) {
            $BODY.addClass('nav-md');
        }
        $BODY.removeClass('nav-sm');
        $('body').css('overflow', '');

        $SIDEBAR_MENU.find('a').off('click.sidebar');
        init_sidebar();
    }
}
// اجرا در زمان لود
$(document).ready(function() {
    // چک کردن اندازه صفحه و اجرای مناسب
    if (window.innerWidth < 990) {
        // توی موبایل فقط سیستم موبایل رو راه بنداز
        handleMobileMode();
    } else {
        // توی دسکتاپ فقط سایدبار اصلی رو راه بنداز
        init_sidebar();
    }

    setTimeout(function() {
        openActiveMenuItem();
        if (window.innerWidth < 990) {
            $('.mobile-sidebar .child_menu').hide();
            $('.mobile-sidebar li.current-page').each(function() {
                $(this).parents('.child_menu').show();
                $(this).parents('li').addClass('active');
            });
        }
    }, 200);
});

// مدیریت تغییر سایز صفحه
let resizeTimer;
$(window).on('resize', function() {
    clearTimeout(resizeTimer);
    resizeTimer = setTimeout(function() {
        handleMobileMode();
    }, 200);
});
