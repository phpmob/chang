(function ($) {
    $(document).on('click', '.ripple', function () {
        const $me = $(this);
        const $ripple = $('<span class="ripple-effect"><span class="ripple-circle"/></span>');

        $ripple.one('animationend webkitAnimationEnd oanimationend MSAnimationEnd', function () {
            $me.find('.ripple-effect').remove();
        });

        $me.append($ripple);
    });
})(jQuery);
