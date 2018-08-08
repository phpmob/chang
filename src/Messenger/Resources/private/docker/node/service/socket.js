const Logger = require('./logger');
const Server = require('http').createServer().listen(8080, function () {
    Logger.info('Server listening at port: ' + 8080);
});

const IO = require('socket.io').listen(Server, {
    // https://mashhurs.wordpress.com/2016/09/30/polling-vs-websocket-transport/
    transports: ['polling', 'websocket'],
});

module.exports = function (channel, msg, data, extras) {
    Logger.info('Socket consume messages', data);
    const recipients = data.body['recipients'];
    delete data.body['recipients'];

    recipients.forEach(function (hash) {
        IO.sockets.emit(extras['prefix'] + hash, data);
        Logger.info('Emit: ' + extras['prefix'] + hash);
    });

    channel.ack(msg);
};
