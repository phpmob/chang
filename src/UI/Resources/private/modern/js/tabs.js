(function ($) {
    $(document).on('click', '[data-tab]', function (e) {
        e.preventDefault();

        const $me = $(this);

        $me.parent()
            .find('[data-tab]')
            .removeClass('is-active')
        ;

        const tabId = $me.data('tab');
        const $targetTab = $(`#${tabId}`);

        $me.addClass('is-active');
        $targetTab.parent().find('.navtab-content').removeClass('is-active');
        $targetTab.addClass('is-active');
    });
})(jQuery);
