// https://developer.mozilla.org/en-US/docs/Learn/HTML/Forms/Form_validation
$(document).on('blur', 'form input,form textarea,form select', function (e) {
    if (false === this.validity.valid) {
        $(this).addClass('is-invalid');
        $(this).removeClass('is-valid');
    } else {
        $(this).removeClass('is-invalid');

        if (this.value) {
            $(this).addClass('is-valid');
        }
    }
});

$(document).on('submit', 'form:not([data-confirmation-form]):not([data-custom-submit])', function (e) {
    e.preventDefault();

    if (this.hasAttribute('novalidate')) {
        return this.submit();
    }

    if (false === this.checkValidity()) {
        $(this).find('input:visible,textarea:visible,select:visible').each(function () {
            if (false === this.validity.valid) {
                $(this).addClass('is-invalid');
            } else {
                $(this).removeClass('is-invalid');
            }
        });

        return false;
    }

    $(this).find('.btn, button')
        .attr('disabled', true)
        .addClass('disabled')
    ;

    this.submit();
});
