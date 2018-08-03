const Request = require('request');
const Logger = require('./logger');

module.exports = function (channel, msg, data, extras) {
    Logger.info('Worker consume messages', data);

    const options = {
        url: extras['uri'],
        body: data,
        json: true,
        headers: {
            'User-Agent': 'Chang/Messenger-Worker',
            'Content-type': 'application/json',
            'Accept': 'application/json',
            'X-Chang-Worker-Version': '1.0@dev',
            'X-Chang-Hash': extras['hash'],
            'X-Chang-Signature': extras['signature'],
            'X-Chang-Msg-Type': msg.properties['headers']['type'] || null,
        }
    };

    // TODO: ack when expired

    try {
        Request.post(options, function (err, res, body) {
            if (200 === res.statusCode) {
                channel.ack(msg);
                Logger.info('Push success.');
            } else {
                switch (true) {
                    case body['expired']:
                        channel.ack(msg);
                        Logger.info('Push success with expired return.');
                        break;
                    case body['invalid']:
                        channel.ack(msg);
                        Logger.info('Push success with invalid return.');
                        break;
                    default:
                        channel.reject(msg);
                        Logger.warn('Push not success with reason: ' + (body.message || res.statusMessage));
                }
            }
        });
    } catch (e) {
        console.log(e);
    }
};
