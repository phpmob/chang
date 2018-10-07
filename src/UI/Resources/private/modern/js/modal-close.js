(function ($) {
    $(document).on('click', '.modal-close', function (e) {
        e.preventDefault();

        const $body = $('.modal-open');
        const $modal = $(this).closest('.modal');

        $modal.find('.zoomIn').animateCss('zoomOut', function() {
            $body.removeClass('modal-open');
            $modal.remove();
        });
    });
})(jQuery);
