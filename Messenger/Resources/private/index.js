require('dotenv').config();
const Promise = require('es6-promise').Promise;
const AmpqLib = require('amqp-connection-manager');
const Winston = require('winston');
const DailyRotateFile = require('winston-daily-rotate-file');

const Logger = Winston.createLogger({
    level: 'info',
    transports: [
        new Winston.transports.Console(),
        new DailyRotateFile({
            maxSize: '1m',
            filename: 'node-%DATE%.log',
            dirname: './logs'
        })
    ]
});

const Queues = process.env['QUEUES'].split(',');
const Services = {
    worker: require('./service/worker'),
    socket: require('./service/socket'),
};

const Connection = AmpqLib.connect(process.env['AMPQ_HOST']);

Connection.on('connect', function () {
    Logger.info('AMQP Connected!');
});

Connection.on('disconnect', function (params) {
    Logger.info('AMQP Disconnected.', params.err.stack);
});

for (let i in Queues) {
    Connection.createChannel({
        json: true,
        setup: (function (queue) {
            return function (channel) {
                return Promise.all([
                    channel.assertQueue(queue, { durable: true }),
                    channel.prefetch(1),
                    channel.consume(queue, function (msg) {
                        const data = JSON.parse(msg.content.toString());
                        try {
                            const extras = data['extras'] || {};
                            let hasService = false;

                            for (let n in extras) {
                                if (Services.hasOwnProperty(n)) {
                                    Services[n].call(null, channel, msg, data, extras[n]);
                                    hasService = true;
                                }
                            }

                            if (!hasService) {
                                Logger.warn('ACK with no service to handle.');
                                channel.ack(msg);
                            }
                        } catch (e) {
                            Logger.error('ACK with error: ' + e.getMessage());
                            channel.ack(msg);
                        }
                    })
                ]);
            }
        })(Queues[i])
    });
}
