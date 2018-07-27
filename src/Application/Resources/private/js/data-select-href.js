$(document).on('change', '[data-select-href]', function (e) {
    e.preventDefault();

    const url = $(this).find(':selected').data('url');

    if (!url) {
        return;
    }

    window.location.href = url;
});
