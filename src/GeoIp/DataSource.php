<?php

declare(strict_types=1);

namespace Chang\GeoIp;

class DataSource implements DataSourceInterface
{
    private $adapter;

    public function __construct(AdapterInterface $adapter)
    {
        $this->adapter = $adapter;
    }

    /**
     * {@inheritdoc}
     */
    public function getData(string $ip): Data
    {
        return $this->adapter->lookup($ip);
    }
}
