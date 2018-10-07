(function ($) {
    $(document).on('click', '[data-modal]', function (e) {
        e.preventDefault();

        const $me = $(this);
        const href = $me.data('modal-href') || $me.attr('href');

        if (!href) {
            throw new Error('No `data-modal-href` or `href` was defined!');
        }

        const title = $me.data('modal') || $me.data('modal-title');
        let modalCss = $me.data('modal-css');

        const $modal = $(`
            <div class="modal modal-hero">
                <button class="modal-close"></button>
                <div class="modal-background animated faster zoomIn"></div>
                <div class="modal-dialog modal-dialog-centered ${modalCss} scaleIn">
                    <div class="modal-content animated fast zoomIn">
                        ${title ? '<div class="modal-header"><h5 class="modal-title">' + title + '</h5></div>' : ''}
                        <div class="modal-body">
                            <div class="lds-loading">
                                <div class="lds-ring"><div></div><div></div><div></div><div></div></div>
                            </div>
                        </div>
                    </div> 
                </div> 
            </div> 
        `);

        $('body').append($modal).addClass('modal-open');

        // activate
        $modal.modal({
            backdrop: false,
            show: true,
        });

        const $content = $modal.find('.modal-body');

        $.ajax({
            type: 'GET',
            url: href,
            success: function (res) {
                const $res = $($.parseHTML(res));

                $content.html($res);
                $(document).trigger('dom-node-inserted', [$res]);
            }
        });
    });
})(jQuery);
