const moment = require('moment');

module.exports = window.ChangCountDown = function (unixTime, callback) {
    const timer = setInterval(function () {
        const duration = moment.duration(moment.unix(unixTime).diff(moment()));
        const isEnded = duration.asSeconds() < 0;

        callback(duration, isEnded);

        if (isEnded) {
            clearInterval(timer);
        }
    }, 1000);
};
