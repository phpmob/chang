$(document).on('submit', '[data-confirmation-form]', function (e) {
    e.preventDefault();

    const $form = $(this);
    let btnClass = 'btn-info';
    let icon = 'far fa-question-circle';

    switch ($form.data('confirmation-type')) {
        case 'danger':
            btnClass = 'btn-danger';
            icon = 'fas fa-exclamation-triangle';
            break;
        case 'warning':
            btnClass = 'btn-warning';
            icon = 'fas fa-exclamation-triangle';
            break;
    }

    $.confirm({
        theme: 'modern',
        title: $form.data('confirmation-title') || 'Confirmation',
        icon: icon,
        content: $form.data('confirmation-form') || 'Do you want to continue?',
        buttons: {
            confirm: {
                text: 'Yes, I do!',
                btnClass: btnClass + ' focus',
                keys: ['enter'],
                action: function (e) {
                    $form.addClass('disabled');

                    $.ajax({
                        url: $form.attr('action'),
                        type: 'POST',
                        data: $form.serialize(),
                        cache: false,
                        processData: true,
                        success: function (res, status, xhr) {
                            const callback = $form.data('submit-callback');
                            const location = xhr.getResponseHeader('x-sylius-location') || xhr.getResponseHeader('location');

                            if (callback) {
                                return window[callback](res, status, xhr, location);
                            }

                            if (location) {
                                return window.location.href = location;
                            }

                            window.location.reload();
                        },
                        error: function (jqXHR, textStatus, errorThrown) {
                            $form.removeClass('disabled');

                            alert(textStatus);
                        }
                    });
                }
            },
            cancel: {
                keys: ['esc'],
                text: 'No'
            }
        }
    });
});
