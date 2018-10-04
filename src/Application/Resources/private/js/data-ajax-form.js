(function ($) {
    const ajaxForm = require('./ajax-form');

    /**
     *  [data-reload] : bool | null
     *  [data-redirect] : url | null
     *  [data-callback] : string(name of function) | null
     */
    $(document).on('submit', 'form[data-ajax-form]', function (e) {
        if (this.hasAttribute('data-confirm')) {
            return;
        }

        ajaxForm.submit(this, e);
    });
})(jQuery);
