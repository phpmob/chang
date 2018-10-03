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

    const $form = $el.is('form') ? $el : ($el.closest('form').length ? $el.closest('form') : null);
    const isForm = $form ? true : false;
    const isAjaxForm = isForm && $form.get(0).hasAttribute('data-ajax-form');

    if (isForm) {
        $form.find('button,.btn')
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

                    if (isForm && isAjaxForm) {
                        ajaxForm.submit($el.get(0), e);

                        return;
                    }

                    if (isForm) {
                        $form.submit();

                        return;
                    }

                    if ($el.is('a')) {
                        window.location.href = $el.attr('href');
                    }
                }
            },
            cancel: {
                keys: ['esc'],
                text: 'No',
                action: function () {
                    if (isForm) {
                        $form.find('button,.btn')
                            .removeClass('disabled')
                            .attr('disabled', false)
                        ;
                    }
                }
            }
        }
    });
});
