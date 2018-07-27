$(document).on('change', 'input[data-preview]', function () {
    const $target = $($(this).data('preview'));
    const imgOriginSrc = $target.attr('src');

    if (this.files && this.files[0]) {
        const reader = new FileReader();

        reader.onload = function (e) {
            $target.attr('src', e.target.result);
        };
        reader.readAsDataURL(this.files[0]);
    } else {
        $target.attr('src', imgOriginSrc);
    }
});
