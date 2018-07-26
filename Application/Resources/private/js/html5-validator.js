module.exports = {
    isValidForm: function (form) {
        if (false === form.checkValidity()) {
            $(form).find('input:visible,textarea:visible,select:visible').each(function () {
                if (false === this.validity.valid) {
                    $(this).addClass('is-invalid');
                } else {
                    $(this).removeClass('is-invalid');
                }
            });

            return false;
        }

        return true;
    }
};
