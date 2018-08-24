<?php

namespace Chang\Redis\Factory;

use Chang\Redis\RedisDsnParser;
use Symfony\Component\Config\Definition\Exception\InvalidConfigurationException;

class PhpredisClientFactory
{
    /**
     * @param string $dsn One DSN string
     * @param array $options Options provided in bundle client config
     *
     * @return \Redis
     * @throws InvalidConfigurationException
     */
    public static function create(string $dsn, array $options = [])
    {
        $client = new \Redis();
        $parsedDsn = new RedisDsnParser($dsn);

        $connectParameters = array();
        if (null !== $parsedDsn->getSocket()) {
            $connectParameters[] = $parsedDsn->getSocket();
            $connectParameters[] = null;
        } else {
            $connectParameters[] = $parsedDsn->getHost();
            $connectParameters[] = $parsedDsn->getPort();
        }

        if (isset($options['connection_timeout'])) {
            $connectParameters[] = $options['connection_timeout'];
        } else {
            $connectParameters[] = null;
        }
        if (isset($options['connection_persistent'])) {
            $connectParameters[] = $parsedDsn->getPersistentId();
        }

        $connectMethod = !empty($options['connection_persistent']) ? 'pconnect' : 'connect';
        call_user_func_array(array($client, $connectMethod), $connectParameters);

        if (isset($options['prefix'])) {
            $client->setOption(\Redis::OPT_PREFIX, $options['prefix']);
        }

        if (null !== $parsedDsn->getPassword()) {
            $client->auth($parsedDsn->getPassword());
        }

        if (null !== $parsedDsn->getDatabase()) {
            $client->select($parsedDsn->getDatabase());
        }

        if (isset($options['serialization'])) {
            $client->setOption(\Redis::OPT_SERIALIZER, self::loadSerializationType($options['serialization']));
        }

        return $client;
    }

    /**
     * Load the correct serializer for Redis
     *
     * @param string $type
     *
     * @return string
     * @throws InvalidConfigurationException
     */
    private static function loadSerializationType($type)
    {
        $types = array(
            'default' => \Redis::SERIALIZER_NONE,
            'none' => \Redis::SERIALIZER_NONE,
            'php' => \Redis::SERIALIZER_PHP
        );

        if (defined('Redis::SERIALIZER_IGBINARY')) {
            $types['igbinary'] = \Redis::SERIALIZER_IGBINARY;
        }

        if (array_key_exists($type, $types)) {
            return $types[$type];
        }

        throw new InvalidConfigurationException(sprintf('%s in not a valid serializer. Valid serializers: %s', $type, implode(', ', array_keys($types))));
    }
}
