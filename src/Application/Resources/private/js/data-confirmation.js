$(document).on('click', '[data-confirmation]', function (e) {
    e.preventDefault();

    var $el = $(this);

    let btnClass = 'btn-info';
    let icon = 'far fa-question-circle';

    switch ($el.data('confirmation-type')) {
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
        title: $el.data('confirmation-title') || 'Confirmation',
        icon: icon,
        content: $el.data('confirmation') || 'Do you want to continue?',
        buttons: {
            confirm: {
                text: 'Yes, I do!',
                btnClass: btnClass + ' focus',
                keys: ['enter'],
                action: function (e) {
                    $el.addClass('disabled');

                    if ($el.is('a')) {
                        window.location.href = $el.attr('href');
                    } else {
                        $el.closest('form').submit();
                    }
                }
            },
            cancel: {
                keys: ['esc'],
                text: 'No'
            }
        }
    });
});
