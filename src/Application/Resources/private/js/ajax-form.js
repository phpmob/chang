const html5Validator = require('./html5-validator');

module.exports = {
    submit: function (form, submitEvent) {
        submitEvent && submitEvent.preventDefault();

        if (false === html5Validator.isValidForm(form)) {
            submitEvent && submitEvent.stopPropagation();

            return;
        }

        var $form = $(form);
        var data = new FormData();
        var url = $form.attr('action') || $form.data('ajax-form');

        $form.find('input[type=file]').each(function (i, f) {
            if (f.files[0]) {
                data.append(f.name, f.files[0]);
            }
        });

        $.each($form.serializeArray(), function (i, f) {
            data.append(f.name, f.value);
        });

        $form
            .addClass('x-form-loading')
            .find('.alert-error').hide()
        ;

        var $buttons = $form.find('button,.btn')
            .addClass('disabled')
            .attr('disabled', true)
        ;

        $.ajax({
            url: url,
            type: 'POST',
            data: data,
            cache: false,
            processData: false, // Don't process the files
            contentType: false, // Set content type to false as jQuery will tell the server its a query string request
            // all status
            complete: function (xhr, status) {
                $form.removeClass('x-form-loading');
                $buttons.removeClass('disabled').attr('disabled', false);
            },
            // 200 status
            success: function (res, status, xhr) {
                let location = $form.data('redirect')
                    || xhr.getResponseHeader('x-sylius-location')
                    || xhr.getResponseHeader('x-chang-location')
                ;

                if ($form.data('callback')) {
                    window[$form.data('callback')].call(this, $form, res, xhr, location);

                    return;
                }

                if (!(res = res.toString().trim())) {
                    if (location) {
                        window.location.href = location;

                        return;
                    }

                    return;
                }

                var $res = $($.parseHTML(res));

                // valid form
                if (!$res.find('.is-invalid').length) {
                    if ($form.data('reload')) {
                        window.location.reload();

                        return;
                    }

                    if (location) {
                        window.location.href = location;

                        return;
                    }
                }

                $form.replaceWith($res);
                $(document).trigger('dom-node-inserted', [$res]);
            }
        });
    }
};
