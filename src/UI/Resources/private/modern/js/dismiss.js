(function ($) {
    $(document).on('click', '[data-dismiss]', function (e) {
        e.preventDefault();

        const targetClass = $(this).data('dismiss');
        const activeClass = $(this).data('dismiss-cls');

        $(`.${targetClass}`).removeClass(activeClass || 'active');
    });
})(jQuery);
