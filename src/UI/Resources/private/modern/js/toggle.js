(function ($) {
    $(document).on('click', '[data-tgg]', function (e) {
        e.preventDefault();

        let targetEl = $(this).data('tgg');
        const toggleClass = $(this).data('tgg-cls');

        // toggle by class name by default
        if (/^[a-zA-Z]/.test(targetEl)) {
            targetEl = `.${targetEl}`;
        }

        $(targetEl).toggleClass(toggleClass || 'active');
    });
})(jQuery);
