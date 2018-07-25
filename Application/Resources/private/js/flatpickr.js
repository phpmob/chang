require('flatpickr');

$(function () {
    $('input[type=date]').flatpickr();

    $(document).on('dom-node-inserted', function (e, scope) {
        $('input[type=date]', scope).flatpickr();
    })
});
