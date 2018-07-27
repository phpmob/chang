const html5Validator = require('./html5-validator');

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

$(document).on('submit', 'form', function (e) {
    if (false === html5Validator.isValidForm(this)) {
        e.preventDefault();
        e.stopPropagation();

        return;
    }

    $(this).find('button').addClass('disabled').attr('disabled', true);
});
