const Winston = require('winston');
const DailyRotateFile = require('winston-daily-rotate-file');
const Logger = Winston.createLogger({
    level: 'info',
    transports: [
        new Winston.transports.Console(),
        new DailyRotateFile({
            maxSize: process.env['LOG_FILE_SIZE'] || '1m',
            filename: process.env['LOG_FILE_NAME'] || 'node-%DATE%.log',
            dirname: process.env['LOG_DIR'] || './logs'
        })
    ]
});

module.exports = Logger;
