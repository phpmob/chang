const moment = require('moment');

const initMoment = function (scope) {
    moment.locale($('html').attr('lang') || 'en');
    $('[data-humanize-time]', scope).each(function () {
        const $me = $(this);
        $me.html(moment.unix($me.data('humanize-time')).fromNow());
    })
};

$(function () {
    initMoment(document);
});

$(document).on('dom-node-inserted', function (e, scope) {
    initMoment(scope);
});
