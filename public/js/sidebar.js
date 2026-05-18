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
    $SIDEBAR_MENU.find('a').each(function() {
        var href = $(this).attr('href');
        if (href && href !== '#' && href !== 'javascript:;' && CURRENT_URL.indexOf(href) !== -1) {
            var $parentLi = $(this).parent('li');
            var $parentUl = $parentLi.parents('ul.child_menu');

            if ($parentUl.length) {
                $parentUl.show();
                $parentUl.parent('li').addClass('active');
            }

            $parentLi.addClass('active current-page');
            return false;
        }
    });
}

// سایدبار دسکتاپ
function init_sidebar() {
    setContentHeight();

    if (window.innerWidth >= 768 && $BODY.hasClass('nav-md')) {
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
    // دکمه منو - الان توی HTML هست
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

        if ($childMenu.length) {
            e.preventDefault();
            $('.mobile-sidebar .child_menu').not($childMenu).slideUp('fast');

            if ($childMenu.is(':visible')) {
                $childMenu.slideUp('fast');
                $parentLi.removeClass('active');
            } else {
                $childMenu.slideDown('fast');
                $parentLi.addClass('active');
            }
        } else {
            setTimeout(function() {
                closeMobileMenu();
            }, 200);
        }
    });

    setTimeout(function() {
        $('.mobile-sidebar .child_menu').hide();
        $('.mobile-sidebar li.current-page').each(function() {
            $(this).parents('.child_menu').show();
            $(this).parents('li').addClass('active');
        });
    }, 100);
}

function handleMobileMode() {
    const isMobile = window.innerWidth < 768;

    if (isMobile) {
        if (!$BODY.hasClass('nav-md')) {
            $BODY.addClass('nav-md');
        }
        $BODY.removeClass('nav-sm');

        // حذف رویدادهای سایدبار اصلی برای جلوگیری از تداخل
        $SIDEBAR_MENU.find('a').off('click.sidebar');

        createMobileSidebar();
        setupMobileEvents();

        if (isMobileMenuOpen) {
            closeMobileMenu();
        }
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

        // ریست و راه‌اندازی مجدد سایدبار دسکتاپ
        $SIDEBAR_MENU.find('a').off('click.sidebar');
        init_sidebar();
    }
}

// اجرا در زمان لود
$(document).ready(function() {
    // چک کردن اندازه صفحه و اجرای مناسب
    if (window.innerWidth < 768) {
        // توی موبایل فقط سیستم موبایل رو راه بنداز
        handleMobileMode();
    } else {
        // توی دسکتاپ فقط سایدبار اصلی رو راه بنداز
        init_sidebar();
    }

    setTimeout(function() {
        openActiveMenuItem();
        if (window.innerWidth < 768) {
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
