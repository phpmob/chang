$(document).on('click', '[data-toggle-display]', function (e) {
    e.preventDefault();

    $($(this).data('toggle-display')).toggle();
});
