(function ($) {
    $(function () {
        const $crudBody = $('.crud-body');

        if ($crudBody.find('.crud-aside').length) {
            $crudBody.addClass('has-aside');

            if (!$crudBody.find('.crud-content').length) {
                console.warn('Missing `> .crud-content` in `.has-aside` layout.');
            }
        }
    });
})(jQuery);
