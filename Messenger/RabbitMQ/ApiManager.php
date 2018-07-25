<?php

declare(strict_types=1);

namespace Chang\Messenger\RabbitMQ;

use Http\Client\HttpClient;
use RabbitMq\ManagementApi\Api;
use RabbitMq\ManagementApi\Client;

/**
 * @property array $overview
 * @property array $extensions
 * @property array $definitions
 * @property array $whoami
 * @property Api\Connection $connections
 * @property Api\Channel $channels
 * @property Api\Exchange $exchanges
 * @property Api\Queue $queues
 * @property Api\Vhost $vhosts
 * @property Api\Binding $bindings
 * @property Api\User $users
 * @property Api\Permission $permissions
 * @property Api\Parameter $parameters
 * @property Api\Policy $policies
 */
class ApiManager
{
    /**
     * @var Client
     */
    private $client;

    public function __construct(string $dsn, HttpClient $client = null)
    {
        if (false === $parsedUrl = parse_url($dsn)) {
            throw new \InvalidArgumentException(sprintf('The given AMQP DSN "%s" is invalid.', $dsn));
        }

        $address = sprintf('%s://%s:%s', $parsedUrl['scheme'], $parsedUrl['host'], $parsedUrl['port']);
        $this->client = new Client($client, $address, $parsedUrl['user'], $parsedUrl['pass']);
    }

    /**
     * @param string $name
     *
     * @return mixed
     */
    public function __get(string $name)
    {
        return call_user_func_array([$this->client, $name], []);
    }

    /**
     * @param string $vhost
     *
     * @return array
     */
    public function aliveness(string $vhost): array
    {
        return $this->client->alivenessTest($vhost);
    }
}
