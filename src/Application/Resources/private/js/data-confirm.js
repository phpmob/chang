(function ($) {
    const ajaxForm = require('./ajax-form');
    const selectors = [
        '[data-confirm]',
        '[data-confirm] button[type=submit]',
    ];

    const hasAttr = ($el, attr) => {
        return $el.get(0).hasAttribute(attr);
    };

    const handler = ($el) => {
        let btnClass = 'btn-green';
        let icon = 'far fa-question-circle';
        let $target = hasAttr($el, 'data-confirm') ? $el : $el.closest('[data-confirm]');
        let $form = $el.closest('form').length ? $el.closest('form') : null;
        const isAjaxForm = $form && hasAttr($form, 'data-ajax-form');

        const getData = (key) => {
            if (key) {
                return $target.data(`confirm-${key}`);
            }

            return $target.data(`confirm`);
        };

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

        if ($form) {
            $form.addClass('x-form-loading');
            $form.find('button,.btn')
                .addClass('disabled')
                .attr('disabled', true)
            ;
        }

        $.confirm({
            icon: getData('icon') || icon,
            theme: getData('theme') || 'modern',
            title: getData('title') || 'Confirmation',
            content: getData() || 'Do you want to continue?',
            buttons: {
                confirm: {
                    text: 'Yes, I do!',
                    btnClass: btnClass + ' focus',
                    keys: ['enter'],
                    action: function () {
                        $el.addClass('disabled');

                        if ($form && isAjaxForm) {
                            ajaxForm.submit($form.get(0));

                            return;
                        }

                        if ($form) {
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
                        if ($form) {
                            $form.removeClass('x-form-loading');
                            $form.find('button,.btn')
                                .removeClass('disabled')
                                .attr('disabled', false)
                            ;
                        }
                    }
                }
            }
        });
    };

    $(document).on('click', selectors.join(','), function (e) {
        const $el = $(this);

        // click on form
        if ($el.is('form')) {
            return;
        }

        e.preventDefault();

        return handler($el);
    });
})(jQuery);
