require('dotenv').config();
const Winston = require('winston');
const DailyRotateFile = require('winston-daily-rotate-file');

const Logger = Winston.createLogger({
    level: 'info',
    transports: [
        new Winston.transports.Console(),
        new DailyRotateFile({
            maxSize: '1m',
            filename: 'socket-%DATE%.log',
            dirname: './logs'
        })
    ]
});

const Server = require('http').createServer().listen(process.env['WEB_SOCKET_PORT'], function () {
    Logger.info('Server listening at port: ' + process.env['WEB_SOCKET_PORT']);
});

const IO = require('socket.io').listen(Server, {
    // https://mashhurs.wordpress.com/2016/09/30/polling-vs-websocket-transport/
    transports: ['polling', 'websocket'],
});

module.exports = function (channel, msg, data, extras) {
    Logger.info('Socket consume messages', data);

    data.body['recipients'].forEach(function (hash) {
        IO.sockets.emit(extras['prefix'] + hash, data.body['message']);
        Logger.info('Emit: ' + extras['prefix'] + hash);
    });

    channel.ack(msg);
};
