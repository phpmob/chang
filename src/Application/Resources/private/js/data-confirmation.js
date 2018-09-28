const ajaxForm = require('./ajax-form');

$(document).on('click', '[data-confirmation],[data-confirm]', function (e) {
    e.preventDefault();

    const $el = $(this);
    const getData = (key) => {
        return $el.data(`confirmation-${key}`) || $el.data(`confirm-${key}`);
    };

    let btnClass = 'btn-green';
    let icon = 'far fa-question-circle';

    switch (getData('type')) {
        case 'danger':
            btnClass = 'btn-red';
            icon = 'fas fa-exclamation-triangle';
            break;
        case 'warning':
            btnClass = 'btn-orange';
            icon = 'fas fa-exclamation-triangle';
            break;
    }

    const isForm = $el.is('form');

    if (isForm) {
        $el.find('button,.btn')
            .addClass('disabled')
            .attr('disabled', true)
        ;
    }

    $.confirm({
        icon: getData('icon') || icon,
        theme: getData('theme') || 'modern',
        title: getData('title') || 'Confirmation',
        content: $el.data('confirmation') || $el.data('confirm') || 'Do you want to continue?',
        buttons: {
            confirm: {
                text: 'Yes, I do!',
                btnClass: btnClass + ' focus',
                keys: ['enter'],
                action: function (e) {
                    $el.addClass('disabled');

                    if (isForm) {
                        ajaxForm.submit($el.get(0), e);

                        return;
                    }

                    if ($el.is('a')) {
                        window.location.href = $el.attr('href');
                    } else {
                        $el.closest('form').submit();
                    }
                }
            },
            cancel: {
                keys: ['esc'],
                text: 'No',
                action: function () {
                    if (isForm) {
                        $el.find('button,.btn')
                            .removeClass('disabled')
                            .attr('disabled', false)
                        ;
                    }
                }
            }
        }
    });
});
