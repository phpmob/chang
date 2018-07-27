const ajaxForm = require('./ajax-form');

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
                action: function () {
                    ajaxForm.submit($form.get(0), e);
                }
            },
            cancel: {
                keys: ['esc'],
                text: 'No',
                action: function () {
                    $form.find('button,.btn')
                        .removeClass('disabled')
                        .attr('disabled', false)
                    ;
                }
            }
        }
    });
});
