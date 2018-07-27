$(document).on('click', '[data-href]', function (e) {
    e.preventDefault();

    window.location.href = $(this).data('href');
});
