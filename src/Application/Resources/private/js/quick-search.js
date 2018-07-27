$(document).on('submit', 'form.top-bar-search', function (e) {
    e.preventDefault();

    const $form = $(this);
    const value = $form.find('input[type=text]').val();
    let action = $form.data('action');

    window.location.href = action.replace(/:value|%3Avalue/, value);
});

$(document).on('click', 'form.top-bar-search [data-search]', function (e) {
    e.preventDefault();

    const $form = $(this).closest('form');
    const data = $(this).data('search');

    $form.find('[data-toggle=dropdown]').html(data.label);
    //$form.find('[data-toggle=dropdown]').html('<i class="'+ data.icon +'"></i> ' + data.label);
    //$form.find('[data-toggle=dropdown]').html($(this).html());
    $form.data('action', data.path);
});
