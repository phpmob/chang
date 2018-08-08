// this file will use jquery from app.js, use it after of app.js
const io = require('socket.io-client');
const Howl = require('howler').Howl;
const Sounds = [
    '/build/sounds/quite-impressed.ogg',
    '/build/sounds/quite-impressed.mp3',
    '/build/sounds/quite-impressed.m4r',
];

const ChangMsg = function (opts) {
    if (opts.el) {
        const $inbox = $(opts.el['inbox']);
        const $inboxList = $(opts.el['list']);
    }

    let recipientPrefix = 'message/';

    if (typeof opts.recipientPrefix !== 'undefined') {
        recipientPrefix = opts.recipientPrefix;
    }

    const getList = function (successCallback) {
        $inboxList.html('<div class="loader" style="margin: 1rem auto; display: block;"></div>');
        $.ajax($inboxList.data('inbox-list'), {
            type: 'GET',
            success: function (res) {
                $inboxList.html(res);
                $(document).trigger('dom-node-inserted', [$inboxList]);

                $inbox.find('[data-mark-all-as-read]').on('click', function (e) {
                    e.preventDefault();
                    const $me = $(this);

                    $me.attr('disabled', true).addClass('disabled');
                    $.ajax($me.data('mark-all-as-read'), {
                        type: 'GET',
                        complete: function (e) {
                            $me.attr('disabled', false).removeClass('disabled');
                        },
                        success: function () {
                            $me.remove();
                            $inbox.removeClass(opts.el['css']);
                            $inbox.find('.unread').removeClass('unread');
                        }
                    });
                });

                if (successCallback) {
                    successCallback.call(this, $inboxList);
                }
            }
        });
    };

    opts.el && $inbox.find('[data-toggle="dropdown"]').on('click', function (e) {
        e.preventDefault();

        const $me = $(this);
        let now = parseInt(Date.now());

        // 10 secs
        if ($me.data('clicked_at') && now < ($me.data('clicked_at') + (10 * 1000))) {
            return;
        }

        $me.data('clicked_at', now);

        getList();
    });

    io(opts.url).on(recipientPrefix + opts.recipient, opts.callback || function (data) {
        $inbox.addClass(opts.el['css']);

        getList(function ($res) {
            if (opts.successCallback) {
                opts.successCallback.call(this, data.body['message'], opts, $inbox, $inboxList, $res, Howl, $)
            }
        });

        new Howl({ src: opts.sounds || Sounds }).play()
    });
};

try {
    ChangMsg(JSON.parse(document.querySelector('script[data-inbox-client]').innerHTML));
} catch (e) {
}

module.exports = window.ChangMsg = ChangMsg;
