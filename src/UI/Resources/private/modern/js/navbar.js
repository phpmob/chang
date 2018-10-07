(function ($, win) {
    const NavBarSticky = (w) => {
        const $nav = $('.navbar');

        if (w.scrollY > $nav.height()) {
            $nav.addClass('navbar-overflow');
        } else {
            $nav.removeClass('navbar-overflow');
        }
    };

    $(win).on('scroll', function () {
        NavBarSticky(this);
    });

    NavBarSticky(win);
})(jQuery, window);
