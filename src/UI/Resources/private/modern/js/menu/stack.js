(function ($) {
    let $mainMenu;

    $(function () {
        $mainMenu = $('.menu-main');

        if ($mainMenu.length) {
            $('body').addClass('has-stack-menu');
            $mainMenu.find('li').tooltip({
                placement: 'right',
            });
        }
    });

    $(document).on('click', '[data-menu-child]', function (e) {
        e.preventDefault();

        const $me = $(this);

        if ($me.hasClass('is-active')) {
            return;
        }

        const $mainMenu = $('.menu-main');
        const $childMenu = $('.menu-child');
        const childId = $me.data('menu-child');

        $('.sidebar-title').html($me.attr('title') || $me.data('original-title'));

        $mainMenu.find(`[data-menu-child]`).removeClass('is-active');
        $childMenu.addClass('is-sidebar-translated');
        $childMenu.find('.icon-box-toggle').addClass('active');
        $childMenu.find(`[data-menu]`).removeClass('is-active');
        $childMenu.find(`[data-menu=${childId}]`).addClass('is-active');
        $me.addClass('is-active');
    });

    $(document).on('click', '[data-menu] .have-children', function (e) {
        e.preventDefault();

        const $me = $(this);

        if ($me.hasClass('active')) {
            $me.removeClass('active');
            $me.find(`ul`).slideUp('fast');

            return;
        }

        const $sidebarMenu = $me.closest('.sidebar-menu');

        $sidebarMenu.find(`.have-children.active ul`).slideUp('fast');
        $sidebarMenu.find(`.have-children.active`).removeClass('active');

        $me.addClass('active');
        $me.find(`ul`).slideDown('fast');
    });

    $(document).on('click', '.menu-wrapper', function (e) {
        e.preventDefault();

        const $me = $(this);

        $me.find('.icon-box-toggle').removeClass('active');
        $me.closest('.menu-child').removeClass('is-sidebar-translated');
        $mainMenu.find(`[data-menu-child]`).removeClass('is-active');
    });
})(jQuery);
