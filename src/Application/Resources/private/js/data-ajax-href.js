$(document).on('click', '[data-ajax-href]', function (e) {
    e.preventDefault();

    const $el = $(this);
    const $insertTarget = $($el.data('target') || 'body');
    let url = $el.attr('href');

    if ('#' === url || !url) {
        url = $el.data('ajax-href');
    }

    $el.attr('disabled', true).addClass('disabled');

    $.ajax({
        async: true,
        type: 'GET',
        url: url,
        complete: function () {
            $el.attr('disabled', false).removeClass('disabled');
        },
        success: function (res) {
            const $res = $(res);
            $insertTarget.append($res);

            if ($res.hasClass('modal')) {
                $res.modal('show');
            }

            if ($res.find('modal').length) {
                $res.find('modal').modal('show');
            }

            $(document).trigger('dom-node-inserted', [$res]);
        }
    });
});
