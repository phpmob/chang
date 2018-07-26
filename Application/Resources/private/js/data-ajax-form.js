const ajaxForm = require('./ajax-form');

/**
 *  [data-reload] : bool | null
 *  [data-redirect] : url | null
 *  [data-callback] : string(name of function) | null
 */
$(document).on('submit', 'form[data-ajax-form]', function (e) {
    ajaxForm.submit(this, e);
});
